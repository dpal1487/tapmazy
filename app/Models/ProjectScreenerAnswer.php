<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectScreenerAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['project_id' , 'question_id' , 'answer'];

    public function question()
    {
        return $this->hasOne(ProjectScreener::class , 'id' , 'question_id');
    }

    public function project()
    {
        return $this->hasOne(Project::class , 'id' , 'project_id');
    }
}
