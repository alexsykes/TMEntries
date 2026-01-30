<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Score extends Model
{
    public function trial(): BelongsTo
    {
        return $this->belongsTo(Trial::class);
    }
}
