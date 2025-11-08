<?php

namespace App\Console\Commands;

use App\Models\Entry;
use App\Models\Trial;
use Illuminate\Console\Command;

class CheckEntries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-entries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //        Get currently active trial IDs
        $tomorrow = date("Y-m-d", strtotime('tomorrow'));


        $currentTrialIDs = Trial::whereTodayOrAfter('date')
            ->where('isEntryLocked', false)
            ->whereDate('closingDate', $tomorrow)
            ->pluck('id');

        Info("Check Entries: " . count($currentTrialIDs)) . " trial(s).";
        foreach ($currentTrialIDs as $trialID) {
            $trial = Trial::findOrFail($trialID);
//          Entry limit defaults to 0
            $entryLimit = $trial->entryLimit;

//            Check for entry limit
            if ($entryLimit > 0) {
                $unconfirmed = Entry::where('trial_id', $trial->id)
                    ->leftJoin('users', 'users.id', '=', 'entries.created_by')
                    ->where('status', 0)
                    ->select('entries.*', 'users.email')
                    ->get();

                Info('Trial ' . $trial->name . ' has ' . sizeof($unconfirmed) . ' unconfirmed entries');

                foreach ($unconfirmed as $entry) {
                    Mail::to($entry->email)->send(new LastChance($trial));
                    $entry->status = 10;
                    $entry->updated_at = date("Y-m-d H:i:s");
                    $entry->save();
                    Info('EntryID:' . $entry->id . ' Last Chance email sent to ' . $entry->email);
                }
            }
        }
    }
}
