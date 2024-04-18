<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'is_published' => $this->is_published,
            'image' => $this->image,
            'content' => $this->content,

            'image' => new ImageResource($this->image),
        ];
    }
}
