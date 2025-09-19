<?php

namespace App\Listeners;

use App\Mail\PaymentReceived;
use App\Mail\EntryOffer;
use App\Models\Entry;
use App\Models\Price;
use App\Models\Trial;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\EntryWithdrawn;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
            ->whereIn('status', [1, 4, 7, 8, 9])
                ->count();

//  Check for vacancy created
        info("Trial limit: $entryLimit \n TrialID: $trialID \n Trial hasLmit: $hasLimit \n NumEntries: $numEntries \n");
        if($hasLimit && $entryLimit > $numEntries) {
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

                $entry->update();

//              Get product reference for invoice
                $productID = $entry->stripe_product_id;
//                $priceID = Price::where('stripe_product_id', $productID)
//                    ->select('stripe_price_id')
//                    ->orderBy('id', 'desc')
//                    ->first();

//              Prepare invoice
                $this->invoice($entry, $email, $username);
            }
        }

    }

    public function invoice($entry, $email, $username){
//    info('Invoice');
        $another = new StripeClient(Config::get('stripe.stripe_secret_key'));

        $trialID = $entry->trial_id;
        $trial = Trial::findOrFail($trialID);
        $trialName = $trial->name;
        $trialClub = $trial->club;

        $customer = $another->customers->create([
            'email' => $email,
            'name' => $username,
        ]);

        $customerId = $customer->id;

        $entryID = $entry->id;
        // Create an Invoice
        $invoice = $another->invoices->create([
            'customer' => $customerId,
            'description' => $trialClub.' - '.$trialName,
            'collection_method' => 'send_invoice',
            'days_until_due' => 3,
            'metadata' => [
                'entryID' => $entryID,
            ]
        ]);

//   Add line items
        $invoiceItem = $another->invoiceItems->create([
            'customer' => $customerId,
//            "amount" => 2000,
            'pricing' => [
                'price' => $entry->stripe_price_id,
//                'product' => $entry->stripe_product_id,
            ],
            'description' => ' Ref: '.$entryID,
            'invoice' => $invoice->id,
        ]);


        $invoice->sendInvoice();
    }
}
