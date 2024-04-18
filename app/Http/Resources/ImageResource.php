<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ImageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'entity_id' => $this->entity_id,
            'url' => asset("/images/$this->entity_type") . '/' . $this->name,
        ];
    }
}
