<?php

namespace App\Exports;

use App\Models\FinalId;
use App\Models\Project;
use App\Models\SupplierProject;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class ExportSupplierFinalId implements FromQuery, WithMapping, WithHeadings
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
            'Client ID',
            'Respondent ID',
            'Supplier Name',
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
            $final->respondent?->project->project_id,
            $final->respondent?->project->project_name,
            $final->respondent?->project->client_id,
            $final->respondent?->id,
            $final->respondent?->supplier->supplier_name,
            $final->respondent?->starting_ip,
            $final->respondent?->end_ip,
            ucfirst($final->respondent?->status),
            $final->respondent?->created_at->diff($final->respondent?->updated_at)->format('%H:%i:%s'),
            $final->respondent?->created_at->toDatestring(),
        ];
    }
    public function query()
    {
        return FinalId::where('project_id', $this->id)->whereHas('respondent', function ($query) {
            $query->where('supplier_id', '!=', null);
        });
    }
}
