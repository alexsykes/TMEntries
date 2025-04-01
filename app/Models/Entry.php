<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Entry extends Model
{
    //
    protected $fillable = [
        'name', 'trial_id', 'class', 'course',  'isYouth', 'dob', 'updated_at', 'email', 'phone', 'stripe_product_id', 'stripe_price_id', 'status', 'licence', 'IPaddress', 'make', 'size', 'type', 'token', 'created_by'
    ];


    public function trial(): HasOne {
        return $this->hasOne(Trial::class, 'id', 'trial_id');
    }
    public function price(): HasOne {
        return $this->hasOne(Price::class, 'stripe_price_id', 'id');
    }

    public function result(): HasOne {
        return $this->hasOne(Result::class, 'id', 'id');
    }
}
