<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
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
            'plan' => $this->stripePlan,
        ];
    }
}
