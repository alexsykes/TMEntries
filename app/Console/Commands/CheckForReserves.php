<?php

namespace App\Console\Commands;

use App\Mail\EntryOffer;
use App\Models\Entry;
use App\Models\Trial;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use function Pest\Laravel\get;

class CheckForReserves extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-for-reserves';

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
        $currentTrialIDs = Trial::whereTodayOrAfter('date')
            ->where('isEntryLocked', false)
            ->pluck('id');

//        For each one…
        foreach ($currentTrialIDs as $trialID) {
            $trial = Trial::findOrFail($trialID);
            $entryLimit = $trial->entryLimit;

//            Check for reserve riders
            $reserves = DB::table('entries')
                ->where('trial_id', $trialID)
                ->where('status', 5)
                ->get();
            $numReserves = count($reserves);

//            If there are reserve riders, check for spaces available
            if($numReserves > 0) {
                $entries = DB::table('entries')
                    ->where('trial_id', $trialID)
                    ->whereIn('status', [1, 4, 7, 8, 9])
                    ->get();

                $numEntries = count($entries);

                $numSpaces =  $entryLimit - $numEntries;
                if($numSpaces > 0 && $numReserves > 0) {
//                    info("Get $numSpaces reserve(s) for trial $trialID");
                    $entriesForOffer = DB::table('entries')
                        ->where('trial_id', $trialID)
                        ->where('status', 5)
                        ->orderBy('updated_at')
                        ->limit($numSpaces)
                    ->get();

//                    Offer entry to each reserve
                    foreach($entriesForOffer as $entry) {
                        $entryID = $entry->id;

                        $entry = DB::table('entries')->where('id', $entryID)->first();
                        $entrant = DB::table('users')->where('id', $entry->created_by)->first();
                        $trial = DB::table('trials')->where('id', $entry->trial_id)->first();
                        $email = $entrant->email;
                        $entrantName = $entrant->name;
                        $date = date_create($trial->date);
//                        info("\nOffer to $entryID\n $entry->name\n $entrantName\n$email\n");

                        $entryData = array();
                        $entryData['trialName'] = $trial->name;
                        $entryData['trialClub'] = $trial->club;
                        $entryData['date'] = date_format($date, "F jS, Y");
                        $entryData['rider'] = $entry->name;
                        $entryData['class'] = $entry->class;
                        $entryData['course'] = $entry->course;
                        $entryData['entryID'] = $entryID;

//                        Mail::to($email, $entrantName)->send(new EntryOffer($entryData));
                    }
                }
                else {
//                    info("\nTrialID: $trialID \nlimit: $entryLimit\nNumber of entries: $numEntries \nNumber of reserves: $numReserves\nNumSpaces: $numSpaces\n");
                }
            }
        }
    }
}
