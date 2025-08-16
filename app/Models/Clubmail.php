<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clubmail extends Model
{
    protected $fillable = ['updated_at', 'club_id', 'trial_id','subject', 'bodyText', 'isLibrary', 'category' , 'summary', 'created_by', 'addressTo', 'othersTo', 'testAddress', 'fileName', 'mimeType', 'originalName'
    ];
}
