<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersRole extends Model
{
    use HasFactory;
    protected $fillable=['role_id','user_id'];
    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}
