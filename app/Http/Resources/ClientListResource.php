<?php

namespace App\Http\Resources;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientListResource extends JsonResource
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
            'display_name' => $this->display_name,
            'tax_number' => $this->tax_number,
            'status' => $this->status,
            'name' => $this->client_name,
            'linkedin_profile' => $this->linkedin_profile,
            'skype_profile' => $this->skype_profile,
            'id' => $this->id,
            'description' => $this->description,
            'contact' =>new ContactResource($this->contact),
            'country' => new CountryResource($this->country),
            'created_at' => date('d M,Y', strtotime($this->created_at)),
            'user' => new UserResource($this->user),
        ];
    }
}
