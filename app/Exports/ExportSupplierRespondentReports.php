<?php

namespace App\Exports;

use App\Models\Respondent;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportSupplierRespondentReports implements FromQuery, WithMapping, WithHeadings
{
    protected $request, $id;
    public function __construct($request, $id)
    {
        $this->request = $request;
        $this->id = $id;
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
        $respondents =new Respondent;
        $respondents = $respondents->where('supplier_id', $this->id);
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
