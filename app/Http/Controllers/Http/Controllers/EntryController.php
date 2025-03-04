<?php

namespace App\Http\Controllers\Http\Controllers;

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
        $entry->dob = $request->dob;

        if (isset($request->isYouth)) {
            $entry->isYouth = 1;
        } else {
            $entry->isYouth = 0;
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

    public function checkout(Request $request) {
//        dd($request->email);
        $email = session('email');
        $phone = session('phone');
        $trial_id = session('trial_id');

        $trial = Trial::findorfail($trial_id);

        $entries = Entry::all()->where('email', $email)
            ->where('trial_id', $trial_id)
            ->where('status', 0)
            ->where('phone', $phone)
            ->sortBy('name');

        return view('entries.checkout', ['entries' => $entries,  'trial' => $trial, 'email' => $email, 'phone' => $phone] );
    }

//    public function create_another() {
//        $IPaddress = request()->ip();
//        $id = session('trial_id');
//        $trial = Trial::findOrFail($id);
//        return view('entries.create_another', ['trial' => $trial]);
//    }



//    Store first record then pass email and trial_id to create_another view
    public function store(Request $request)
    {
        $IPaddress = $request->ip();
        $request->session()->put('trial_id', $request->trial_id);
        $request->session()->put('email', $request->email);
        $accept = session('accept');

//        Get product/price IDs
        $youthProductID = DB::table('products')
            ->where('trial_id', $request->trial_id)
            ->where('isYouth', true)
            ->value('stripe_product_id');


        $adultProductID = DB::table('products')
            ->where('trial_id', $request->trial_id)
            ->where('isYouth', false)
            ->value('stripe_product_id');


        $youthPriceID = DB::table('products')
            ->where('trial_id', $request->trial_id)
            ->where('isYouth', true)
            ->value('stripe_price_id');

        $adultPriceID = DB::table('products')
            ->where('trial_id', $request->trial_id)
            ->where('isYouth', false)
            ->value('stripe_price_id');


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
            $attributes['stripe_price_id'] = $youthPriceID;
            $attributes['stripe_product_id'] = $youthProductID;
        } else {
            $attributes['isYouth'] = 0;
            $attributes['stripe_price_id'] = $adultPriceID;
            $attributes['stripe_product_id'] = $adultProductID;
        }

        $attributes['dob'] = $request->dob;



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



    public function adminEntries(Request $request) {
        $email = session('email');
        $trial_id = $request->input('trial_id');
        $trial =  Trial::findOrFail($trial_id);
        $phone = session('phone');
        $entries = Entry::all()->where('email', $email)->where('trial_id', $trial_id)->where('phone', $phone)->where('paid', 0);
//        dd($entries);
        return view('entries.adminEntries', ['entries' => $entries, 'trial_id' => $trial_id, 'email' => $email, 'phone' => $phone, 'trial' => $trial]);
    }

    public function edit(Request $request) {
        $entry = Entry::findorfail($request->entry);
        $trialid = session('trial_id');
        $trial = Trial::findorfail($trialid);
        return view('entries.edit', ['entry' => $entry, 'trial' => $trial]);
    }

    public function createStripeSession(Request $request) {
        require ('../vendor/autoload.php');
        require('../vendor/stripe/stripe-php/lib/StripeClient.php');
        $stripe = new \Stripe\StripeClient('sk_test_51MMQJsDJZeL6aXCC6MsOulFeSySfNQI7NELzajF8qZKhDueOO1vWVM1oj59FN7cPOluMZ2GFOS9Hp0J8u9oofbNy00v3rPESVH');

        $email = $request->input('email');
        $trial_id = $request->input('trial_id');
        $trial_id = session('trial_id');
        $phone  = $request->input('phone');
        $entryIDs = $request->input('entryIDs');
        $entryIDArray  = explode(',',$request->input('entryIDs'));



        $entryData = Entry::all()->whereIn('id',  $entryIDArray);

        $lineItems = array();

        foreach($entryData as $entry ) {
//            dump($index);
            $entryID = $entry['id'];
            $isYouth = $entry['isYouth'];
            $stripe_price_id = $entry['stripe_price_id'];
            $stripe_product_id = $entry['stripe_product_id'];

            $name = $entry['name'];
            $entryid = $trial_id . "/" . $entryID;


            $line = [
                'product' => $stripe_product_id,
                'quantity' => 1,
            ];
            // Add to lineItems

//            dump($line);
            array_push($lineItems, $line);
//            array_push($ids, $id);
//            array_push($entryPriceIds, $stripe_product_id);
        }

        $data = [
            'metadata' => [
                'entryids' => $entryIDs,
                'payment_type' => 'session',
                'trialid' => $trial_id,
                'category' => 'entry fee',
            ],
            'line_items' => $lineItems,
//            'mode' => 'payment',
            'success_url' =>"https://dev.trialmonster.net",
            'cancel_url' => "https://dev.trialmonster.net/entries/checkout",
//            'phone_number_collection' => ['enabled' => true],
        ];
dd("lineitems: ", $lineItems, "data: ", $data);
        $checkout_session = $stripe->checkout->sessions->create($data);
        $url = $checkout_session->url;
    }
}
