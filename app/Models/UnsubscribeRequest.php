<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnsubscribeRequest extends Model
{
    protected function casts(): array
    {
        return [
            'isCompleted' => 'boolean',
        ];
    }
}
