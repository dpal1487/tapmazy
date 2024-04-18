<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceListResource extends JsonResource
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
            'name'  => $this->name,
            'slug'  => $this->slug,
            'page'  => $this->page,
            'description' =>  substr($this->description, 0, 100),
            'image' => new ImageResource($this->image),
        ];
    }
}
