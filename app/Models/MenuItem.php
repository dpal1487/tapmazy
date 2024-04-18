<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;
    protected $fillable = ['menu_id', 'role', 'title', 'url', 'target', 'icon_class', 'color', 'parent_id', 'order_by', 'route', 'parameters'];

    public function menu()
    {
        return $this->hasOne(Menu::class, 'id', 'menu_id');
    }

    public function parent()
    {
        return $this->hasOne(MenuItem::class, 'id', 'parent_id');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'menu_item_id', 'id');
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($menuItems) {
            // before delete() method call this
            $menuItems->permissions()->delete();
            // do the rest of the cleanup...
        });
    }
}
