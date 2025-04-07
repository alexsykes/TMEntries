<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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


        $stripe = new \Stripe\StripeClient(Config::get('stripe.stripe_secret_key'));

        $redirectUrl = route('checkout-success') . '?session_id={CHECKOUT_SESSION_ID}';
//        $redirectUrl = "https://bbc.com";

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

            'consent_collection' => ['terms_of_service' => 'required'],
            'custom_text' => ['terms_of_service_acceptance' =>
                ['message' => 'I agree to the Terms and Conditions which were supplied by email on registration with TrialMonster',],
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

    public function checkoutSuccess(Request $request)
    {
//        dd($request->all());
        $stripe = new \Stripe\StripeClient(Config::get('stripe.stripe_secret_key'));

        $session = $stripe->checkout->sessions->retrieve($request->session_id);
        $successMessage = "Your payment has been successfully processed! You should shortly received an email notification.";

        return view('success', compact('successMessage'));
    }
}
