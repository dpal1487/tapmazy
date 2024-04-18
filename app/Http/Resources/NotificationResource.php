<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'title'=>$this->source->project_name.' '.$this->type->title,
            'sub_title'=>str_replace(array('{username}','{source_type}'),array($this->source->user->first_name,$this->source->project_name),$this->type->sub_title),
            'source_url'=>'/'.str_replace('{id}',$this->source_id,$this->type->slug),
            'type_id'=>$this->type_id,
			'created_at'=>$this->created_at->diffForHumans(),
        ];
    }
}
