<?php

namespace App\Exports;

use App\Models\Respondent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserReport implements FromQuery, WithMapping, WithHeadings
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
            $final->project->project_name,
            $final->project->project?->country->name,
            $final->starting_ip,
            $final->end_ip,
            ucfirst($final->status),
            $final->created_at->diff($final->updated_at)->format('%H:%i:%s'),
            $final->created_at,
        ];
    }
    public function query()
    {
        $respondents = Respondent::where('user_id', Auth::user()->id);
        if (!empty($this->request->q)) {
            $respondents = $respondents->whereHas('project', function ($query) {
                $query->where('project_name', 'like', '%' . $this->request->q . '%');
            });
        }
        if (!empty($this->request->status)) {
            $respondents = $respondents->where('status', $this->request->status);
        }
        return $respondents;
    }
}
