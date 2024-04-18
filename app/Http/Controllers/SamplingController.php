<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\SupplierProjectResource;
use App\Http\Resources\SupplierResource;
use App\Models\Country;
use App\Models\Project;
use App\Models\ProjectLink;
use App\Models\Redirect;
use App\Models\Supplier;
use App\Models\SupplierProject;
use App\Models\SupplierRedirect;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SamplingController extends Controller
{
    public $clients, $countries, $suppliers, $status;
    public function __construct($data = array())
    {
        $this->countries = CountryResource::collection(Country::orderBy('name', 'asc')->get());
    }
    public function create(Request $request, $id)
    {
        $this->suppliers = SupplierResource::collection(Supplier::where(['company_id'=>$this->companyId()])->orderBy('supplier_name', 'asc')->get());
        $project = ProjectLink::find($id);
        if ($project) {
            $projects = SupplierProject::where(['project_link_id' => $id])->paginate(10);
            return Inertia::render('Sampling/Create', [
                'projects' => SupplierProjectResource::collection($projects),
                'countries' => $this->countries,
                'suppliers' => $this->suppliers,
                'project' => $project
            ]);
        }
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
            'supplier' => 'required',
            'project_cpi' => 'required|numeric',
            'sample_size' => 'required|numeric',
            'complete_url' => 'required',
            'terminate_url' => 'required',
            'quotafull_url' => 'required',
            'security_terminate_url' => 'required',
            'project_status' => 'required|numeric',
        ]);
        if (!SupplierProject::where(['project_link_id' => $request->id, 'supplier_id' => $request->supplier])->first()) {
            if ($supplier = SupplierProject::create([
                'project_link_id' => $request->project_link_id,
                'company_id' => $this->companyId(),
                'project_id' => $request->project_id,
                'supplier_id' => $request->supplier,
                'cpi' => $request->project_cpi,
                'sample_size' => $request->sample_size,
                'complete_url' => $request->complete_url,
                'terminate_url' => $request->terminate_url,
                'quotafull_url' => $request->quotafull_url,
                'security_terminate_url' => $request->security_terminate_url,
                'notes' => $request->notes,
                'status' => $request->project_status,
            ])) {

                SupplierProject::where(['id' => $supplier->id])->update(['supplier_link' => env('APP_URL')."/surveyRoute/$supplier->id?uid=XXX"]);
                if ($request->action == 'more') {
                    return redirect()->back()->with('flash', ['message' => 'Supplier successfully added.']);
                }
                if ($request->redirect_to == 'supplier') {
                    return redirect("/supplier/$request->supplier/'suppliers")->with('flash', ['message' => 'Supplier successfully added.']);
                }
                return redirect("/project/" . $request->project_id.'/suppliers')->with('flash', ['message' => 'Supplier successfully added.']);
            }
            return redirect()->back()->withErrors(['Opps something went wrong!']);
        }
        return redirect()->back()->withErrors(['Supplier already added.']);
    }

    
    public function edit($id)
    {

        
        $this->suppliers = SupplierResource::collection(Supplier::where(['company_id'=>$this->companyId()])->orderBy('supplier_name', 'asc')->get());
        $project = SupplierProject::find($id);
        if ($project) {
            $projects = SupplierProject::where(['project_link_id' => $project->project_link_id])->paginate(10);
            return Inertia::render('Sampling/Edit', [
                'projects' => SupplierProjectResource::collection($projects),
                'suppliers' => $this->suppliers,
                'project' => new SupplierProjectResource($project)
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier' => 'required',
            'project_cpi' => 'required|numeric',
            'sample_size' => 'required|numeric',
            'complete_url' => 'required',
            'terminate_url' => 'required',
            'quotafull_url' => 'required',
            'security_terminate_url' => 'required',
            'project_status' => 'required|numeric',
        ]);
        if (SupplierProject::where('id', $id)->update([
            'company_id' => $this->companyId(),
            'supplier_id' => $request->supplier,
            'cpi' => $request->project_cpi,
            'sample_size' => $request->sample_size,
            'complete_url' => $request->complete_url,
            'terminate_url' => $request->terminate_url,
            'quotafull_url' => $request->quotafull_url,
            'security_terminate_url' => $request->security_terminate_url,
            'notes' => $request->notes,
            'status' => $request->project_status,
            'supplier_link' => env('APP_URL')."/surveyRoute/$id?uid=XXX"
        ])) {
            if ($request->redirect_to == 'supplier') {
                return redirect("/supplier/$request->supplier/'suppliers")->with('flash', ['message' => 'Supplier successfully updated.']);
            }
            return redirect("/project/" . $request->project_id."/suppliers")->with('flash', ['message' => 'Supplier successfully updated.']);
        }
        return redirect()->back()->withErrors(['Opps something went wrong!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($supplier = SupplierProject::where(['company_id'=>$this->companyId(),'id'=>$id])->delete()){
            if ($supplier) {
                return response()->json([
                    'success' => true,
                    'message' => 'Supplier Deleted Successfully'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong '
            ]);
        }
    }
    public function redirect($id)
    {
        $redirect = SupplierRedirect::where('supplier_id', $id)->first();
        if ($redirect) {
            return response()->json(['success' => true, 'data' => Redirect::find($redirect->id)]);
        }
        return response()->json(['success' => false, 'message' => 'Redirect not exists.']);
    }
}
