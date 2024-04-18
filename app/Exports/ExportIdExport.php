<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportIdExport implements FromQuery, WithMapping, WithHeadings
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
    public function collection()
    {
        return Project::where('id', $this->id)->get();
    }


    public function headings(): array
    {
        return [
            'ID',
            'Project ID',
            'Project Name',
            'Username',
            'Client',
            'Project Type',
            'Device Type',
            'Start Date',
            'End Date',
            'Target',
            'Status',
            'Created At',
        ];
    }

    public function map($final): array
    {
        return [
            $final->id,
            $final->project_id,
            $final->project_name,
            $final->user ? $final->user->first_name . " " . $final->user->last_name : $final->user,
            $final->client?->name,
            $final->project_type,
            $final->device_type,
            $final->start_date,
            $final->end_date,
            $final->target,
            ucfirst($final->status),
            $final->created_at->toDatestring(),
        ];
    }
    public function query()
    {
        return Project::where('id', $this->id);
    }
}
