<?php

namespace App\Listeners;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Events\WebhookReceived;
use App\Models\Price;


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

//                onCheckoutSessionCompleted($event);
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
