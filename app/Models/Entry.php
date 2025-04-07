<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Entry extends Model
{
    //
    protected $guarded = ['id'];

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
