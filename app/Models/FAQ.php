<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'title', 'artical'];

    public function category()
    {
        return $this->hasOne(FAQsCategory::class, 'id', 'category_id');
    }
    public function categories()
    {
        return $this->hasMany(FAQsCategory::class, 'id', 'category_id');
    }
}
