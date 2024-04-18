<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserShowResource extends JsonResource
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
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'date_of_birth' => date('m/d/Y', strtotime($this->date_of_birth)),
            'join_date' => date('d-M-Y h:i A', strtotime($this->created_at)),
            'gender' => ucfirst($this->gender),
            'status' => $this->status,
            'role' => $this->role?->role,
            'header' => [
                'total_project' => $this->projects ?  count($this->projects) : 0,
                'complete' => $this->projects ? count($this->projects->where('status', 'complete')) : 0,
                'terminate' => $this->projects ? count($this->projects->where('status', 'terminate')) : 0,
                'quotafull' => $this->projects ? count($this->projects->where('status', 'quotafull')) : 0,
            ]
        ];
    }
}
