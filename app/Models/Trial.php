<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trial extends Model
{

    //
//    protected $fillable = [
//        'name', 'id', 'classlist', 'courselist',  'date', 'club', 'updated_at','authority','created_by', 'email', 'phone',
//    ];

    protected $guarded = ['id'];
}
