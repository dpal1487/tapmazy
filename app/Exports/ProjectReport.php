<?php

namespace App\Exports;

use App\Models\Respondent;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class ProjectReport implements FromQuery, WithMapping, WithHeadings
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
            'Respondent ID',
            'Project ID',
            'Project Name',
            'Suppleir Name',
            'Country Name',
            'Username',
            'Starting IP',
            'End IP',
            'Client Browser',
            'Device Type',
            'Status',
            'Duration',
            'Created At',
        ];
    }

    public function map($final): array
    {
        return [
            $final->id,
            $final->project->project_id,
            $final->project->project_name,
            $final->supplier?->supplier_name,
            $final->project_link?->country?->name,
            $final->user ? $final->user->first_name . " " . $final->user->last_name : $final->user_id,
            $final->starting_ip,
            $final->end_ip,
            $final->client_browser,
            $final->device,
            ucfirst($final->status),
            $final->created_at->diff($final->updated_at)->format('%H:%i:%s'),
        ];
    }
    public function query()
    {
        return Respondent::where('project_id', $this->id);
    }
}
