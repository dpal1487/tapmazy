<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'is_primary' => $this->is_primary == 1 ? true : false,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'pincode' => $this->pincode,
            'country' => new CountryResource($this->country),
        ];
    }
}
