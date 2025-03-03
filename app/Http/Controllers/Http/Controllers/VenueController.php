<?php

namespace App\Http\Controllers\Http\Controllers;

use App\Models\Venue;

class VenueController extends Controller
{
    //
    public function list() {
        $venues = Venue::all()->sortBy('name');
//        dd($venues);
        return view('venues.list', ['venues' => $venues]);
    }

    public function edit($id) {

        dd($id);
        $venues = Venue::all()->sortBy('name');
        return view('venues.list', ['venues' => $venues]);
    }

    public function add() {
        $venues = Venue::all()->sortBy('name');
        return view('venues.list', ['venues' => $venues]);
    }
}
