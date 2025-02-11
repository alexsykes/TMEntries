<?php

namespace App\Http\Controllers;

use App\Models\Trial;
use App\Models\Entry;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    //
    public function create($id) {
        if(session()->has('trial_id')) {
            $id = session('trial_id');
    }

        $trial = Trial::findOrFail($id);
        return view('entries.create', ['trial' => $trial]);
    }


    public function create_another() {
        $IPaddress = request()->ip();
        $id = session('trial_id');
        $trial = Trial::findOrFail($id);
        $entries = Entry::all()->where('IPaddress', $IPaddress)->where('trial_id', session('trial_id'));

        return view('entries.create_another', ['entries' => $entries, 'trial' => $trial]);
    }
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
//        dd($attributes);
        Entry::create($attributes);
        return view('/entries/create_another', ['email' => $request->email, 'trial_id' => $request->trial_id]);
    }


    public function edit(Request $request) {
        return view('entries.edit');
    }
}
