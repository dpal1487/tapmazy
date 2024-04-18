<?php

namespace App\Http\Controllers;

use App\Models\SupplierProject;
use App\Models\SupplierSurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class RedirectController extends Controller
{
    public $supplier_survey, $project,$project_id, $url, $data;
    public function status(Request $request, $id)
    {
        if (in_array($request->status, [1, 2, 3, 4])) {
            $this->supplier_survey = SupplierSurvey::where(['id' => $request->uid, 'company_id' => $id])->first();
            if($this->supplier_survey){
                $this->project = SupplierProject::where(['id' => $this->supplier_survey->supplier_project_id])->first();
                $this->project_id = $this->project->project->project->project_id;
            }
            if ($request->status == 1) {
                $this->data = array(
                    'headTitle' => 'Completed',
                    'success' => true,
                    'title' => strtoupper('Your survey has been Completed'),
                    'message' => "Congratulations! You have successfully completed the survey.",
                );
                $this->complete($request);
                $this->url = $this->project  ? str_replace('ProjectID', $this->project->project->project_id, $this->project->complete_url) : [];
            }
            if ($request->status == 2) {
                $this->data = array(
                    'headTitle' => 'Terminated',
                    'success' => true,
                    'title' => strtoupper('Your survey has been Terminated'),
                    'message' => "Thank you very much for your participation. Unfortunately, at the moment we are looking for a diffrent profile to match survey's conditions.",
                );
                $this->complete($request);
                $this->url = $this->project  ? str_replace('ProjectID', $this->project->project->project_id, $this->project->terminate_url) : [];
            }
            if ($request->status == 3) {
                $this->data = array(
                    'headTitle' => 'Quotafull',
                    'success' => true,
                    'title' => strtoupper('Your survey has been Quotafull'),
                    'message' => "Thank you very much for your participation. Unfortunately, the quota was reached for your demographic group.",
                );
                $this->complete($request);
                $this->url = $this->project  ? str_replace('ProjectID', $this->project->project->project_id, $this->project->quotafull_url) : [];
            }
            if ($request->status == 4) {
                $this->data = array(
                    'headTitle' => 'Security Terminated',
                    'success' => true,
                    'title' => strtoupper('Your survey has been Terminated'),
                    'message' => "Thank you very much for your participation. Unfortunately, at the moment we are looking for a diffrent profile to match survey's conditions.",
                );
                $this->complete($request);
                $this->url = $this->project  ? str_replace('ProjectID', $this->project->project->project_id, $this->project->security_terminate_url) : [];
            }
        }
        if($this->supplier_survey){
            $this->url = str_replace('RespondentID', $this->supplier_survey->respondent_id, $this->url);
        }

        return Inertia::render('Survey/Redirect', [
            'data' => $this->data,
            'redirect' => $this->url
        ]);
        return Redirect::to(env('APP_URL'));
    }
    public function complete($request)
    {
        SupplierSurvey::where('id', $request->uid)->update([
            'end_ip' => $request->ip(),
            'status' => $request->status,
        ]);
    }
}
