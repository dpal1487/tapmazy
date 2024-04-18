<?php

namespace App\Http\Controllers;


use Inertia\Inertia;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;

use App\Models\{Invoice, Client, InvoiceItem, ClientAddress, Currency};
use App\Http\Resources\{AddressResource, ClientListResource, ClientResource, CurrencyResource, InvoiceListResource, InvoiceResource, PlanResource};

class InvoiceController extends Controller
{
    private $clients, $status;
    public function __construct()
    {
        $this->clients = ClientListResource::collection(Client::where(['status' => 1])->get());
        $this->status = [
            ['label' => 'Paid', 'id' => 1],
            ['label' => 'Unpaid', 'id' => 2],
            ['label' => 'Canelled', 'id' => 3],
        ];
    }
    public function index(Request $request)
    {
        $invoices = new Invoice();
        $invoiceReport = $invoices;
        if (!empty($request->q)) {
            $invoices = $invoices->where('invoice_number', 'like', '%' . $request->q . '%');
        }
        if ($request->status !== 'all' && $request->status != null) {
            $invoices = $invoices->where('status',  (int)$request->status);
        }
        if (!empty($request->client)) {
            $invoices = $invoices->where('client_id', '=', $request->client);
        }
        return Inertia::render('Invoice/Index', [
            'invoices' => InvoiceListResource::collection($invoices->latest()->paginate(10)->appends($request->all())),
            'allowed_invoices' => auth()->user()->stripePlan,

            'clients' => $this->clients,
            'status' => $this->status,
            'reports' => [
                'sent_invoices' => [
                    'count' => $invoiceReport->count(),
                    'total_amount' => $invoiceReport->sum('total_amount'),
                ],
                'paid_invoices' => [
                    'count' => $invoiceReport->where('status', 1)->count(),
                    'total_amount' => $invoiceReport->where('status', 1)->sum('total_amount'),
                ],
                'unpaid_invoices' => [
                    'count' => $invoiceReport->where('status', 2)->count(),
                    'total_amount' => $invoiceReport->where('status', 2)->sum('total_amount'),
                ],
                'cancel_invoices' => [
                    'count' => $invoiceReport->where('status', 3)->count(),
                    'total_amount' => $invoiceReport->where('status', 3)->sum('total_amount'),
                ],
            ],
        ]);
    }
    public function create()
    {
        $currencies = Currency::get();
        $clients = Client::get();
        return Inertia::render('Invoice/Create', [
            'clients' => ClientResource::collection($clients),
            'currencies' => CurrencyResource::collection($currencies),
            'status' => $this->status,
            'invoices' => count(Invoice::get()),
            'allowed_invoices' => new PlanResource(auth()->user()->stripePlan),
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'issue_date' => 'required',
            'due_date' => 'required',
            'from_address' => 'required',
            'to_address' => 'required',
            'currency' => 'required',
            'type' => 'required',
            'tax_rate' => 'required',
            'client' => 'required',
            'conversion_rate' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'status' => 'required',
            'total_amount' => 'required'
        ]);

        $id = IdGenerator::generate(['table' => 'invoices', 'field' => 'invoice_number', 'length' => 10, 'prefix' => 'INV-']);
        $invoice = Invoice::create([
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date,
            'invoice_number' => $id,
            'from_address' => $request->from_address,
            'to_address' => $request->to_address,
            'type' => $request->type,
            'tax_rate' => $request->tax_rate,
            'client_id' => $request->client,
            'conversion_rate' => $request->conversion_rate,
            'currency_id' => $request->currency['id'],
            'total_amount' => $request->total_amount,
            'notes' => $request->notes,
            'status' => $request->status,
            'total_amount' => $request->total_amount,
        ]);

        foreach ($request->items as $value) {
            $invoiceItem = InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'project_name' => $value['project_name'],
                'cpi' => $value['cpi'],
                'quantity' => $value['quantity'],
            ]);
        }
        if ($invoiceItem) {
            return redirect('/invoices')->with('flash', createMessage('Invoice'));
        }
        return redirect()->back()->withErrors(errorMessage());
    }

    public function edit($id)
    {
        $invoice = Invoice::find($id);
        $currencies = Currency::get();
        $clients = Client::get();
        return Inertia::render('Invoice/Edit', [
            'invoice' => new InvoiceResource($invoice),
            'clients' => ClientResource::collection($clients),
            'currencies' => CurrencyResource::collection($currencies),
            'status' => $this->status
        ]);
    }
    public function show($id)
    {
        $invoice = Invoice::find($id);
        return Inertia::render('Invoice/Show', [
            'invoice' => new InvoiceResource($invoice),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'issue_date' => 'required',
            'due_date' => 'required',
            'from_address' => 'required',
            'to_address' => 'required',
            'currency' => 'required',
            'type' => 'required',
            'tax_rate' => 'required',
            'client' => 'required',
            'conversion_rate' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'status' => 'required',
            'total_amount' => 'required',
            'items.*.project_name' => 'required',
            'items.*.cpi' => 'required',
            'items.*.quantity' => 'required',
        ]);

        $invoice = Invoice::where('id', $id)->update([
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date,
            'from_address' => $request->from_address,
            'to_address' => $request->to_address,
            'type' => $request->type,
            'tax_rate' => $request->tax_rate,
            'client_id' => $request->client,
            'conversion_rate' => $request->conversion_rate,
            'currency_id' => $request->currency['id'],
            'total_amount' => $request->total_amount,
            'notes' => $request->notes,
            'status' => $request->status,
            'total_amount' => $request->total_amount,
        ]);
        InvoiceItem::where('invoice_id', $id)->delete();
        foreach ($request->items as $value) {
            $invoiceItem = InvoiceItem::create([
                'invoice_id' => $id,
                'project_name' => $value['project_name'],
                'cpi' => $value['cpi'],
                'quantity' => $value['quantity'],
            ]);
        }
        if ($invoice) {
            return redirect('/invoices')->with('flash', updateMessage('Invoice'));
        }
        return redirect()->back()->withErrors(errorMessage());
    }
    public function destroy($id)
    {

        $invoice = Invoice::find($id);
        if ($invoice->delete()) {
            return response()->json(['success' => true, 'message' => 'Invoice has been deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'Opps something went wrong!'], 400);
    }

    public function getAddress($id)
    {
        $address = ClientAddress::where(['client_id' => $id])->first();
        return $address;
        $data = new AddressResource($address);
        if ($address) {
            return response()->json(['success' => true, 'data' => $data]);
        }
        return response()->json(['success' => false, 'message' => 'Address Not Found.']);
    }
}
