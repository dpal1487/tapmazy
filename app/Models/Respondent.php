<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Respondent extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['client_browser', 'device', 'uid', 'starting_ip', 'end_ip', 'status'];
    protected static function boot()
    {
        parent::boot();
        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Uuid::uuid4());
        });
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function users()
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }
    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }
    public function project_link()
    {
        return $this->hasOne(ProjectLink::class, 'id', 'project_link_id');
    }
    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }
}
