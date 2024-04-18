<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientContact extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'contact_id'];
    public $timestamps = false;

    public function contact()
    {
        return $this->hasOne(Contact::class, 'id', 'contact_id');
    }
}
