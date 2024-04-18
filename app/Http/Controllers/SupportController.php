<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeResources;
use App\Http\Resources\FaqCategoryResource;
use App\Http\Resources\FaqResource;
use App\Http\Resources\FAQsCategoryResource;
use App\Http\Resources\SupportResource;
use App\Models\Contact;
use App\Models\Employee;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\FAQsCategory;
use App\Models\Support;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'description' => 'required',
            'priority' => 'required',

        ]);
        if ($validate->fails()) {
            return response()->json(['message' => $validate->errors()->first(), 'success' => false], 400);
        }
        $supplier = Support::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
        ]);
        if ($supplier) {
            return response()->json(createMessage('Support ticket'));
        }
        return response()->json(errorMessage());
    }

    public function show()
    {
        $employees = Employee::where('company_id', $this->companyId())->get();
        return Inertia::render('Support/Overview', [
            'employees' => EmployeeResources::collection($employees),
        ]);
    }

    public function edit(Support $support)
    {
        //
    }

    public function update(Request $request, Support $support)
    {
        //
    }

    public function destroy(Support $support)
    {
        //
    }

    public function tickets(Request $request)
    {
        $employees = Employee::where('company_id', $this->companyId())->get();
        $tickets = new Support();
        if (!empty($request->q)) {
            $tickets = $tickets->where('subject', 'like', '%' . $request->q . '%');
        }

        return Inertia::render('Support/Tickets', [
            'employees' => EmployeeResources::collection($employees),
            'tickets' => SupportResource::collection($tickets->paginate(5)),
        ]);
    }
    public function viewTickets($id)
    {
        $employees = Employee::where('company_id', $this->companyId())->get();

        $ticket = Support::find($id);

        return Inertia::render('Support/ViewTicket', [
            'employees' => EmployeeResources::collection($employees),
            'ticket' => new SupportResource($ticket),
        ]);
    }
    public function tutorials()
    {
        $employees = Employee::where('company_id', $this->companyId())->get();

        return Inertia::render('Support/Tutorials', [
            'employees' => EmployeeResources::collection($employees),
        ]);
    }
    public function viewTutorials()
    {
        $employees = Employee::where('company_id', $this->companyId())->get();

        return Inertia::render('Support/ViewTutorials', [
            'employees' => EmployeeResources::collection($employees),
        ]);
    }
    public function faq()
    {
        $employees = Employee::where('company_id', $this->companyId())->get();

        $faqs = FaqCategory::get();
        return Inertia::render('Support/Faq', [
            'employees' => EmployeeResources::collection($employees),
            'faqs' => FAQsCategoryResource::collection($faqs),
        ]);
    }
    public function licenses()
    {
        $employees = Employee::where('company_id', $this->companyId())->get();

        return Inertia::render('Support/Licenses', [
            'employees' => EmployeeResources::collection($employees),
        ]);
    }
    public function contactUs()
    {
        $employees = Employee::where('company_id', $this->companyId())->get();

        return Inertia::render('Support/ContactUs', [
            'employees' => EmployeeResources::collection($employees),
        ]);
    }
    public function contactUsStore(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'subject' => 'required',
            'message' => 'required',

        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first()]);
        }
        $contact = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'subject' => $request->subject,
            'message' => $request->message,
            'ip_address' => request()->ip(),
        ]);
        if ($contact) {
            return redirect('support/contact-us')->with('flash', ['message' => 'Thank you for contact us. we will contact you shortly.']);
        }
        return redirect()->back()->withErrors(['Opps something went wrong!']);
    }
}
