<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierListResource extends JsonResource
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
            'id'=>$this->id,
            'supplier_name'=>$this->supplier_name,
            'display_name'=>$this->display_name,
            'rfq_email'=>$this->rfq_email,
            'website'=>$this->website,
            'skype_profile'=>$this->skype_profile,
            'linkedin_profile'=>$this->linkedin_profile,
            'description'=>$this->description,
            'country'=>new CountryResource($this->country),
            'status'=>$this->status,
            'created_at'=>$this->created_at,
        ];
    }
}
