<?php

namespace App\Listeners;

use App\Events\TenSpacesReached;
use App\Mail\LastChance;
use App\Models\Entry;
use App\Models\Trial;
use Illuminate\Support\Facades\Mail;

class OnTenSpacesReached
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
    public function handle(TenSpacesReached $event): void
    {
        $numEntries = $event->numEntries;
        $entryLimit = $event->entry_limit;
        $trialID = $event->trial_id;
//        dump($trialID, $entryLimit, $numEntries);
//
//        Info("Confirmed entries: $numEntries" );
//        Info("Entry limit: $entryLimit" );

//        Handle unconfirmed if 5 entries left
//        if($entryLimit - $numEntries == 5) {
        $unconfirmed = Entry::where("status", 0)
            ->leftJoin("users", "users.id", "=", "entries.created_by")
            ->select('entries.id', 'entries.name', 'users.email')
            ->where("trial_id", $trialID)
            ->get();

        $ids = array();
        $trial = Trial::findOrFail($trialID);
        $bcc = "monster@trialmonster.uk";

        foreach ($unconfirmed as $entry) {
//                Send LastChance email
            $entry->status = 11;
            $entry->updated_at = now();
            $entry->save();
            echo "LastChance to $entry->email\n";
            Mail::to($entry->email)->send(new LastChance($trial));
        }
    }
//    }
}
