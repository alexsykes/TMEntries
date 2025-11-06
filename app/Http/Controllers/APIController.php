<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class APIController extends Controller
{
    //

    public function appLogin()
    {

//        dd($request->all());
        response()->json(['success' => 'success'], 200);


    }
}
