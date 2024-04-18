<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'descriptions', 'page'];
    public function image()
    {
        return $this->hasOne(Image::class, 'entity_id', 'id')->where('entity_type', 'service');
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($service) { {
                if ($service->image) {
                    $service->image->delete();
                    unlink($service->image->path . '/' . $service->image->name);
                }
            }
        });
    }
}
