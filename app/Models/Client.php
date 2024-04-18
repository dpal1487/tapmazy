<?php

namespace App\Models;

class Client extends UID
{
    protected $fillable = [
        'company_id',
        'client_name',
        'tax_number',
        'display_name',
        'status',
        'website',
        'linkedin_profile',
        'skype_profile',
        'description',

    ];

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
    public function address()
    {
        return $this->hasOne(ClientAddress::class, 'client_id', 'id');
    }
    public function addresses()
    {
        return $this->hasMany(ClientAddress::class, 'client_id', 'id');
    }
    public function projects()
    {
        return $this->hasMany(Project::class, 'client_id', 'id');
    }
    public function contacts()
    {
        return $this->hasMany(ClientContact::class, 'client_id', 'id');
    }
    public function contact()
    {
        return $this->hasOne(ClientContact::class, 'client_id', 'id');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'company_id', 'company_id');
    }



    public static function boot()
    {
        parent::boot();
        static::deleting(function ($client) {
            // before delete() method call this
            $client->contacts()->delete();
            $client->addresses()->delete();
            // do the rest of the cleanup...
        });
    }
}
