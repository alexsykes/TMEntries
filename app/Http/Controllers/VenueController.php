<?php

namespace App\Http\Controllers;

use App\Models\Venue;

class VenueController extends Controller
{
    //
    public function list() {
        $venues = Venue::all()->sortBy('name');
//        dd($venues);
        return view('venues.list', ['venues' => $venues]);
    }

    public function edit() {
        $venueID = request()->id;

        $venue = \DB::table('venues')->where('id', $venueID)->first();
        return view('venues.edit', ['venue' => $venue]);
    }

    public function add() {
        $venues = Venue::all()->sortBy('name');
        return view('venues.edit', ['venues' => $venues]);
    }

    public function save(){
//        dd(request()->all());
        $id = request()->id;
        $attrs = request()->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'directions' => 'required',
            'landowner' => 'required',
            'latitude' => 'required','decimal:6',
            'longitude' => 'required','decimal:6',
        ]);

        $attrs['w3w'] = request('w3w', '');
        $attrs['notes'] = request('notes', '');
        $attrs['centre'] = request('centre', '');
        $attrs['club'] = request('club', '');
        $attrs['postcode'] = request('postcode', '');


        $venue = Venue::findorfail($id);
        $venue->update($attrs);
        return redirect()->route('venues');
    }
}
