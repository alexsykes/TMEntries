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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

        info("Trial limit: $entryLimit \n TrialID: $trialID \n Trial hasLmit: $hasLimit");
        if($hasLimit && $entryLimit > $numEntries) {
            $vacancies = $entryLimit - $numEntries;

            $entriesToOffer = Entry::where('trial_id', $trialID)
                ->where('status', 5)
                ->limit($vacancies)
                ->get();

            foreach ($entriesToOffer as $entry) {
                info("Entry Withdrawn: place to offer");
                $userID = $entry->created_by;
                $email = User::findOrFail($userID)->email;
                $name = $entry->name;
                $entryID = $entry->id;

                $entry->status = 4;
                $entry->update();

                $productID = $entry->stripe_product_id;
                $priceID = Price::where('stripe_product_id', $productID)
                    ->select('stripe_price_id')
                    ->orderBy('id', 'desc')
                    ->first();

                info("Product ID: $productID \n PriceID: $priceID");
                $bcc = 'admin@trialmonster.uk';
//
//
//                Mail::to($email)
//                    ->bcc($bcc)
//                    ->send(new EntryOffer());

                info("EntryID: $entryID Name: $name - Email: $email");
            }
        }

    }

//    function raiseInvoice()
//    {
//
//        require_once '../vendor/autoload.php';
//        require_once '../secrets.php';
//
//        \Stripe\Stripe::setApiKey($stripeSecretKey);
//// You probably have a database to keep track of preexisting customers.
//// But to keep things simple, we'll use an Object to store Stripe object IDs in this example.
//        $CUSTOMERS = [
//            [
//                'stripeId' => 'cus_123456789',
//                'email' => 'jenny.rosen@example.com'
//            ],
//        ];
//// Prices on Stripe model the pricing scheme of your business.
//// Create Prices in the Dashboard or with the API before accepting payments
//// and store the IDs in your database.
//        $PRICES = [
//            'basic' => 'price_123456789',
//            'professional' => 'price_987654321',
//        ];
//
//        function sendInvoice($email)
//        {
//            // Look up a customer in your database
//            global $CUSTOMERS;
//            global $PRICES;
//
//            $customerId = null;
//
//            $customers = array_filter($CUSTOMERS, function ($customer) use ($email) {
//                return $customer['email'] === $email;
//            });
//
//            if (!$customers) {
//                // Create a new Customer
//                $customer = \Stripe\Customer::create([
//                    'email' => $email,
//                    'description' => 'Customer to invoice',
//                ]);
//                // Store the Customer ID in your database to use for future purchases
//                $CUSTOMERS[] = [
//                    'stripeId' => $customer->id,
//                    'email' => $email
//                ];
//
//                $customerId = $customer->id;
//            } else {
//                // Read the Customer ID from your database
//                $customerId = $customers[0]['stripeId'];
//            }
//
//            // Create an Invoice
//            $invoice = \Stripe\Invoice::create([
//                'customer' => $customerId,
//                'collection_method' => 'send_invoice',
//                'days_until_due' => 30,
//            ]);
//
//            // Create an Invoice Item with the Price, and Customer you want to charge
//            $invoiceItem = \Stripe\InvoiceItem::create([
//                'customer' => $customerId,
//                'price' => $PRICES['basic'],
//                'invoice' => $invoice->id
//            ]);
//
//
//            // Send the Invoice
//            $invoice->sendInvoice();
//        }
//    }
}
