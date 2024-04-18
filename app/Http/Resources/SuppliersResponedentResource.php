<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SuppliersResponedentResource extends JsonResource
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
            'project_id' => $this->project?->project->project_name ?? $this->project_id,
            'user' =>  $this->user_id,
            'supplier_name' => $this->supplier?->supplier_name,
            'supplier_project_id' => $this->supplier_project_id,
            'project_name' => $this->project_link?->project_name,
            'starting_ip' => $this->starting_ip,
            'end_ip' => $this->end_ip,
            'client_browser' => $this->client_browser,
            'device' => $this->device,
            'status' => $this->status,
            'created_at' => date('d-m-Y H:i:s', strtotime($this->created_at)),
            'duration' => $this->created_at->diff($this->updated_at)->format('%H:%I:%S'),
        ];
    }
}
