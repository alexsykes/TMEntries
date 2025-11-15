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

    public function handle(TrialFull $event): void
    {
        Info("Handling TrialFull event");
        $numEntries = $event->numEntries;
        $entryLimit = $event->entry_limit;
        $trialID = $event->trial_id;

        Info("Confirmed entries: $numEntries");
        Info("Entry limit: $entryLimit");

        $unconfirmed = Entry::whereIn("status", [0,10])
            ->join("users", "users.id", "=", "entries.created_by")
            ->select('entries.id', 'entries.name', 'users.email')
            ->where("trial_id", $trialID)
            ->get();

        $ids = array();

        $trial = Trial::findOrFail($trialID);
        $bcc = "monster@trialmonster.uk";
        foreach ($unconfirmed as $entry) {
            array_push($ids, $entry->id);
//                Send TrialFull email
            Info("Send Trial Full mail to $entry->email");
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
