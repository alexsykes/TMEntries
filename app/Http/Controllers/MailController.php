<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MailController extends Controller
{
    //

    public function edit($id){


        return view('mail.edit', ['id' => $id]);
    }
    public function preview($id){
        return view('mail.preview', ['id' => $id]);
    }
    public function add(){
        return view('mail.add');
    }
}
