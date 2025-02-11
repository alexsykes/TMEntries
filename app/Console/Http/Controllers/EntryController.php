<?php

namespace App\Console\Http\Controllers;

use App\Models\Entry;
use App\Models\Trial;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    //
    public function create($id) {
//        Not sure if this is necessary
        $trial = Trial::findOrFail($id);
        return view('entries.create', ['trial' => $trial]);
    }


    public function create_another() {
        $IPaddress = request()->ip();
        $id = session('trial_id');
        $trial = Trial::findOrFail($id);
        return view('entries.create_another', ['trial' => $trial]);
    }

//    Store first record then pass email and trial_id to create_another view
    public function store(Request $request) {
        $IPaddress = $request->ip();
        $request->session()->put('trial_id', $request->trial_id);
        $request->session()->put('email', $request->email);
        $attributes = $request->validate([
            'name' => 'required',
            'trial_id' => 'required',
            'phone' => 'required',
            'email' => ['required', 'email', 'max:254'],
            'class' => 'required',
            'course' => 'required',
            'make' => 'required',
            'type' => 'required',
        ]);

        $attributes['IPaddress'] = $IPaddress;
        $attributes['size'] = $request->size;

        if(isset($request->isYouth)) {
            $attributes['isYouth'] = 1;
            $attributes['dob'] = $request->dob;
        }

        $trial = Trial::findOrFail($attributes['trial_id']);
        Entry::create($attributes);
        $entries = Entry::all()->where('IPaddress', $IPaddress)->where('trial_id', session('trial_id'))->where('email', $attributes['email']);
//        dd($entries);
        return view('.entries.create_another', ['email' => $request->email, 'trial' => $trial, 'entries' => $entries]);
    }


    public function edit(Request $request) {
        return view('entries.edit');
    }
}
