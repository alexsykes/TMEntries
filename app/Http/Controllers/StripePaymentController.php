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
        $numEntries = count($entryIDs);

        //        Get price and qty data
        $priceData = DB::table('entries')
            ->whereIn('id', $entryIDs)
            ->select(DB::raw('stripe_price_id, count(*) as qty'))
            ->groupBy('stripe_price_id')
            ->get()
            ->toArray();

        //        Get price and qty data
        $extraData = DB::table('entries')
            ->whereNotNull('extras')
            ->whereIn('id', $entryIDs)
            ->select(DB::raw('extras, count(*) as qty'))
            ->groupBy('extras')
            ->get()
            ->toArray();

        $trialIDs = DB::table('entries')
            ->whereIn('id', $entryIDs)
            ->select('trial_id')
            ->get();

        $trialIDArray = [];
        foreach ($trialIDs as $trialID) {
            array_push($trialIDArray, $trialID->trial_id);
        }

        $trialIDString = implode(',', array_unique($trialIDArray));

        $stripe = new StripeClient(Config::get('stripe.stripe_secret_key'));

        $redirectUrl = route('checkout-success') . '?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = config('app.url') . '/user/entries';

        $lineItems = [];
        $optionalItems = [];

//        dd($priceData);
        foreach ($priceData as $entry) {
            $line = [
                'price' => $entry->stripe_price_id,
                'quantity' => $entry->qty,
            ];
            // Add to lineItems
            array_push($lineItems, $line);
        }
        foreach ($extraData as $extra) {
            $line = [
                'price' => $extra->extras,
                'quantity' => $extra->qty,
            ];
            // Add to lineItems
            array_push($lineItems, $line);
        }

        //        dd($lineItems, $optionalItems);
        //        if ($extras) {
        //        $extraProductIDs = array_unique(explode(",", $extras->extras));

        //        if (sizeof($extraProductIDs) > 0) {
        //            foreach ($extraProductIDs as $extra) {
        //                $optionalItem =
        //                    ['price' => $extra,
        //                        'quantity' => $numEntries,
        //                        'adjustable_quantity' => [
        //                            'enabled' => true,
        //                            'minimum' => 0,
        //                            'maximum' => $numEntries,
        //                        ],
        //                    ];
        //                array_push($optionalItems, $optionalItem);
        //            }
        //        }
        //        }

        //        if (!is_null($optionalItems)) {
        //            $requestArray = [
        //                'success_url' => $redirectUrl,
        //                'cancel_url' => $cancelUrl,
        //
        //                'consent_collection' => ['terms_of_service' => 'required'],
        //                'custom_text' => ['terms_of_service_acceptance' =>
        //                    ['message' => 'I agree to the Terms and Conditions as displayed on the TrialMonster website',],
        //                ],
        //                'line_items' => [
        //                    $lineItems
        //                ],
        //                'optional_items' => [
        //                    $optionalItems
        //                ],
        //                'phone_number_collection' => ['enabled' => true],
        //                'mode' => 'payment',
        //                'allow_promotion_codes' => false,
        //                'metadata' => [
        //                    'entryIDs' => $request->entryIDs,
        //                    'trialID' => $trialIDString,
        //                ]
        //            ];
        //        } else {
        $requestArray = [
            'success_url' => $redirectUrl,
            'cancel_url' => $cancelUrl,

            'consent_collection' => ['terms_of_service' => 'required'],
            'custom_text' => ['terms_of_service_acceptance' => ['message' => 'I agree to the Terms and Conditions as displayed on the TrialMonster website'],
            ],
            'line_items' => [
                $lineItems,
            ],
            'phone_number_collection' => ['enabled' => true],
            'mode' => 'payment',
            'allow_promotion_codes' => false,
            'metadata' => [
                'entryIDs' => $request->entryIDs,
                'trialID' => $trialIDString,
            ],
        ];
        //        }

        $response = $stripe->checkout->sessions->create($requestArray);

        return redirect($response['url']);
    }

    public function stripeUserCheckout(Request $request)
    {
        info('StripePaymentController:StripeUserCheckout');
        $stripe = new StripeClient(Config::get('stripe.stripe_secret_key'));

        $redirectUrl = route('checkout-success') . '?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = config('app.url') . '/user/entries';

        $userID = auth()->user()->id;

        $toPayEntries = DB::table('entries')
            ->join('trials', 'entries.trial_id', '=', 'trials.id')
            ->select('entries.name', 'entries.id', 'entries.trial_id', 'entries.extras', 'entries.stripe_price_id', 'trials.name as trial')
            ->where('entries.created_by', $userID)
            ->where('entries.status', '=', 0)
            ->whereFuture('trials.date')
            ->get();

        $priceIDs = [];
        $entryIDs = [];
        $trialIDs = [];
        foreach ($toPayEntries as $entry) {
            array_push($priceIDs, $entry->stripe_price_id);
            array_push($entryIDs, $entry->id);
            array_push($trialIDs, $entry->trial_id);

            $extras = json_decode($entry->extras);
            if (!is_null($extras)) {
                foreach ($extras as $extra) {
                    for ($i = 0; $i < $extra->qty; $i++) {
                        array_push($priceIDs, $extra->priceID);
                    }
                }
            }
        }

        $entryIDstring = implode(',', array_unique($entryIDs));
        $trialIDstring = implode(',', array_unique($trialIDs));
//        dd($priceIDs);

        //        dd($entryIDstring, $priceIDs, $trialIDstring);
        $count_values = array_count_values($priceIDs);

        $priceIDs = array_keys($count_values);
        $quantities = array_values($count_values);

        $lineItems = [];
        for ($i = 0; $i < count($priceIDs); $i++) {
            $line = [
                'price' => $priceIDs[$i],
                'quantity' => $quantities[$i],
            ];
            array_push($lineItems, $line);
        }

        if (sizeof($lineItems) > 0) {
            $requestArray = [
                'success_url' => $redirectUrl,
                'cancel_url' => $cancelUrl,

                'consent_collection' => ['terms_of_service' => 'required'],
                'custom_text' => ['terms_of_service_acceptance' => ['message' => 'I agree to the Terms and Conditions as displayed on the TrialMonster website'],
                ],
                'line_items' => [
                    $lineItems,
                ],
                'phone_number_collection' => ['enabled' => true],
                'mode' => 'payment',
                'allow_promotion_codes' => false,
                'metadata' => [
                    'entryIDs' => $entryIDstring,
                    'trialID' => $trialIDstring,
                ],
            ];
            //        }

            $response = $stripe->checkout->sessions->create($requestArray);
        } else {
            info("Nothing in basket");
            return redirect('/');
//            return redirect('/entries/register/'.$trialIDs[0]);
        }

        return redirect($response['url']);
    }

    public function checkoutSuccess(Request $request)
    {
        //        dd($request->all());
        $stripe = new StripeClient(Config::get('stripe.stripe_secret_key'));

        $session = $stripe->checkout->sessions->retrieve($request->session_id);
        $successMessage = 'Your payment has been successfully processed! You should shortly receive an email notification.';

        return view('success', compact('successMessage'));
    }
}
