<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportUserIDs implements FromQuery, WithMapping, WithHeadings
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
            $final->user,
            // $final->project->project_name,
            // $final->user ? $final->user->first_name . ' ' . $final->user->last_name : $final->user_id,
            // $final->project->project->country->name,
            // $final->starting_ip,
            // $final->end_ip,
            // ucfirst($final->status),
            // $final->created_at->diff($final->updated_at)->format('%H:%i:%s'),
            // $final->created_at,
        ];
    }
    public function query()
    {
        $users = User::get();
        return $users;
    }
}
