<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    public function participant(){
        return $this->hasOne(Participant::class,'conversation_id','id');
    }

    public function participants(){
        return $this->hasMany(Participant::class,'conversation_id','id');
    }
    public function messages(){
        return $this->hasMany(Message::class,'conversation_id','id');
    }
}
