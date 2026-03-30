<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntryPurchase extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'entry_id',
        'stripe_price_id',
        'quantity',
    ];
}
