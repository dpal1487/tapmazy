<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectScreenerResource;
use App\Models\ProjectLink;
use App\Models\ProjectScreener;
use App\Models\ProjectScreenerAnswer;
use App\Models\Respondent;
use App\Models\SupplierProject;
use App\Models\SupplierSurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Jenssegers\Agent\Agent;

class SurveyInitController extends Controller
{
    protected $survey;
    public function start(Request $request, $id)
    {
        // return $id;
        $agent = new Agent();
        $supplier_project = SupplierProject::find($id);
        $project = ProjectLink::where(['id' => $supplier_project->project_link_id])->first();
        $count = count($supplier_project->complete);
        /*Already Quotafull*/
        if ($request->uid && $supplier_project) {
            if ($count < $supplier_project->project_link->sample_size) {
                /*Not Live*/
                if ($project->project->status == 1 && $project->status == 1 && $supplier_project->status == 1) {
                    /*Alread Attempt*/
                    if (!SupplierSurvey::where(['supplier_project_id' => $id, 'respondent_id' => $request->uid])->first()) {
                        /*Duplicate IP */
                        if (!SupplierSurvey::where(['project_link_id' => $supplier_project->project_link_id, 'starting_ip' => $request->ip()])->first()) {
                            $this->survey = SupplierSurvey::create([
                                'respondent_id' => $request->uid,
                                'company_id' => $supplier_project->company_id,
                                'project_id' => $supplier_project->project_id,
                                'supplier_id' => $supplier_project->supplier_id,
                                'project_link_id' => $supplier_project->project_link_id,
                                'starting_ip' => $request->ip(),
                                'supplier_project_id' => $id,
                                'device' => $agent->device(),
                                'platform' => $agent->platform(),
                                'client_browser' => $agent->browser(),
                            ]);

                            $url = str_replace('RespondentID', $this->survey->id, $project->project_link);
                            return Redirect::to($url);
                        }
                        if (in_array($ip->zipcode, $array)) {
                        }
                        $url = str_replace('ProjectID', $supplier_project->project->project->project_id, $supplier_project->security_terminate_url);
                        $url = str_replace('RespondentID', $request->uid, $url);
                        return Redirect::to($url);
                    }
                    $data = ['message' => 'You have already attempt this survey please try agian later.', 'success' => false];
                }
                $data = ['message' => 'Project is not live please try again later.', 'success' => false];
            }
            $data = ['message' => 'Thank you for your interest, we have reached our target number of participants.', 'success' => false];
        }
    }

    public function questionTest($id)
    {
        $supplier_project = SupplierProject::find($id);
        $project = ProjectLink::where(['id' => $supplier_project->project_link_id])->first();
        $project_screener = ProjectScreener::where('project_id', $project->project_id)->get();
        return Inertia::render('ProjectScreener', [
            'project_screener' => ProjectScreenerResource::collection($project_screener),
        ]);
    }
    public function checkAnswer(Request $request)
    {
        $correctAnswer = ProjectScreenerAnswer::where('question_id', $request->question_id)->where('nature', 'correct')->first();
        $isCorrect = ($correctAnswer && $correctAnswer->answer == $request->answer);
        if ($isCorrect) {
            return response()->json(['message' => 'Correct answer!']);
        } else {
            return response()->json(['message' => 'Incorrect answer. Please try again.']);
        }
    }
}
