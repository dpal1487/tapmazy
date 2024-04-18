<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountResource;
use App\Http\Resources\AddressResource;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\EmailResource;
use App\Http\Resources\RedirectResource;
use App\Models\Account;
use App\Models\Address;
use App\Models\Company;
use App\Models\CompanyAccount;
use App\Models\CompanyAddress;
use App\Models\CompanyEmail;
use App\Models\CompanyRedirect;
use App\Models\CompanySize;
use App\Models\CompanyUser;
use App\Models\Country;
use App\Models\Redirect;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CompanyController extends Controller
{
    public $countries, $headers;
    public function __construct($data = array())
    {
        $this->countries = CountryResource::collection(Country::orderBy('name', 'asc')->get());
    }
    public function index()
    {
        return "company_countries";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Company/Create', [
            'countries' => $this->countries
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'business_name' => 'required|unique:companies,name',
            'company_size' => 'required',
            'legal_registration_no' => 'required',
            'company_type' => 'required',
            'business_name' => 'required',
            'business_subdomain' => 'required|unique:companies,subdomain',
            'company_website' => 'required',
            'skype_profile' => 'required',
            'linkedin_profile' => 'required',
            'short_description' => 'required',
            'corporation_type' => 'required',
            'business_description' => 'required',
        ]);
        $company = Company::create(
            [
                'company_name' => $request->business_name,
                'company_size_id' => $request->company_size,
                'legal_registration_no' => $request->legal_registration_no,
                'company_type' => $request->company_type,
                'subdomain' => $request->business_subdomain,
                'website' => $request->company_website,
                'skype_profile' => $request->skype_profile,
                'linkedin_profile' => $request->linkedin_profile,
                'short_description' => $request->short_description,
                'corporation_type_id' => $request->corporation_type,
                'description' => $request->business_description,
            ]
        );
        if ($company) {
            $redirect = Redirect::create([
                'complete_url' => "https://www.samplizy.com/surveyEnd/$company->id?status=1&uid=XXX",
                'terminate_url' => "https://www.samplizy.com/surveyEnd/$company->id?status=2&uid=XXX",
                'quotafull_url' => "https://www.samplizy.com/surveyEnd/$company->id?status=3&uid=XXX",
                'security_terminate_url' => "https://www.samplizy.com/surveyEnd/$company->id?status=4&uid=XXX",
            ]);
            CompanyRedirect::create(['company_id' => $company->id, 'redirect_id' => $redirect->id]);
            User::where(['id' => Auth::user()->id])->update(['company_id' => $company->id]);
            return redirect("/company/overview")->with('flash', ['message' => 'Company created succesfully.']);
        }
        return redirect()->back()->withErrors(['Opps something went wrong!']);
    }

    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'business_name' => 'required',
            'company_size' => 'required',
            'legal_registration_no' => 'required',
            'company_type' => 'required',
            'business_name' => 'required',
            'business_subdomain' => 'required',
            'company_website' => 'required',
            'skype_profile' => 'required',
            'linkedin_profile' => 'required',
            'short_description' => 'required',
            'corporation_type' => 'required',
            'business_description' => 'required',
        ]);
        $company = Company::where(['id' => $this->companyId()])->update(
            [
                'company_name' => $request->business_name,
                'company_size_id' => $request->company_size,
                'legal_registration_no' => $request->legal_registration_no,
                'company_type' => $request->company_type,
                'subdomain' => $request->business_subdomain,
                'website' => $request->company_website,
                'skype_profile' => $request->skype_profile,
                'linkedin_profile' => $request->linkedin_profile,
                'short_description' => $request->short_description,
                'corporation_type_id' => $request->corporation_type,
                'description' => $request->business_description,
            ]
        );
        if ($company) {
            return redirect("/company/overview")->with('flash', ['message' => 'Redirect successfully updated.']);
        }
        return redirect()->back()->withErrors(['Opps something went wrong!']);
    }

    public function overview()
    {
        $company = Company::find($this->companyId());
        return Inertia::render('Company/Overview', [
            'sizes' => CompanySize::get(),
            'header' => [],
            'company' => new CompanyResource($company),
        ]);
    }
    public function addresses()
    {
        $company = Company::where('id', $this->companyId())->first();
        if ($company) {
            return Inertia::render('Company/Address', [
                'addresses' => count($company->addresses) > 0 ? AddressResource::collection($company->addresses) : ['data' => []],
                'company' => new CompanyResource($company),
                'countries' => $this->countries
            ]);
        }
        return redirect()->back();
    }
    public function addAddress(Request $request)
    {
        $address = [];
        $request->validate([
            'address_line_1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'pincode' => 'required',
        ]);

        if (CompanyUser::where(['user_id' => $this->uid(), 'company_id' => $this->companyId()])->first()) {
            $addresses = CompanyAddress::where('company_id', $this->companyId())->get();
            if ($request->is_primary) {
                foreach ($addresses as $address) {
                    $address = Address::where(['id' => $address->address_id])->update(['is_primary' => 0]);
                }
            }
            $address = Address::create([
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'city' => $request->city,
                'state' => $request->state,
                'country_id' => $request->country,
                'pincode' => $request->pincode,
                'is_primary' => $request->is_primary ? 1 : 0,

            ]);
            $companyAddress = CompanyAddress::create([
                'company_id' => $this->companyId(),
                'address_id' => $address->id,
            ]);
            if ($companyAddress) {
                return redirect("/company/addresses")->with('flash', ['message' => 'Address successfully created.']);
            }
            return redirect()->back()->withErrors(['Opps something went wrong!']);
        }
        return redirect()->back();
    }
    public function updateAddress(Request $request)
    {
        $address = [];
        $request->validate([
            'address_line_1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'pincode' => 'required',
        ]);
        if (Company::where(['id' => $this->companyId()])->first()) {
            if ($addresses = CompanyAddress::where(['company_id' => $this->companyId()])->get()) {

                if ($request->is_primary) {
                    foreach ($addresses as $address) {
                        $address = Address::where(['id' => $address->address_id])->update(['is_primary' => 0]);
                    }
                }
                $address = Address::where(['id' => $request->id])->update([
                    'address_line_1' => $request->address_line_1,
                    'address_line_2' => $request->address_line_2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'country_id' => $request->country,
                    'pincode' => $request->pincode,
                    'is_primary' => $request->is_primary ? 1 : 0,
                ]);
                return redirect("/company/addresses")->with('flash', ['message' => 'Address successfully updated.']);
            }
            return redirect()->back()->withErrors(['Opps something went wrong!']);
        }
        return redirect()->back();
    }

    public function delAddress($id)
    {
        if (Address::where(['id' => $id])->delete()) {
            $address = CompanyAddress::where(['address_id' => $id])->delete();
            if ($address) {
                return response()->json(['success' => true, 'message' => 'Address successfully deleted.']);
            }
            return response()->json(['success' => true, 'message' => 'Opps something went wrong!']);
        }
    }


    public function accounts()
    {
        $company = Company::where('id', $this->companyId())->first();
        if ($company) {
            return Inertia::render('Company/Account', [
                'accounts' => count($company->accounts) > 0 ? AccountResource::collection($company->accounts) : ['data' => []],
                'company' => new CompanyResource($company),
            ]);
        }
    }

    public function addAccount(Request $request)
    {
        $account = [];
        $request->validate([
            'bank_name' => 'required',
            'bank_address' => 'required',
            'beneficiary_name' => 'required',
            'account_number' => 'required',
            'routing_number' => 'required',
            'swift_code' => 'required',
            'ifsc_code' => 'required',
            'sort_code' => 'required',
            'pan_number' => 'required',
        ]);
        if (Company::where(['id' => $this->companyId()])->first()) {
            $account = CompanyAccount::where(['company_id' => $this->companyId()])->get();

            foreach ($account as $account) {
                Account::where(['id' => $account->account_id])->update(['is_primary' => 0]);
            }
            $account = Account::create([
                'bank_name' => $request->bank_name,
                'bank_address' => $request->bank_address,
                'beneficiary_name' => $request->beneficiary_name,
                'account_number' => $request->account_number,
                'routing_number' => $request->routing_number,
                'swift_code' => $request->swift_code,
                'ifsc_code' => $request->ifsc_code,
                'sort_code' => $request->sort_code,
                'pan_number' => $request->pan_number,
                'is_primary' => $request->is_primary ? 1 : 0,
            ]);

            $account = CompanyAccount::create(['company_id' => $this->companyId(), 'account_id' => $account->id]);

            if ($account) {
                return redirect("/company/accounts")->with('flash', ['message' => 'Account successfully created.']);
            }
            return redirect()->back()->withErrors(['Opps something went wrong!']);
        }
        return redirect()->back();
    }
    public function updateAccount(Request $request)
    {
        $account = [];
        $request->validate([
            'bank_name' => 'required',
            'bank_address' => 'required',
            'beneficiary_name' => 'required',
            'account_number' => 'required',
            'routing_number' => 'required',
            'swift_code' => 'required',
            'ifsc_code' => 'required',
            'sort_code' => 'required',
            'pan_number' => 'required',
        ]);
        if (Company::where(['id' => $this->companyId()])->first()) {
            if ($account = CompanyAccount::where(['Company_id' => $this->companyId()])->get()) {

                foreach ($account as $account) {
                    Account::where(['id' => $account->account_id])->update(['is_primary' => 0]);
                }
                $account = Account::where(['id' => $request->id])->update([
                    'bank_name' => $request->bank_name,
                    'bank_address' => $request->bank_address,
                    'beneficiary_name' => $request->beneficiary_name,
                    'account_number' => $request->account_number,
                    'routing_number' => $request->routing_number,
                    'swift_code' => $request->swift_code,
                    'ifsc_code' => $request->ifsc_code,
                    'sort_code' => $request->sort_code,
                    'pan_number' => $request->pan_number,
                    'is_primary' => $request->is_primary ? 1 : 0,
                ]);
            }
            if ($account) {
                return redirect("/company/accounts")->with('flash', ['message' => 'Account successfully updated.']);
            }
            return redirect()->back()->withErrors(['Opps something went wrong!']);
        }
        return redirect()->back();
    }
    public function delAccount($id)
    {
        if (Account::where(['id' => $id])->delete()) {
            $account = CompanyAccount::where(['account_id' => $id])->delete();
            if ($account) {
                return response()->json(['success' => true, 'message' => 'Account successfully deleted.']);
            }
            return response()->json(['success' => true, 'message' => 'Opps something went wrong!']);
        }
    }

    public function emails()
    {
        $company = Company::where('id', $this->companyId())->first();

        if ($company) {
            return Inertia::render('Company/Email', [
                'emails' => count($company->emails) > 0 ? EmailResource::collection($company->emails) : [],
                'company' => new CompanyResource($company),
            ]);
        }
    }

    public function addEmail(Request $request)
    {
        $email = [];
        $request->validate([
            'email_address' => 'required|unique:company_emails,email_address',
        ]);
        if (CompanyUser::where(['user_id' => $this->uid(), 'company_id' => $this->companyId()])->first()) {
            $email = CompanyEmail::where('company_id', $this->companyId());
            foreach ($email as $email) {
                CompanyEmail::where(['id' => $email->id])->update(['is_primary' => 0]);
            }
            $email =  CompanyEmail::create([
                'email_address' => $request->email_address,
                'is_primary' => $request->is_primary ? 1 : 0,
                'company_id' => $this->companyId(),
            ]);
            if ($email) {
                return redirect("/company/emails")->with('flash', ['message' => 'Email successfully created.']);
            }
            return redirect()->back()->withErrors(['Opps something went wrong!']);
        }
        return redirect()->back();
    }
    public function updateEmail(Request $request)
    {
        $email = [];
        $request->validate([
            'email_address' => 'required',

        ]);
        if (Company::where(['id' => $this->companyId()])->first()) {
            if ($email = CompanyEmail::where(['company_id' => $this->companyId()])->get()) {
                foreach ($email as $email) {
                    CompanyEmail::where(['id' => $email->id])->update(['is_primary' => 0]);
                }
                $email = CompanyEmail::where(['id' => $request->id])->update([
                    'email_address' => $request->email_address,
                    'is_primary' => $request->is_primary ? 1 : 0,
                    'company_id' => $this->companyId(),
                ]);
            }
            if ($email) {
                return redirect("/company/emails")->with('flash', ['message' => 'Email successfully updated.']);
            }
            return redirect()->back()->withErrors(['Opps something went wrong!']);
        }
        return redirect()->back();
    }
    public function delEmail($id)
    {
        $email = CompanyEmail::where(['id' => $id])->delete();
        if ($email) {
            return response()->json(['success' => true, 'message' => 'Email successfully deleted.']);
        }
        return response()->json(['success' => true, 'message' => 'Opps something went wrong!']);
    }

    public function redirects()
    {
        $company = Company::where('id', $this->companyId())->first();
        if ($company) {
            return Inertia::render('Company/Redirect', [
                'redirect' => $company->redirect ? new RedirectResource($company->redirect->redirect) : [],
                'company' => new CompanyResource($company),
            ]);
        }
    }
}
