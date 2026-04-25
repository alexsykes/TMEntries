<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebContact extends Model
{
    protected $fillable = [
        'category',
        'from',
        'email',
        'content',
        'response',
        'responded_at',
        'closed',
        'action',
        'action_by',
    ];

    protected function casts(): array
    {
        return [
            'responded_at' => 'timestamp',
            'closed' => 'boolean',
        ];
    }
}
