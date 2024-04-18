<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['company_id', 'user_id'];
    public function company(){
        return $this->hasOne(Company::class,'id','company_id');
    }
}
