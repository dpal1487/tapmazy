<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripeUser extends UID
{
    use HasFactory;

    protected $fillable = ['user_id', 'stripe_customer_id'];

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class, 'user_id', 'user_id')->where(['status' => 'active']);
    }
}
