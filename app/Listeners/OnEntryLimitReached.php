<?php

namespace App\Listeners;

use App\Events\EntryLimitReached;
use App\Mail\LastChance;
use App\Mail\ReserveAdded;
use App\Models\Entry;
use App\Models\Trial;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class OnEntryLimitReached implements ShouldQueue
{
    /**
     * Create the event listener.
     */
//    public int $trial_id;
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(EntryLimitReached $event): void
    {
        $numEntries = $event->numEntries;
        $entryLimit = $event->entry_limit;
        $trialID = $event->trial_id;
//        dump($trialID, $entryLimit, $numEntries);

        Info("Confirmed entries: $numEntries" );
        Info("Entry limit: $entryLimit" );

//        Handle unconfirmed if 5 entries left
        if($entryLimit - $numEntries <= 5) {
            $unconfirmed = Entry::where("status", 0)
                ->join("users", "users.id", "=", "entries.created_by")
                ->select('entries.id', 'entries.name', 'users.email')
                ->where("trial_id", $trialID)
                ->get();

            $ids = array();
            $trial = Trial::findOrFail($trialID);
            $bcc = "monster@trialmonster.uk";
            foreach ($unconfirmed as $entry) {
//                Send LastChance email
                Info("Sendmail to $entry->email");
                Mail::to($entry->email)
                    ->bcc($bcc)
                    ->send(new LastChance($trial));
            }
        }
    }

    public function moveToReserveList(Entry $entry): void{
//        Mail


//        Downgrade



    }
}
