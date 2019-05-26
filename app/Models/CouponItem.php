<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponItem extends Model
{
    protected $table = 'coupon_items';
    protected $fillable = [
        'code',
        'description',
        'amount',
        'unit',
        'value',
        'total',
        'coupon_id'
    ];
}