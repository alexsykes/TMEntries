<?php

namespace App\Http\Controllers;

use App\Events\EntryWithdrawn;
use App\Models\Entry;
use App\Models\Price;
use App\Models\Trial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;

class UserController extends Controller
{
    //

    public function entryList()
    {
        $userID = auth()->user()->id;

        $futureTrials = DB::table('trials')
            ->where('published', 1)
            ->whereAfterToday('date')
            ->get('id');

        $todaysTrials = DB::table('trials')
            ->where('published', 1)
            ->whereToday('date')
            ->get('id');

        $todaysEntries = DB::table('entries')
            ->where('created_by', $userID)
            ->whereIn('trial_id', $todaysTrials->pluck('id'))
            ->get();
//
//
//        $allEntries = DB::table('entries')
//            ->join('trials', 'entries.trial_id', '=', 'trials.id')
//            ->where('entries.created_by', Auth::user()->id)
//            ->get(['entries.name', 'entries.class', 'entries.course', 'trials.name as trial', 'trials.date as date']);
//        dump($allEntries);

        $futureTrialsArray = array();
        foreach ($futureTrials as $futureTrial) {
            array_push($futureTrialsArray, $futureTrial->id);
        }
        $toPays = DB::table('entries')
            ->join('trials', 'entries.trial_id', '=', 'trials.id')
            ->where('entries.created_by', $userID)
            ->where('entries.status', 0)
            ->whereIn('entries.trial_id', $futureTrialsArray)
            ->orderBy('entries.name')
            ->select('entries.id', 'entries.status', 'entries.name', 'entries.class', 'entries.course', 'trials.name as trial', 'trials.isEntryLocked')
            ->get();

        $entries = DB::table('entries')
            ->join('trials', 'entries.trial_id', '=', 'trials.id')
            ->where('entries.created_by', $userID)
            ->whereIn('entries.status', [1, 2, 3, 4, 5, 6, 7, 8, 9])
            ->whereIn('entries.trial_id', $futureTrialsArray)
            ->orderBy('entries.name', 'asc')
            ->select('entries.id', 'entries.status', 'entries.name', 'entries.class', 'entries.course', 'trials.name as trial', 'trials.isEntryLocked')
            ->get();

        return view('user.entry_list', compact('entries', 'toPays', 'todaysEntries'));
    }

    public function editEntry($id)
    {
        info("EntryID: $id");
        $userID = auth()->user()->id;
        $entry = DB::table('entries')
            ->join('trials', 'entries.trial_id', '=', 'trials.id')
            ->where('entries.id', $id)
            ->where('entries.created_by', $userID)
            ->whereIn('entries.status', [0, 1, 4, 5])
            ->get(['entries.*', 'trials.name as trial_name', 'trials.club as club', 'trials.classlist', 'trials.courselist', 'trials.customClasses', 'trials.customCourses', 'trials.isEntryLocked', 'trials.date as trialdate'])
            ->first();

        if ($entry == null) {
            abort(404);
        }
        return view('user.edit_entry', ['entry' => $entry]);
    }

//    Update entry from My Entries page
    public function updateEntry(Request $request)
    {
        $id = $request->entryID;
        $action = $request->action;

        switch ($action) {
            case 'save':
                $entry = Entry::findorfail($id);
                $trial_id = $entry->trial_id;
                $trial = Trial::findOrFail($trial_id);
                $trial_date = date_create($trial->date);

                //        Get product/price IDs
                $youthProductID = DB::table('products')
                    ->where('trial_id', $trial_id)
                    ->where('isYouth', true)
                    ->where('product_category', 'entry fee')
                    ->value('stripe_product_id');


                $adultProductID = DB::table('products')
                    ->where('trial_id', $trial_id)
                    ->where('isYouth', false)
                    ->where('product_category', 'entry fee')
                    ->value('stripe_product_id');


                $youthPriceID = DB::table('prices')
                    ->where('stripe_product_id', $youthProductID)
                    ->value('stripe_price_id');

                $adultPriceID = DB::table('prices')
                    ->where('stripe_product_id', $adultProductID)
                    ->value('stripe_price_id');


                $request->validate([
                    'class' => 'required',
                    'course' => 'required',
                    'make' => 'required',
                    'type' => 'required',
                ]);
                $entry->dob = $request->dob;
                $entry->class = $request->class;
                $entry->course = $request->course;
                $entry->make = $request->make;
                $entry->type = $request->type;
                $entry->size = $request->size;

//                Adjust product etc. for dob
                $birthDate = date_create($request->dob);

                $interval = $trial_date->diff($birthDate);
                $ageInYears = $interval->format('%y');

                if($ageInYears < 18) {
                    $entry->isYouth = 1;
                    $entry->stripe_price_id = $youthPriceID;
                    $entry->stripe_product_id = $youthProductID;
                } else {
                    $entry->isYouth = 0;
                    $entry->stripe_price_id = $adultPriceID;
                    $entry->stripe_product_id = $adultProductID;
                }



                $entry->save();
                return redirect('/user/entries');
                break;

            case 'withdraw':
                $userID = auth()->user()->id;
                $entry = Entry::findorfail($id);

                if ($userID != $entry->created_by) {
                    abort(403);
                }

                return view('user.confirm_remove_entry', ['entry' => $entry]);
                break;

            default:
                dd("None");
                return redirect('/user/entries');
                break;
        }
    }

    public function removeEntry($id)
    {
        $userID = auth()->user()->id;
        $entry = Entry::findorfail($id);


        if ($userID != $entry->created_by) {
            abort(403);
        }
        return view('user.confirm_remove_entry', ['entry' => $entry]);
    }

    public function userWithdraw($id)
    {
        $userID = auth()->user()->id;
        $entry = Entry::findorfail($id);

        if ($userID != $entry->created_by) {
            abort(403);
        }

        if ($entry->status == 1) {

//        Get payment details
            $pi = $entry->stripe_payment_intent;
            $price = Price::where('stripe_price_id', $entry->stripe_price_id)->first();
            $cost = $price->stripe_price;

            $entry->updated_at = now();
            $entry->status = 2;
            $entry->save();

//        dd($id, $entry->stripe_payment_intent);
//                    Request request
            require('../vendor/autoload.php');
            require('../vendor/stripe/stripe-php/lib/StripeClient.php');
            $stripe = new StripeClient(config('stripe.stripe_secret_key'));

            $stripe->refunds->create
            ([
                'payment_intent' => $pi,
                'amount' => $cost - 300,
//                'amount' => 1,
                'metadata' => [
                    'entry_id' => $entry->id,
                ]
            ]);
        } elseif ($entry->status == 0) {
            $entry->updated_at = now();
            $entry->status = 6;
            $entry->save();
        }

        EntryWithdrawn::dispatch($id);
        return redirect('user/entries');
    }

    public function checkout()
    {


        return redirect('stripe/usercheckout');
    }

}
