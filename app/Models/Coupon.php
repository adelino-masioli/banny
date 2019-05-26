<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';
    protected $fillable = [
        'url',
        'company',
        'document',
        'address',
        'datetime',
        'date',
        'time',
        'key_access',
        'protocol',
        'payment_method',
        'discount',
        'total',
        'status',
        'user_id'
    ];
}