<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends UID
{
    use HasFactory;
    protected $fillable = [
        'company_name',
        'user_id',
        'company_size_id',
        'legal_registration_no',
        'company_type',
        'business_name',
        'subdomain',
        'website',
        'skype_profile',
        'linkedin_profile',
        'short_description',
        'corporation_type_id',
        'description',
    ];
    public function address()
    {
        return $this->hasOne(CompanyAddress::class, 'company_id', 'id');
    }
    public function account()
    {
        return $this->hasOne(CompanyAccount::class, 'company_id', 'id');
    }
    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
    public function company_size(){
        return $this->hasOne(CompanySize::class,'id','company_size_id');
    }
    public function corporation_type(){
        return $this->hasOne(CorporationType::class,'id','corporation_type_id');
    }
    
    public function addresses()
    {
        return $this->hasMany(CompanyAddress::class, 'company_id', 'id');
    }
    
    public function redirect()
    {
        return $this->hasOne(CompanyRedirect::class, 'company_id', 'id');
    }
    
    public function accounts()
    {
        return $this->hasMany(CompanyAccount::class, 'company_id', 'id');
    }
}
