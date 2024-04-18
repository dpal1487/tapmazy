<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable = ['address', 'entity_id', 'entity_type', 'state', 'city', 'country_id', 'pincode', 'is_primary'];
    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
}
