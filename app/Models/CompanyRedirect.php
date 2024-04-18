<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyRedirect extends Model
{
    use HasFactory;
    protected $fillable=['company_id','redirect_id'];
    public $timestamps = false;
    public function redirect(){
        return $this->hasOne(Redirect::class,'id','redirect_id');
    }
}
