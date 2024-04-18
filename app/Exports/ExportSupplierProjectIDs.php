<?php

namespace App\Exports;

use App\Models\SupplierProject;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class ExportSupplierProjectIDs implements FromQuery, WithMapping, WithHeadings
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
            'Client Name',
            'Respondent ID',
            'Username',
            'County',
            'Created At',
        ];
    }

    public function map($final): array
    {
        return [
            $final->project?->project_id,
            $final->project?->project_name,
            $final->project?->client?->name,
            $final->project?->id,
            $final->project?->user ? $final->project?->user?->first_name . ' ' . $final->project?->user?->last_name : $final->user_id,
            $final->supplier?->country?->name,
            $final->project?->created_at->toDatestring(),
        ];
    }
    public function query()
    {
        return SupplierProject::where('supplier_id', $this->id);
    }
}
