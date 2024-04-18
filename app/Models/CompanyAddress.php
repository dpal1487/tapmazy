<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAddress extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['company_id', 'address_id'];
    
    public function address(){
        return $this->hasOne(Address::class,'id','address_id')->orderBy('is_primary','asc');
    }
}
