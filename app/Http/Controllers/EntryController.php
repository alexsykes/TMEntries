<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Result;
use App\Models\Trial;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EntryController extends Controller
{
    //

//    Used at initial system entry to get user email/phone
    public function getUserDetails(Request $request)
    {
        $trial_id = request('id');
        session(['trial_id' => $trial_id]);

        return view('entries.get_user_details');
    }

//  Used to display user's current entries
    public function showUserData(Request $request)
    {

        $email = session('email');
        $phone = session('phone');
        $trial_id = session('trial_id');
        $entries = Entry::all()->where('created_by', 1)
            ->where('trial_id', $trial_id)
            ->where('status', 0);
//        $entries = Entry::all();

//        dump($entries);
        $trial = Trial::findorfail($trial_id);

        return view('entries.userdata', ['entries' => $entries, 'trial' => $trial]);
    }

    public function userdata(Request $request)
    {
        $trial_id = $request->trialid;
        $user_id = \Auth::user()->id;

        $trial = Trial::findorfail($trial_id);

        $entries = Entry::all()
            ->where('created_by', $user_id)
            ->where('trial_id', $trial_id)
            ->where('status', 0);

        return view('entries.userdata', ['entries' => $entries, 'trial' => $trial]);
    }

    public function updateEntry(Request $request)
    {
//        $accept = session('accept');
        //        Get product/price IDs

        $request->validate([
            'name' => ['required', 'min:5', 'max:255'],
            'class' => 'required',
            'course' => 'required',
            'make' => 'required',
            'type' => 'required',
        ]);


        $entry = Entry::find($request->id);
        $trial_id = $entry->trial_id;

        $youthProductID = DB::table('products')
            ->where('trial_id', $trial_id)
            ->where('isYouth', true)
            ->value('stripe_product_id');


        $adultProductID = DB::table('products')
            ->where('trial_id', $trial_id)
            ->where('isYouth', false)
            ->value('stripe_product_id');


        $youthPriceID = DB::table('products')
            ->where('trial_id', $trial_id)
            ->where('isYouth', true)
            ->value('stripe_price_id');

        $adultPriceID = DB::table('products')
            ->where('trial_id', $trial_id)
            ->where('isYouth', false)
            ->value('stripe_price_id');

        $entry->name = $request->name;
        $entry->class = $request->class;
        $entry->course = $request->course;
        $entry->licence = $request->licence;

        $entry->make = $request->make;
        $entry->type = $request->type;
        $entry->size = $request->size;
//        $entry->accept = $accept;
        $entry->dob = $request->dob;

        if (isset($request->isYouth)) {
            $entry->isYouth = 1;
            $entry->stripe_price_id = $youthPriceID;
            $entry->stripe_product_id = $youthProductID;
        } else {
            $entry->isYouth = 0;
            $entry->stripe_price_id = $adultPriceID;
            $entry->stripe_product_id = $adultProductID;
        }

        $entry->save();
        return redirect("/entries/userdata/{$trial_id}");
    }

    public function withdraw(Request $request)
    {
        $id = $request->id;
        $entry = Entry::where('id', $id)->where('status', 1)->first();

        if($entry) {
            $pi = $entry->stripe_payment_intent;
            $price = Price::where('stripe_price_id', $entry->stripe_price_id)->first();
            $cost = $price->stripe_price;

//            $entry->status = 2; // Mark as withdrawn, having paid, waiting for refund
//            $entry->token = $token = bin2hex(random_bytes(16));
            $entry->save();

//        Request request
            require('../vendor/autoload.php');
            require('../vendor/stripe/stripe-php/lib/StripeClient.php');
            $stripe = new \Stripe\StripeClient(config('stripe.stripe_secret_key'));

            $stripe->refunds->create
            ([
                'payment_intent' => $pi,
                'amount' => $cost - 300,
            ]);

//    Mark as refund requested
//    Email user
        }
        return redirect("/");

    }

    public function userupdate(Request $request)
    {
        $id = $request->id;

        $attributes = $request->validate([
            'class' => 'required',
            'course' => 'required',
            'make' => 'required',
            'type' => 'required',
        ]);

        $entry = Entry::find($id);
        $entry->class = $request->class;
        $entry->course = $request->course;
        $entry->make = $request->make;
        $entry->type = $request->type;
        $entry->size = $request->size;
        $entry->token = $token = bin2hex(random_bytes(16));
        $entry->save();

//       emailConfirmation($id, 'change');
        return redirect("/entries/userdata/");
    }

    public function useredit(Request $request)
    {
//        dd($request->all());
        $token = $request->token;
        $id = $request->id;

        $entry = Entry::get()
            ->where('id', $id)
            ->where('status', 1)
            ->where('token', $token)->first();
        if($entry) {
            return view('entries.useredit', ['entry' => $entry]);
        }
        else { return redirect('404'); }
    }

//  Not sure if currently used
    public function create($id)
    {
//        Not sure if this is necessary

        session(['trial_id' => $id]);
        $trial = Trial::findOrFail($id);
        return view('entries.get_user_details', ['trial' => $trial, 'entry' => new Entry()]);
    }

    public function checkout(Request $request)
    {
        $user_id = \Auth::user()->id;
        $trial_id = session('trial_id');

        $trial = Trial::findorfail($trial_id);

        $entries = Entry::all()->where('created_by', $user_id)
            ->where('trial_id', $trial_id)
            ->where('status', 0)
            ->sortBy('name');

        return view('entries.checkout', ['entries' => $entries, 'trial' => $trial]);
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

        $trial_id = session('trial_id');

        $IPaddress = $request->ip();
        $request->session()->put('trial_id', $request->trial_id);
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
        $attributes['created_by'] = \Auth::user()->id;

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
        $entry = Entry::create($attributes);

        $entryID = $entry->id;
        $attr['entryID'] = $entryID;
        $trial = Trial::findOrFail($attributes['trial_id']);
        $entries = Entry::all()
            ->where('trial_id', $trial_id)
            ->where('created_by', $attributes['created_by']);

//        $entries = Entry::all()->where('IPaddress', $IPaddress)->where('trial_id', session('trial_id'))->where('email', $attributes['email']);
        return view('entries.userdata', ['entries' => $entries, 'trial' => $trial]);
    }

    public function delete(Request $request)
    {
        Entry::destroy($request->id);
        return redirect('entries/userdata/' . session('trial_id'));
    }

    public function list(Request $request)
    {
        $email = session('email');
        $trial_id = $request->input('trial_id');
        $trial = Trial::findOrFail($trial_id);
        $phone = session('phone');
        $entries = Entry::all()->where('email', $email)->where('trial_id', $trial_id)->where('phone', $phone)->where('paid', 0);
//        dd($entries);
        return view('entries.userdata', ['entries' => $entries, 'trial_id' => $trial_id, 'email' => $email, 'phone' => $phone, 'trial' => $trial]);
    }


    public function adminEntries(Request $request)
    {
        $email = session('email');
        $trial_id = $request->input('trial_id');
        $trial = Trial::findOrFail($trial_id);
        $phone = session('phone');
        $entries = Entry::all()->where('email', $email)->where('trial_id', $trial_id)->where('phone', $phone)->where('paid', 0);
//        dd($entries);
        return view('entries.adminEntries', ['entries' => $entries, 'trial_id' => $trial_id, 'email' => $email, 'phone' => $phone, 'trial' => $trial]);
    }

    public function edit(Request $request)
    {
        $entry = Entry::findorfail($request->entry);
        $trialid = session('trial_id');
        $trial = Trial::findorfail($trialid);
        return view('entries.edit', ['entry' => $entry, 'trial' => $trial]);
    }

    public function createStripeSession(Request $request)
    {
        require('../vendor/autoload.php');
        require('../vendor/stripe/stripe-php/lib/StripeClient.php');

        $stripe = new \Stripe\StripeClient(config('stripe.stripe_secret_key'));

        $email = $request->input('email');
        $trial_id = $request->input('trial_id');
        $trial_id = session('trial_id');
        $phone = $request->input('phone');
        $entryIDs = $request->input('entryIDs');
        $entryIDArray = explode(',', $request->input('entryIDs'));


        $entryData = Entry::all()->whereIn('id', $entryIDArray);

        $lineItems = array();

        foreach ($entryData as $entry) {
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
            'success_url' => "https://dev.trialmonster.net",
            'cancel_url' => "https://dev.trialmonster.net/entries/checkout",
//            'phone_number_collection' => ['enabled' => true],
        ];
        dd("lineitems: ", $lineItems, "data: ", $data);
        $checkout_session = $stripe->checkout->sessions->create($data);
        $url = $checkout_session->url;
    }
}
