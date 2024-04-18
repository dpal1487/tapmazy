<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogListResource extends JsonResource
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
            'title' => $this->title,
            'is_published' => $this->is_published,
            'image' => new ImageResource($this->image),
            'content' => $request->segment(3)  == 'blog' ?  $this->content : substr($this->content, 0, 100),
        ];
    }
}
