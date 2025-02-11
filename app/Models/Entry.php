<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    //
    protected $fillable = [
        'name', 'trial_id', 'class', 'course',  'isYouth', 'dob', 'updated_at', 'email', 'phone', 'stripe_product_id', 'stripe_price_id', 'status', 'licence', 'IPaddress', 'make', 'size', 'type'
    ];
}
