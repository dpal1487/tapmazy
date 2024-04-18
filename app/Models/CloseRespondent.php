<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloseRespondent extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['id', 'project_id', 'supplier_id', 'supplier_project_id', 'user_id', 'project_link_id', 'client_browser', 'device', 'starting_ip', 'end_ip', 'status'];

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
