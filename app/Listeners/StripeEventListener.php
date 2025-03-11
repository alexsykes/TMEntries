<?php

namespace App\Listeners;

use App\Mail\PaymentReceived;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Events\WebhookReceived;
use App\Models\Price;
use App\Models\Entry;
use Illuminate\Support\Facades\Mail;


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


function onProductCreated($productObject)
{
    $metadata = $productObject['metadata'];

    $stripe_product_id = $productObject['id'];
    $stripe_price_id = $productObject['default_price'];
    $stripe_product_description = $productObject['description'];
    $isLive = $productObject['livemode'];
    $isEntryFee = false;
    $hasQuantity = false;
    $product_name = $productObject['name'];
    $product_category = $metadata['category'];
    $trial_id = $metadata['trialid'];
    $isYouth = $metadata['isYouth'];
    if ($isYouth == 'true') {
        $youth = true;
    } else {
        $youth = false;
    };
////
////
    if ($product_category == 'entry fee') {
        $isEntryFee = true;
    }

    $product = Product::create([
        'stripe_product_id' => $stripe_product_id,
        'stripe_price_id' => $stripe_price_id,
        'stripe_product_description' => $stripe_product_description,
        'isLive' => $isLive,
        'isEntryFee' => $isEntryFee,
        'hasQuantity' => $hasQuantity,
        'product_name' => $product_name,
        'product_category' => $product_category,
        'trial_id' => $trial_id,
        'isYouth' => $youth,
        'purchases' => 0,
        'version' => 1,
    ]);


}

function onProductUpdated($productObject)
{
    $metadata = $productObject['metadata'];

    $stripe_product_id = $productObject['id'];
    $stripe_price_id = $productObject['default_price'];
    $stripe_product_description = $productObject['description'];
    $isLive = $productObject['livemode'];
    $isEntryFee = false;
    $hasQuantity = false;
    $product_name = $productObject['name'];
    $product_category = $metadata['category'];
    $trial_id = $metadata['trialid'];
    $isYouth = $metadata['isYouth'];
    if ($isYouth == 'true') {
        $youth = true;
    } else {
        $youth = false;
    };

    if ($product_category == 'entry fee') {
        $isEntryFee = true;
    }

    $product = DB::table('products')
        ->where('stripe_product_id', '=', $stripe_product_id)
        ->update([
            'stripe_product_id' => $stripe_product_id,
            'stripe_price_id' => $stripe_price_id,
            'stripe_product_description' => $stripe_product_description,
            'isLive' => $isLive,
            'isEntryFee' => $isEntryFee,
            'hasQuantity' => $hasQuantity,
            'product_name' => $product_name,
            'product_category' => $product_category,
            'trial_id' => $trial_id,
            'isYouth' => $youth,
            'purchases' => 0,
            'updated_at' => now(),
        ]);

    $product = DB::table('products')
        ->where('stripe_product_id', '=', $stripe_product_id)
        ->increment('version');

}

function onCheckoutSessionCompleted($sessionObject) {
    $metadata = $sessionObject['metadata'];
    $email = $sessionObject['customer_details']['email'];
    $bcc = 'admin@trialmonster.uk';
    $entryIDs = $metadata['entryIDs'];
    $entryIDArray = explode(',', $entryIDs);
    
    $entries = DB::table('entries')
        ->whereIn('id', $entryIDArray)
        ->update(['status' => 1]);

    $entries = Entry::all()
    ->whereIn('id', $entryIDArray)
    ;

//    $entryData = "<table>";
//    foreach (Entry::all() as $entry) {
//        $id = $entry->id;
//        $name = $entry->name;
//        $token = $entry->token;
//        $entryLine = "<tr><td>Ref: $id</td><td>$name</td><td>$token</td></tr>";
//        $entryData .= $entryLine;
//
//    }
//
//    $entryData .= "</table>";
//    info($entryData);

    Mail::to($email)
        ->bcc($bcc)
        ->send(new PaymentReceived($entries));
    info("Mail sent to $email, bcc: $bcc");

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

        switch ($eventType) {
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
                onPriceUpdate($event);
                break;
            case 'price.created':
                $object = $event->payload['data']['object'];
                onPriceCreated($object);
                break;
            case 'invoice.created':
                onInvoiceCreated($event);
                break;
            default:
                echo 'Received unknown event type ' . $eventType;
        }
    }


}
