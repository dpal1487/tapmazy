<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'role' => $this->role,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'code' => $this->employee->code,
            'date_of_joining' => $this->employee->date_of_joining,
            'qualification' => $this->employee->qualification,
            'emergency_number' => $this->employee->emergency_number,
            'pan_number' => $this->employee->pan_number,
            'father_name' => $this->employee->father_name,
            'salary' => $this->employee->salary,
            'last_working_day' => $this->employee->last_working_day,
            'department' => $this->employee->department,
            'created_at' => $this->created_at,
            'status' => $this->status,
        ];
    }
}
