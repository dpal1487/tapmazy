<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ChatConversation extends Model
{
    protected $fillable=['creator_id'];
    public function participant() {
        return $this->hasOne(ChatParticipant::class,'conversation_id','id');
    }
}
