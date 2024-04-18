<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAddress extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['client_id','is_primary', 'address_id'];
    public function address(){
        return $this->hasOne(Address::class,'id','address_id');
    }
}
