<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = ['stripe_payment_id', 'amount', 'status', 'payment_mode'];
    public function subscription()
    {
    }
}
