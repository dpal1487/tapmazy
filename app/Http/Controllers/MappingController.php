<?php

namespace App\Http\Controllers;

use App\Events\SendMessage;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\ProjectLinkResource;
use App\Http\Resources\RespondentResource;
use App\Http\Resources\StateResource;
use App\Http\Resources\SupplierProjectResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Project;
use App\Models\ProjectActivity;
use App\Models\ProjectLink;
use App\Models\Respondent;
use App\Models\State;
use App\Models\SupplierProject;
use App\Notifications\ActionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Str;

class MappingController extends Controller
{

    public $clients, $countries, $suppliers, $status;
    public function __construct()
    {
        $this->countries = CountryResource::collection(Country::orderBy('name', 'asc')->get());
    }
    public function create(Request $request, $id)
    {
        $project = ProjectLink::where('project_id', $id)->first();
        if ($project) {
            $links = ProjectLink::where('project_id', $id)->get();
            return Inertia::render('Mapping/Create', [
                'project' => [
                    'project_name' => $project->project_name,
                    'id' => $project->project_id,
                    'cpi' => $project->cpi,
                    'ir' => $project->ir,
                    'loi' => $project->loi,
                    'sample_size' => $project->sample_size,
                    'target' => $project->target,
                ],
                'links' => ProjectLinkResource::collection($links),
                'countries' => $this->countries,
            ]);
        }
        return redirect('/');
    }

    public function store(Request $request, $id)
    {
        $stringToCheck = $request->input('project_link'); // Replace 'your_string_key' with the actual key in your request
        $containsRespondentID = Str::contains($stringToCheck, 'RespondentID');

        $request->validate([
            'project_name' => 'required',
            'cpi' => 'required',
            'loi' => 'required|numeric',
            'ir' => 'required|numeric',
            'project_link' => 'required|url',
            'sample_size' => 'required|numeric',
        ]);
        $zipcode = preg_replace('/\s+/', ' , ',  $request->project_zipcode);
        if ($containsRespondentID) {
            if (ProjectLink::create([
                'project_id' => $id,
                'user_id' => Auth::user()->id,
                'cpi' => $request->cpi,
                'project_name' => $request->project_name,
                'loi' => $request->loi,
                'ir' => $request->ir,
                'project_link' => $request->project_link,
                'sample_size' => $request->sample_size,
                'notes' => $request->notes,
                'country_id' => $request->project_country,
                'state' => implode(' , ', $request->project_state),
                'city' => implode(' , ', $request->project_city),
                'zipcode' => $zipcode,
                'status' => $request->status,
            ])) {
                return response()->json(createMessage('Project link'));

                // if ($request->add_more) {
                //     return redirect()->back()->with('flash', createMessage('Project Link'));
                // }
                // return redirect("/project/" . $id)->with('flash', createMessage('Project Link'));
            }
            return redirect()->back()->withErrors(errorMessage());
        }
        return response()->json([
            'success' => false,
            'message' => 'Project link should be RespondentID'
        ]);
    }

    public function show($id)
    {
        $surveys = Respondent::orderBy('created_at', 'desc')->where('project_link_id', $id)->paginate(10);
        $project = ProjectLink::find($id);
        if ($project) {
            return Inertia::render('Mapping/Show', [
                'project' => new ProjectLinkResource($project),
                'respondents' => RespondentResource::collection($surveys),
                'countries' => $this->countries,
                'states' => StateResource::collection(State::where('country_id', $project->country_id)->get()),
                'cities' => CityResource::collection(City::where('country_id', $project->country_id)->get()),
            ]);
        }
        return redirect()->back();
    }


    public function edit($id)
    {
        $project = ProjectLink::find($id);
        if ($project) {
            return response()->json([
                'project' => new ProjectLinkResource($project),
                'states' => StateResource::collection(State::where('country_id', $project->country_id)->get()),
                'cities' => CityResource::collection(City::where('country_id', $project->country_id)->get()),
                'countries' => $this->countries,
            ]);
        }
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $stringToCheck = $request->input('project_link'); // Replace 'your_string_key' with the actual key in your request
        $containsRespondentID = Str::contains($stringToCheck, 'RespondentID');
        $validator =  Validator::make($request->all(), [
            'project_name' => 'required',
            'cpi' => 'required',
            'loi' => 'required|numeric',
            'ir' => 'required|numeric',
            'project_link' => 'required',
            'project_country' => 'required',
            'sample_size' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
        $zipcode = preg_replace('/\s+/', ' , ',  $request->project_zipcode);

        if ($containsRespondentID) {

            if ($project = ProjectLink::find($id)) {
                ProjectLink::where('id', $id)->update([
                    // 'user_id' => Auth::user()->id,
                    'cpi' => $request->cpi,
                    'project_name' => $request->project_name,
                    'loi' => $request->loi,
                    'ir' => $request->ir,
                    'project_link' => $request->project_link,
                    'sample_size' => $request->sample_size,
                    'target' => $request->target,
                    'country_id' => $request->project_country,
                    'state' => implode(' , ', $request->project_state),
                    'city' => implode(' , ', $request->project_city),
                    'zipcode' => $zipcode,
                    'status' => $request->status,
                ]);
                return response()->json(updateMessage('Project Link'));
                // return redirect("/project/{$project->project_id}")->with('flash', updateMessage('Project Link'));
            }
            return response()->json(errorMessage());
        }
        return response()->json([
            'success' => false,
            'message' => 'Project link should be RespondentID'
        ]);
    }
    public function projectLinkSuppliers($id)
    {
        $project = ProjectLink::find($id);
        if ($project) {
            return response()->json([
                'project' => new ProjectLinkResource($project),
                'suppliers' => SupplierProjectResource::collection(SupplierProject::where('project_link_id', $id)->get()),
                'countries' => $this->countries,
            ]);
        }
        return redirect()->back();
    }

    public function suppliers($id)
    {
        $project = ProjectLink::find($id);

        $states = State::where('country_id', $project->country_id)->get();
        $cities = City::where('country_id', $project->country_id)->get();
        if ($project) {
            return Inertia::render('Mapping/Supplier', [
                'project' => new ProjectLinkResource($project),
                'suppliers' => SupplierProjectResource::collection(SupplierProject::where('project_link_id', $id)->get()),
                'states' => StateResource::collection($states),
                'cities' => CityResource::collection($cities),
                'countries' => $this->countries,
            ]);
        }
        return redirect()->back();
    }
    public function destroy($id)
    {
        if (ProjectLink::where('id', $id)->delete()) {
            return response()->json(deleteMessage('Project Link'));
        }
        return response()->json(errorMessage());
    }

    public function status(Request $request)
    {
        $project =  ProjectLink::where(['id' => $request->id])->first();

        $statusValue =   $request->status ? "activate" : "inactivate";

        if (ProjectLink::where(['id' => $request->id])->update(['status' => $request->status ? 1 : 0])) {
            $status = $request->status  ? "activate" : "inactivate";

            $activity = ProjectActivity::create([
                "project_id" => $project->project_id,
                "type_id" => "status",
                "text" =>  $project->project_name . " has been " . $statusValue,
                "user_id"   => Auth::user()->id,
            ]);
            broadcast(new SendMessage($project->project_id));
            auth()->user()->notify(new ActionNotification(Project::where('id',  $project->project_id)->first(), Auth::user(), $project->project_name . " has been " . $statusValue,));

            return response()->json(statusMessage('Project Link'));
        }
        return response()->json(errorMessage());
    }

    public function supplierProjectStatus(Request $request)
    {
        if (SupplierProject::where(['id' => $request->id])->update(['status' => $request->status ? 1 : 0])) {
            $status = $request->status  ? "activate" : "inactivate";
            return response()->json(statusMessage('Project Link'));
        }
        return response()->json(errorMessage());
    }

    public function sampleSize(Request $request)
    {
        if (ProjectLink::where(['id' => $request->id])->update(['sample_size' => $request->sample_size])) {
            return response()->json(updateMessage('Sample size'));
            // return redirect("/project/{$request->project_id}")->with('flash', updateMessage('Project Link'));
        }
        return response()->json(errorMessage());
    }
}
