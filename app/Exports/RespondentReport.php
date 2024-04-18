<?php

namespace App\Exports;

use App\Models\Respondent;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RespondentReport implements FromQuery, WithMapping, WithHeadings
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
            'Country',
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
            $final->id,
            $final->project_link?->project_name,
            $final->user ? $final->user->first_name . ' ' . $final->user->last_name : $final->user_id,
            $final->project_link?->country->name,
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
        if (!empty($this->request->q)) {
            $respondents = $respondents->whereHas('project', function ($query) {
                $query->where('project_name', 'like', '%' . $this->request->q . '%');
            });
        }
        if (!empty($this->request->user)) {
            $respondents = $respondents->where('user_id', $this->request->user);
        }
        if (!empty($this->request->status)) {
            $respondents = $respondents->where('status', $this->request->status);
        }
        return $respondents;
    }
}
