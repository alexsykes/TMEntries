<?php

namespace App\Http\Controllers;

use App\Events\TrialFull;
use App\Mail\EntryChanged;
use App\Mail\ReserveAdded;
use App\Models\Entry;
use App\Models\Price;
use App\Models\Trial;
use App\Rules\NoDuplicates;
use Auth;
use DateTime;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PDF;
use Stripe\StripeClient;


class EntryController extends Controller
{
    //

//    Used at initial system entry to get user email/phone
    public function getUserDetails(Request $request)
    {
        $trial_id = request('id');
        session(['trial_id' => $trial_id]);
        return view('entries.get_user_details');
    }

//  Used to display user's current entries
    public function showUserData(Request $request)
    {

        $email = session('email');
        $phone = session('phone');
        $trial_id = session('trial_id');
        $user_id = Auth::user()->id;
        $entries = Entry::all()
            ->where('created_by', $user_id)
            ->where('trial_id', $trial_id)
            ->where('status', 0);
//        $entries = Entry::all();

//        dump($entries);
        $trial = Trial::findorfail($trial_id);

        return view('entries.userdata', ['entries' => $entries, 'trial' => $trial]);
    }

    public function userEntryList(Request $request)
    {
        $user = Auth::user();
        $userID = $user->id;
        $usedStatus = DB::table('entries')
            ->distinct('status')
            ->where('created_by', $userID)
            ->orderBy('status', 'asc')
            ->get('status');

//        dd($usedStatus);
        $entriesArray = array();

        foreach ($usedStatus as $status) {
            $entries = DB::table('entries')
                ->where('status', $status->status)
                ->where('created_by', $userID)
                ->orderBy('name', 'asc')
                ->get();
            array_push($entriesArray, $entries);
        }
        return view('entries.user_entry_list', ['entriesArray' => $entriesArray, 'user' => $user]);
    }

    public function register(Request $request)
    {
        $trial_id = $request->trialid;
        $user_id = Auth::user()->id;

        $trial = Trial::findorfail($trial_id);

        $entries = Entry::all()
            ->where('created_by', $user_id)
            ->where('trial_id', $trial_id)
            ->where('status', 0);

        $reserves = Entry::all()
            ->where('created_by', $user_id)
            ->where('trial_id', $trial_id)
            ->whereIn('status', [4, 5]);

        return view('entries.register', ['entries' => $entries, 'trial' => $trial, 'reserves' => $reserves]);
    }

//     From editing from list on register page
    public function updateEntry(Request $request)
    {
//        $accept = session('accept');
        //        Get product/price IDs

        $request->validate([
            'name' => ['required', 'min:5', 'max:255'],
            'class' => 'required',
            'course' => 'required',
            'make' => 'required',
            'type' => 'required',
            'dob' => 'required',
        ]);


        $entry = Entry::find($request->id);
        $trial_id = $entry->trial_id;
        $trial = Trial::findOrFail($trial_id);
        $trial_date = date_create($trial->date);

//        Get product/price IDs
        $youthProductID = DB::table('products')
            ->where('trial_id', $trial_id)
            ->where('isYouth', true)
            ->where('product_category', 'entry fee')
            ->value('stripe_product_id');


        $adultProductID = DB::table('products')
            ->where('trial_id', $trial_id)
            ->where('isYouth', false)
            ->where('product_category', 'entry fee')
            ->value('stripe_product_id');


        $youthPriceID = DB::table('prices')
            ->where('stripe_product_id', $youthProductID)
            ->value('stripe_price_id');

        $adultPriceID = DB::table('prices')
            ->where('stripe_product_id', $adultProductID)
            ->value('stripe_price_id');

//        dd($trial_id, $adultProductID, $adultPriceID, $youthProductID, $youthPriceID);
        $utilityController = new UtilityController();


        $entry->name = $utilityController->nameize($request->name);
        $entry->class = $request->class;
        $entry->course = $request->course;
        $entry->licence = $request->licence;

        $entry->make = $request->make;
        $entry->type = $request->type;

        $entry->size = $request->size;
//        $entry->accept = $accept;
        $entry->dob = $request->dob;

        $birthDate = date_create($request->dob);

        $interval = $trial_date->diff($birthDate);
//dump($interval->y);
//        Calculation for yout goes here
        if ($interval->y < 18) {
            $entry->isYouth = 1;
            $entry->stripe_price_id = $youthPriceID;
            $entry->stripe_product_id = $youthProductID;
        } else {
            $entry->isYouth = 0;
            $entry->stripe_price_id = $adultPriceID;
            $entry->stripe_product_id = $adultProductID;
        }

        $entry->save();
//        dd($entry);
        return redirect("/entries/register/{$trial_id}");
    }

    public function adminEntryUpdate(Request $request)
    {
        $entryID = $request->entryID;
        $trialID = $request->trialID;

        $request->validate([
            'name' => ['required', 'min:5', 'max:255'],
            'class' => 'required',
            'course' => 'required',
            'make' => 'required',
            'type' => 'required',
            'status' => 'required',
        ]);

        $entry = Entry::find($entryID);
//        dd($entry);
        $entry->name = $request->name;

        $entry->class = $request->class;
        $entry->course = $request->course;
        $entry->make = $request->make;
        $entry->type = $request->type;
        $entry->size = $request->size;
        $entry->status = $request->status;
        $entry->startsAt = $request->startsAt;
        $entry->dob = $request->dob;
        $entry->updated_at = date('Y-m-d H:i:s');

        if ($request->status == 6) {
            $entry->ridingNumber = 0;
        }
        $entry->save();

//      Entry numbers check

        $trial = Trial::findOrFail($trialID);

//        Check whether trial has entry limit
        if ($trial->hasEntryLimit) {
            // info("Trial has entryLimit");
//        Check for full entry list
            $entryLimit = $trial->entryLimit;
            $numEntries = Entry::where('trial_id', $trialID)
                ->whereIn('status', [1, 4, 7, 8, 9])
                ->count();
            Info("NumEntries: $numEntries");
//        Check for number of entries left
            $spaces = $entryLimit - $numEntries;
            if ($spaces <= 0) {
                TrialFull::dispatch($trialID, $entryLimit, $numEntries);
            }
        }

        return redirect("/trials/adminEntryList/{$trialID}");
    }

    public function adminEntryStore(Request $request)
    {
        $token = bin2hex(random_bytes(16));
        $trialID = $request->trialID;
        $attributes = $request->validate([
            'name' => ['required', 'min:5', 'max:255'],
            'class' => 'required',
            'course' => 'required',
            'make' => 'required',
            'type' => 'required',
            'status' => 'required',
        ]);
        $attributes['status'] = $attributes['status'] + 7;
        $attributes['trial_id'] = $trialID;
        $attributes['IPaddress'] = $request->ip();
        $attributes['size'] = $request->size;
        $attributes['licence'] = $request->licence;
        $attributes['token'] = $token;
        $attributes['accept'] = false;
        $attributes['created_by'] = Auth::user()->id;

        if (isset($request->isYouth)) {
            $attributes['isYouth'] = 1;
        } else {
            $attributes['isYouth'] = 0;
        }

        $entry = Entry::create($attributes);
        return redirect("/trials/adminEntryList/{$trialID}");
    }

    /*   User updates entry - from email
        Show screen for entry with form for updated fields
        Limited changes can be made
    */

    public function create($id)
    {
        session(['trial_id' => $id]);
        $trial = Trial::findOrFail($id);
        return view('entries.get_user_details', ['trial' => $trial, 'entry' => new Entry()]);
    }

    /*
     * Email confirmation of entry changes
     */

    public function withdrawConfirm(Request $request)
    {
        $entryID = $request->id;
        $entry = Entry::where('id', $entryID)->where('status', 1)->first();

        return view('entries.withdraw_confirm', ['entry' => $entry]);
    }

    public function withdraw(Request $request)
    {

        $id = $request->id;
        $token = $request->token;
        $entry = Entry::where('id', $id)
            ->where('status', 1)
            ->where('token', $token)
            ->first();

        if ($entry) {
            $pi = $entry->stripe_payment_intent;
            $price = Price::where('stripe_price_id', $entry->stripe_price_id)->first();
            $cost = $price->stripe_price;

//            $entry->status = 2; // Mark as withdrawn, having paid, waiting for refund
//            $entry->token = $token = bin2hex(random_bytes(16));
            $entry->save();

//        Request request
//            require('../vendor/autoload.php');
//            require('../vendor/stripe/stripe-php/lib/StripeClient.php');
            $stripe = new StripeClient(config('stripe.stripe_secret_key'));

            $stripe->refunds->create
            ([
                'metadata' => ['id' => $id],
                'payment_intent' => $pi,
                'amount' => $cost - 300,
//            'amount' => 1,
            ]);
//    Mark as refund requested
//    Email user
        }
        return redirect("/");
    }

    /*
     * Entry is loaded based on entry ID and token emailed in link on entry confirmation
     */

    public function userupdate(Request $request)
    {
        $id = $request->id;

        $attributes = $request->validate([
            'class' => 'required',
            'course' => 'required',
            'make' => 'required',
            'type' => 'required',
        ]);
        $newToken = bin2hex(random_bytes(16));
        $entry = Entry::find($id);
        $entry->class = $request->class;
        $entry->course = $request->course;
        $entry->make = $request->make;
        $entry->type = $request->type;
        $entry->size = $request->size;
        $entry->token = $newToken;
        $entry->save();

        $this->emailConfirmation($id, $newToken);
        return redirect("/");
    }

//  Not sure if currently used

    public function emailConfirmation($id, $newToken)
    {
        $entry = DB::table('entries')->where('id', $id)->first();
        $email = $entry->email;
        $token = $entry->token;
        $bcc = 'monster@trialmonster.uk';
        Mail::to($email)
            ->bcc($bcc)
            ->send(new EntryChanged($entry, $newToken));
        info("Entry changed: $entry->id");
        return redirect("/");
    }

    public function useredit(Request $request)
    {
//        dd($request->all());
        $token = $request->token;
        $id = $request->id;

        $entry = Entry::get()
            ->where('id', $id)
            ->where('status', 1)
            ->where('token', $token)->first();


        if ($entry == null) {
            return view('entries.expiredLink');
        }

        $trial = Trial::select('date')
            ->where('id', $entry->trial_id)
            ->get();

        $trial_date = date_create($trial[0]->date);
        $today = date_create(date('Y-m-d'));

//      In time / Too late to edit entry
        if ($trial_date > $today) {
            $trial = Trial::findOrFail($entry->trial_id);
//            dd($trial);
            return view('entries.useredit', ['entry' => $entry]);
        } else {
            return view('entries.noChanges');
        }
    }

//  Store from Register page
//    Store first record then pass email and trial_id to create_another view

    public function checkout(Request $request)
    {
        $user_id = Auth::user()->id;
        $trial_id = $request->trial_id;

        $trial = Trial::findorfail($trial_id);

        $entries = Entry::all()->where('created_by', $user_id)
            ->where('trial_id', $trial_id)
            ->where('status', 0)
            ->sortBy('name');

        return view('entries.checkout', ['entries' => $entries, 'trial' => $trial, 'trial_id' => $trial_id]);
    }

    public function store(Request $request)
    {
//        $trial_id = session('trial_id');
        $trial_id = $request->trial_id;
        $trial = Trial::findOrFail($trial_id);

//        Get product and price data
//        Get product/price IDs
        $youthProductID = DB::table('products')
            ->where('trial_id', $request->trial_id)
            ->where('isYouth', true)
            ->where('product_category', 'entry fee')
            ->value('stripe_product_id');


        $adultProductID = DB::table('products')
            ->where('trial_id', $request->trial_id)
            ->where('isYouth', false)
            ->where('product_category', 'entry fee')
            ->value('stripe_product_id');


        $youthPriceID = DB::table('prices')
            ->where('stripe_product_id', $youthProductID)
            ->value('stripe_price_id');

        $adultPriceID = DB::table('prices')
            ->where('stripe_product_id', $adultProductID)
            ->value('stripe_price_id');

//        Check for entry limit
        $hasEntryLimit = $trial->hasEntryLimit;


        $status = 0;

        if ($hasEntryLimit) {
//        Check for full entry list
            $entryLimit = $trial->entryLimit;
            $numEntries = Entry::where('trial_id', $trial_id)
                ->whereIn('status', [1, 4, 7, 8, 9])
                ->count();
            Info("EntryController: line 444: NumEntries: $numEntries");
//        Check for number of entries left
//        If 5, then email registered but not paid
            $spaces = $entryLimit - $numEntries;

//        If no spaces, then change status 0 to status 5 - Reserve List
            if ($spaces <= 0) {
//            TrialFull::dispatch($trial_id, $entryLimit, $numEntries);
                $status = 5;
            }
        }

        $trial_date = date_create($trial->date);

        $IPaddress = $request->ip();
        $request->session()->put('trial_id', $request->trial_id);

//        Token added to emailed entry link
        $token = bin2hex(random_bytes(16));

        $attributes = $request->validate([
            'name' => ['required', 'min:5', 'max:255'],
            'trial_id' => 'required',
            'class' => 'required',
            'course' => 'required',
            'make' => 'required',
            'type' => 'required',
            'dob' => 'required',
        ]);

        $utilityController = new UtilityController();
        $attributes['name'] = $utilityController->nameize($request->name);
        $attributes['IPaddress'] = $IPaddress;
        $attributes['size'] = $request->size;
        $attributes['licence'] = $request->licence;
        $attributes['token'] = $token;
//        $attributes['accept'] = $accept;
        $attributes['status'] = $status;
        $attributes['created_by'] = Auth::user()->id;

        $birthDate = date_create($request->dob);

        $interval = $trial_date->diff($birthDate);
        $ageInYears = $interval->format('%y');

        if ($ageInYears < 18) {
            $attributes['isYouth'] = 1;
            $attributes['stripe_price_id'] = $youthPriceID;
            $attributes['stripe_product_id'] = $youthProductID;
        } else {
            $attributes['isYouth'] = 0;
            $attributes['stripe_price_id'] = $adultPriceID;
            $attributes['stripe_product_id'] = $adultProductID;
        }


        $attributes['dob'] = $request->dob;
        $entry = Entry::create($attributes);

//        $trial = Trial::findOrFail($attributes['trial_id']);

        if ($entry->status == 5) {
            $this->sendReserveEmail($entry, $trial);
        }

        $entryID = $entry->id;
        $attr['entryID'] = $entryID;
        $entries = Entry::all()
            ->where('trial_id', $trial_id)
            ->where('status', 0)
            ->where('created_by', $attributes['created_by']);


        $reserves = Entry::all()
            ->where('trial_id', $trial_id)
            ->whereIn('status', [4, 5])
            ->where('created_by', $attributes['created_by']);
        return view('entries.register', ['entries' => $entries, 'trial' => $trial, 'reserves' => $reserves]);
    }

    function sendReserveEmail(Entry $entry, Trial $trial)
    {
        $userID = $entry->created_by;
        $user = DB::table('users')->where('id', $userID)->first();
        $username = $user->name;
        $email = $user->email;

        $bcc = "monster@trialmonster.uk";

        Mail::to($email)
            ->bcc($bcc)
            ->send(new ReserveAdded($entry, $trial));
    }

    public function delete(Request $request)
    {
        $entry = Entry::findOrFail($request->id);
        $entry->status = 6;
        $entry->ridingNumber = 0;
        $entry->save();

        return redirect('entries/register/' . session('trial_id'));
    }

    public function list(Request $request)
    {
        $email = session('email');
        $trial_id = $request->input('trial_id');
        $trial = Trial::findOrFail($trial_id);
        $phone = session('phone');
        $entries = Entry::all()->where('email', $email)->where('trial_id', $trial_id)->where('phone', $phone)->where('paid', 0);
//        dd($entries);
        return view('entries.userdata', ['entries' => $entries, 'trial_id' => $trial_id, 'email' => $email, 'phone' => $phone, 'trial' => $trial]);
    }

    public function adminEntries(Request $request)
    {
        $email = session('email');
        $trial_id = $request->input('trial_id');
        $trial = Trial::findOrFail($trial_id);
        $phone = session('phone');
        $entries = Entry::all()->where('email', $email)->where('trial_id', $trial_id)->where('phone', $phone)->where('paid', 0);
//        dd($entries);
        return view('entries.adminEntries', ['entries' => $entries, 'trial_id' => $trial_id, 'email' => $email, 'phone' => $phone, 'trial' => $trial]);
    }

    public function adminEdit($id)
    {
        $entry = Entry::findOrFail($id);
        $trial = Trial::findOrFail($entry->trial_id);
        return view('entries.adminEdit', ['entry' => $entry, 'trial' => $trial]);
    }

    public function adminCancel($id)
    {
        $entry = Entry::findOrFail($id);
        $trial_id = $entry->trial_id;
        $entry->status = 6;
        $entry->ridingNumber = 0;
        $entry->save();
        return redirect("/trials/adminEntryList/{$trial_id}");
    }

    public function edit(Request $request)
    {
        $entry = Entry::findorfail($request->entry);
        $trialid = session('trial_id');
        $trial = Trial::findorfail($trialid);
        return view('entries.edit', ['entry' => $entry, 'trial' => $trial]);
    }

    public function editRidingNumbers(Request $request)
    {
        $trialid = $request->id;

        $entries = DB::table('entries')
            ->where('trial_id', $trialid)
            ->whereIn('status', [0, 1, 4, 5, 7, 8, 9, 10])
            ->orderBy('course')
            ->orderBy('ridingNumber')
            ->orderBy('id')
            ->get();


        $numSections = DB::table('trials')
            ->where('id', $trialid)
            ->select('numSections')
            ->first();

        return view('entries.editRidingNumbers', ['entries' => $entries, 'trialid' => $trialid, 'numSections' => $numSections->numSections]);
    }

    public function saveRidingNumbers(Request $request)
    {
        $trialID = $request->trialID;

        $numbers = $request->input('ridingNumber');
        $startsAts = $request->input('startsAt');
        $entryIDs = $request->input('entryID');
        for ($i = 0; $i < count($numbers); $i++) {
            $entryID = $entryIDs[$i];
            $number = $numbers[$i];
            $startsAt = $startsAts[$i];

            DB::table('entries')
                ->where('id', $entryID)
                ->update(['ridingNumber' => $number, 'startsAt' => $startsAt]);
        }
        return redirect("/trials/adminEntryList/{$trialID}");
    }

    public function printSignOnSheets($id)
    {
//      Generate QR codes, save in images/qr
        $this->generate($id);

//      Get entry list
        $trialDetails = DB::table('trials')->where('id', $id)->first();
        $venueID = $trialDetails->venueID;
        $venue = DB::table('venues')->where('id', $venueID)->first();
        $venueName = $venue->name;

        $rawDate = new DateTime($trialDetails->date);
        $date = date_format($rawDate, "jS M, Y");
        $club = $trialDetails->club;

        $startList = DB::table('entries')
            ->where('trial_id', $trialDetails->id)
            ->whereIn('status', [0, 1, 4, 5, 7, 8, 9, 10])
            ->orderBy('name')
            ->get();
        if (sizeof($startList) == 0) {
            exit("No entries to print");
        }

        $ridingGroups = DB::table('entries')
            ->select(DB::raw('startsAt, GROUP_CONCAT(name ORDER BY name) AS names'))
            ->where('trial_id', $trialDetails->id)
            ->whereIn('status', [0, 1, 4, 5, 7, 8, 9, 10])
            ->groupBy('startsAt')
            ->get();


        $filename = "Sign-on $trialDetails->name.pdf";


        MYPDF::SetCreator('TM UK');

        MYPDF::SetAuthor('TrialMonster.uk');
        MYPDF::SetTitle('Sign-on sheet');
        MYPDF::SetImageScale(PDF_IMAGE_SCALE_RATIO);
        MYPDF::AddPage();
        $bMargin = MYPDF::GetBreakMargin();
        MYPDF::SetHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        MYPDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        MYPDF::SetHeaderMargin(0);
        MYPDF::SetFooterMargin(0);
        MYPDF::SetPrintFooter(false);
        MYPDF::SetAutoPageBreak(TRUE, 0);

        MYPDF::SetMargins(0, 0, 0);


        $authority = $trialDetails->authority;

//        Add template imageand setup variables
        switch ($authority) {
            case 'AMCA':
                $img_file = storage_path('app/public/images/amca.jpg');
                $topMargin = 97;
                $bottomMargin = 24;
                $rowHeight = 7.95;
                $numberIndent = 15;
                $nameIndent = 20;
                $idIndent = 132;
                $idWidth = 19;
                $classIndent = 177;
                $parentIndent = 85;
                $numberWidth = 3;
                $nameWidth = 46;
                $linesPerPage = 22;
                MYPDF::setLeftMargin(26);
                MYPDF::setY(75);
                MYPDF::Cell(61, 0, $club, 0, 0, 'L', false, null, 0, false, 'C' . 'M');
                MYPDF::Cell(53, 0, $date, 0, 0, 'L', false, null, 0, false, 'C' . 'M');
                MYPDF::Cell(0, 0, $venueName, 0, 0, 'L', false, null, 0, false, 'C' . 'M');
                break;
            case 'ACU' :
                $img_file = storage_path('app/public/images/ACU_2025.png');
                $topMargin = 158;
                $bottomMargin = 10;
                $rowHeight = 6.65;
                $numberIndent = 15;
                $nameIndent = 18;
                $parentIndent = 68;
                $parentSignIndent = 103;
                $idIndent = 132;
                $idWidth = 19;
                $classIndent = 177;
                $numberWidth = 3;
                $nameWidth = 33;
                $linesPerPage = 19;
                MYPDF::setLeftMargin(21);
                MYPDF::setY(38);
                MYPDF::Cell(0, 0, $trialDetails->name, 0, 1, 'L', false, null, 0, false, 'C' . 'M');
                MYPDF::setY(46);
                MYPDF::Cell(0, 0, $venueName, 0, 1, 'L', false, null, 0, false, 'C' . 'M');
                MYPDF::setY(54);
                MYPDF::setLeftMargin(29);
                MYPDF::Cell(100, 0, $club, 0, 0, 'L', false, null, 0, false, 'C' . 'M');
                MYPDF::Cell(0, 0, $date, 0, 0, 'L', false, null, 0, false, 'C' . 'M');
                MYPDF::setY(62);
                MYPDF::setLeftMargin(29);
                MYPDF::Cell(0, 0, $trialDetails->permit, 0, 0, 'L', false, null, 0, false, 'C' . 'M');
                break;

            default:
                $img_file = storage_path('app/public/images/grid.jpg');
                $topMargin = 10;
                $bottomMargin = 10;
                $rowHeight = 6.65;
                $numberIndent = 15;
                $nameIndent = 18;
                $idIndent = 132;
                $idWidth = 19;
                $classIndent = 177;
                $numberWidth = 3;
                $nameWidth = 33;
                $linesPerPage = 20;
                break;
        }
//        Add background image
        MYPDF::Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);

        MYPDF::SetPageMark();
        MYPDF::SetFontSize(10, true);
        MYPDF::SetTopMargin($topMargin);
        MYPDF::SetAutoPageBreak(false, $bottomMargin);

//        MYPDF::Write(0, "What's next?");
        $index = 0;

        $lineNumber = 1;
        if (sizeof($startList) > 0) {
            foreach ($startList as $entry) {
//            if($trialDetails-> == 5) {
//                $number = $rrCodes[$entry[0]];
//            } else {
                $number = $entry->ridingNumber;
//            }
                if ($entry->isYouth == 1) {
                    $name = $entry->name . "*";
                } else {
                    $name = $entry->name;
                }
                $name = ucwords(strtolower($name), " \t\r\n\f\v'");
                $status = $entry->status;
                if ($status == 0 or $status == 4 or $status == 5 or $status == 7 or $status == 10) {
                    $name = "To pay - " . $name;
                }
                $id = $entry->licence;
                $class = $entry->class;

                if ($class == "Adult") {
                    $class = "";
                }

                // Number cell
                if ($number != 0) {
                    MYPDF::setX($numberIndent);
                    MYPDF::Cell($numberWidth, $rowHeight, $number, 0, 0, 'R', false, null, 0, false, 'C' . 'M');
                }
                // AMCA
                if ($authority == 'AMCA') {
                    // Name cell
                    MYPDF::setX($nameIndent);
                    MYPDF::Cell($nameWidth, $rowHeight, $name, 0, 0, 'L', false, null, 1, false, 'C' . 'M');

                    // ID cell
                    if ($entry->isYouth != 0) {
                        MYPDF::setX($parentIndent);
                        MYPDF::Cell($idWidth, $rowHeight, "*", 0, 0, 'R', false, null, 0, false, 'C' . 'M');
                    }
                    // ID cell
                    if ($id != 0) {
                        MYPDF::setX($idIndent);
                        MYPDF::Cell($idWidth, $rowHeight, $id, 0, 0, 'R', false, null, 0, false, 'C' . 'M');
                    }
                    // Class cell
                    MYPDF::setX($classIndent);
                    MYPDF::Cell(17, $rowHeight, $class, 0, 1, 'L', false, null, 1, 0, 'C' . 'M');
                } // ACU
                else if ($authority == 'ACU') {
                    // Name cell
                    MYPDF::setX($nameIndent);
                    MYPDF::Cell($nameWidth, $rowHeight, $name, 0, 1, 'L', false, null, 1, false, 'C' . 'M');
                    if ($entry->isYouth != 0) {
                        MYPDF::setX($parentIndent);
                        MYPDF::Cell($idWidth, $rowHeight, "*", 0, 0, 'R', false, null, 0, false, 'C' . 'M');
                        MYPDF::setX($parentSignIndent);
                        MYPDF::Cell($idWidth, $rowHeight, "*", 0, 0, 'R', false, null, 0, false, 'C' . 'M');
                    }
                }

                // ID cell


                if ($lineNumber % $linesPerPage == 0) {
                    MYPDF::addPage();
                    MYPDF::Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
                    switch ($trialDetails->authority) {
                        case 'ACU':
                            MYPDF::setLeftMargin(21);
                            MYPDF::setY(38);
                            MYPDF::Cell(0, 0, $trialDetails->name, 0, 1, 'L', false, null, 0, false, 'C' . 'M');
                            MYPDF::setY(46);
                            MYPDF::Cell(0, 0, $venueName, 0, 1, 'L', false, null, 0, false, 'C' . 'M');
                            MYPDF::setY(54);
                            MYPDF::setLeftMargin(29);
                            MYPDF::Cell(100, 0, $club, 0, 0, 'L', false, null, 0, false, 'C' . 'M');
                            MYPDF::Cell(0, 0, $date, 0, 0, 'L', false, null, 0, false, 'C' . 'M');
                            MYPDF::setY(62);
                            MYPDF::setLeftMargin(29);
                            MYPDF::Cell(0, 0, $trialDetails->permit, 0, 0, 'L', false, null, 0, false, 'C' . 'M');
                            break;

                        case 'AMCA':
                            MYPDF::setLeftMargin(26);
                            MYPDF::setY(75);
                            MYPDF::Cell(61, 0, $club, 0, 0, 'L', false, null, 0, false, 'C' . 'M');
                            MYPDF::Cell(53, 0, $date, 0, 0, 'L', false, null, 0, false, 'C' . 'M');
                            MYPDF::Cell(0, 0, $venueName, 0, 0, 'L', false, null, 0, false, 'C' . 'M');
                            break;
                    }

                    MYPDF::setY($topMargin);
                }
                $lineNumber++;

            }
        }

        MYPDF::addPage();
        MYPDF::SetFontSize(18);
        MYPDF::Text(0, 10, "Riding groups", 0, false, true, 0, 1, 'C');

        MYPDF::SetFontSize(12);

        MYPDF::SetMargins(10, 0);
        $y = 3 + MYPDF::getY();
        MYPDF::setY($y);
        MYPDF::Write(0, "Would riders kindly start at the following sections.", '', false, 'C', true);
        for ($i = 0; $i < count($ridingGroups); $i++) {
            $y = 3 + MYPDF::getY();
            MYPDF::setY($y);

            $group = $ridingGroups[$i];
            $startsAt = $group->startsAt;
            $riders = str_replace(',', ', ', $group->names);

            if ($startsAt) {
                MYPDF::Write(0, "Section $startsAt", '', false, '', true);
                MYPDF::Write(0, $riders, '', false, '', true);

            } else {
//                MYPDF::Write(0, "Unallocated", '', false, '', true);
//                MYPDF::Write(0, $riders, '', false, '', true);
            }
        }
        $y = 3 + MYPDF::getY();
        MYPDF::setY($y);
        MYPDF::SetFontSize(18);
        MYPDF::Write(0, "Not listed?", '', false, 'C', true);
        MYPDF::SetFontSize(12);
        $y = 3 + MYPDF::getY();
        MYPDF::setY($y);
        MYPDF::Write(0, "If you have entered and your name does not appear in the list above, you will be allocated a section at random to try and spread the entry around the course. ", '', false, '', true);
        $y = 3 + MYPDF::getY();
        MYPDF::setY($y);
        MYPDF::Write(0, "If you wish to be added to a particular group, please reply to this email NOT LESS THAN 24 HOURS BEFORE the trial.", '', false, '', true);

        MYPDF::addPage();
        MYPDF::SetFontSize(18);
        MYPDF::Text(0, 10, "Registration", 0, false, true, 0, 0, 'C');
// References storage/app/public/images
        $tid = $trialDetails->id;

        $qr1 = "images/qr/data_$tid.png";
        $qr2 = "images/qr/programme_$tid.png";


        $img_file = public_path($qr1);
        MYPDF::Image($img_file, 40, 20, 130, '', '', '', '', false, 300, '', false, false, 0);

        MYPDF::Text(30, 170, '1 - Scan QR code on your phone');
        MYPDF::Text(30, 180, '2 - Complete details, click Register');
        MYPDF::Text(30, 190, '3 - Join queue. Correct entry fee(s), please');
        MYPDF::Text(30, 200, '4 - Complete Sign-on sheet');
        MYPDF::Text(30, 210, '5 - Enjoy your ride');


        MYPDF::addPage();
        MYPDF::SetFontSize(18);
        MYPDF::Text(0, 10, "Entry List", 0, false, true, 0, 0, 'C');

        $img_file = public_path($qr2);
        MYPDF::Image($img_file, 40, 20, 130, '', '', '', '', false, 300, '', false, false, 0);

        MYPDF::SetY(170);
        MYPDF::SetX(0);
        MYPDF::SetLeftMargin(20);
        MYPDF::SetRightMargin(20);


        MYPDF::MultiCell(0, 0, 'Scan the QR code on your phone. Entries are correct at the time of compilation.', 0, 'L', false);
        MYPDF::SetY(190);
        MYPDF::MultiCell(0, 0, "Although every effort is made to provide accurate and up-to-date information, late changes may sometimes be unavoidable due to entrants' changes of course or class.", 0, 'L', false);
//        dd(public_path($filename));
        $filename = $this->filter_filename($filename);
        MYPDF::Close();
        MYPDF::Output(public_path('pdf/signon/' . $filename), 'F');
        MYPDF::reset();
        return response()->download('pdf/signon/' . $filename);
    }

    public function generate($id)
    {
        $liveSite = config('app.url');
        $data = "$liveSite/otd/$id";
        $data2 = "$liveSite/trial/programme/$id";
        $trial = DB::table('trials')
            ->where('id', $id)
            ->first();

        if ($trial == null) {
            abort(404);
        }

        $name = $trial->name;

        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 600,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
//            logoPath: __DIR__.'/assets/bender.png',
//            logoResizeToWidth: 50,
//            logoPunchoutBackground: true,
            labelText: $name,
            labelFont: new OpenSans(24),
            labelAlignment: LabelAlignment::Center
        );


        $result = $builder->build();
        $filename = "data_$id.png";
        $dir = 'images/qr/' . $filename;
        $result->saveToFile($dir);


        $name = $trial->name;
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $data2,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 600,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
//            logoPath: __DIR__.'/assets/bender.png',
//            logoResizeToWidth: 50,
//            logoPunchoutBackground: true,
            labelText: $name,
            labelFont: new OpenSans(24),
            labelAlignment: LabelAlignment::Center
        );
        $result = $builder->build();
        $filename = "programme_$id.png";
        $dir = 'images/qr/' . $filename;
        $result->saveToFile($dir);


//        return redirect("/trials/adminEntryList/$id");
    }

    function filter_filename($name)
    {
        // remove illegal file system characters https://en.wikipedia.org/wiki/Filename#Reserved_characters_and_words
        $name = str_replace(array_merge(
            array_map('chr', range(0, 31)),
            array('<', '>', ':', '"', '/', '\\', '|', '?', '*')
        ), '', $name);
        // maximise filename length to 255 bytes http://serverfault.com/a/9548/44086
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $name = mb_strcut(pathinfo($name, PATHINFO_FILENAME), 0, 255 - ($ext ? strlen($ext) + 1 : 0), mb_detect_encoding($name)) . ($ext ? '.' . $ext : '');
        return $name;
    }

    public function storeMultiple(Request $request)
    {
//        dd(request()->all());
        $trial_id = $request->input('trialID');
        $trial = Trial::findOrFail($trial_id);
        $trial_date = date_create($trial->date);
        $ridingNumbers = $request->input('ridingNumber', null);
        $startsAt = $request->input('startsAt');
        $names = $request->input('name');
        $makes = $request->input('make');
        $sizes = $request->input('size');
        $types = $request->input('type');
        $courses = $request->input('course');
        $classs = $request->input('class');
        $birthDates = $request->input('dob');
        $statuss = $request->input('status');


        $utilityController = new UtilityController();

        for ($i = 0; $i < sizeof($names); $i++) {
            if (isset($names[$i]) && $names[$i] != "") {

                if ($classs[$i] != "Youth") {
                    $isYouth = false;
                } else {
                    $isYouth = true;
                }

                DB::table('entries')->insert([
                    'name' => $utilityController->nameize($names[$i]),
                    'ridingNumber' => $ridingNumbers[$i],
                    'make' => $makes[$i],
                    'startsAt' => $startsAt[$i],
                    'size' => $sizes[$i],
                    'type' => $types[$i],
                    'course' => $courses[$i],
                    'class' => $classs[$i],
                    'isYouth' => $isYouth,
                    'status' => $statuss[$i],
                    'created_by' => Auth::user()->id,
                    'ipaddress' => $request->ip(),
                    'created_at' => date('Y-m-d H:i:s'),
//                    'dob' => $birthDates[$i],
                    'trial_id' => $trial_id,
                ]);
            }
        }
        return redirect("/trials/adminEntryList/{$trial_id}");
    }

    public function otdCreate(Request $request)
    {
        $trial_id = $request->trial_id;
        $trial = Trial::findOrFail($trial_id);
        $trial_date = date_create($trial->date);

        $IPaddress = $request->ip();
//        $request->session()->put('trial_id', $request->trial_id);
//        $accept = session('accept');

//        Get product/price IDs
        $youthProductID = "OTD ";
        $adultProductID = "OTD";
        $youthPriceID = "OTD";
        $adultPriceID = "OTD";

        $attributes = $request->validate([
            'name' => ['required', 'min:5', 'max:255'],
            'trial_id' => 'required',
            'class' => 'required',
            'course' => 'required',
            'make' => 'required',
            'type' => 'required',
            'dob' => 'required',
        ]);

        $utilityController = new UtilityController();

        $attributes['name'] = $utilityController->nameize($request->name);
        $attributes['IPaddress'] = $IPaddress;
        $attributes['size'] = $request->size;
        $attributes['licence'] = $request->licence;
        $attributes['ridingNumber'] = $request->number;
        $attributes['token'] = "OTD";
        $attributes['accept'] = false;
        $attributes['created_by'] = 0;

        $birthDate = date_create($request->dob);

        $interval = $trial_date->diff($birthDate);

//        Calculation for yout goes here
        if ($interval->y < 18) {
            $attributes['isYouth'] = 1;
            $attributes['stripe_price_id'] = "Youth EoD";
            $attributes['stripe_product_id'] = "Youth EoD";
        } else {
            $attributes['isYouth'] = 0;
            $attributes['stripe_price_id'] = "Adult EoD";
            $attributes['stripe_product_id'] = "Adult EoD";
        }
        $attributes['status'] = 7;
        $attributes['dob'] = $request->dob;
        $entry = Entry::create($attributes);
        $url = "/otd/confirm/$entry->id";

        return redirect($url);
    }

    public function otd_form($id)
    {
        $trial = DB::table('trials')
            ->where('id', $id)
            ->whereTodayOrAfter('date')
            ->first();

        if ($trial == null) {
            abort(404);
        }
        return view('entries.otd_entry', ['trial' => $trial]);
    }

    public function otdSaveNumbers(Request $request)
    {
//        dd($request->all());
        $trialid = $request->trialid;

        $numEntries = sizeof($request->ridingNumber);

        for ($i = 0; $i < $numEntries; $i++) {
            $ridingNumber = $request->ridingNumber[$i];
            if ($ridingNumber != null) {
                $entryID = $request->entryID[$i];
                DB::table('entries')->where('id', $entryID)->update(['ridingNumber' => $ridingNumber, 'status' => 8, 'updated_at' => NOW()]);
            }
        }

        return redirect("/trials/adminEntryList/$trialid");
    }

    public function otd_confirm(Request $request)
    {
        $entryid = $request->entryid;
        $entry = DB::table('entries')->where('id', $entryid)->first();

        $trialid = $entry->trial_id;
        $isYouth = $entry->isYouth;
        $product_code = DB::table('products')
            ->select('stripe_product_id')
            ->where('trial_id', $trialid)
            ->where('product_category', 'entry fee')
            ->where('isYouth', $isYouth)
            ->first();


        $code = $product_code->stripe_product_id;
        $price = DB::table('prices')->select('stripe_price')
            ->where('stripe_product_id', $code)
            ->first();
        $cost = $price->stripe_price / 100;
        return view('entries.otd_confirm', ['trialid' => $trialid, 'cost' => $cost]);
    }

    public function showRidingGroups($id)
    {

        $trial = DB::table('trials')
            ->where('id', $id)
            ->first();

        $ridingGroups = DB::table('entries')
            ->select(DB::raw('startsAt, GROUP_CONCAT(name ORDER BY name) AS names, GROUP_CONCAT(ridingNumber ORDER BY name) AS numbers, GROUP_CONCAT(Concat(ridingNumber, \' \', name) ORDER BY name SEPARATOR \', \') AS entries'))
            ->where('trial_id', $id)
            ->whereIn('status', [0, 1, 4, 5, 7, 8, 9, 10])
            ->groupBy('startsAt')
            ->get();

//        dd($ridingGroups);
        return view('entries.showRidingGroups', ['ridingGroups' => $ridingGroups, 'trial' => $trial]);
    }
}

class MYPDF extends PDF
{
    //Page header
    public function Header()
    {
        // get the current page break margin

        info("Header \App\Http\Controllers\MYPDF");
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->getAutoPageBreak();
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        $img_file = storage_path('app/public/images/acu.jpg');
//        $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
}
