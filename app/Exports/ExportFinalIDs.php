<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\FinalId;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class ExportFinalIDs implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return [
            'Project ID',
            'Project Name',
            'Client ID',
            'Respondent ID',
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
            $final->respondent?->project->project_id,
            $final->respondent?->project->project_name,
            $final->respondent?->project->client_id,
            $final->respondent?->id,
            $final->respondent?->user ? $final->respondent->user->first_name . ' ' . $final->respondent->user->last_name : $final->respondent?->user_id,
            $final->respondent?->starting_ip,
            $final->respondent?->end_ip,
            ucfirst($final->respondent?->status),
            $final->respondent?->created_at->diff($final->respondent?->updated_at)->format('%H:%i:%s'),
            $final->respondent?->created_at->toDatestring(),
        ];
    }
    public function query()
    {
        return FinalId::where('project_id', $this->id);
    }
}
