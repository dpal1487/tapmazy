<?php

namespace App\Exports;


use Carbon\Carbon;
use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class ExportClientProjectIDs implements FromQuery, WithMapping, WithHeadings
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
            'Created At',
        ];
    }

    public function map($final): array
    {
        return [
            $final->project_id,
            $final->project_name,
            $final->client->name,
            $final->id,
            $final->user ? $final->user->first_name . ' ' . $final->user->last_name : $final->user_id,
            $final->created_at->toDatestring(),
        ];
    }
    public function query()
    {
        return Project::where('client_id', $this->id);
    }
}
