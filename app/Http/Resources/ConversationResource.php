<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
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
            'conversation_id' => intval($this->id),
            'user' => new ChatUserRescource($this->participant?->user),
            'last_message' => new MessageResource($this->participant?->lastMessage),
            'unseen_counter' => $this->participant ?  count($this->participant?->unreadMessages) : 0,
            'last_read' => Carbon::parse($this->participant?->last_read)->diffForHumans(),
        ];
    }
}
