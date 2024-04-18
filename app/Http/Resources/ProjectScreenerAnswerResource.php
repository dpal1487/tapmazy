<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectScreenerAnswerResource extends JsonResource
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
            'mark_as_correct' => $this->mark_as_correct == 'correct' ?  true : false,
            'question_id' => $this->question_id,
            'answer'    => $this->answer,
        ];
    }
}
