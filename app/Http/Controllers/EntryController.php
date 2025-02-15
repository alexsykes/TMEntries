<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Trial;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    //

    public function getUserDetails(Request $request) {
        $trial_id = request('id');
        session(['trial_id' => $trial_id]);

        return view('entries.get_user_details');
    }

    public function showUserData(Request $request)
    {
        session(['email' => $request->email]);
        session(['phone' => $request->phone]);

        $trial_id = session('trial_id');
        $entries = Entry::all()->where('email', $request->email)->where('trial_id', $trial_id)->where('phone', $request->phone)->where('paid', 0);

        $trial = Trial::findorfail($trial_id);

        return view('entries.entrydata', ['entries' => $entries,  'trial' => $trial]);
    }


    public function create($id) {
//        Not sure if this is necessary

        session(['trial_id' => $id]);
        $trial = Trial::findOrFail($id);
        return view('entries.get_user_details', ['trial' => $trial, 'entry' => new Entry()]);
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
//        dd($request->all());
        $IPaddress = $request->ip();
        $request->session()->put('trial_id', $request->trial_id);
        $request->session()->put('email', $request->email);
        $token = bin2hex(random_bytes(16));

        $attributes = $request->validate([
            'name' => ['required', 'min:5', 'max:255'],
            'trial_id' => 'required',
            'phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'email' => ['required', 'email', 'max:254',],
            'class' => 'required',
            'course' => 'required',
            'make' => 'required',
            'type' => 'required',
        ]);

        $attributes['IPaddress'] = $IPaddress;
        $attributes['size'] = $request->size;
        $attributes['token'] = $token;
//        $attributes['course'] = request()->course;
//        $attributes['class'] = request()->class;
//        $attributes['type'] = request()->type;

        if (isset($request->isYouth)) {
            $attributes['isYouth'] = 1;
            $attributes['dob'] = $request->dob;
        }

        $entry =   Entry::create($attributes);
        $trial = Trial::findOrFail($attributes['trial_id']);
        $entries = Entry::all()->where('IPaddress', $IPaddress)->where('trial_id', session('trial_id'))->where('email', $attributes['email']);

//        $request->session()->put('email', $attributes['email']);
//        $request->session()->put('trial_id', $attributes['trial_id']);
//        $request->session()->put('phone', $attributes['phone']);

        session(['trial_id' => $attributes['trial_id']]);
        session(['email' => $attributes['email']]);
        session(['phone' => $attributes['phone']]);
        return view('entries.entrydata', ['entries' => $entries, 'trial' => $trial]);
    }


    public function delete(Request $request) {
        Entry::destroy($request->id);
        return redirect('entries/user_entryList');
    }
    public function list(Request $request) {
        $email = session('email');
        $trial_id = $request->input('trial_id');
        $trial =  Trial::findOrFail($trial_id);
        $phone = session('phone');
        $entries = Entry::all()->where('email', $email)->where('trial_id', $trial_id)->where('phone', $phone)->where('paid', 0);
//        dd($entries);
        return view('entries.entrydata', ['entries' => $entries, 'trial_id' => $trial_id, 'email' => $email, 'phone' => $phone, 'trial' => $trial]);
    }

    public function edit(Request $request) {
        $entry = Entry::findorfail($request->entry);
        $trialid = session('trial_id');
        $trial = Trial::findorfail($trialid);
        return view('entries.edit', ['entry' => $entry, 'trial' => $trial]);
    }
}
