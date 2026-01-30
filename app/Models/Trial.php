<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Trial extends Model
{
    protected $guarded = ['id'];

    public function venue(): HasOne
    {
        return $this->hasOne(Venue::class, 'id', 'venueID');
    }

    public function club(): HasOne
    {
        return $this->hasOne(Club::class, 'id', 'club_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class);
    }
}
