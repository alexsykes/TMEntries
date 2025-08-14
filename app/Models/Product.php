<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'isYouth', 'isLive', 'isEntryFee', 'hasQuantity', 'trial_id', 'product_name', 'product_category', 'stripe_product_id', 'stripe_price_id', 'stripe_product_description', 'purchases', 'version', 'updated_at','club_id'
    ];
}
