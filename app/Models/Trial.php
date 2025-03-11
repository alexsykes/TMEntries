<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Trial extends Model
{
    public function venue(): HasOne {
        return $this->hasOne(Venue::class, 'id', 'venueID');
    }

    protected $guarded = ['id'];
}
