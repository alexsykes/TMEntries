<?php

namespace App\Console\Commands;

use App\Mail\EntryOffer;
use App\Models\Entry;
use App\Models\Trial;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Stripe\StripeClient;
use function Pest\Laravel\get;

/* Paid status
    0 - New entry within limit, not paid
    1 - Confirmed entry
    2 - Withdrawn, having paid, waiting for refund
    3 - Refunded entries
    4 - Reserve - invoiced, awaiting payment
    5 - Reserve - not paid
    6 - Removed
    7 - Manual entry - unpaid
    8 - Manual entry - paid
    9 - Manual entry - FoC
    10 -
    11 - Reminder sent
*/

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
        info("Check for reserves");
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
                        ->limit(1)
                    ->get();

//                    Offer entry to each reserve
                    foreach($entriesForOffer as $entry) {
                        $entryID = $entry->id;

//                        $entry = DB::table('entries')->where('id', $entryID)->first();
                        $entry = Entry::where('id', $entryID)->first();
                        $entrant = DB::table('users')->where('id', $entry->created_by)->first();
//                        $trial = DB::table('trials')->where('id', $entry->trial_id)->first();
                        $email = $entrant->email;
                        $entrantName = $entrant->name;
                        $entry->status = 4;
                        $entry->updated_at = now();
                        $entry->save();

                        $this->invoice($entry, $email, $entrantName);
                    }
                }
                else {
//                    info("\nTrialID: $trialID \nlimit: $entryLimit\nNumber of entries: $numEntries \nNumber of reserves: $numReserves\nNumSpaces: $numSpaces\n");
                }
            }
        }
    }

    public function invoice($entry, $email, $username){
        $another = new StripeClient(Config::get('stripe.stripe_secret_key'));

        $trialID = $entry->trial_id;
        $trial = Trial::findOrFail($trialID);
        $trialName = $trial->name;
        $trialClub = $trial->club;

        $customer = $another->customers->create([
            'email' => $email,
            'name' => $username,
        ]);

        $customerId = $customer->id;

        $entryID = $entry->id;
        // Create an Invoice
        $invoice = $another->invoices->create([
            'customer' => $customerId,
            'description' => $trialClub.' - '.$trialName,
            'collection_method' => 'send_invoice',
            'days_until_due' => 2,
            'metadata' => [
                'entryID' => $entryID,
            ]
        ]);

//   Add line items
        $invoiceItem = $another->invoiceItems->create([
            'customer' => $customerId,
            'pricing' => [
                'price' => $entry->stripe_price_id,
            ],
            'description' => ' Ref: '.$entryID,
            'invoice' => $invoice->id,
        ]);

        $invoice->sendInvoice();
    }
}
