<?php

namespace App\Http\Controllers;


class APIController extends Controller
{
    //

    public function appLogin()
    {

        //        dd($request->all());
        response()->json(['success' => 'success'], 200);

    }
}
