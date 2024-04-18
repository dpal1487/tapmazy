<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\FinalId;

class IdImport implements ToCollection
{
    public $id;
    public function __construct($id)
    {
        $this->id = $id;
    }
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (!empty($row[0])) {
                FinalId::create([
                    'respondent_id' => $row[0],
                    'project_id' => $this->id
                ]);
            }
        }
    }
}
