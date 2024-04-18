<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class ChatParticipant extends Model

{
  protected $fillable=['user_id','conversation_id'];
  public function user()
  {
    return $this->belongsTo(User::class);
  }
  public function lastMessage()
  {
    return $this->hasOne(ChatMessage::class, 'conversation_id', 'conversation_id')->orderBy('created_at','desc');
  }
  public function messages()
  {
    return $this->hasMany(ChatMessage::class, 'conversation_id', 'conversation_id')->orderBy('created_at','desc');
  }
  public function unreadMessages()
  {
    return $this->hasMany(ChatMessage::class, 'conversation_id', 'conversation_id')->where('is_read',0)->where('sender_id','!=',Auth::user()->id);
  }
}
