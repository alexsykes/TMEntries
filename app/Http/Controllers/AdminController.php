<?php

namespace App\Http\Controllers;

use App\Events\TrialBackupCompleted;
use App\Mail\TMLogin;
use App\Models\AppUser;
use App\Models\Trial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\InvalidRequestException;
use Stripe\StripeClient;

class AdminController extends Controller
{
    public function userList()
    {
        $users = User::orderBy('name', 'asc')
            ->paginate(50);

        $appUsers = AppUser::all();

        return view('admin.userList', ['users' => $users, 'appUsers' => $appUsers]);

    }

    public function trialList()
    {
        $trials = DB::table('trials')
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.trialList', ['trials' => $trials]);
    }

    public function resultList()
    {
        $results = DB::table('trials')
            ->where('isResultPublished', 1)
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.resultList', ['results' => $results]);
    }

    public function mailList()
    {
        $mails = DB::table('mails')
            ->orderBy('subject')
            ->get();

        return view('admin.mailList', ['mails' => $mails]);
    }

    public function refundTrial($id)
    {
        $trialID = $id;

        //        Get entries with status 1
        $entryData = DB::table('entries')
            ->select('stripe_payment_intent', DB::raw('group_concat(id) as ids'))
            ->groupBy('stripe_payment_intent')
            ->where('trial_id', $trialID)
            ->where('status', 1)
            ->whereNotNull('stripe_payment_intent')
            ->get();

        foreach ($entryData as $entry) {
            $paymentIntent = $entry->stripe_payment_intent;
            $entry_id = $entry->ids;

            $stripe = new StripeClient(Config::get('stripe.stripe_secret_key'));
            $stripe->refunds->create([
                'payment_intent' => $paymentIntent,
                'amount' => 1,
                'metadata' => ['entry_id' => $entry_id,
                    'reason' => 'cancellation'],
            ]);
            //        payment intents to refund
            Log::info("Refund requested - $entry_id");
        }
    }

    public function sendMail()
    {
        $delay = 1;
        $users = User::get();

        foreach ($users as $user) {
            Mail::to($user->email)
                ->later(now()->addSeconds($delay++), new TMLogin($user));
            $delay++;
            info("sendMail - delay: $delay");
        }

        return redirect('/adminaccess');
    }

    public function closeMyAccount()
    {
        $email = request('email');
        $id = request('id');
        $user = User::where('email', $email)
            ->where('id', $id)
            ->first();
        if ($user) {
            $user->delete();
        } else {
            abort(404);
        }

        return redirect('/');
    }

    public function adminRemove()
    {
        //        dd(request('id'));
        $id = request('id');
        $user = User::find($id)
            ->where('id', $id)
            ->where('isSuperUser', '!=', 1)
            ->delete();

        return redirect('/adminaccess');
    }

    public function editUser()
    {
        $user = User::find(request('id'));

        return view('admin.adminUserEdit', ['user' => $user]);
    }

    public function updateUser()
    {
        $user = User::find(request('id'));
        $user->name = request('name');
        $user->email = request('email');
        $user->save();

        return redirect('/adminaccess');
    }

    public function toggleResultPublished()
    {
        $trial = Trial::find(request('id'));
        $trial->isResultPublished = !$trial->isResultPublished;
        $trial->save();

        return redirect('/admin/trials');
    }

    public function toggleEntry()
    {
        $trial = Trial::find(request('id'));
        $trial->isEntryLocked = !$trial->isEntryLocked;
        $trial->save();

        return redirect('/admin/trials');
    }

    public function toggleScoring()
    {
        $trial = Trial::find(request('id'));
        $trial->isScoringLocked = !$trial->isScoringLocked;
        $trial->save();

        return redirect('/admin/trials');
    }

    public function toggleLock()
    {
        $trial = Trial::find(request('id'));
        $trial->isLocked = !$trial->isLocked;
        $trial->save();

        return redirect('/admin/trials');
    }

    public function about()
    {
        return view('about.story');
    }

    public function addAppUser()
    {
        return view('admin.addAppUser');
    }

    public function storeAppUser(Request $request)
    {
        $request->validate([
                'username' => ['required', 'string', 'max:255'],
                //                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . AppUser::class],
                'password' => ['required', 'string', 'min:8'],
            ]
        );

        $salt = substr('0faPWOZpvQCuEWcAj0qm1.1', 7, 22);
        $salt = '0faPWOZpvQCuEWcAj0qm1.';

        $rawPassword = $request->password;
        $crypted = crypt($rawPassword, '$2y$10$' . $salt);
        $user = AppUser::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $crypted,
        ]);
    }

    public function trialEdit(string $id)
    {
        $trial = Trial::find($id);
        $club = $trial->club()->first();
        $venue = $trial->venue()->first();

        return view('admin.trial.edit', ['trial' => $trial, 'club' => $club, 'venue' => $venue]);
    }

    public function trialUpdate(Request $request)
    {
        $trial = Trial::find($request->id);

        $trial->published = isset($request->published);
        $trial->isEntryLocked = isset($request->isEntryLocked);
        $trial->isScoringLocked = isset($request->isScoringLocked);
        $trial->isLocked = isset($request->isLocked);
        $trial->isResultPublished = isset($request->isResultPublished);

        $trial->update();

        return redirect('/admin/trials');
    }

    public function unpublish(Request $request)
    {
        $trial = Trial::find($request->id);
        $trial->isEntryLocked = false;
        $trial->isResultPublished = false;
        $trial->isScoringLocked = false;
        $trial->isLocked = false;

        $trial->update();
    }

    public function archive(Request $request)
    {
        $prefix = config('database.connections.mysql.prefix');
        $rawQuery = 'INSERT INTO ' . $prefix . 'score_backup SELECT * FROM ' . $prefix . "scores WHERE `trial_id` = '" . $request->id . "'";
        $result = DB::select($rawQuery);

        $deleted = DB::table('scores')->where('trial_id', $request->id)->delete();

        return redirect('/admin/trial/edit/' . $request->id);
    }

    public function backupTrial(Request $request)
    {
        $id = $request->id;
        $exportDir = "backups/$id/";

        if (!file_exists($exportDir)) {
            mkdir($exportDir, 0777, true);
        }

        $trial = Trial::find($id)
            ->toJson();

        //        Get all score data for trial
        $scores = Trial::find($request->id)->scores()->get()
            ->toJson();

        //        Get relevant entry data for trial
        $entries = Trial::find($request->id)->entries()
            ->select('name', 'class', 'course', 'sectionScores', 'sequentialScores', 'make', 'size', 'ridingNumber', 'dob')
            ->get()
            ->toJson();

        $filename = 'Scores.json';
        file_put_contents($exportDir . $filename, $scores);
        $filename = 'Trial.json';
        file_put_contents($exportDir . $filename, $trial);
        $filename = 'Entries.json';
        file_put_contents($exportDir . $filename, $entries);

        $tables = ['entries', 'scores'];

        $this->exportToCsv($request->id, $tables, $exportDir);

        TrialBackupCompleted::dispatch($id);

        return redirect('/admin/trial/edit/' . $id);
    }

    private function exportToCsv($requestID, mixed $tables, string $exportDir)
    {
        if ($tables) {
            foreach ($tables as $table) {
                $data = DB::table($table)
                    ->where('trial_id', $requestID)
                    ->get();

                $size = count($data);
                if ($size > 0) {
                    $csvFileName = $exportDir . $table . '.csv';
                    $csvFile = fopen($csvFileName, 'w');
                    $headers = array_keys((array)$data[0]); // Get the column headers from the first row
                    fputcsv($csvFile, $headers);

                    foreach ($data as $row) {
                        fputcsv($csvFile, (array)$row);
                    }
                    fclose($csvFile);
                }
            }
            //            Trial as key field is `id`
            $table = 'trials';
            $data = DB::table($table)
                ->where('id', $requestID)
                ->get();

            $size = count($data);
            if ($size > 0) {
                $csvFileName = $exportDir . $table . '.csv';
                $csvFile = fopen($csvFileName, 'w');
                $headers = array_keys((array)$data[0]); // Get the column headers from the first row
                fputcsv($csvFile, $headers);

                foreach ($data as $row) {
                    fputcsv($csvFile, (array)$row);
                }
                fclose($csvFile);
            }
        }
        //        dd();
    }

    public function resetScoring(Request $request)
    {
        $id = $request->id;

        $affected = DB::table('scores')
            ->where('trial_id', $id)
            ->update(['score' => null, 'updated_at' => null]);

        return redirect('/admin/trial/edit/' . $id);
    }

    public function refund(Request $request)
    {
        $trialID = $request->id;

        $task = $request->submitbutton;
        $adminFee = is_null($request->fee) ? 0 : 100 * $request->fee;

        switch ($task) {
            case 'refund':
                $pis = DB::select("SELECT stripe_payment_intent AS pi, 
       email,  
       GROUP_CONCAT(e.name SEPARATOR ', ') AS names, 
       GROUP_CONCAT(e.id SEPARATOR ',') AS entryIDs, 
       SUM(p.stripe_price) AS value 
FROM `tme_entries` e
JOIN tme_prices p ON e.`stripe_price_id` = p.`stripe_price_id`
WHERE e.`trial_id` = $trialID AND e.status = 1 
GROUP BY `stripe_payment_intent`, `email`");

                foreach ($pis as $pi) {
                    //                    Change entry status to 2 - now moved to StripeListener
                    //                    $entries = DB::table('entries')
                    //                        ->update(['status' => 2, 'updated_at' => date('Y-m-d H:i:s')]);

                    $intent = $pi->pi;
                    $value = $pi->value - $adminFee;
                    $entryIDs = $pi->entryIDs;
                    $names = $pi->names;
                    $email = $pi->email;

                    //                    Request refund from Stripe
                    $stripe = new StripeClient(Config::get('stripe.stripe_secret_key'));
                    try {
                        $result = $stripe->refunds->create([
                            'payment_intent' => $intent,
                            'amount' => $value,        // Need to get amount of item
                            'metadata' => [
                                'reason' => 'cancellation',
                                'refunded_amount' => $value,
                                'pi' => $intent,
                                'entryIDs' => $entryIDs,
                                'names' => $names,
                                'email' => $email,
                                'admin_fee' => $adminFee,
                                'trial_id' => $trialID,
                            ],
                        ]);
                        //                Catch error if, for example, refund has already been made
                    } catch (InvalidRequestException $e) {
                        $message = $e->getMessage();
                        Info("Refund failed - $message, PI: $intent");
                    }
                }
                break;
            case 'refundAll':

                break;
            default:
                break;
        }

        return redirect('/admin/trial/edit/' . $trialID);
    }

    public function refund_(Request $request)
    {
        $trialID = $request->id;
        // Get product data for trial
        $productArray = DB::table('products')
            ->where('trial_id', $trialID)
            ->where('product_category', 'entry fee')
            ->select('stripe_product_id')
            ->get()
            ->toArray();

        $productIDs = array_column($productArray, 'stripe_product_id');

        //        Find intents for entries
        $intents = DB::table('purchases')
            ->whereIn('stripe_product_id', $productIDs)
            ->distinct()
            ->get('pi')
            ->toArray();

        $intentIDs = array_column($intents, 'pi');

        $numRefunds = count($intents);
        info("$numRefunds refunds to process");

        foreach ($intentIDs as $intentID) {
            //            Reset for each intent
            $refundValue = 0;
            $lineItems = '';
            $entryIDarray = [];

            //            Get purchases for intent
            $purchases = DB::table('purchases')
                ->join('prices', 'purchases.stripe_product_id', '=', 'prices.stripe_product_id')
                ->join('products', 'purchases.stripe_product_id', '=', 'products.stripe_product_id')
                ->where('pi', $intentID)
                ->select('quantity', 'purchases.entryIDs as entryIDs', 'purchases.stripe_product_id', 'prices.stripe_price', 'products.product_name')
                ->get();

            //            Get details of each purchase and prepare for
            //              * Stripe transcation
            //              * Email notification
            foreach ($purchases as $purchase) {
                $quantity = $purchase->quantity;
                $itemValue = $purchase->stripe_price * $quantity;
                $lineValuePounds = $itemValue / 100;
                $itemValuePounds = $purchase->stripe_price / 100;
                $line = "$purchase->product_name($quantity) - £$itemValuePounds - £$lineValuePounds \n";
                array_push($entryIDarray, $purchase->entryIDs);

                $lineItems .= $line;

                $refundValue += $itemValue;
            }
            $entryIDs = $entryIDarray[0];

            $stripe = new StripeClient(Config::get('stripe.stripe_secret_key'));

            info("Entry IDs: $entryIDs");
            try {
                $result = $stripe->refunds->create([
                    'payment_intent' => $intentID,
                    'amount' => $refundValue,        // Need to get amount of item
                    'metadata' => [
                        'reason' => 'cancellation',
                        'line_items' => $lineItems,
                        'refunded_amount' => $refundValue,
                        'pi' => $intentID,
                        'entryIDs' => $entryIDs,
                    ],
                ]);
                //                Catch error if, for example, refund has already been made
            } catch (InvalidRequestException $e) {
                $message = $e->getMessage();
                Info("Refund failed - $message");
            }
        }

        return redirect('/admin/trial/edit/' . $trialID);
    }

    public function showPurchases(string $id)
    {
//        $productArray = DB::table('products')
//            ->leftJoin('prices', 'products.stripe_product_id', '=', 'prices.stripe_product_id')
//            ->where('trial_id', $id)
//            ->whereNot('product_category', 'entry fee')
//            ->orderBy('product_name', 'asc')
//            ->orderBy('stripe_product_description', 'asc')
//            ->get(['products.product_name', 'products.stripe_product_description', 'products.stripe_product_id', 'prices.stripe_price_id'])
//            ->toArray();
//
//        $priceIDs = array_column($productArray, 'stripe_price_id');
//        $productIDs = array_column($productArray, 'stripe_product_id');
//        dump($priceIDs, $productIDs);
//
//        $purchases = DB::table('purchases')
//            ->whereIn('stripe_product_id', $productIDs)
//            ->get();
//
//        dd($purchases);

        $entryData = DB::table('entries')
            ->where('trial_id', $id)
            ->select('name','entries.extras')
            ->get()
            ->toArray();
//        dump($entryData);

        $data = array();

        foreach ($entryData as $entry) {
            $name = $entry->name;
            $priceIDs = json_decode($entry->extras);
            $dataa = array($name, $priceIDs);
            array_push($data, $dataa);
        }

        dd($data);
        return view('admin/purchases', compact('productArray', 'purchases'));
    }
}
