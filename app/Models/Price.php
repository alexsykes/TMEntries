<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = [
        'stripe_product_id', 'stripe_price_id', 'stripe_price', 'purchases'
    ];
}
