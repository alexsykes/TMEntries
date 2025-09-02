<?php

namespace App\Listeners;

use App\Events\TrialFull;
use App\Models\Entry;
use App\Models\Trial;
use Illuminate\Support\Facades\Mail;

class OnTrialFull
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
    public function handle(TrialFull $event): void
    {
        $numEntries = $event->numEntries;
        $entryLimit = $event->entry_limit;
        $trialID = $event->trial_id;
//        dump($trialID, $entryLimit, $numEntries);

        Info("Confirmed entries: $numEntries");
        Info("Entry limit: $entryLimit");

        $unconfirmed = Entry::where("status", 0)
            ->join("users", "users.id", "=", "entries.created_by")
            ->select('entries.id', 'entries.name', 'users.email')
            ->where("trial_id", $trialID)
            ->get();

        $ids = array();

        $trial = Trial::findOrFail($trialID);
        $bcc = "monster@trialmonster.uk";
        foreach ($unconfirmed as $entry) {
            array_push($ids, $entry->id);
//                Send LastChance email
            Info("Sendmail to $entry->email");
                Mail::to($entry->email)
                    ->bcc($bcc)
                    ->send(new \App\Mail\TrialFull($trial));
        }
        Entry::whereIn('entries.id', $ids)
            ->update(['entries.status' => 5,
                'updated_at' => now()
            ]);
    }
}
