<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectScreenerResource extends JsonResource
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
            'project_id' => $this->project_id,
            'question' => $this->question,
            'question_type' => $this->question_type,
            'is_required' => $this->is_required ? 'true' : 'false',
            'answers' => ProjectScreenerAnswerResource::collection($this->answers),
            'answer' => $this->answer,
        ];
    }
}
