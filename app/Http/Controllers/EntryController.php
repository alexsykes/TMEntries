<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Trial;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class EntryController extends Controller
{
    //
    public function create($id) {
//        Not sure if this is necessary
        $trial = Trial::findOrFail($id);
        return view('entries.create', ['trial' => $trial, 'entry' => new Entry()]);
    }


    public function create_another() {
        $IPaddress = request()->ip();
        $id = session('trial_id');
        $trial = Trial::findOrFail($id);
        return view('entries.create_another', ['trial' => $trial]);
    }

//    Store first record then pass email and trial_id to create_another view
    public function store(Request $request)
    {
        $IPaddress = $request->ip();
        $request->session()->put('trial_id', $request->trial_id);
        $request->session()->put('email', $request->email);
        $token = bin2hex(random_bytes(16));

        $attributes = $request->validate([
            'name' => ['required', 'min:3', 'max:255'],
            'trial_id' => 'required',
            'phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'email' => ['required', 'email', 'max:254'],
            'class' => 'required',
            'course' => 'required',
            'date' => [Rule::date()->before(today()->subYears(4)),],
            'make' => 'required',
            'type' => 'required',
        ]);

        $attributes['IPaddress'] = $IPaddress;
        $attributes['size'] = $request->size;
        $attributes['token'] = $token;
        $attributes['course'] = request()->course;
        $attributes['class'] = request()->class;
        $attributes['type'] = request()->type;

        if (isset($request->isYouth)) {
            $attributes['isYouth'] = 1;
            $attributes['dob'] = $request->dob;
        }

        Entry::create($attributes);

        $trial = Trial::findOrFail($attributes['trial_id']);
        $entries = Entry::all()->where('IPaddress', $IPaddress)->where('trial_id', session('trial_id'))->where('email', $attributes['email']);
    return view('entries/user_entryList', ['entries' => $entries, 'trial' => $trial]  );
    }


    public function edit(Request $request) {
        return view('entries.edit');
    }
}
