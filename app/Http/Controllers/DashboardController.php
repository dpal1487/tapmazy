<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\SupplierSurvey;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $projects = new Project();
        return Inertia::render('Dashboard', [
            'projects' => [
                'live_projects' => $projects->where(['company_id' => $this->companyId()])->where(['status' => 'live'])->count(),
                'inactive_projects' => $projects->where(['company_id' => $this->companyId()])->where('status', 'pause')->count(),
                'closed_projects' => $projects->where(['company_id' => $this->companyId()])->where('status', 'closed')->count(),
                'archived_projects' => $projects->where(['company_id' => $this->companyId()])->where('status', 'pause')->count(),
                'cancelled_projects' => $projects->where(['company_id' => $this->companyId()])->where('status', 'cancelled')->count(),
                'invoiced_projects' => $projects->where(['company_id' => $this->companyId()])->where('status', 'invoiced')->count(),
                'latest_projects' => ProjectResource::collection($projects->where(['company_id' => $this->companyId()])->latest()->limit(10)->get()),
            ]
        ]);
    }
}
