<?php

namespace App\Http\Controllers;

use App\Http\Resources\{ActivityProjectResource, CityResource, ClientListResource, ClientResource, CountryResource, PlanResource, ProjectLinkResource, ProjectResource, ProjectListResource, ProjectScreenerResource, ProjectStatusResource, StateResource, SupplierListResource, SupplierProjectResource};
use App\Models\{City, Country, Client, CloseRespondent, Company, Project, ProjectActivity, ProjectLink, ProjectStatus, Respondent, State, SupplierProject, SupplierSurvey, FinalId, ProjectScreener, ProjectScreenerAnswer, Supplier};
use App\Events\{NotificationEvent, SendMessage};
use App\Exports\{ExportFinalIDs, ProjectReport, ExportIdExport};
use App\Notifications\ActionNotification;
use App\Imports\IdImport;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ProjectController extends Controller
{
    public $clients, $countries, $suppliers, $status, $company, $user;
    public function __construct()
    {
        $this->countries = CountryResource::collection(Country::orderBy('name', 'asc')->get());
        $this->suppliers = SupplierListResource::collection(Supplier::orderBy('supplier_name', 'asc')->get());
        $this->status = ProjectStatus::orderBy('id', 'asc')->get();
    }
    public function project($id)
    {
        $project = Project::find($id);

        return $project;
    }
    public function user_details()
    {
        return  auth()->user()->first_name . " " . auth()->user()->last_name;
    }
    public function index(Request $request)
    {

        $this->clients = ClientListResource::collection(Client::where(['status' => 1, 'company_id' => $this->companyId()])->get());
        $projects = Project::where(['company_id' => $this->companyId()])->latest();

        if (Auth::user()->role->slug == 'user') {
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

        return Inertia::render('Project/Index', [
            'projects' => ProjectListResource::collection($projects),
            'status' => ProjectStatusResource::collection($this->status),
            'clients' => $this->clients,
            'allowed_projects' => auth()->user()->stripePlan,
        ]);
    }
    public function create()
    {
        $this->clients = ClientListResource::collection(Client::where(['status' => 1, 'company_id' => $this->companyId()])->get());
        if (Auth::user()->role->slug != 'user') {
            return Inertia::render('Project/Create', [
                'clients' => ClientResource::collection($this->clients),
                'countries' => CountryResource::collection($this->countries),
                'status' => ProjectStatusResource::collection($this->status),
                'projects' => count(Project::get()),
                'allowed_projects' => new PlanResource(auth()->user()->stripePlan),
            ]);
        }
        return redirect('/');
    }

    // get state based on country
    public function getState(Request $request)
    {
        $states = State::where('country_id', $request->country_id)->get();
        $cities = City::where('country_id', $request->country_id)->get();
        return response()->json([
            'states' => StateResource::collection($states),
            'cities' => CityResource::collection($cities),
            'success' => true,
        ]);
    }

    public function store(Request $request)
    {
        $company = Company::find($this->companyId());
        $id = IdGenerator::generate(['table' => 'projects', 'field' => 'project_id', 'length' => 10, 'prefix' => $company->initials . date('ym')]);
        $zipcode = preg_replace('/\s+/', ' , ',  $request->project_zipcode);
        $stringToCheck = $request->input('project_link'); // Replace 'your_string_key' with the actual key in your request
        $containsRespondentID = Str::contains($stringToCheck, 'RespondentID');
        $request->validate([
            'project_name' => 'required|unique:projects,project_name',
            'client' => 'required',
            'project_cpi' => 'required',
            'project_length' => 'required|numeric',
            'project_ir' => 'required|numeric',
            'start_date' => 'required',
            'end_date' => 'required',
            'project_status' => 'required',
            'project_link' => 'required',
            'project_country' => 'required',
            'device_type' => 'required',
            'project_type' => 'required',
            'sample_size' => 'required',
            'target' => 'required',
        ]);
        if (!Client::where(['id' => $request->client, 'company_id' => $this->companyId()])->get() && !Country::find($request->project_country)) {
            return response()->json(['success' => false, 'message' => 'Opps something went wrong!']);
        }
        if ($project = Project::create([
            'project_id' => $id,
            'company_id' => $this->companyId(),
            'project_name' => $request->project_name,
            'client_id' => $request->client,
            'user_id' => Auth::user()->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'device_type' => json_encode($request->device_type),
            'project_type' => $request->project_type,
            'target' => $request->target,
            'status' => $request->project_status,
        ])) {
            if (ProjectLink::create([
                'project_id' => $project->id,
                'cpi' => $request->project_cpi,
                'project_name' => $request->project_name,
                'loi' => $request->project_length,
                'ir' => $request->project_ir,
                'project_link' => $request->project_link,
                'sample_size' => $request->sample_size,
                'target' => $request->target,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'country_id' => $request->project_country,
                'state' => implode(' , ', $request->project_state),
                'city' => implode(' , ', $request->project_city),
                'zipcode' => $zipcode,
                'status' => $request->project_status,
            ])) {

                $activity = ProjectActivity::create([
                    "project_id" => $project->project_id,
                    "type_id" => "status",
                    "text" => $request->project_name . ' was created',
                    "user_id"   => Auth::user()->id,

                ]);
                broadcast(new SendMessage($project));
                broadcast(new NotificationEvent([
                    'user_id' => auth()->user()->id,
                    'message' => 'Project - ' . $project->project_name . ' with id - ' . $project->project_id . ' was created by ' . $this->user_details() . '.',
                    'type' => 'notification',
                    'title' => 'Project - ' . $project->project_id
                ]));
                auth()->user()->notify(new ActionNotification($project, auth()->user(), $request->project_name . ' was created'));

                if (!empty($request->add_more)) {
                    return redirect("/project/create")->with('flash', ['message' =>  createMessage('Project')]);
                }
                return redirect("/projects")->with('flash', ['message' =>  createMessage('Project')]);
            }
            return redirect()->back()->withErrors(['Opps something went wrong!']);
        }
    }



    public function projectClone(Request $request)
    {
        function randomNumber($inputString)
        {
            $autoNumber =  time();
            $number = $autoNumber % 100;
            $pattern = '/^(.+) clone-(\d+)$/';
            if (preg_match($pattern, $inputString, $matches)) {
                return [
                    "project_name" => $matches[1],
                    "number" => $number
                ];
            } else {
                return [
                    "project_name" => $inputString,
                    "number" => 1
                ];
            }
        }


        $project = Project::where('id', $request->id)->first();

        $projectLinks = ProjectLink::where('project_id', $project->id)->get();
        if ($project) {
            if ($project = Project::create([
                'project_id' => $project->project_id,
                'company_id' => $this->companyId(),
                'project_name' => randomNumber($project->project_name)["project_name"] . " clone-" . (randomNumber($project->project_name)["number"]),
                'client_id' => $project->client_id,
                'user_id' => Auth::user()->id,
                'start_date' => $project->start_date,
                'end_date' => $project->end_date,
                'device_type' => $project->device_type,
                'project_type' => $project->project_type,
                'target' => $project->target,
                'status' => $project->status,
            ])) {
                foreach ($projectLinks as $projectlink) {
                    ProjectLink::create([
                        'project_id' => $project->id,
                        'user_id' => Auth::user()->id,
                        'cpi' => $projectlink->cpi,
                        'project_name' => $projectlink->project_name,
                        'loi' => $projectlink->loi,
                        'ir' => $projectlink->ir,
                        'project_link' => $projectlink->project_link,
                        'sample_size' => $projectlink->sample_size,
                        'notes' => $projectlink->notes,
                        'start_date' => $projectlink->start_date,
                        'end_date' => $projectlink->end_date,
                        'country_id' => $projectlink->country_id,
                        'status' => $projectlink->status,
                    ]);
                }
                $activity = ProjectActivity::create([
                    "project_id" => $project->project_id,
                    "type_id" => "status",
                    "text" => $request->project_name . ' was clone',
                    "user_id"   => Auth::user()->id,
                ]);
                broadcast(new SendMessage($project));
                broadcast(new NotificationEvent([
                    'user_id' => auth()->user()->id,
                    'message' => 'Project - ' . $project->project_name . ' with id - ' . $project->project_id . ' was clone by ' . $this->user_details() . '.',
                    'type' => 'notification',
                    'title' => 'Project - ' . $project->project_id
                ]));
                auth()->user()->notify(new ActionNotification($project, auth()->user(), $project->project_name . ' was clone'));
                return response()->json(createMessage('Project clone'));
            }
            return redirect()->back()->withErrors(errorMessage());
        }
    }
    public function show(Request $request, $id)
    {
        $project = Project::find($id);
        $links = ProjectLink::where('project_id', $id);
        $this->clients = Client::where(['status' => 1, 'company_id' => $this->companyId()])->get();
        $this->status = ProjectStatus::orderBy('id', 'asc')->get();
        $this->countries = CountryResource::collection(Country::orderBy('name', 'asc')->get());

        if (!empty($project)) {
            // $links = ProjectLink::where('project_id', $id)->paginate(10)->appends(request()->query());
            return Inertia::render('Project/Show', [
                'project' => new ProjectResource($project),
                'project_links' => ProjectLinkResource::collection($links->get()),
                'clients' => ClientResource::collection($this->clients),
                'status' => ProjectStatusResource::collection($this->status),
                'countries' => CountryResource::collection($this->countries),
            ]);
        }
        return redirect(route('projects'));
    }
    public function edit($id)
    {
        $this->clients = ClientListResource::collection(Client::where(['status' => 1, 'company_id' => $this->companyId()])->get());
        $this->countries = CountryResource::collection(Country::orderBy('name', 'asc')->get());
        $this->status = ProjectStatus::orderBy('id', 'asc')->get();
        $project = Project::find($id);
        return Inertia::render('Project/Edit', [
            'project' => new ProjectResource($project),
            'clients' => $this->clients,
            'countries' => CountryResource::collection($this->countries),
            'status' => ProjectStatusResource::collection($this->status)
        ]);
    }
    public function update(Request $request)
    {
        $request->validate([
            'project_name' => 'required',
            'client' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'project_status' => 'required',
            'device_type' => 'required',
            'project_type' => 'required',
            'target' => 'required',
        ]);
        $project = Project::find($request->id);


        if (Project::where('id', $request->id)->update([
            'project_name' => $request->project_name,
            'client_id' => $request->client,
            'target' => $request->target,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'device_type' => json_encode($request->device_type),
            'project_type' => $request->project_type,
            'target' => $request->target,
            'status' => $request->project_status,
        ])) {
            $activity = ProjectActivity::create([
                "project_id" => $request->id,
                "type_id" => "status",
                "text" => $request->project_name . " was updated",
                "user_id"   => Auth::user()->id,

            ]);
            broadcast(new SendMessage($this->project($request->id)));
            broadcast(new NotificationEvent([
                'user_id' => auth()->user()->id,
                'message' => 'Project - ' . $this->project($request->id)->project_name . ' with id - ' . $this->project($request->id)->project_id . ' was updated by ' . $this->user_details() . '.',
                'type' => 'notification',
                'title' => 'Project - ' . $this->project($request->id)->project_id
            ]));
            auth()->user()->notify(new ActionNotification($project, auth()->user(), $request->project_name . ' was updated'));

            if ($request->action == 'project-edit') {
                return redirect("/project/" . $request->id)->with('flash', UpdateMessage('Project'));
            }
            if ($request->action == 'project_show') {
                return redirect('project/' . $request->id)->with('flash', updateMessage('Project'));
            }
            return redirect("projects")->with('flash', UpdateMessage('Project'));
        }
        return redirect()->back()->withErrors(['Opps something went wrong!']);
    }
    public function destroy($id)
    {
        $project = Project::where('id', $id)->first();

        if (Project::where('id', $id)->delete()) {
            ProjectLink::where('project_id', $id)->delete();
            SupplierSurvey::where('project_id', $id)->delete();
            $activity = ProjectActivity::create([
                "project_id" => $project->project_id,
                "type_id" => "status",
                "text" => $project->project_name . ' was deleted',
                "user_id"   => Auth::user()->id,
            ]);
            broadcast(new SendMessage($this->project($id)));

            broadcast(new NotificationEvent([
                'user_id' => auth()->user()->id,
                'message' => 'Project - ' . $this->project($id)?->project_name . ' with id - ' . $this->project($id)?->project_id . ' was deleted by ' . $this->user_details() . '.',
                'type' => 'notification',
                'title' => 'Project - ' . $this->project($id)?->project_id
            ]));
            auth()->user()->notify(new ActionNotification($project,  Auth::user(), $project?->project_name . ' was deleted'));

            return response()->json(['message' => 'Project deleted successfully.', 'success' => true]);
        }
        return response()->json(['message' => 'Opps! something went wrong.', 'success' => false], 400);
    }

    public function status(Request $request)
    {
        $notification = $this->project($request->id)->project_name . " has been " . $request->status;

        if ($request->status == 'closed') {
            $respondents = SupplierSurvey::where('project_id', '=', $request->id)->get();
            if (count($respondents) > 0) {
                foreach ($respondents as $respondent) {
                    $create =  CloseRespondent::create([
                        'client_browser' => $respondent->client_browser,
                        'device' => $respondent->device,
                        'end_ip' => $respondent->end_ip,
                        'id' => $respondent->id,
                        'project_id' => $respondent->project_id,
                        'project_link_id' => $respondent->project_link_id,
                        'starting_ip' => $respondent->starting_ip,
                        'status' => $respondent->status,
                        'supplier_id' => $respondent->supplier_id,
                        'supplier_project_id' => $respondent->supplier_project_id,
                        'user_id' => $respondent->user_id,
                    ]);
                }
                $respondents = SupplierSurvey::where('project_id', '=', $request->id)->delete();
            }
            $activity = ProjectActivity::create([
                "project_id" => $request->id,
                "type_id" => "status",
                "text" => $notification,
                "user_id"   => Auth::user()->id,

            ]);
            broadcast(new SendMessage($this->project($request->id)));
            broadcast(new NotificationEvent([
                'user_id' => auth()->user()->id,
                'message' => 'Project - ' . $this->project($request->id)->project_name . ' with id - ' . $this->project($request->id)->project_id . ' has been ' . $request->status . ' ' . $this->user_details() . '.',
                'type' => 'notification',
                'title' => 'Project - ' . $this->project($request->id)->project_id
            ]));
            auth()->user()->notify(new ActionNotification($this->project($request->id), Auth::user(), $notification));
            if (Project::where(['id' => $request->id])->update(['status' => $request->status])) {
                return response()->json(updateMessage('Project status'));
            }
        } else {
            $activity = ProjectActivity::create([
                "project_id" => $request->id,
                "type_id" => "status",
                "text" => $notification,
                "user_id"   => Auth::user()->id,
            ]);
            broadcast(new SendMessage($this->project($request->id)));
            broadcast(new NotificationEvent([
                'user_id' => auth()->user()->id,
                'message' => 'Project - ' . $this->project($request->id)->project_name . ' with id - ' . $this->project($request->id)->project_id . ' has been ' . $request->status . ' ' . $this->user_details() . '.',
                'type' => 'notification',
                'title' => 'Project - ' . $this->project($request->id)->project_id
            ]));
            auth()->user()->notify(new ActionNotification($this->project($request->id), Auth::user(), $notification));
            $project = Project::where(['id' => $request->id, 'status' => 'close'])->first();
            if (!empty($project)) {
                Project::where(['id' => $request->id])->update(['status' => $request->status]);
                $respondents = CloseRespondent::where('project_id', '=', $request->id)->get();
                if (count($respondents) > 0) {
                    foreach ($respondents as $respondent) {
                        Respondent::create([
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
                }
                return response()->json(updateMessage('Project status'));
            }
            if (Project::where(['id' => $request->id])->update(['status' => $request->status])) {
                return response()->json(updateMessage('Project status'));
            }
            return response()->json(errorMessage());
        }
    }
    public function suppliers($id)
    {

        $project = Project::find($id);

        if ($project) {
            return Inertia::render('Project/Supplier', [
                'project' => new ProjectResource($project),
                'suppliers' => SupplierProjectResource::collection(SupplierProject::where('project_id', $id)->get())
            ]);
        }
        return redirect()->back();
    }

    public function  activity($id)
    {
        $project = Project::find($id);
        $projectActivities = ProjectActivity::where('project_id', $id)->orderBy('created_at', 'DESC')->paginate(5);
        if ($project) {
            return Inertia::render('Project/Activity', [
                'project' => new ProjectResource($project),
                'activities' => ActivityProjectResource::collection($projectActivities),
            ]);
        }
        return redirect()->back();
    }

    public function questionAnswer(Request $request, $id)
    {
        $project = Project::find($id);
        $this->clients = Client::where(['status' => 1, 'company_id' => $this->companyId()])->get();
        $questions = ProjectScreener::where('project_id', $id);

        $questions = $questions->paginate(10)->appends($request->all());
        return Inertia::render('Project/Question', [
            'project' => new ProjectResource($project),
            'clients' => ClientResource::collection($this->clients),
            'questions' => ProjectScreenerResource::collection($questions)
        ]);
    }

    public function storeQuestionAnswer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'question_type' => 'required',
            'answers.*.answer' => 'required', // Validate each answer field
            'answers.*.mark_as_correct' => '' // Validate each mark_as_correct field
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'success' => false]);
        }
        $question_screener = ProjectScreener::create([
            'project_id' => $request->project_id,
            'question' => $request->question,
            'is_required' => $request->is_required,
            'question_type' => $request->question_type,
        ]);
        if ($question_screener) {
            foreach ($request->answers as $answer) {
                $correctLabel = $answer['mark_as_correct'] ? 'correct' : 'incorrect';

                // Create a new ProjectScreenerAnswer instance
                $projectScreenerAnswer = new ProjectScreenerAnswer();
                $projectScreenerAnswer->mark_as_correct = $correctLabel;
                $projectScreenerAnswer->question_id = $question_screener->id;
                $projectScreenerAnswer->answer = $answer['answer'];

                // Save the answer and handle any errors
                try {
                    $projectScreenerAnswer->save();
                } catch (\Exception $e) {
                    // Handle the exception (e.g., log error, return response)
                    return response()->json(['error' => 'Failed to save answer'], 500);
                }
            }
            return response()->json(createMessage('Question'));
        }

        return response()->json(errorMessage());
    }

    public function editQuestionAnswer($id)
    {
        $projectscreener = ProjectScreener::find($id);
        return response()->json([
            'question' => new ProjectScreenerResource($projectscreener)
        ]);
    }

    public function updateQuestionAnswer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'question_type' => 'required',
            'answers.*.answer' => 'required', // Validate each answer field
            'answers.*.mark_as_correct' => '' // Validate each mark_as_correct field
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'success' => false]);
        }
        $screener = ProjectScreener::where('id', $request->id)->update([
            'project_id' => $request->project_id,
            'question' => $request->question,
            'is_required' => $request->is_required,
            'question_type' => $request->question_type,
        ]);
        if ($screener) {
            ProjectScreenerAnswer::where('question_id', $request->id)->delete();
            foreach ($request->answers as $answer) {
                $correctLabel = $answer['mark_as_correct'] ? 'correct' : 'incorrect';
                // Create a new ProjectScreenerAnswer instance
                $projectScreenerAnswer = new ProjectScreenerAnswer();
                $projectScreenerAnswer->mark_as_correct = $correctLabel;
                $projectScreenerAnswer->question_id = $request->id;
                $projectScreenerAnswer->answer = $answer['answer'];

                // Save the answer and handle any errors
                try {
                    $projectScreenerAnswer->save();
                } catch (\Exception $e) {
                    // Handle the exception (e.g., log error, return response)
                    return response()->json(['error' => 'Failed to save answer'], 500);
                }
            }
            return response()->json(updateMessage('Question'));
        }

        return response()->json(errorMessage());
    }

    public function destroyQuestionAnswer($id)
    {
        $projectscreener = ProjectScreener::find($id);
        if ($projectscreener->delete()) {
            return response()->json(deleteMessage('ProjectScreener'));
        } else {
            return response()->json(errorMessage());
        }
    }
    public function importId(Request $request)
    {
        if ($request->hasFile('file')) {
            if (Excel::import(new IdImport($request->id), $request->file('file')->store('files'))) {

                $activity = ProjectActivity::create([
                    "project_id" => $request->id,
                    "type_id" => "status",
                    "text" => "Project " . $this->project($request->id)->project_name . " import",
                    "user_id"   => Auth::user()->id,
                ]);
                broadcast(new SendMessage($this->project($request->id)));
                broadcast(new NotificationEvent([
                    'user_id' => auth()->user()->id,
                    'message' => 'Project - ' . $this->project($request->id)->project_name . ' with id - ' . $this->project($request->id)->project_id . ' was imported by ' . $this->user_details() . '.',
                    'type' => 'notification',
                    'title' => 'Project - ' . $this->project($request->id)->project_id
                ]));
                auth()->user()->notify(new ActionNotification($this->project($request->id), Auth::user(), "Project " . $this->project($request->id)->project_name . " import",));

                return response()->json(['success' => true, 'message' => 'Import file successfully']);
            }
            return response()->json(errorMessage());
        }
    }
    public function exportId($id)
    {
        $project = Project::find($id);
        if ($project) {
            $activity = ProjectActivity::create([
                "project_id" => $id,
                "type_id" => "status",
                "text" => "Project " . $project->project_name . " Export",
                "user_id"   => Auth::user()->id,
            ]);
            broadcast(new SendMessage($this->project($id)));
            broadcast(new NotificationEvent([
                'user_id' => auth()->user()->id,
                'message' => 'Project - ' . $this->project($id)->project_name . ' with id - ' . $this->project($id)->project_id . ' was exported by ' . $this->user_details() . '.',
                'type' => 'notification',
                'title' => 'Project - ' . $this->project($id)->project_id
            ]));
            auth()->user()->notify(new ActionNotification($this->project($id), Auth::user(), $this->project($id)->project_name . " export",));
            return Excel::download(new ExportIdExport($project->id), $project->project_id . '-' . $project->project_name . '.xlsx');
        }
    }
    public function report($id)
    {
        $project = Project::find($id);
        if ($project) {
            $activity = ProjectActivity::create([
                "project_id" => $id,
                "type_id" => "status",
                "text" =>  $project->project_name . " report download",
                "user_id"   => Auth::user()->id,
            ]);
            broadcast(new SendMessage($this->project($id)));
            broadcast(new NotificationEvent([
                'user_id' => auth()->user()->id,
                'message' => 'Project - ' . $this->project($id)->project_name . ' with id - ' . $this->project($id)->project_id . ' was reported by ' . $this->user_details() . '.',
                'type' => 'notification',
                'title' => 'Project - ' . $this->project($id)->project_id
            ]));
            auth()->user()->notify(new ActionNotification($this->project($id), Auth::user(), $this->project($id)->project_name . " report download",));
            return Excel::download(new ProjectReport($id), $project->project_id . '-' . $project->project_name . '.xlsx');
        }
    }

    public function finalIds($id)
    {
        $project = Project::find($id);
        if (FinalId::where('project_id', $id)->first() && Respondent::where('project_id', $id)->first()) {
            return Excel::download(new ExportFinalIDs($id), $project->project_id . '-' . $project->project_name . '.xlsx');
        }
    }
}
