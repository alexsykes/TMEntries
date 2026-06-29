<?php

namespace App\Listeners;

use App\Events\RefundCreated;
use App\Mail\CancellationRefundRequested;
use App\Mail\RefundRequested;
use App\Models\Trial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OnRefundCreated
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
    public function handle(RefundCreated $refund): void
    {
//        info("OnRefundCreated handle");

        $object = $refund->object;
        $metadata = $refund->metadata;
        if (is_null($object['reason'])) {
            info("Manual refund requested");
            exit();
        }


        $bcc = 'monster@trialmonster.uk';
        $reason = $metadata['reason'];

//    Get the entryID from the metadata
        if ($reason == 'user_request') {
            $entryID = $metadata['entry_id'];
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

                Mail::to($email)
                    ->bcc($bcc)
                    ->queue(new RefundRequested($entry));
            }
        } elseif ($reason == 'cancellation') {
            $trial = Trial::findOrFail($metadata['trial_id']);
            $trialName = $trial->name;
            $trialClub = $trial->club;
            //           get all metadata
            $entryIDs = $metadata['entryIDs'];
            $names = $metadata['names'];
            $email = $metadata['email'];
            $refunded_amount = $metadata['refunded_amount'] / 100;
            $adminFee = $metadata['admin_fee'];

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


    }
}
