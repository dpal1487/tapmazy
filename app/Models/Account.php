<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $fillable = ['is_primary','bank_name', 'bank_address', 'beneficiary_name', 'account_number', 'routing_number', 'swift_code', 'ifsc_code', 'sort_code', 'pan_number'];
}
