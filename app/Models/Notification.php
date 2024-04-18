<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable=['type_id','source_id','company_id'];
    public function type(){
        return $this->hasOne(NotificationType::class,'id','type_id');
    }
    public function source(){
        return $this->hasOne(Project::class,'id','source_id');
    }
}
