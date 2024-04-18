<?php

namespace App\Http\Controllers;

use App\Http\Resources\{ClientResource, CountryResource, AddressResource, ClientListResource, ContactResource, PlanResource, ProjectListResource, ProjectStatusResource};
use App\Models\{Address, Client, Country, ClientContact, Contact, Project, ProjectStatus};
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientController extends Controller
{
    public $countries, $status;
    public function __construct()
    {
        $this->countries = CountryResource::collection(Country::orderBy('name', 'asc')->get());
        $this->status = ProjectStatus::orderBy('id', 'asc')->get();
    }
    public function index(Request $request)
    {
        // return auth()->user();
        $clients = Client::where(['company_id' => $this->companyId()]);
        if (!empty($request->q)) {
            $clients = $clients->where('client_name', 'like', "%{$request->q}%")->orWhere('display_name', 'like', "%{$request->q}%");
        }
        if ($request->status !== 'all' && $request->status !== null) {
            $clients = $clients->where('status', (int)$request->status);
        }
        $clients = $clients->paginate(10)->appends(request()->query());


        return Inertia::render('Client/Index', [
            'clients' => ClientListResource::collection($clients),
            'allowed_clients' => new PlanResource(auth()->user()->stripePlan),
        ]);
    }

    public function create()
    {
        $clients = Client::get();
        return Inertia::render('Client/Create', [
            'clients' => count($clients),
            'allowed_clients' => new PlanResource(auth()->user()->stripePlan),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required',
            'website' => 'required',
            'skype_profile' => 'required',
        ]);
        $client = Client::create(
            [
                'company_id' => $this->companyId(),
                'client_name' => $request->client_name,
                'display_name' => $request->display_name,
                'website' => $request->website,
                'linkedin_profile' => $request->linkedin_profile,
                'skype_profile' => $request->skype_profile,
                'tax_number' => $request->tax_number,
                'description' => $request->description,
                'status' => 1
            ]
        );
        if ($client) {
            return redirect("/clients")->with('flash', createMessage('Client'));
        }
        return redirect()->back()->withErrors(errorMessage());
    }
    public function getClient($id)
    {
        return Client::find($id);
    }

    public function show($id)
    {
        return Inertia::render('Client/Overview', [
            'client' => new ClientResource($this->getClient($id)),
            'countries' => CountryResource::collection($this->countries)
        ]);
    }

    public function edit($id)
    {
        return Inertia::render('Client/Create', [
            'client' => new ClientResource($this->getClient($id)),
            'countries' => $this->countries,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'client_name' => 'required',
            'website' => '',
            'skype_profile' => '',
        ]);
        $client = Client::where(['id' => $request->id])->update(
            [
                'client_name' => $request->client_name,
                'display_name' => $request->display_name,
                'website' => $request->website,
                'linkedin_profile' => $request->linkedin_profile,
                'skype_profile' => $request->skype_profile,
                'tax_number' => $request->tax_number,
                'description' => $request->description,
            ]
        );
        if ($client) {
            if ($request->action == 'overview') {
                return redirect("/client/$request->id")->with('flash', updateMessage('Client'));
            }
            return redirect("/clients")->with('flash', updateMessage('Client'));
        }
        return redirect()->back()->withErrors(errorMessage());
    }


    public function destroy($id)
    {
        $client = Client::find($id);

        if ($client->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Client deleted successfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Opps something went wrong',
            ]);
        }
    }

    public function projects(Request $request, $id)
    {
        $projects = Project::where(['client_id' => $id]);
        if ($request->status) {
            $projects = $projects->where('status', 'like', "%$request->status%");
        }
        $projects = $projects->paginate(10)->appends(request()->query());

        return Inertia::render('Client/Projects', [
            'client' => new ClientResource($this->getClient($id)),
            'projects' => ProjectListResource::collection($projects),
            'status' => ProjectStatusResource::collection($this->status),
        ]);
    }

    public function address($id)
    {
        $client = Client::where('company_id', $this->companyId())->find($id);
        if ($client) {
            $address = Address::where(['entity_id' => $id, 'entity_type' => 'client'])->first();
            return Inertia::render('Client/Address', [
                'address' => $address ?  new AddressResource($address) : null,
                'client' => new ClientResource($this->getClient($id)),
                'countries' => $this->countries
            ]);
        }
    }
    public function addUpdateAddress(Request $request, $id)
    {
        $request->validate([
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'pincode' => 'required|numeric|digits:6',
        ]);
        if (Client::where(['company_id' => $this->companyId(), 'id' => $id])->first()) {
            $address = Address::where(['id' => $request->address_id])->first();
            if ($address) {
                $update = Address::where(['id' => $request->address_id])->update([
                    'address' => $request->address,
                    'entity_id' => $id,
                    'entity_type' => 'client',
                    'city' => $request->city,
                    'state' => $request->state,
                    'country_id' => $request->country,
                    'pincode' => $request->pincode,
                    'is_primary' => $request->is_primary ? 1 : 0,
                ]);
                if ($update) {
                    return redirect("/client/$id/address")->with('flash', updateMessage('Address'));
                }
            } else {
                $create = Address::create([
                    'address' => $request->address,
                    'entity_id' => $id,
                    'entity_type' => 'client',
                    'city' => $request->city,
                    'state' => $request->state,
                    'country_id' => $request->country,
                    'pincode' => $request->pincode,
                    'is_primary' => $request->is_primary ? 1 : 0,
                ]);
                if ($create) {
                    return redirect("/client/$id/address")->with('flash', createMessage('Address'));
                }
            }
            return redirect()->back()->withErrors(['Opps something went wrong!']);
        }
        return redirect()->back();
    }
    public function delAddress($id)
    {
        $address = Address::find($id);
        if ($address->delete()) {
            return response()->json(deleteMessage('Client Address'));
        }
        return redirect()->back()->withErrors(['Opps something went wrong!']);
    }

    public function contact($id)
    {
        $client = Client::where('company_id', $this->companyId())->find($id);
        if ($client) {
            $contact = Contact::where(['entity_id' => $id, 'entity_type' => 'client'])->first();
            return Inertia::render('Client/Contact', [
                'contact' => $contact ? new ContactResource($contact) :  null,
                'client' => new ClientResource($this->getClient($id)),
                'countries' => $this->countries
            ]);
        }
    }
    public function addUpdateContact(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'country' => 'required',
        ]);
        if (Client::where(['company_id' => $this->companyId(), 'id' => $id])->first()) {
            $contact = Contact::where('id', $request->id)->first();
            if ($contact) {
                $contact = Contact::where(['id' => $request->id])->update([
                    'entity_id' => $id,
                    'entity_type' => 'client',
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'country_id' => $request->country,
                    'is_primary' => $request->is_primary ? 1 : 0,
                ]);
                return redirect("/client/$id/contact")->with('flash', ['message' => 'Contact updated successfully.']);
            }
            $contact = Contact::create([
                'entity_id' => $id,
                'entity_type' => 'client',
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'country_id' => $request->country,
                'is_primary' => $request->is_primary ? 1 : 0,
            ]);
            $contact = ClientContact::create(['client_id' => $id, 'contact_id' => $contact->id]);
            if ($contact) {
                return redirect("/client/$id/contact")->with('flash', ['message' => 'Contact created successfully.']);
            }
            return redirect()->back()->withErrors(['Opps something went wrong!']);
        }
        return redirect()->back();
    }

    public function delContact($id)
    {
        if (Contact::where(['id' => $id])->delete()) {
            $contact = ClientContact::where(['contact_id' => $id])->delete();
            if ($contact) {
                return response()->json(['success' => true, 'message' => 'Contact deleted successfully.']);
            }
            return response()->json(['success' => true, 'message' => 'Opps something went wrong!']);
        }
    }

    public function status(Request $request)
    {
        if (Client::where(['id' => $request->id, 'company_id' => $this->companyId()])->update(['status' => $request->status ? 1 : 0])) {
            $status = $request->status == 0  ? "Inactivate" : "Activate";
            return response()->json(['message' => "Your client has been " . $status, 'success' => true]);
        }
        return response()->json(['message' => 'Opps! something went wrong.', 'success' => false]);
    }
}
