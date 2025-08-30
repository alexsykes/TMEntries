<?php

namespace App\Listeners;

use App\Events\EntryLimitReached;
use App\Models\Entry;
use App\Models\Trial;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OnEntryLimitReached
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
//        dd($event->trial_id);
        Info("Entry limit + 5 reached");
        $unconfirmed = Entry::where("status", 0)
            ->where("trial_id", $event->trial_id)
            ->get();
        dd("Unconfirmed: " . $unconfirmed);
    }
}
