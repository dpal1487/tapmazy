<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientListResource;
use App\Http\Resources\CloseProjectListResource;
use App\Http\Resources\ProjectStatusResource;
use App\Models\Client;
use App\Models\CloseProject;
use App\Models\CloseRespondent;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\Respondent;
use App\Models\SupplierSurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CloseProjectController extends Controller
{
    public $clients, $countries, $suppliers, $status, $company, $user;

    public function __construct()
    {
        $this->clients = ClientListResource::collection(Client::where(['status' => 1])->get());
        $this->status = ProjectStatus::orderBy('id', 'asc')->get();
    }

    public function index(Request $request)
    {
        $projects = Project::orderBy('updated_at', 'desc')->groupBy('project_id')->where('status', 'closed');

        if (Auth::user()->role?->role?->slug == 'user') {
            $projects = $projects->where(['status' => 'live']);
        }
        if (!empty($request->q)) {
            $projects = $projects->where('project_name', 'like', "%{$request->q}%")->orWhere('project_id', 'like', "%{$request->q}%");
        }
        if (!empty($request->status)) {
            $projects = $projects->where('status', $request->status);
        }
        if (!empty($request->client)) {
            $projects = $projects->where('client_id', $request->client);
        }
        $projects = $projects->paginate(20)->appends(request()->query());

        return Inertia::render('CloseProject/Index', [
            'projects' => CloseProjectListResource::collection($projects),
            'status' => ProjectStatusResource::collection($this->status),
            'clients' => $this->clients,
        ]);
    }
    public function restore(Request $request)
    {
        $project = Project::where('id', '=', $request->id)->first();

        if ($project) {
            //project update close to archived project
            $project->update(['status' => 'archived']);

            $respondents = CloseRespondent::where('project_id', '=', $request->id)->get();

            if (count($respondents) > 0) {
                foreach ($respondents as $respondent) {
                    SupplierSurvey::create([
                        'client_browser' => $respondent->client_browser,
                        'device' => $respondent->device,
                        'end_ip' => $respondent->end_ip,
                        'id' => $respondent->id,
                        'project_id' => $project->id,
                        'project_link_id' => $respondent->project_link_id,
                        'starting_ip' => $respondent->starting_ip,
                        'status' => $respondent->status,
                        'supplier_id' => $respondent->supplier_id,
                        'supplier_project_id' => $respondent->supplier_project_id,
                        'user_id' => $respondent->user_id,
                    ]);
                }
                $respondents = CloseRespondent::where('project_id', '=', $request->id)->delete();
                return response()->json(restoreMessage('Project'));
            }
        }
        return response()->json(errorMessage());
    }

    public function destroy($id)
    {
        if (Project::where('id', $id)->delete()) {
            CloseRespondent::where('project_id', $id)->delete();
            return response()->json(deleteMessage('Close Project'));
        }
        return response()->json(errorMessage());
    }
}
