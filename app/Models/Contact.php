<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $fillable = ['entity_id', 'entity_type', 'name', 'email', 'phone', 'country_id', 'is_primary'];
    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
}
