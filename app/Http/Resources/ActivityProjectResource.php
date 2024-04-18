<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityProjectResource extends JsonResource
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
            'project_id' => $this->project?->project_id,
            'project_name' => $this->project?->project_name,
            'user' => $this->user?->first_name . ' ' . $this->user?->last_name,
            'profile'  => storage_path('app/public/' .  $this->user->profile_photo_path),
            'text' => $this->text,
            'date' => date('d-M-y', strtotime($this->created_at)),
            'time' => date('h:s A', strtotime($this->created_at)),
        ];
    }
}
