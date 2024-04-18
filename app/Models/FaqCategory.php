<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FaqCategory extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'title', 'status', 'image_id'];

    public function image()
    {
        return $this->hasOne(Image::class, 'entity_id', 'id')->where('entity_type', 'faq-category');
    }

    public function category()
    {
        return $this->hasOne(Faq::class, 'category_id', 'id');
    }

    public function categories()
    {
        return $this->hasMany(Faq::class, 'category_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($faqCategory) {
            $faqCategory->slug = Str::slug($faqCategory->title);
        });

        static::deleting(function ($faqCategory) {
            if ($faqCategory->image) {
                $faqCategory->image->delete();
                unlink($faqCategory->image->path . '/' . $faqCategory->image->name);
            }
            $faqCategory->categories->delete();
        });
    }
}
