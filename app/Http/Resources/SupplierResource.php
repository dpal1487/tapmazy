<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'label' => $this->supplier_name . " ($this->display_name)",
            'supplier_name' => $this->supplier_name,
            'display_name' => $this->display_name,
            'email_address' => $this->email_address,
            'website' => $this->website,
            'skype_profile' => $this->skype_profile,
            'linkedin_profile' => $this->linkedin_profile,
            'description' => $this->description,
            'country' => new CountryResource($this->country),
            'status' => $this->status,
            'created_at' => $this->created_at,
            'supplier_redirect' => $this->supplier_redirect,
            'header' => [
                'total_projects' => count($this->respondents),
                'live_projects' => count($this->respondents->where('status', 'live')),
                'completed_projects' => count($this->respondents->where('status', 'complete')),
            ]
        ];
    }
}
