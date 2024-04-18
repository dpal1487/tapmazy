<?php

namespace App\Exports;


use App\Models\Respondent;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class ExportUserProjectReport implements FromQuery, WithMapping, WithHeadings
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
            'ID',
            'User Name',
            'Project Name',
            'Project Name',
            'Project Type',
            'Device Type',
            'Starting IP',
            'End IP',
            'Client Browser',
            'Status',
            'Duration',
            'Created At',
        ];
    }
    public function map($respondent): array
    {
        return [
            $respondent->id,
            $respondent->user->first_name . " " . $respondent->user->last_name,
            $respondent->project->project_id,
            $respondent->project->project_name,
            $respondent->project->project_type,
            $respondent->project->device_type,
            $respondent->starting_ip,
            $respondent->end_ip,
            $respondent->client_browser,
            $respondent->status,
            $respondent->created_at->diff($respondent->updated_at)->format('%H:%i:%s'),
            $respondent->created_at->toDatestring(),
        ];
    }
    public function query()
    {
        $respondents = new Respondent();
        return $respondents->where('user_id', $this->id);
    }
}
