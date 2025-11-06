<?php

namespace App\Console\Commands;

use App\Events\TenSpacesReached;
use App\Models\Entry;
use App\Models\Trial;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;

class TrialNumberCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:trial-number-check';

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

        foreach ($currentTrialIDs as $trialID) {
            $trial = Trial::findOrFail($trialID);
//          Entry limit defaults to 0
            $entryLimit = $trial->entryLimit;

//            Check for entry limit
            if ($entryLimit > 0) {
                $numEntries = Entry::where('trial_id', $trial->id)
                    ->whereIn('status', [1, 4, 7, 8, 9])
                    ->count();

                $unconfirmed = Entry::where('trial_id', $trial->id)
                    ->where('status', 0)
                    ->count();

                $reserves = Entry::where('trial_id', $trial->id)
                    ->where('status', 5)
                    ->count();

                $numSpares = $entryLimit - $numEntries;

//                TODO - check for ?5 entries left
                if ($numSpares > 0 && $reserves > 0) {
                    $this->handleReserves($trial, $numSpares);
                }
            }
        }
    }

    private function handleReserves(Trial $trial, $numSpares)
    {
        $trialID = $trial->id;

        $reserves = Entry::where('trial_id', $trialID)
            ->where('status', 5)
            ->orderBy('created_at')
            ->limit(1)
            ->get();

        foreach ($reserves as $entry) {
            echo date("h:i") . " Entry ID: $entry->id status changed to 4\n";

            $entryID = $entry->id;
            $entry = Entry::where('id', $entryID)->first();
            $entrant = DB::table('users')->where('id', $entry->created_by)->first();
            $email = $entrant->email;
            $entrantName = $entrant->name;
            $entry->status = 4;
            $entry->updated_at = now();
            $entry->save();

            $this->invoice($entry, $email, $entrantName);
        }
    }

    public function invoice($entry, $email, $username)
    {
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
            'description' => $trialClub . ' - ' . $trialName,
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
            'description' => ' Ref: ' . $entryID,
            'invoice' => $invoice->id,
        ]);

        $invoice->sendInvoice();
    }

    private function last10Spaces(Trial $trial, $numEntries)
    {
        TenSpacesReached::dispatch($trial->id, $trial->entryLimit, $numEntries);
    }

    private function handleUnconfirmed(Trial $trial)
    {
//        $limit = $trial->entryLimit;
////        $numEntries =
//
//        $spaces = $limit - $numEntries;
//        if($spaces <= 0) {
//
//            $unconfirmed = Entry::where('trial_id', $trial->id)
//                ->where('status', 0)
//                ->count();
//
//            if($unconfirmed > 0) {
////              Change status to reserve, email notification
//            }
//
//            echo "Entry limit $limit\nNum Entries: $numEntries\nUnconfirmed: $unconfirmed\n\n";
    }
}