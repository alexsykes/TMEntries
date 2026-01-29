<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Club;
use App\Models\Venue;

class Trial extends Model
{
    public function venue(): HasOne {
        return $this->hasOne(Venue::class, 'id', 'venueID');
    }

    public function club(): HasOne {
        return $this->hasOne(Club::class, 'id', 'club_id');
    }

    protected $guarded = ['id'];
}
