<?php

namespace App\Listeners;

use App\Events\RefundUpdated;
use App\Mail\CancellationRefundConfirmed;
use App\Mail\RefundConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OnRefundUpdate
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
    public function handle(RefundUpdated $event): void
    {
        $object = $event->object;
        $bcc = 'monster@trialmonster.uk';

        if (is_null($object['reason'])) {
            info("Manual refund updated");
            exit();
        }

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

                info("Refund confirmed: ".$entryID);
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
}
