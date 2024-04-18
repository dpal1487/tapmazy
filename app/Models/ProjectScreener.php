<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectScreener extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'project_id', 'question_type', 'is_required'];

    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }

    public function answer()
    {
        return $this->hasOne(ProjectScreenerAnswer::class, 'question_id', 'id');
    }

    public function answers()
    {
        return $this->hasMany(ProjectScreenerAnswer::class, 'question_id', 'id');
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($projectscreener) {
            // before delete() method call this
            if ($projectscreener->answer) {
                $projectscreener->answers()->delete();
            }
            // do the rest of the cleanup...
        });
    }
}
