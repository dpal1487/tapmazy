<?php

namespace App\Exports;

use App\Models\Project;
use App\Models\Respondent;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientProjectReports implements FromQuery, WithMapping, WithHeadings
{
    protected $request;
    public function __construct($request)
    {
        $this->request = $request;
    }
    public function headings(): array
    {
        return [
            'Project ID',
            'Project Name',
            'Target Name',
            'Status',
        ];
    }
    public function map($final): array
    {
        return [
            $final->project_id,
            $final->project_name,
            $final->target,
            ucfirst($final->status),
        ];
    }
    public function query()
    {

        $projects = new Project();

        $client_projects = $projects->where('client_id', $this->request->id);

        if (!empty($this->request->q)) {
            $client_projects = $client_projects->whereHas('project', function ($query) {
                $query->where('project_name', 'like', '%' . $this->request->q . '%');
            });
        }

        if (!empty($this->request->status) && $this->request->status != "all") {
            $client_projects = $client_projects->where('status', $this->request->status);
        }
        return $client_projects;
    }
}
