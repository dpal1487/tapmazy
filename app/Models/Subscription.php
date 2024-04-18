<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'session_id', 'plan_id', 'stripe_subscription_id', 'status', 'quantity', 'current_period_start', 'current_period_end', 'trial_start', 'trial_end', 'trial_period_days'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function plan()
    {
        return $this->hasOne(Plan::class, 'id', 'plan_id');
    }
    public function plans()
    {
        return $this->hasMany(Plan::class, 'id', 'plan_id');
    }
}
