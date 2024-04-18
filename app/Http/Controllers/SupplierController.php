<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\{Company, Country, Supplier, Respondent, SupplierProject, SupplierRedirect};
use App\Http\Resources\{AccountResource, CountryResource, PlanResource, RedirectResource, SupplierProjectResource, SupplierResource, SuppliersResponedentResource};

class SupplierController extends Controller
{
    public $countries;
    public function __construct($data = array())
    {
        $this->countries = CountryResource::collection(Country::orderBy('name', 'asc')->get());
    }
    public function getSupplier($id)
    {
        $supplier = Supplier::where(['id' => $id, 'company_id' => $this->companyId()])->first();
        return new SupplierResource($supplier);
    }
    public function index(Request $request)
    {
        $suppliers = Supplier::where(['company_id' => $this->companyId()]);
        if (!empty($request->q)) {
            $suppliers = $suppliers
                ->where('supplier_name', 'like', "%$request->q%")
                ->orWhere('display_name', 'like', "%$request->q%");
        }
        if ($request->status !== 'all' && $request->status != null) {
            $suppliers = $suppliers->where('status', (int)$request->status);
        }
        return Inertia::render('Supplier/Index', [
            'suppliers' => SupplierResource::collection($suppliers->paginate(10)->appends($request->all())),
            'allowed_suppliers' => auth()->user()->stripePlan,
        ]);
    }


    public function create()
    {
        return Inertia::render('Supplier/Create', [
            'countries' => $this->countries,
            'suppliers' => count(Supplier::get()),
            'allowed_suppliers' => new PlanResource(auth()->user()->stripePlan),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_name' => 'required',
            'display_name' => 'required',
            'email_address' => 'required',
            'website' => '',
            'skype_profile' => '',
            'linkedin_profile' => '',
            'description' => '',
            'status' => 'required',
            'complete_url' => 'required',
            'terminate_url' => 'required',
            'quotafull_url' => 'required',
            'security_terminate_url' => 'required',
        ]);
        $supplier = Supplier::create([
            'company_id' => $this->companyId(),
            'country_id' => $request->country,
            'supplier_name' => $request->supplier_name,
            'display_name' => $request->display_name,
            'email_address' => $request->email_address,
            'website' => $request->website,
            'skype_profile' => $request->skype_profile,
            'linkedin_profile' => $request->linkedin_profile,
            'description' => $request->description,
            'status' => $request->status,
        ]);
        if ($supplier) { {
                $supplierRedirect = SupplierRedirect::create([
                    'supplier_id' => $supplier->id,
                    'complete_url' => $request->complete_url,
                    'terminate_url' => $request->terminate_url,
                    'quotafull_url' => $request->quotafull_url,
                    'security_terminate_url' => $request->security_terminate_url,
                ]);
                if ($supplierRedirect) {
                    return redirect("/suppliers")->with('flash', createMessage('Supplier'));
                }
            }
            return redirect()->back()->withErrors(['Opps something went wrong!']);
        }
    }

    public function show($id)
    {
        $supplier = Supplier::where(['company_id' => $this->companyId(), 'id' => $id])->first();
        if ($supplier) {
            return Inertia::render('Supplier/Overview', [
                'supplier' => new SupplierResource($supplier),
                'countries' => $this->countries

            ]);
        }
    }

    public function redirect($id)
    {
        if ($this->getSupplier($id)) {
            $redirect = SupplierRedirect::where('supplier_id', $id)->first();
            return Inertia::render('Supplier/Redirect', [
                'redirect' => !empty($redirect) ? new RedirectResource($redirect) : [],
                'supplier' => $this->getSupplier($id),
            ]);
        }
    }

    public function updateRedirect(Request $request)
    {
        $redirect = [];
        $request->validate([
            'complete_url' => 'required',
            'terminate_url' => 'required',
            'quotafull_url' => 'required',
        ]);
        if (Supplier::where(['company_id' => $this->companyId(), 'id' => $request->supplier_id])->first()) {
            if (SupplierRedirect::where(['supplier_id' => $request->supplier_id])->first()) {
                $update = SupplierRedirect::where(['supplier_id' => $request->supplier_id])->update([
                    'complete_url' => $request->complete_url,
                    'terminate_url' => $request->terminate_url,
                    'quotafull_url' => $request->quotafull_url,
                    'security_terminate_url' => $request->security_terminate_url,
                ]);
                if ($update) {
                    return redirect("/supplier/$request->supplier_id/redirect")->with('flash', updateMessage('Supplier Redirect'));
                }
            } else {
                $redirect = SupplierRedirect::create([
                    'supplier_id' => $request->supplier_id,
                    'complete_url' => $request->complete_url,
                    'terminate_url' => $request->terminate_url,
                    'quotafull_url' => $request->quotafull_url,
                    'security_terminate_url' => $request->security_terminate_url,
                ]);
            }
            if ($redirect) {
                return redirect("/supplier/$request->supplier_id/redirect")->with('flash', createMessage('Supplier Redirect'));
            }
            return redirect()->back()->withErrors(['Opps something went wrong!']);
        }
        return redirect()->back();
    }
    public function account($id)
    {
        $supplier = Supplier::where('company_id', $this->companyId())->find($id);
        if ($supplier) {
            return Inertia::render('Supplier/Account', [
                'account' => new AccountResource($supplier->account),
                'address' => $this->supplierAddress($id),

                'supplier' => $this->supplierHeader($id),

            ]);
        }
    }

    public function projects($id)
    {
        $supplier = Supplier::where('company_id', $this->companyId())->find($id);

        if ($supplier) {
            $projects = SupplierProject::where(['supplier_id' => $supplier->id])->paginate(10);
            return Inertia::render('Supplier/Projects', [
                'supplier' => new SupplierResource($supplier),
                'projects' => SupplierProjectResource::collection($projects)
            ]);
        }
    }

    public function respondents(Request $request, $id)
    {
        $respondents = Respondent::where('supplier_id', $id);
        if (!empty($request->q)) {
            $respondents = $respondents->whereHas('project', function ($query) use ($request) {
                $query->where('project_name', 'like', '%' . $request->q . '%');
            });
        }
        if (!empty($request->status) && $request->status != 'all') {
            $respondents = $respondents->where('status', $request->status);
        }
        return Inertia::render('Supplier/Respondents', [
            'respondents' => SuppliersResponedentResource::collection($respondents->paginate(10)->appends(request()->query())),
            'supplier' => $this->getSupplier($id),
        ]);
    }

    public function edit(Supplier $supplier)
    {
        $company = Company::get();

        return Inertia::render('Supplier/Form', [
            'supplier' => new SupplierResource($supplier),
            'company' => $company,
        ]);
    }



    public function update(Request $request)
    {
        $request->validate([
            'supplier_name' => 'required',
            'display_name' => 'required',
            'email_address' => 'required',
            'website' => '',
            'skype_profile' => '',
            'linkedin_profile' => '',
            'description' => '',
            'status' => '',
        ]);

        $supplier = Supplier::where(['id' => $request->id])->update([
            'country_id' => $request->id,
            'supplier_name' => $request->supplier_name,
            'display_name' => $request->display_name,
            'email_address' => $request->email_address,
            'website' => $request->website,
            'country_id' => $request->country,
            'skype_profile' => $request->skype_profile,
            'linkedin_profile' => $request->linkedin_profile,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        if ($supplier) {
            return redirect("/supplier/$request->id")->with('flash', updateMessage('Supplier'));
        }
        return redirect()->back()->withErrors(['Opps something went wrong!']);
    }


    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        if ($supplier->delete()) {
            return response()->json(deleteMessage('Supplier'));
        }
        return response()->json(errorMessage());
    }

    public function changeStatus(Request $request)
    {
        if (Supplier::where(['id' => $request->id, 'company_id' => $this->companyId()])->update(['status' => $request->status ? 1 : 0])) {
            $status = $request->status == 0  ? "Inactivate" : "Activate";
            return response()->json(['message' => "Your supplier has been " . $status, 'success' => true]);
        }
        return response()->json(['message' => 'Opps! something went wrong.', 'success' => false]);
    }
}
