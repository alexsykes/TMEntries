<?php

namespace App\Listeners;

use App\Events\EntryWithdrawn;
use App\Models\Entry;
use App\Models\Trial;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;

class OnEntryWithdrawn
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EntryWithdrawn $event): void
    {
        //        info("OnEntryWithdrawn");
        $entryID = $event->entryID;

        //      Get trial details
        $entry = Entry::findOrFail($entryID);
        $trialID = $entry->trial_id;
        $trial = Trial::findOrFail($trialID);
        $entryLimit = $trial->entryLimit;
        $hasLimit = $trial->hasEntryLimit;

        $numEntries = Entry::where('trial_id', $trialID)
            ->whereIn('status', [1, 4, 7, 8, 9, 10])
            ->count();

        //  Check for vacancy created
        info("Trial limit: $entryLimit \n TrialID: $trialID \n Trial hasLmit: $hasLimit \n NumEntries: $numEntries \n");
        if ($hasLimit && $entryLimit > $numEntries) {
            $vacancies = $entryLimit - $numEntries;

            //            Get reserve entry
            $entriesToOffer = Entry::where('trial_id', $trialID)
                ->where('status', 5)
                ->limit($vacancies)
                ->get();

            foreach ($entriesToOffer as $entry) {
                $entryID = $entry->id;

                //                Change entry status to under offer
                $entry->status = 4;
                info("Entry Withdrawn: place to offer: $entryID");
                $userID = $entry->created_by;
                $user = User::findOrFail($userID);
                $email = $user->email;
                $username = $user->name;

                //                TODO remove comment
                $entry->update();

                //              Prepare invoice
                $this->invoice($entry, $email, $username);
            }
        }

    }

    public function invoice($entry, $email, $username)
    {
//        New stuff
//        dd($entry);


//        Ends

        $newStripe = new StripeClient(Config::get('stripe.stripe_secret_key'));

        $trialID = $entry->trial_id;
        $trial = Trial::findOrFail($trialID);
        $trialName = $trial->name;
        $trialClub = $trial->club;

        $customer = $newStripe->customers->create([
            'email' => $email,
            'name' => $username,
        ]);

        $customerId = $customer->id;

        $entryID = $entry->id;
        // Create an Invoice

//         TODO uncomment
        $invoice = $newStripe->invoices->create([
            'customer' => $customerId,
            'description' => $trialClub . ' - ' . $trialName,
            'collection_method' => 'send_invoice',
            'days_until_due' => 3,
            'metadata' => [
                'entryID' => $entryID,
            ],
        ]);

        $invoiceId = $invoice->id;

        info("Invoice with ID: $invoice->id");

        //   Add line items
//         Firstly entry fee
        $invoiceItem = $newStripe->invoiceItems->create([
            'customer' => $customerId,
            'pricing' => [
                'price' => $entry->stripe_price_id,
            ],
            'description' => 'Entry fee Ref: ' . $entryID,
            'invoice' => $invoice->id,
        ]);

//        Then, any extras
        if (!is_null($entry->extras)) {
            $extras = json_decode($entry->extras);

            foreach ($extras as $extra) {
                $priceID = $extra->priceID;
                $qty = $extra->qty;
                info("PriceID: " . $priceID);

                $product = DB::table('products')
                    ->leftJoin('prices', 'products.stripe_product_id', '=', 'prices.stripe_product_id')
                    ->where('prices.stripe_price_id', $priceID)
                    ->select('products.stripe_product_description')
                    ->first();
                info(json_encode($product));

                $invoiceItem = $newStripe->invoiceItems->create([
                    'customer' => $customerId,
                    'pricing' => [
                        'price' => $priceID,
                    ],
                    'description' => $product->stripe_product_description,
                    'invoice' => $invoice->id,
                ]);

            }
        }

        $newStripe->invoices->finalizeInvoice($invoiceId);
        $newStripe->invoices->sendInvoice($invoiceId);
    }

    public function invoice_orig($entry, $email, $username)
    {
//        New stuff
//        dd($entry);


//        Ends

        $newStripe = new StripeClient(Config::get('stripe.stripe_secret_key'));

        $trialID = $entry->trial_id;
        $trial = Trial::findOrFail($trialID);
        $trialName = $trial->name;
        $trialClub = $trial->club;

        $customer = $newStripe->customers->create([
            'email' => $email,
            'name' => $username,
        ]);

        $customerId = $customer->id;

        $entryID = $entry->id;
        // Create an Invoice
        $invoice = $newStripe->invoices->create([
            'customer' => $customerId,
//            'custom_fields' => [
//                'TrialID' => $trialID,
//                'EntryID' => $entryID,
//            ],
            'description' => $trialClub . ' - ' . $trialName,
            'collection_method' => 'send_invoice',
            'days_until_due' => 3,
            'metadata' => [
                'entryID' => $entryID,
            ],
        ]);

        $invoiceId = $invoice->id;

        info("Invoice with ID: $invoice->id");

        //   Add line items
//         Firstly entry fee
        $invoiceItem = $newStripe->invoiceItems->create([
            'customer' => $customerId,
            'pricing' => [
                'price' => $entry->stripe_price_id,
            ],
            'description' => ' Ref: ' . $entryID,
            'invoice' => $invoice->id,
        ]);

//        Then, any extras

        if (!is_null($entry->extras)) {
            $extras = json_decode($entry->extras);

            foreach ($extras as $extra) {
                $priceID = $extra->priceID;
                $qty = $extra->qty;

                $newStripe->invoices->addLines(
                    $invoiceId,
                    [
                        'lines' => [
                            'pricing' => [
                                'price' => $priceID,
                                'quantity' => $qty,
                            ]
                        ]
                    ],

                );
            }
        }
//
//        $newStripe->invoices->finalizeInvoice($invoiceId);
//        $newStripe->invoices->sendInvoice($invoiceId);
    }
}
