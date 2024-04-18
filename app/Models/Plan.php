<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends UID
{
    use HasFactory;
    protected $fillable = ['project_id', 'allowed_projects', 'allowed_invoices', 'allowed_users', 'allowed_clients', 'allowed_suppliers', 'slug', 'plan_name', 'sort_description', 'description', 'plan_type', 'status', 'stripe_plan_id', 'plan_price', 'currency_value', 'coupon_name', 'coupon_id', 'percent_off'];
    public function currency()
    {
        return $this->hasOne(Currency::class, 'currency_value', 'currency_value');
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'plan_id', 'id');
    }
}
