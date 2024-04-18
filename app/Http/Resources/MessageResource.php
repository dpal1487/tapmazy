<?php

namespace App\Http\Resources;

use App\Http\Resources\User\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => intval($this->id),
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d'),
            'time_ago' => $this->created_at,
            'sender_id' => $this->sender_id,
            'message_type' => $this->message_type,
            'message' =>$this->message ?  Crypt::decryptString($this->message) : null,
            'attachment' => $this->attachment,
            'is_read' => $this->is_read,
            'user' => new UserResource($this->user),
            'conversation_id' => $this->conversation_id,

        ];
    }
}
