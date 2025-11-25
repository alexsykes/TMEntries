<?php

namespace App\Listeners;

use App\Events\TrialFull;
use App\Mail\EntryOffer;
use App\Mail\InvoiceOverdue;
use App\Mail\PaymentReceived;
use App\Mail\ProductCreated;
use App\Mail\RefundConfirmed;
use App\Mail\RefundRequested;
use App\Mail\SecretaryNotificationPaymentReceived;
use App\Models\Club;
use App\Models\Entry;
use App\Models\Price;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Trial;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Events\WebhookReceived;
use Stripe\StripeClient;


/* Paid status
    0 - New entry within limit, not paid
    1 - Confirmed entry
    2 - Withdrawn, having paid, waiting for refund
    3 - Refunded entries
    4 - Reserve - invoiced, awaiting payment
    5 - Reserve - not paid
    6 - Removed
    7 - Manual entry - unpaid
    8 - Manual entry - paid
    9 - Manual entry - FoC
    10 -
    11 - Reminder sent
*/

function onInvoiceSent($invoiceObject)
{
    $email = $invoiceObject['customer_email'];
    $name = $invoiceObject['customer_name'];
    $url = $invoiceObject['hosted_invoice_url'];
    $pdf = $invoiceObject['invoice_pdf'];
    $entryID = $invoiceObject['metadata']['entryID'];
    $pi = $invoiceObject['payment_intent'];

    $entry = Entry::where('id', $entryID)->first();
    $entry->stripe_payment_intent = $pi;
    $entry->updated_at = now();
    $entry->save();

    $trial = DB::table('trials')->where('id', $entry->trial_id)->first();

    $date = date_create($trial->date);
    $entryData = array();
    $entryData['trialName'] = $trial->name;
    $entryData['trialClub'] = $trial->club;
    $entryData['date'] = date_format($date, "F jS, Y");
    $entryData['rider'] = $entry->name;
    $entryData['class'] = $entry->class;
    $entryData['course'] = $entry->course;
    $entryData['entryID'] = $entryID;
    $entryData['url'] = $url;
    $entryData['pdf'] = $pdf;

    Mail::to($email, $name)->send(new EntryOffer($entryData));

}

function onInvoiceOverdue($invoiceObject)
{
    $email = "monster@trialmonster.uk";
    Mail::to($email)
        ->send(new InvoiceOverdue());

}

function onInvoicePaid($invoiceObject)
{
    $entryID = $invoiceObject['metadata']['entryID'];
    $email = $invoiceObject['customer_email'];

    $entry = Entry::where('id', $entryID)->first();
    $entry->status = 1;
    $entry->updated_at = now();
    $entry->save();

    //  Get entries for confirmation email
    $entries = DB::table('entries')
        ->join('trials', 'entries.trial_id', '=', 'trials.id')
        ->where('entries.id', $entryID)
        ->get(['entries.*', 'trials.name as trial', 'trials.date as date']);

//  Send confirmation email with bcc: to admin
    $bcc = 'monster@trialmonster.uk';
    Mail::to($email)
        ->bcc($bcc)
        ->send(new PaymentReceived($entries));
//    Mail::to($email, $name)->send(new EntryOffer($entryData));

}

function onPriceCreated($priceObject)
{
    $stripe_price_id = $priceObject['id'];
    $unit_amount = $priceObject['unit_amount'];
    $product = $priceObject['product'];

    $price = Price::create([
        'stripe_price_id' => $stripe_price_id,
        'stripe_price' => $unit_amount,
        'stripe_product_id' => $product,
    ]);
}

function onPriceUpdated($priceObject)
{
    $stripe_price_id = $priceObject['id'];
    $amount = $priceObject['unit_amount'];
    $stripe_product_id = $priceObject['product'];

    $price = DB::table('prices')->where('stripe_price_id', $stripe_price_id)
        ->update(['stripe_price' => $amount,
            'updated_at' => now(),]);
}

function onProductCreated($productObject)
{
    $metadata = $productObject['metadata'];

    $stripe_product_id = $productObject['id'];
    $stripe_product_description = $productObject['description'];
    $isLive = $productObject['livemode'];
    $isEntryFee = false;
    $hasQuantity = false;
    $product_name = $productObject['name'];

    $youth = false;
    if (isset($metadata['isYouth'])) {
        $isYouth = $metadata['isYouth'];
        if ($isYouth == 'true') {
            $youth = true;
        }
    }

    $product_category = '';
    if (isset($metadata['category'])) {
        $product_category = $metadata['category'];
    }

    if (isset($metadata['has_quantity'])) {
        $hasQuantity = $metadata['has_quantity'];
    }

    $club_id = $metadata['club_id'];

    $trialid = 0;
    if (isset($metadata['trialid'])) {
        $trialid = $metadata['trialid'];
    }


    $product = Product::create([
        'stripe_product_id' => $stripe_product_id,
        'stripe_product_description' => $stripe_product_description,
        'isLive' => $isLive,
        'isEntryFee' => $isEntryFee,
        'hasQuantity' => 1,
        'product_name' => $product_name,
        'product_category' => $product_category,
        'trial_id' => $trialid,
        'isYouth' => $youth,
        'club_id' => $club_id,
        'purchases' => 0,
        'version' => 1,
    ]);

    // info("Product created" . $product->product_name);
//    echo "ClubID: $product->club_id";
    $email = 'monster@trialmonster.uk';
    Mail::to($email)->send(new ProductCreated($product));
}

function onProductUpdated($productObject)
{
    $metadata = $productObject['metadata'];

    $stripe_product_id = $productObject['id'];
    $stripe_product_description = $productObject['description'];
    $isLive = $productObject['livemode'];
    $isEntryFee = false;
    $hasQuantity = false;
    $product_name = $productObject['name'];


    $youth = false;
    if (isset($metadata['isYouth'])) {
        $isYouth = $metadata['isYouth'];
        if ($isYouth == 'true') {
            $youth = true;
        }
    }

    $product_category = '';
    if (isset($metadata['category'])) {
        $product_category = $metadata['category'];
    }

    $trialid = 0;
    if (isset($metadata['trialid'])) {
        $trialid = $metadata['trialid'];
    }

    $product = DB::table('products')
        ->where('stripe_product_id', '=', $stripe_product_id)
        ->update([
            'stripe_product_id' => $stripe_product_id,
            'stripe_product_description' => $stripe_product_description,
            'isLive' => $isLive,
            'isEntryFee' => $isEntryFee,
            'hasQuantity' => $hasQuantity,
            'product_name' => $product_name,
            'product_category' => $product_category,
            'trial_id' => $trialid,
            'isYouth' => $youth,
            'purchases' => 0,
            'updated_at' => now(),
        ]);

    $product = DB::table('products')
        ->where('stripe_product_id', '=', $stripe_product_id)
        ->increment('version');

}

function onCheckoutSessionCompleted($sessionObject)
{
//    Get secret key
    $stripe = new StripeClient(Config::get('stripe.stripe_secret_key'));
// and data from session
    $metadata = $sessionObject['metadata'];
    $email = $sessionObject['customer_details']['email'];
    $stripe_payment_intent = $sessionObject['payment_intent'];
    $entryIDs = $metadata['entryIDs'];
    $trialIDstring = $metadata['trialID'];
    $trialIDs = array_unique(explode(',', $trialIDstring));

//    Create array of entryIDs
    $entryIDArray = explode(',', $entryIDs);


//  Process purchased items
//    Get all line items
    $lineItems = $stripe->checkout->sessions->allLineItems(
        $sessionObject['id'],
        []
    );

//  Record other items purchased
    $containsExtras = false;
    $product_names = array();
    $product_quantities = array();
    $product_descriptions = array();
    $purchaseData = array();

    foreach ($lineItems as $lineItem) {
        $stripe_product_id = $lineItem['price']['product'];
        $quantity = $lineItem['quantity'];
        $description = $lineItem['description'];
        $product = DB::table('products')
            ->where('stripe_product_id', '=', $stripe_product_id)
            ->first();

        if ($product->product_category != 'entry fee') {
            $containsExtras = true;
            $product_name = $product->product_name;
            $qty = $quantity;
            $description = $description;

            array_push($purchaseData, array("product" => $product_name, "quantity" => $qty, "description" => $description));
        }

        $stripe_price_id = $lineItem['price']['id'];

        $attrs = [
            'stripe_product_id' => $stripe_product_id,
            'quantity' => $quantity,
            'entryIDs' => $entryIDs,
            'email' => $email,
            'pi' => $stripe_payment_intent,
        ];

        $purchase = Purchase::create($attrs);

        DB::table('products')
            ->where('stripe_product_id', '=', $stripe_product_id)
            ->increment('purchases', $quantity);

        DB::table('prices')
            ->where('stripe_price_id', '=', $stripe_price_id)
            ->increment('purchases', $quantity);
    }

    $msg = "";
    if ($containsExtras) {
        info("Contains Extras");

        $msg = "<div>Your payment also included the following purchase(s):</div>";
        $items = "";
        for ($i = 0; $i < sizeof($purchaseData); $i++) {
            $item = $purchaseData[$i]['description'];
            $qty = $purchaseData[$i]['quantity'];
            $items .= "<div class='pl-4 font-semibold'>Item: $item Qty: $qty</div>";
        }

        $msg .= $items;
        sendNotification($items, $entryIDs);
    } else {
        info("Doesn't contain Extras");
    }

// Update entry status
    $entries = DB::table('entries')
        ->whereIn('id', $entryIDArray)
        ->update(['status' => 1,
            'accept' => true,
            'email' => $email,
            'updated_at' => now(),
            'stripe_payment_intent' => $stripe_payment_intent,]);

//  Get entries for confirmation email
    $entries = DB::table('entries')
        ->join('trials', 'entries.trial_id', '=', 'trials.id')
        ->whereIn('entries.id', $entryIDArray)
        ->get(['entries.*', 'trials.name as trial', 'trials.date as date']);

//  Send confirmation email with bcc: to admin
    $bcc = 'monster@trialmonster.uk';
//    info($msg);
    Mail::to($email)
        ->bcc($bcc)
        ->send(new PaymentReceived($entries, $msg));

//    Check for entry limit
    foreach ($trialIDs as $trialID) {
        $trial = Trial::findOrFail($trialID);

//        Check whether trial has entry limit
        if ($trial->hasEntryLimit) {
            // info("Trial has entryLimit");
//        Check for full entry list
            $entryLimit = $trial->entryLimit;
            $numEntries = Entry::where('trial_id', $trialID)
                ->whereIn('status', [1, 4, 7, 8, 9])
                ->count();
            Info("NumEntries: $numEntries");
//        Check for number of entries left
            $spaces = $entryLimit - $numEntries;
            if ($spaces <= 0) {
                TrialFull::dispatch($trialID, $entryLimit, $numEntries);
            }
        }
    }
}

function sendNotification($items, $entryIDs)
{
    $bcc = "monster@trialmonster.uk";
    $email = "ammnewhouse@gmail.com";
//    $email = "alex@alexsykes.net";
    $entryIDArray = explode(',', $entryIDs);


//    $clubIDArray = explode(',', $items['clubIDs']);

    $riderNames = DB::table('entries')
        ->whereIn('id', $entryIDArray)
        ->orderBy('name')
        ->pluck('name')->toArray();

    $riders = implode(', ', $riderNames);

    $club = Club::findOrFail(5);
    $confirmed = explode(',', $club->confirmed_list);
    $merged = array_unique(array_merge($riderNames, $confirmed));

    asort($merged);
    $sortedS = implode(',', $merged);

    $club->confirmed_list = $sortedS;
    $club->save();

    Mail::to($email)
        ->bcc($bcc)
        ->send(new SecretaryNotificationPaymentReceived($riders, $items));

    info("SecretaryNotificationPaymentReceived sent");
}

function onRefundCreated(mixed $object)
{
//    Get the entryID from the metadata
    if (isset($object['metadata']['entry_id'])) {
        $entryID = $object['metadata']['entry_id'];
        $reason = $object['metadata']['reason'];
        $status = $object['status'];

        $entryIDs = explode(',', $entryID);

//        Update status -> 2 (waiting for refund)
        $entries = DB::table('entries')
            ->whereIn('id', $entryIDs)
            ->update(['status' => 2, 'updated_at' => now()]);

        $bcc = 'monster@trialmonster.uk';

        foreach ($entryIDs as $entryID) {
            $entry = DB::table('entries')->find($entryID);
            $email = $entry->email;
            echo $email . PHP_EOL;
            Mail::to($email)
                ->bcc($bcc)
                ->queue(new RefundRequested($entry, $reason));
        }
    }
}

function onRefundUpdated(mixed $object)
{
    //    Get the entryID from the metadata
    if (isset($object['metadata']['entry_id'])) {
        $entryID = $object['metadata']['entry_id'];
        $reason = $object['metadata']['reason'];
        $status = $object['status'];

        $entryIDs = explode(',', $entryID);

//        Update status -> 2 (waiting for refund)
        $entries = DB::table('entries')
            ->whereIn('id', $entryIDs)
            ->update(['status' => 3, 'updated_at' => now()]);

        $bcc = 'monster@trialmonster.uk';
        foreach ($entryIDs as $entryID) {

            $entry = DB::table('entries')->find($entryID);
            $priceID = $entry->stripe_price_id;
            $price = DB::table('prices')
                ->where('stripe_price_id', '=', $priceID)
                ->increment('refunds');

            $productID = $entry->stripe_product_id;
            $price = DB::table('products')
                ->where('stripe_product_id', '=', $productID)
                ->increment('refunds');

            $email = $entry->email;
            // info("$email");
            Mail::to($email)
                ->bcc($bcc)
                ->queue(new RefundConfirmed($entry, $reason));
        }
    }
}

function onPaymentIntentSucceeded()
{
    // info("Payment intent succeeded");
}

function onPaymentIntentCreated($object)
{
    // info("Payment intent created");
}

class StripeEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }


    /**
     * Handle the event.
     */
    public function handle(WebhookReceived $event): void
    {
        $eventType = $event->payload['type'];
//        // info("event type: $eventType");
        switch ($eventType) {
            case 'refund.created':
                $object = $event->payload['data']['object'];
                onRefundCreated($object);
                break;

            case 'refund.updated':
                $object = $event->payload['data']['object'];
                $status = $object['status'];
                if ($status == 'succeeded') {
                    onRefundUpdated($object);
                }
                break;

            case 'checkout.session.completed':
                $object = $event->payload['data']['object'];
                onCheckoutSessionCompleted($object);
                break;

            case 'invoice.created':

                break;

            case 'invoice.sent':
                $object = $event->payload['data']['object'];
                onInvoiceSent($object);

                break;

            case 'invoice.paid':
                $object = $event->payload['data']['object'];
                onInvoicePaid($object);

                break;

            case 'invoice.overdue':
                $object = $event->payload['data']['object'];
                onInvoiceOverdue($object);
                break;

            case 'product.updated':
                $object = $event->payload['data']['object'];
                onProductUpdated($object);
                break;
            case 'product.created':
                $object = $event->payload['data']['object'];
                onProductCreated($object);
                break;
            case 'price.updated':
                $object = $event->payload['data']['object'];
                onPriceUpdated($object);
                break;
            case 'price.created':
                $object = $event->payload['data']['object'];
                onPriceCreated($object);
                break;
            case 'invoice.created':
                onInvoiceCreated($event);
                break;
            case 'payment_intent.succeeded':
//                onInvoiceCreated($event);
                onPaymentIntentSucceeded();
                break;
            case 'payment_intent.created':
                $object = $event->payload['data']['object'];
                onPaymentIntentCreated($object);
                break;
            default:
//                // info('Received unknown event type ' . $eventType);
        }
    }
}
