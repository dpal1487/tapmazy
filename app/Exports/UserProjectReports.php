<?php

namespace App\Exports;

use App\Models\Respondent;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserProjectReports implements FromQuery, WithMapping, WithHeadings
{
    protected $request;
    public function __construct($request)
    {
        $this->request = $request;
    }
    public function headings(): array
    {
        return [
            'ID',
            'Project Name',
            'Username',
            'Starting IP',
            'End IP',
            'Status',
            'Duration',
            'Created At',
        ];
    }
    public function map($final): array
    {
        return [
            $final->project->project_id,
            $final->project?->project_name,
            $final->user ? $final->user->first_name . ' ' . $final->user->last_name : $final->user_id,
            $final->starting_ip,
            $final->end_ip,
            ucfirst($final->status),
            $final->created_at->diff($final->updated_at)->format('%H:%i:%s'),
            $final->created_at,
        ];
    }
    public function query()
    {

        $respondents = new Respondent();

        $user_respondents = $respondents->where('user_id', $this->request->user_id);

        if (!empty($this->request->q)) {
            $user_respondents = $user_respondents->whereHas('project', function ($query) {
                $query->where('project_name', 'like', '%' . $this->request->q . '%');
            });
        }

        if (!empty($this->request->status) && $this->request->status != "all") {
            $user_respondents = $user_respondents->where('status', $this->request->status);
        }
        return $user_respondents;
    }
}
