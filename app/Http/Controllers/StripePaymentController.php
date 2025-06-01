<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Stripe\StripeClient;

class StripePaymentController extends Controller
{

    public function stripe()
    {
        $product = Config::get('stripe.product');
        return view('stripe', compact('product'));
    }

    public function stripeCheckout(Request $request)
    {
        $entryIDs = explode(',', $request->entryIDs);
        $entries = DB::table('entries')
            ->whereIn('id', $entryIDs)
            ->get();


        $stripe = new StripeClient(Config::get('stripe.stripe_secret_key'));

        $redirectUrl = route('checkout-success') . '?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = config('app.url')."/user/entries";
//        dd($cancelUrl);;
//        $cancelUrl = route('checkout-cancel');

        $lineItems = array();
        foreach ($entries as $entry) {
            $line = [
                'price' => $entry->stripe_price_id,
                'quantity' => 1,
            ];
            // Add to lineItems
            array_push($lineItems, $line);
        }

        $response = $stripe->checkout->sessions->create([
            'success_url' => $redirectUrl,
            'cancel_url' => $cancelUrl,

            'consent_collection' => ['terms_of_service' => 'required'],
            'custom_text' => ['terms_of_service_acceptance' =>
                ['message' => 'I agree to the Terms and Conditions as displayed on the TrialMonster website',],
            ],
            'line_items' => [
                $lineItems
            ],
            'phone_number_collection' => ['enabled' => true],
            'mode' => 'payment',
            'allow_promotion_codes' => false,
            'metadata' => [
                'entryIDs' => $request->entryIDs,
                'trialID' => $request->trialID,
            ]
        ]);

        return redirect($response['url']);
    }


    public function stripeUserCheckout(Request $request){
        $userID = auth()->user()->id;

        $toPayEntries = DB::table('entries')
            ->join('trials', 'entries.trial_id', '=', 'trials.id')
            ->select('entries.id',  'entries.trial_id', 'entries.stripe_price_id' ,'trials.name as trial')
            ->where('entries.created_by', $userID)
            ->where('entries.status', '=', 0)
            ->whereFuture('trials.date')
            ->get();

        dd($toPayEntries);
    }

    public function checkoutSuccess(Request $request)
    {
//        dd($request->all());
        $stripe = new StripeClient(Config::get('stripe.stripe_secret_key'));

        $session = $stripe->checkout->sessions->retrieve($request->session_id);
        $successMessage = "Your payment has been successfully processed! You should shortly receive an email notification.";

        return view('success', compact('successMessage'));
    }
}
