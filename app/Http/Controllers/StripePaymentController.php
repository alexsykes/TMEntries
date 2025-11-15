<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
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
        $numEntries = sizeof($entryIDs);

//        Get price and qty data
        $priceData = DB::table('entries')
            ->whereIn('id', $entryIDs)
            ->select(DB::raw('stripe_price_id, count(*) as qty'))
            ->groupBy('stripe_price_id')
            ->get()
            ->toArray();


        $trialIDs = DB::table('entries')
            ->whereIn('id', $entryIDs)
            ->select('trial_id')
            ->get();

        $trialIDArray = array();
        foreach ($trialIDs as $trialID) {
            array_push($trialIDArray, $trialID->trial_id);
        }

        $trialIDString = implode(",", array_unique($trialIDArray));

        $extras = DB::table('trials')
            ->select(DB::raw('GROUP_CONCAT(extras) AS extras'))
            ->whereIn('id', $trialIDArray)
            ->whereNot('extras', '')
            ->first();


        $stripe = new StripeClient(Config::get('stripe.stripe_secret_key'));

        $redirectUrl = route('checkout-success') . '?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = config('app.url') . "/user/entries";

        $lineItems = array();
        $optionalItems = array();

        foreach ($priceData as $entry) {
            $line = [
                'price' => $entry->stripe_price_id,
                'quantity' => $entry->qty,
            ];
            // Add to lineItems
            array_push($lineItems, $line);
        }

//        if ($extras) {
            $extraProductIDs = array_unique(explode(",", $extras->extras));



            if (sizeof($extraProductIDs) > 0) {
                foreach ($extraProductIDs as $extra) {
                    $optionalItem =
                        ['price' => $extra,
                            'quantity' => $numEntries,
                            'adjustable_quantity' => [
                                'enabled' => true,
                                'minimum' => 0,
                                'maximum' => $numEntries,
                            ],
                        ];
                    array_push($optionalItems, $optionalItem);
                }
            }
//        }

        if($extras->extras) {
        $requestArray = [
            'success_url' => $redirectUrl,
            'cancel_url' => $cancelUrl,

            'consent_collection' => ['terms_of_service' => 'required'],
            'custom_text' => ['terms_of_service_acceptance' =>
                ['message' => 'I agree to the Terms and Conditions as displayed on the TrialMonster website',],
            ],
            'line_items' => [
                $lineItems
            ],
            'optional_items' => [
                $optionalItems
            ],
            'phone_number_collection' => ['enabled' => true],
            'mode' => 'payment',
            'allow_promotion_codes' => false,
            'metadata' => [
                'entryIDs' => $request->entryIDs,
                'trialID' => $trialIDString,
            ]
        ]; }
        else {
            $requestArray = [
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
                    'trialID' => $trialIDString,
                ]
            ];
        }

        $response = $stripe->checkout->sessions->create($requestArray);

        return redirect($response['url']);
    }


    public function stripeUserCheckout(Request $request)
    {
        $userID = auth()->user()->id;

        $toPayEntries = DB::table('entries')
            ->join('trials', 'entries.trial_id', '=', 'trials.id')
            ->select('entries.id', 'entries.trial_id', 'entries.stripe_price_id', 'trials.name as trial')
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
