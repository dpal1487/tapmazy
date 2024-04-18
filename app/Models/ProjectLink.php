<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class ProjectLink extends Model
{
    protected $fillable = [
        'project_name',
        'project_id',
        'cpi',
        'loi',
        'ir',
        'sample_size',
        'status',
        'country_id',
        'state',
        'city',
        'zipcode',
        'target',
        'project_link'
    ];
    use HasFactory;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected static function boot()
    {
        parent::boot();
        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Uuid::uuid4());
        });
    }
    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }

    public function total()
    {
        return $this->hasMany(SupplierSurvey::class, 'project_link_id', 'id');
    }
    public function terminate()
    {
        return $this->hasMany(SupplierSurvey::class, 'project_link_id', 'id')->where('status', 'terminate');
    }

    public function security_terminate()
    {
        return $this->hasMany(SupplierSurvey::class, 'project_link_id', 'id')->where('status', 'security-terminate');
    }
    public function completes()
    {
        return $this->hasMany(SupplierSurvey::class, 'project_link_id', 'id')->where('status', 'complete');
    }

    public function quotafull()
    {
        return $this->hasMany(SupplierSurvey::class, 'project_link_id', 'id')->where('status', 'quotafull');
    }

    public function incomplete()
    {
        return $this->hasMany(SupplierSurvey::class, 'project_link_id', 'id')->where('status', NULL);
    }
    public function suppliers()
    {
        return $this->hasMany(SupplierProject::class, 'project_link_id', 'id');
    }
    public function getRouteKeyName()
    {
        return 'id';
    }
}
