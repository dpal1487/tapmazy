<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    use HasFactory;
    protected $fillable =['complete_url','terminate_url','quotafull_url','security_terminate_url'];
}
