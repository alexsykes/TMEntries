<?php

namespace App\Listeners;

use App\Events\FiveSpacesReached;
use App\Events\TrialFull;
use App\Mail\PaymentReceived;
use App\Mail\ProductCreated;
use App\Mail\RefundConfirmed;
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
        'hasQuantity' => $hasQuantity,
        'product_name' => $product_name,
        'product_category' => $product_category,
        'trial_id' => $trialid,
        'isYouth' => $youth,
        'club_id' => $club_id,
        'purchases' => 0,
        'version' => 1,
    ]);

    info("Product created" . $product->product_name);
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
    $trialIDs = explode(',', $trialIDstring);

//    Create array of entryIDs
    $entryIDArray = explode(',', $entryIDs);


//  Process purchased items
//    Get all line items

    $lineItems = $stripe->checkout->sessions->allLineItems(
        $sessionObject['id'],
        []
    );

//  Record other items purchased
    foreach ($lineItems as $lineItem) {
        $stripe_product_id = $lineItem['price']['product'];
        $quantity = $lineItem['quantity'];
        $product = DB::table('products')
            ->where('stripe_product_id', '=', $stripe_product_id)
            ->first();
        $stripe_price_id = $lineItem['price']['id'];

        info("PI: $stripe_payment_intent Qty - $quantity Product - $product->product_name");
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
    $bcc = 'admin@trialmonster.uk';
    Mail::to($email)
        ->bcc($bcc)
        ->send(new PaymentReceived($entries));
    info("Checkout session completed. $entryIDs");


//    Check for entry limit reached
//    Check for multiple trials

//    Iterate through trials
    foreach ($trialIDs as $trialID) {
        $trial = Trial::findOrFail($trialID);

//        Check whether trial has entry limit
        if ($trial->hasEntryLimit) {
            info("Trial has entryLimit");
//        Check for full entry list
            $entryLimit = $trial->entryLimit;
            $numEntries = Entry::where('trial_id', $trialID)
                ->whereIn('status', [1, 4, 7, 8, 9])
                ->count();
            Info("NumEntries: $numEntries");
//        Check for number of entries left
//        If 5, then email registered but not paid
            $spaces = $entryLimit - $numEntries;

//            echo "Spaces: $spaces\n Limit: $entryLimit\n Entries: $numEntries";

            if ($spaces == 5) {
//             send LastChance email
                info("FiveSpacesReached ($spaces spaces) dispatched");
                FiveSpacesReached::dispatch($trialID, $entryLimit, $numEntries);
            }

            if ($spaces <= 0) {
                info("TrialFull ($spaces spaces) dispatched");
                TrialFull::dispatch($trialID, $entryLimit, $numEntries);
            }
        }
    }


}

function onRefundCreated(mixed $object)
{
//    Get the entryID from the metadata
    if (isset($object['metadata']['id'])) {
        $entryID = $object['metadata']['id'];

        $entry = DB::table('entries')
            ->where('id', $entryID)
            ->update(['status' => 2]);

        $bcc = 'admin@trialmonster.uk';

        $entry = DB::table('entries')->find($entryID);
        $email = $entry->email;
        /*

//        TODO Remove comments
        Mail::to($email)
            ->bcc($bcc)
            ->send(new RefundRequested($entry));
        */
        info("Refund Requested: $entryID");

//        dd("Refund Requested: $entryID");        */
////       Check for spaces
        $trialID = $entry->trial_id;

        $trial = Trial::findOrFail($trialID);

//        Check for full entry list
        $entryLimit = $trial->entryLimit;
        $numEntries = Entry::where('trial_id', $trialID)
            ->whereIn('status', [1, 4, 7, 8, 9])
            ->count();
        Info("NumEntries: $numEntries");
//        Check for number of entries left
//        If 5, then email registered but not paid
        $spaces = $entryLimit - $numEntries;

        echo "Spaces: $spaces\n";
//        $spaces - which should equal 1 in most cases
        $reserveIDs = Entry::where('status', 5)
            ->where('trial_id', $trialID)
            ->orderBy('updated_at')
            ->limit(1)
            ->get();

        foreach ($reserveIDs as $reserveID) {

            $stripe = new StripeClient(Config::get('stripe.stripe_secret_key'));

            $id = $reserveID->id;
            $email = $reserveID->email;
            $name = $reserveID->name;
            $stripe_product_id = $reserveID->stripe_product_id;

            $customer = $stripe->customers->create
            ([
                'name' => $name,
                'email' => $email,
            ]);

            $invoice = $stripe->invoices->create([
                    'customer' => $customer->id,
                    'collection_method' => 'send_invoice',
                    'days_until_due' => 3,
                ]
            );

// TODO Remove comment on next line
////            $reserveID->update(['status' => 4]);

//            echo("Reserve $id: $name - $email \n");
//            echo("Product $stripe_product_id \n");
//            echo("Customer $customer->id     \n");
//            echo("Invoice $invoice->id     \n");
//            Raise invoice

        }
    }
}

function onRefundUpdated(mixed $object)
{
    if (isset($object['metadata']['id'])) {
        $entryID = $object['metadata']['id'];

        $entry = DB::table('entries')
            ->where('id', $entryID)
            ->update(['status' => 3]);

        $bcc = 'admin@trialmonster.uk';

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
        Mail::to($email)
            ->bcc($bcc)
            ->send(new RefundConfirmed($entry));
        info("Refund Confirmed: $entryID");
    }
}

function onPaymentIntentSucceeded()
{
    info("Payment intent succeeded");
}

function onPaymentIntentCreated($object)
{
    info("Payment intent created");
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
//        info("event type: $eventType");
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
                info('Received unknown event type ' . $eventType);
        }
    }


}
