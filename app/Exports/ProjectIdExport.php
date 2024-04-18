<?php

namespace App\Exports;

use App\Models\Project;
use App\Models\ProjectLink;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class ProjectIdExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;
    protected $id;


    public function __construct($id)
    {
        $this->id = $id;
    }

    public function headings(): array
    {
        return [
            'Project ID',
            'Project Name',
            'Country',
            'Client Link',
            'Status',
            'Created At',
        ];
    }

    public function map($final): array
    {
        return [
            $final->project->project_id,
            $final->project_name,
            $final->country->name,
            $final->client_link,
            ucfirst($final->status),
            $final->created_at->toDatestring(),
        ];
    }
    public function query()
    {
        $project = new ProjectLink();
        return $project->where('project_id', $this->id);
    }
}
