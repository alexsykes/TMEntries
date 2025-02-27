<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Result;
use App\Models\Trial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EntryController extends Controller
{
    //

//    Used at initial system entry to get user email/phone
    public function getUserDetails(Request $request) {
        $trial_id = request('id');
        session(['trial_id' => $trial_id]);

        return view('entries.get_user_details');
    }

//  Used to display user's current entries
    public function showUserData(Request $request)
    {
        if(!isset($request->accept)) {
            exit(404);
        } else {
            session(['accept' => 1]);
        }
        session(['email' => $request->email]);
        session(['phone' => $request->phone]);
        $email = session('email');
        $phone = session('phone');
        $trial_id = session('trial_id');
        $entries = Entry::all()->where('email', $email)->where('trial_id', $trial_id)->where('phone', $phone)->where('status', 0);
//        $entries = Entry::all();

//        dump($entries);
        $trial = Trial::findorfail($trial_id);

        return view('entries.entrydata', ['entries' => $entries,  'trial' => $trial]);
    }
    public function userdata(Request $request)
    {
        $email = session('email');
        $phone = session('phone');
        $trial_id = session('trial_id');
        $entries = Entry::all()->where('email', $email)->where('trial_id', $trial_id)->where('phone', $phone)->where('status', 0);
//        $entries = Entry::all();

//        dump($entries);
        $trial = Trial::findorfail($trial_id);

        return view('entries.entrydata', ['entries' => $entries,  'trial' => $trial]);
    }

    public function updateEntry(Request $request)  {
        $accept = session('accept');
        $request->validate([
            'name' => ['required', 'min:5', 'max:255'],
            'class' => 'required',
            'course' => 'required',
            'make' => 'required',
            'type' => 'required',
        ]);


        $entry = Entry::find($request->id);
        $entry->name = $request->name;
        $entry->class = $request->class;
        $entry->course = $request->course;
        $entry->licence = $request->licence;

        $entry->make = $request->make;
        $entry->type = $request->type;
        $entry->size = $request->size;
        $entry->accept = $accept;

        if (isset($request->isYouth)) {
            $entry->isYouth = 1;
            $entry->dob = $request->dob;
        }
        $entry->save();


        return redirect('entries/userdata');
    }

//  Not sure if currently used
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
        $IPaddress = $request->ip();
        $request->session()->put('trial_id', $request->trial_id);
        $request->session()->put('email', $request->email);
        $accept = session('accept');


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
        $attributes['licence'] = $request->licence;
        $attributes['token'] = $token;
        $attributes['accept'] = $accept;

        if (isset($request->isYouth)) {
            $attributes['isYouth'] = 1;
            $attributes['dob'] = $request->dob;
        }

        $entry =   Entry::create($attributes);

        $entryID = $entry->id;
        $attr['entryID'] = $entryID;
        $result = Result::create($attr);
//        DB::insert('insert into results (id) values ($entry->id)');

//        dd($entry->id);
//        ResultController::class->create_result($entry->id);

        $trial = Trial::findOrFail($attributes['trial_id']);
        $entries = Entry::all()->where('IPaddress', $IPaddress)->where('trial_id', session('trial_id'))->where('email', $attributes['email']);

        session(['trial_id' => $attributes['trial_id']]);
        session(['email' => $attributes['email']]);
        session(['phone' => $attributes['phone']]);
        return view('entries.entrydata', ['entries' => $entries, 'trial' => $trial]);
    }


    public function delete(Request $request) {
        Entry::destroy($request->id);
//        return redirect('entries/user_entryList');
        return redirect('entries/userdata');
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
