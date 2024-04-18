<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'company_name' => $this->company_name,
            'company_type' => $this->company_type,
            'corporation_type' => $this->corporation_type,
            'company_size' => $this->company_size,
            'legal_registration_no' => $this->legal_registration_no,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'website' => $this->website,
            'subdomain' => $this->subdomain,
            'linkedin_profile' => $this->linkedin_profile,
            'skype_profile' => $this->skype_profile,

        ];
    }
}
