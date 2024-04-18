<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
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
            'is_primary' => $this->is_primary == 1 ? true : false,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'country' => new CountryResource($this->country),
        ];
    }
}
