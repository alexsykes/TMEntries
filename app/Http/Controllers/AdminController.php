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
use Stripe\StripeClient;
use Stripe\Exception\InvalidRequestException;

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
                    'reason' => 'cancellation']
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

        $salt = substr("0faPWOZpvQCuEWcAj0qm1.1", 7, 22);
        $salt = "0faPWOZpvQCuEWcAj0qm1.";

        $rawPassword = $request->password;
        $crypted = crypt($rawPassword, "$2y$10$" . $salt);
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
        $rawQuery = "INSERT INTO " . $prefix . "score_backup SELECT * FROM " . $prefix . "scores WHERE `trial_id` = '" . $request->id . "'";
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

        $filename = "Scores.json";
        file_put_contents($exportDir . $filename, $scores);
        $filename = "Trial.json";
        file_put_contents($exportDir . $filename, $trial);
        $filename = "Entries.json";
        file_put_contents($exportDir . $filename, $entries);

        TrialBackupCompleted::dispatch($id);
        return redirect('/admin/trial/edit/' . $id);
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


        foreach ($intentIDs as $intentID) {
            $refundValue = 0;
            $lineItems = "";
            $purchases = DB::table('purchases')
                ->join('prices', 'purchases.stripe_product_id', '=', 'prices.stripe_product_id')
                ->join('products', 'purchases.stripe_product_id', '=', 'products.stripe_product_id')
                ->where('pi', $intentID)
                ->select('quantity', 'purchases.stripe_product_id', 'prices.stripe_price', 'products.product_name')
                ->get();

            foreach ($purchases as $purchase) {
                $quantity = $purchase->quantity;
                $itemValue = $purchase->stripe_price * $quantity;
                $lineValuePounds = $itemValue / 100;
                $itemValuePounds = $purchase->stripe_price / 100;
                $line = "$purchase->product_name($quantity) - £$itemValuePounds - £$lineValuePounds \n";

                $lineItems .= $line;

                $refundValue += $itemValue;
            }


            $stripe = new StripeClient(Config::get('stripe.stripe_secret_key'));

            try {
                $result = $stripe->refunds->create([
                    'payment_intent' => $intentID,
                    'amount' => $refundValue,        // Need to get amount of item
                    'metadata' => [
                        'line_items' => $lineItems,
                        'refunded_amount' => $refundValue,
                    ]
                ]);
//            dd($result);
            } catch (InvalidRequestException $e) {
//                dump($e);
                $message = $e->getMessage();
                Info("Refund failed - $message");
            }
        }

        return redirect('/admin/trial/edit/' . $trialID);
    }
}
