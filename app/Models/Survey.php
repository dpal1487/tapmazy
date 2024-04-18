<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Survey extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable=['project_id','project_link_id','respondent_id','user_id','starting_ip','device','client_browser'];
    protected static function boot()
    {
        parent::boot();
        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Uuid::uuid4());
        });
    }
    public function project(){
        return $this->hasOne(Project::class,'id','project_id');
    }
    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function respondents(){
        return $this->hasMany(Respondent::class,'id','respondent_id');
    }
}
