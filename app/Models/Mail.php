<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    protected $fillable = ['updated_at', 'club_id', 'subject', 'bodyText', 'isLibrary', 'category' , 'subject'
    ];
}
