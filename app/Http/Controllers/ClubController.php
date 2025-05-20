<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClubController extends Controller
{
    //

    public function list() {

        return view('clubs.List');
    }

    public function detail() {
        return view('clubs.Detail');
    }

    public function add() {
        return view('clubs.New');
    }

    public function store() {
        return redirect('/clubs/list');
    }

    public function update() {
        return redirect('/clubs/list');
    }
}
