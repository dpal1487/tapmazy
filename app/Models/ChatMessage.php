<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = ['message', 'conversation_id', 'sender_id', 'message_type'];
    public function user(){
        return $this->hasOne(User::class,'id','sender_id');
    }
}
