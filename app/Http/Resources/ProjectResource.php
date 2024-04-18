<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'project_id' => $this->project_id,
            'user' => $this->user?->first_name . ' ' . $this->user?->last_name,
            'device_type' => $this->device_type,
            'project_type' => $this->project_type,
            'start_date' =>  $this->start_date,
            'end_date' => $this->end_date,
            'target' => $this->target,
            'country' => new CountryResource($this->country),
            'project_name' => $this->project_name,
            'client' => new ClientListResource($this->client),
            'status' => $this->status,
            'project_links' => $this->project
        ];
    }
}
