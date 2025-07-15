<?php

namespace App\Listeners;

use App\Mail\PaymentReceived;
use App\Mail\ProductCreated;
use App\Mail\RefundConfirmed;
use App\Mail\RefundRequested;
use App\Models\Entry;
use App\Models\Price;
use App\Models\Product;
use App\Models\Purchase;
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
    if(isset($metadata['isYouth'])) {
        $isYouth = $metadata['isYouth'];
        if ($isYouth == 'true') {
            $youth = true;
        }
    }

    $product_category = '';
    if(isset($metadata['category'])) {
        $product_category = $metadata['category'];
    }

    $trialid = 0;
    if(isset($metadata['trialid'])) {
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
        'purchases' => 0,
        'version' => 1,
    ]);
    info("Product created" . $product->product_name);

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
    if(isset($metadata['isYouth'])) {
        $isYouth = $metadata['isYouth'];
        if ($isYouth == 'true') {
            $youth = true;
        }
    }

    $product_category = '';
    if(isset($metadata['category'])) {
        $product_category = $metadata['category'];
    }

    $trialid = 0;
    if(isset($metadata['trialid'])) {
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
    $stripe = new StripeClient(Config::get('stripe.stripe_secret_key'));

    $metadata = $sessionObject['metadata'];
    $email = $sessionObject['customer_details']['email'];
    $stripe_payment_intent = $sessionObject['payment_intent'];

    $bcc = 'admin@trialmonster.uk';
    $entryIDs = $metadata['entryIDs'];
    $entryIDArray = explode(',', $entryIDs);


//    New stuff starts
    $lineItems = $stripe->checkout->sessions->allLineItems(
        $sessionObject['id'],
        []
    );
    foreach ($lineItems as $lineItem) {
        $stripe_product_id = $lineItem['price']['product'];
        $quantity = $lineItem['quantity'];
        $product = DB::table('products')
            ->where('stripe_product_id', '=', $stripe_product_id)
            ->first();
        if($product->product_category == 'other') {
            info( "PI: $stripe_payment_intent Qty - $quantity Product - $product->product_name" );

            $attrs = [
                'stripe_product_id' => $stripe_product_id,
                'quantity' => $quantity,
                'entryIDs' => $entryIDs,
                'email' => $email,
                'pi' => $stripe_payment_intent,
            ];

//            DB::table('purchases')->insert($purchase);
            $purchase = Purchase::create($attrs);


        };
    }


//    New stuff ends
    foreach ($entryIDArray as $entryID) {

        $entry = DB::table('entries')
            ->where('id', '=', $entryID)
            ->get();

        $stripe_price_id = $entry[0]->stripe_price_id;
        $stripe_product_id = $entry[0]->stripe_product_id;

        DB::table('products')
            ->where('stripe_product_id', '=', $stripe_product_id)
            ->increment('purchases');

        DB::table('prices')
            ->where('stripe_price_id', '=', $stripe_price_id)
            ->increment('purchases');
    }

    $entries = DB::table('entries')
        ->whereIn('id', $entryIDArray)
        ->update(['status' => 1,
            'accept' => true,
            'email' => $email,
            'updated_at' => now(),
            'stripe_payment_intent' => $stripe_payment_intent,]);

    $entries = Entry::all()
        ->whereIn('id', $entryIDArray);

    $entries = DB::table('entries')
        ->join('trials', 'entries.trial_id', '=', 'trials.id')
        ->whereIn('entries.id', $entryIDArray)
        ->get(['entries.*', 'trials.name as trial', 'trials.date as date']);

//    $entriesWithTrialDetails = Entry::all()
//        ->whereIn('id', $entryIDArray);
//        ->join('trials','entries.trial_id', '=', 'trials.id');

    Mail::to($email)
        ->bcc($bcc)
        ->send(new PaymentReceived($entries));
    info("Checkout session completed. $entryIDs");
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
        Mail::to($email)
            ->bcc($bcc)
            ->send(new RefundRequested($entry));
        info("Refund Requested: $entryID");
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

//    $stripe = new StripeClient(Config::get('stripe.stripe_secret_key'));
//    $pi = $object['id'];
//    $paymentIntent = $stripe->paymentIntents->retrieve(
//        $pi,
//        []
//    );
//    var_dump($paymentIntent);
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
    info("event type: $eventType");
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
                echo 'Received unknown event type ' . $eventType;
        }
    }


}
