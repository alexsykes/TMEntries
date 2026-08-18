<?php

namespace App\Listeners;

use App\Events\RefundCreated;
use App\Events\RefundUpdated;
use App\Events\TrialFull;
use App\Mail\CancellationRefundConfirmed;
use App\Mail\EntryOffer;
use App\Mail\InvoiceOverdue;
use App\Mail\InvoicePaid;
use App\Mail\MembershipPaid;
use App\Mail\MembershipReceived;
use App\Mail\PaymentReceived;
use App\Mail\ProductCreated;
use App\Mail\RefundConfirmed;
use App\Models\Club;
use App\Models\Entry;
use App\Models\EntryPurchase;
use App\Models\Price;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Trial;
use Illuminate\Mail\Mailables\Address;
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
//    $pi = $invoiceObject['payment_intent'];

    $entry = Entry::where('id', $entryID)->first();
    $entry->stripe_invoice_id = $invoiceObject['id'];
    $entry->status = 4;
    $entry->updated_at = now();
    $entry->save();

    $trial = DB::table('trials')->where('id', $entry->trial_id)->first();

    $date = date_create($trial->date);
    $entryData = [];
    $entryData['trialName'] = $trial->name;
    $entryData['trialClub'] = $trial->club;
    $entryData['date'] = date_format($date, 'F jS, Y');
    $entryData['rider'] = $entry->name;
    $entryData['class'] = $entry->class;
    $entryData['course'] = $entry->course;
    $entryData['entryID'] = $entryID;
    $entryData['url'] = $url;
    $entryData['pdf'] = $pdf;

    info("Sending invoice to: $email");
    Mail::to($email, $name)->send(new EntryOffer($entryData));
}

function onInvoiceOverdue($invoiceObject)
{
    $email = 'monster@trialmonster.uk';
    Mail::to($email)
        ->send(new InvoiceOverdue);

}

function onInvoicePaid($invoiceObject)
{
    $entryID = $invoiceObject['metadata']['entryID'];
    $email = $invoiceObject['customer_email'];
//    $pi = $invoiceObject['payment']['payment_intent'];

    $entry = Entry::where('id', $entryID)->first();
    $entry->status = 10;
    $entry->email = $email;
    $entry->updated_at = now();
    $entry->save();


    //  Send confirmation email with bcc: to admin
    $bcc = 'monster@trialmonster.uk';

    Mail::to($email)
        ->bcc($bcc)
        ->send(new InvoicePaid($entryID));

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

    if (isset($metadata['options'])) {
        $options = $metadata['options'];
    } else {
        $options = '';
    }

//    $required = $metadata['required'] == 'true' ? 1 : 0;
    if (isset($metadata['required'])) {
//        $required = $metadata['required'];
        $required = $metadata['required'] == 'true' ? true : false;
    } else {
        $required = false;
    }

    $club_id = 0;
    if (isset($metadata['club_id'])) {
        $club_id = $metadata['club_id'];
    }

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
        'required' => $required,
        'options' => $options,
        'purchases' => 0,
        'version' => 1,
    ]);

    info('Product created :: ' . $product->product_name);
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

    info('Product updated :: ' . $product_name);
}

function onCheckoutSessionCompleted($sessionObject)
{
    //    Get secret key
//
//    echo "Checkout session completed";
//    return;
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

    info('Stripe payment intent :: ' . $stripe_payment_intent);
    //  Process purchased items
    //    Get all line items
    $lineItems = $stripe->checkout->sessions->allLineItems(
        $sessionObject['id'],
        []
    );

    //  Record other items purchased
    $containsExtras = false;
    $purchaseData = [];
//
//    echo json_encode($lineItems);
//    exit;

//    Line items -> all items on PI
    //  Get line items from session and update purchase, price and product tables
    foreach ($lineItems as $lineItem) {
        $stripe_product_id = $lineItem['price']['product'];
        $quantity = $lineItem['quantity'];
        $description = $lineItem['description'];

        $product = DB::table('products')
            ->where('stripe_product_id', '=', $stripe_product_id)
            ->first();

//      Check for items other than entry fees, then add to $purchaseData array
        if ($product->product_category != 'entry fee') {
            $containsExtras = true;
            $product_name = $product->product_name;
            $qty = $quantity;

            array_push($purchaseData, ['product' => $product_name, 'quantity' => $qty, 'description' => $description]);
        }

        $stripe_price_id = $lineItem['price']['id'];

        $attrs = [
            'stripe_product_id' => $stripe_product_id,
            'quantity' => $quantity,
            'entryIDs' => $entryIDs,
            'email' => $email,
            'pi' => $stripe_payment_intent,
        ];

//        Record in purchase table
//        IMPORTANT - uncomment
        $purchase = Purchase::create($attrs);

//         And increment Products/Prices tables
        DB::table('products')
            ->where('stripe_product_id', '=', $stripe_product_id)
            ->increment('purchases', $quantity);

        DB::table('prices')
            ->where('stripe_price_id', '=', $stripe_price_id)
            ->increment('purchases', $quantity);
    }

//    Compose additional message if additional items purchsed
    $itemList = '';
    if ($containsExtras) {
//        info('Contains Extras');

//        info(json_encode($purchaseData));

        $itemList = '<div>Your payment also included the following purchase(s):</div>';
        $items = '';
        for ($i = 0; $i < count($purchaseData); $i++) {
            $item = $purchaseData[$i]['description'];
            $qty = $purchaseData[$i]['quantity'];
            $items .= "<div class='pl-4 font-semibold'>Item: $item Qty: $qty</div>";
        }

        $itemList .= $items;
//        sendNotification($items, $entryIDs);

    } else {
        info("Doesn't contain Extras");
    }


//    Add purchases to purchase table
//    Get extras from Entry table
    $entryData = DB::table('entries')
        ->whereIn('id', $entryIDArray)
        ->select('id', 'entries.extras')
        ->get();


    foreach ($entryData as $entry) {
        $id = $entry->id;
        $items = json_decode($entry->extras);
        info("ID: $id" . $entry->extras);
        foreach ($items as $item) {
            $quantity = $item->qty;
            $stripe_price_id = $item->priceID;

            $attrs = [
                'stripe_price_id' => $stripe_price_id,
                'quantity' => $quantity,
                'entry_id' => $id,
            ];
// IMPORTANT - uncomment this line
            $purchase = EntryPurchase::create($attrs);
        }
    }

//    exit;
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
        ->get(['entries.*', 'trials.name as trial', 'trials.date as date', 'trials.club_id as club_id']);

    //  Send confirmation email with bcc: to admin
    $bcc = 'monster@trialmonster.uk';
    //    info($itemList);

    $clubIDs = array();
    foreach ($entries as $entry) {
        $clubID = $entry->club_id;
        array_push($clubIDs, $clubID);
    }

//    IMPORTANT - uncomment these lines
    Mail::to($email)
//        ->bcc($bcc)
        ->send(new PaymentReceived($entries, $itemList, $clubIDs));

// At this stage, PaymentReceived notification has been sent to entrant
// Purchase has been added to table
//    EntryPurchases have been added to table
    sendNewNotifications($entryIDs);

    //    Check for entry limit
    foreach ($trialIDs as $trialID) {
        $trial = Trial::findOrFail($trialID);

        //        Check whether trial has entry limit
        if ($trial->hasEntryLimit) {
            // info("Trial has entryLimit");
            //        Check for full entry list
            $entryLimit = $trial->entryLimit;
            $numEntries = Entry::where('trial_id', $trialID)
                ->whereIn('status', [1, 4, 7, 8, 9, 10])
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

//function sendNotification($items, $entryIDs)
//{
//    $bcc = 'monster@trialmonster.uk';
//    $email = 'alex@alexsykes.net';
//    $entryIDArray = explode(',', $entryIDs);
//
//    $riderNames = DB::table('entries')
//        ->whereIn('id', $entryIDArray)
//        ->orderBy('name')
//        ->pluck('name')->toArray();
//
//    $riders = implode(', ', $riderNames);
//
//    $club = Club::findOrFail(5);
//    $confirmed = explode(',', $club->confirmed_list);
//    $merged = array_unique(array_merge($riderNames, $confirmed));
//
//    asort($merged);
//    $sortedS = implode(',', $merged);
//
//    $club->confirmed_list = $sortedS;
//    $club->save();
//    Mail::to($email)
//        ->bcc($bcc)
//        ->send(new SecretaryNotificationPaymentReceived($riders, $items));
//
//    info('SecretaryNotificationPaymentReceived sent');
//
//}

function sendNewNotifications($entryIDs)
{
    $ids = explode(',', $entryIDs);
    $purchasesForMail = array(); // data to be sent to Mailer
    $membershipNames = array();
    $membershipData = array();
    $clubIDs = array();


//    Get purchases for each entry
    $index = 0;
    foreach ($ids as $entryID) {
//        Get the name
        $entryDetail = DB::table('entries')
            ->where('id', $entryID)
            ->select('name', 'extras', 'email', 'trial_id')
            ->orderBy('name')
            ->first();

//      Get the purchase data
        $purchases = DB::table('entry_purchases')
            ->leftJoin('prices', 'prices.stripe_price_id', '=', 'entry_purchases.stripe_price_id')
            ->leftJoin('products', 'products.stripe_product_id', '=', 'prices.stripe_product_id')
            ->where('entry_id', $entryID)
            ->select('entry_purchases.quantity', 'products.stripe_product_description as product_description', 'products.product_category as category', 'products.club_id as club_id')
            ->get();


        $numPurchases = sizeof($purchases);
//        echo $numPurchases;
        $line = "";
        if ($numPurchases > 0) {
            $entryArray = array();
            $purchaseIItems = array();
            $entryArray['name'] = $entryDetail->name;
            foreach ($purchases as $purchase) {
                $item = "$purchase->product_description ($purchase->quantity)";
                array_push($purchaseIItems, $item);
                if ($purchase->category == 'membership') {
                    array_push($membershipNames, $entryDetail->name);
                    array_push($clubIDs, $purchase->club_id);
                    array_push($membershipData, array('name' => $entryDetail->name, 'email' => $entryDetail->email, 'trial_id' => $entryDetail->trial_id));
                }
            }
            $entryArray['items'] = implode(", ", $purchaseIItems);

//            echo json_encode($membershipData);
            $line .= "<b>" . $entryArray['name'] . "</b> - " . $entryArray['items'];
        }
        array_push($purchasesForMail, $line);
    }
//
//    echo json_encode($purchasesForMail);
//    echo json_encode($membershipData);
//    echo json_encode($membershipNames);

//    $email = "monster@trialmonster.uk";
//    Mail::to($email)
//        ->send(mailable: new NewSecretaryNotificationPaymentReceived($purchasesForMail));

//    echo json_encode($membershipData);
    foreach ($membershipData as $membership) {
//        Get the club id from the entry
        $trialID = $membership['trial_id'];
        $trial = DB::table('trials')
            ->where('id', $trialID)
            ->select('club_id')
            ->first();

//        Get the club
        $clubID = $trial->club_id;
        $club = DB::table('clubs')->where('id', $clubID)->first();
        $clubName = $club->name;

        $bcc = 'monster@trialmonster.uk';

//      Send notification of payment and reminder to complete club registration
        Mail::to($membership['email'])
            ->bcc($bcc)
            ->send(new MembershipPaid($membership['name'], $clubID, $clubName));

//        Send notification to club secretary
        $membershipEmail = $club->memSecEmail;
        $memSecName = $club->membershipSecretary;
        $sendTo = new Address($membershipEmail, $memSecName);

        Mail::to($sendTo)
            ->bcc($bcc)
            ->send(new MembershipReceived($membership['name']));
    }

//  Add names to paid member list
    if (sizeof($membershipNames) > 0) {
        $club = Club::findOrFail($clubID);
        $confirmed = explode(',', $club->confirmed_list);
        $merged = array_unique(array_merge($membershipNames, $confirmed));

        asort($merged);
        $sortedS = implode(',', $merged);
        $club->confirmed_list = $sortedS;
        $club->save();
    }
}


/* ['metadata']['reason']
    user_request
    cancellation

*/

function onRefundCreated(mixed $object)
{
//    info(json_encode($object));
//    info("OnRefundCreated called");
//    RefundCreated::dispatch($object, $object['metadata']);

    /*
    $bcc = 'monster@trialmonster.uk';
    $reason = $object['metadata']['reason'];

    //    Get the entryID from the metadata
    if ($reason == 'user_request') {
        $entryID = $object['metadata']['entry_id'];
        $reason = $object['reason'];
        $status = $object['status'];

        $entryIDs = explode(',', $entryID);

        //        Update status -> 2 (waiting for refund)
        $entries = DB::table('entries')
            ->whereIn('id', $entryIDs)
            ->update(['status' => 2, 'updated_at' => now()]);

        $bcc = 'monster@trialmonster.uk';

        foreach ($entryIDs as $entryID) {

            info("RefundCreated: $entryID");
            $entry = DB::table('entries')->find($entryID);
            $email = $entry->email;
//            echo $email.PHP_EOL;
            Mail::to($email)
                ->bcc($bcc)
                ->queue(new RefundRequested($entry));
        }
    } elseif ($reason == 'cancellation') {
        $trial = Trial::findOrFail($object['metadata']['trial_id']);
        $trialName = $trial->name;
        $trialClub = $trial->club;
        //           get all metadata
        $entryIDs = $object['metadata']['entryIDs'];
        $names = $object['metadata']['names'];
        $email = $object['metadata']['email'];
        $refunded_amount = $object['metadata']['refunded_amount'] / 100;
        $adminFee = $object['metadata']['admin_fee'];

        $refundText = ' A full refund has been requested and you should receive a credit of £';
        if ($adminFee > 0) {
            $refundText = ' As stated in our Terms and Conditions an Admin fee of £' . $adminFee / 100 . ' will be retained. You should receive a credit of £';
        }

        $nameArray = explode(',', $names);
        $idArray = explode(',', $entryIDs);

        $entryData = '';
        for ($i = 0; $i < count($idArray); $i++) {
            $entryData .= 'Ref: ' . $idArray[$i] . ' - ' . $nameArray[$i] . "\n";
        }

        $entryIDs = explode(',', $entryIDs);
        $entries = DB::table('entries')->whereIn('id', $entryIDs)
            ->update(['status' => 2, 'updated_at' => now()]);

        $html = "<div>Dear $email,</div><div>As you may know, " . $trialClub . "'s " . $trialName . ' has unfortunately been cancelled.' . $refundText . $refunded_amount . " to your account.</div><div>This refund is for the following entries: $entryData</div><div>You will be sent a further confirmation email when the refund is completed. If you have any queries, please reply to this email.</div><div>Thank you for entering with TrialMonster.</div>";
        //        echo $html;
        Mail::to($email)
            ->bcc($bcc)
            ->send(mailable: new CancellationRefundRequested($email, $trialName, $trialClub, $refundText, $refunded_amount, $entryData));
    }
    */
}

function onRefundUpdated(mixed $object)
{
    $bcc = 'monster@trialmonster.uk';
    $reason = $object['metadata']['reason'];
    $reason = 'user_request';

    //    Get the entryID from the metadata
    if ($reason == 'user_request') {
        $entryID = $object['metadata']['entry_id'];
//        $reason = $object['reason'];
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
                ->queue(new RefundConfirmed($entry));
        }
    } elseif ($reason == 'cancellation') {
        //           get all metadata
        $entryIDs = $object['metadata']['entryIDs'];
        $names = $object['metadata']['names'];
        $email = $object['metadata']['email'];
        $refunded_amount = $object['metadata']['refunded_amount'];
        $adminFee = $object['metadata']['admin_fee'];

        $nameArray = explode(',', $names);
        $idArray = explode(',', $entryIDs);

        $entryData = '';
        for ($i = 0; $i < count($idArray); $i++) {
            $entryData .= 'Ref: ' . $idArray[$i] . ' - ' . $nameArray[$i] . "\n";
        }

        $entryIDs = explode(',', $entryIDs);
        $entries = DB::table('entries')->whereIn('id', $entryIDs)
            ->update(['status' => 3, 'updated_at' => now()]);

        Mail::to($email)
            ->bcc($bcc)
            ->queue(new CancellationRefundConfirmed($refunded_amount, $adminFee, $entryData));

    }
}

function onRefundFailed(mixed $object)
{
    info('RefundFailed');
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
            case 'checkout.session.completed':
                $object = $event->payload['data']['object'];
//                echo "Call Checkout Session Completed\n";
                onCheckoutSessionCompleted($object);
                break;

            case 'invoice_payment.paid':
                $object = $event->payload['data']['object'];
                $this->onInvoicePaymentPaid($object);

                break;

            case 'invoice.overdue':
                $object = $event->payload['data']['object'];
                onInvoiceOverdue($object);
                break;

            case 'invoice.paid':
                $object = $event->payload['data']['object'];
                onInvoicePaid($object);
                break;

            case 'invoice.sent':
                $object = $event->payload['data']['object'];
                onInvoiceSent($object);

                break;

            case 'payment_intent.created':
                $object = $event->payload['data']['object'];
                onPaymentIntentCreated($object);
                break;

            case 'payment_intent.succeeded':
                //                onInvoiceCreated($event);
                onPaymentIntentSucceeded();
                break;

            case 'price.created':
                $object = $event->payload['data']['object'];
                onPriceCreated($object);
                break;

            case 'price.updated':
                $object = $event->payload['data']['object'];
                onPriceUpdated($object);
                break;

            case 'product.created':
                $object = $event->payload['data']['object'];
                onProductCreated($object);
                break;

            case 'product.updated':
                $object = $event->payload['data']['object'];
                onProductUpdated($object);
                break;

            case 'refund.created':
                $object = $event->payload['data']['object'];
                RefundCreated::dispatch($object, $object['metadata']);
//                onRefundCreated($object);
                break;

            case 'refund.failed':
                $object = $event->payload['data']['object'];
                onRefundFailed($object);
                break;

            case 'refund.updated':
                $object = $event->payload['data']['object'];
                $status = $object['status'];

                if ($status == 'succeeded') {
                    RefundUpdated::dispatch($object, $object['metadata']);
                }
                break;

            case 'invoice.created':
//                onInvoiceCreated($event);
                break;
            default:
                info('Received unknown event type ' . $eventType);
        }
    }

    private function onInvoicePaymentPaid(mixed $object)
    {
        echo "Invoice_Payment Paid\n";
        $invoiceID = $object['invoice'];
        $paymentIntent = $object['payment']['payment_intent'];

        info("PI $paymentIntent, $invoiceID");
        Entry::where('stripe_invoice_id', '=', $invoiceID)
            ->update(['stripe_payment_intent' => $paymentIntent, 'updated_at' => now()]);

    }
}
