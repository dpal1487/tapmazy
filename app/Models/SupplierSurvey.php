<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class SupplierSurvey extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['project_id','company_id','country','respondent_id','supplier_project_id','project_link_id','duration','country','supplier_id','supplier_name','project_name','client_browser','platform','device' , 'starting_ip', 'end_ip', 'status'];
    protected static function boot()
    {
        parent::boot();
        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Uuid::uuid4());
        });
    }
    public function supplier(){
        return $this->hasOne(Supplier::class,'id','supplier_id');
    }

    public function project(){
        return $this->hasOne(Project::class,'id','project_id');
    }
    public function respondents(){
        return $this->hasMany(Respondent::class,'id','respondent_id');
    }
    public function respondent(){
        return $this->hasOne(Respondent::class,'id','respondent_id');
    }
}
