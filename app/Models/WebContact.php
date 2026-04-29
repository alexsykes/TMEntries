<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebContact extends Model
{
    protected $fillable = [
        'category',
        'name',
        'email',
        'message',
        'response',
        'responded_at',
        'closed',
        'action',
        'action_by',
        'ip_address',
        'token',
    ];

    protected function casts(): array
    {
        return [
            'responded_at' => 'timestamp',
            'closed' => 'boolean',
        ];
    }
}
