<?php

namespace App\Listeners;

use App\Mail\PaymentReceived;
use App\Models\Entry;
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
                $userID = $entry->created_by;
                $email = User::findOrFail($userID)->email;
                $name = $entry->name;
                $entryID = $entry->id;

                $entry->status = 4;
                $entry->update();

                $bcc = 'admin@trialmonster.uk';
                Mail::to($email)
                    ->bcc($bcc)
                    ->send(new EntryOffer($entry));

                info("EntryID: $entryID Name: $name - Email: $email");
            }
        }

    }
}
