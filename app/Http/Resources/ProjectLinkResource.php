<?php

namespace App\Http\Resources;

use App\Models\SupplierSurvey;
use App\Models\Survey;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProjectLinkResource extends JsonResource
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
            $zipcode = str_replace(' , ', ' ', $this->zipcode),
            'id' => $this->id,
            'project_uid' => $this->project->project_id,
            'project_name' => $this->project_name,
            'project_id' => $this->project_id,
            'user' => $this->user ?  $this->user?->first_name . ' ' . $this->user?->last_name : '',
            'project' => $this->project,
            'country' => new CountryResource($this->country),
            'country_id' => $this->country?->id,
            'state' => explode(' , ', $this->state),
            'city' => explode(' , ', $this->city),
            'project_zipcode' => $zipcode,
            'sample_size' => $this->sample_size,
            'project_link' => $this->project_link,
            'cpi' => Auth::user()->role?->role?->slug != 'user' ? $this->cpi : '',
            'loi' => $this->loi,
            'ir' => $this->ir,
            'target' => $this->target,
            'status' => $this->status,
            'client' => $this->project->client,
            'supplier_count' => $this->suppliers ?  count($this->suppliers) : 0,
            'created_at' => date('d/M/y - H:m:s A', strtotime($this->created_at)),
            'reports' => [
                'total_clicks' => count($this->total),
                'complete' => count($this->completes),
                'terminate' => count($this->terminate),
                'quotafull' => count($this->quotafull),
                'security_terminate' => count($this->security_terminate),
                'incomplete' => count($this->incomplete),
                'total_ir' => (count($this->completes) > 0) ? intval(((count($this->completes)) / (count($this->completes) + count($this->terminate))) * 100) : 0
            ]
        ];
    }
}
