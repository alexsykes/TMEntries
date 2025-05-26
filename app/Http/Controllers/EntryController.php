<?php

namespace App\Http\Controllers;

use App\Mail\EntryChanged;
use App\Models\Entry;
use App\Models\Price;
use App\Models\Trial;
use App\Rules\NoDuplicates;
use Auth;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
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

        return view('entries.register', ['entries' => $entries, 'trial' => $trial]);
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
            'dob' => Rule::requiredIf(isset($request->isYouth)),
        ]);


        $entry = Entry::find($request->id);
        $trial_id = $entry->trial_id;

//        Get product/price IDs
        $youthProductID = DB::table('products')
            ->where('trial_id', $trial_id)
            ->where('isYouth', true)
            ->value('stripe_product_id');


        $adultProductID = DB::table('products')
            ->where('trial_id', $trial_id)
            ->where('isYouth', false)
            ->value('stripe_product_id');


        $youthPriceID = DB::table('prices')
            ->where('stripe_product_id', $youthProductID)
            ->value('stripe_price_id');

        $adultPriceID = DB::table('prices')
            ->where('stripe_product_id', $adultProductID)
            ->value('stripe_price_id');

//        dd($trial_id, $adultProductID, $adultPriceID, $youthProductID, $youthPriceID);
        $entry->name = $this->nameize($request->name);
        $entry->class = $request->class;
        $entry->course = $request->course;
        $entry->licence = $request->licence;

        $entry->make = $request->make;
        $entry->type = $request->type;

        $entry->size = $request->size;
//        $entry->accept = $accept;
        $entry->dob = $request->dob;

        if (isset($request->isYouth)) {
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
        $entry->updated_at = date('Y-m-d H:i:s');
        $entry->save();

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

    public function withdraw(Request $request)
    {
        $id = $request->id;
        $entry = Entry::where('id', $id)->where('status', 1)->first();

        if ($entry) {
            $pi = $entry->stripe_payment_intent;
            $price = Price::where('stripe_price_id', $entry->stripe_price_id)->first();
            $cost = $price->stripe_price;

//            $entry->status = 2; // Mark as withdrawn, having paid, waiting for refund
//            $entry->token = $token = bin2hex(random_bytes(16));
            $entry->save();

//        Request request
            require('../vendor/autoload.php');
            require('../vendor/stripe/stripe-php/lib/StripeClient.php');
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

    /*   User updates entry - from email
        Show screen for entry with form for updated fields
        Limited changes can be made
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

    /*
     * Email confirmation of entry changes
     */
    public function emailConfirmation($id, $newToken)
    {
        $entry = DB::table('entries')->where('id', $id)->first();
        $email = $entry->email;
        $token = $entry->token;
        $bcc = 'admin@trialmonster.uk';
        Mail::to($email)
            ->bcc($bcc)
            ->send(new EntryChanged($entry, $newToken));
        info("Entry changed: $entry->id");
        return redirect("/");
    }

    /*
     * Entry is loaded based on entry ID and token emailed in link on entry confirmation
     */
    public function useredit(Request $request)
    {
//        dd($request->all());
        $token = $request->token;
        $id = $request->id;

        $entry = Entry::get()
            ->where('id', $id)
            ->where('status', 1)
            ->where('token', $token)->first();


        if($entry == null) {
            return view('entries.expiredLink');
        }

        $trial = Trial::select('date')
            ->where('id', $entry->trial_id)
            ->get();

        $trial_date =   date_create($trial[0]->date);
        $today = date_create(date('Y-m-d'));

//      In time / Too late to edit entry
        if($trial_date > $today) {
            return view('entries.useredit', ['entry' => $entry]);
        } else {
            return view('entries.noChanges');
        }
    }

//  Not sure if currently used
    public function create($id)
    {
        session(['trial_id' => $id]);
        $trial = Trial::findOrFail($id);
        return view('entries.get_user_details', ['trial' => $trial, 'entry' => new Entry()]);
    }

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

//  Store from Register page
//    Store first record then pass email and trial_id to create_another view
    public function store(Request $request)
    {

        $trial_id = session('trial_id');

        $IPaddress = $request->ip();
        $request->session()->put('trial_id', $request->trial_id);
        $accept = session('accept');

//        Get product/price IDs
        $youthProductID = DB::table('products')
            ->where('trial_id', $request->trial_id)
            ->where('isYouth', true)
            ->value('stripe_product_id');


        $adultProductID = DB::table('products')
            ->where('trial_id', $request->trial_id)
            ->where('isYouth', false)
            ->value('stripe_product_id');


        $youthPriceID = DB::table('prices')
            ->where('stripe_product_id', $youthProductID)
            ->value('stripe_price_id');

        $adultPriceID = DB::table('prices')
            ->where('stripe_product_id', $adultProductID)
            ->value('stripe_price_id');

        $token = bin2hex(random_bytes(16));

        $attributes = $request->validate([
            'name' => ['required', 'min:5', 'max:255'],
            'trial_id' => 'required',
            'class' => 'required',
            'course' => 'required',
            'make' => 'required',
            'type' => 'required',
            'dob' => Rule::requiredIf(isset($request->isYouth)),
        ]);

        $attributes['name'] = $this->nameize($request->name);
        $attributes['IPaddress'] = $IPaddress;
        $attributes['size'] = $request->size;
        $attributes['licence'] = $request->licence;
        $attributes['token'] = $token;
        $attributes['accept'] = $accept;
        $attributes['created_by'] = Auth::user()->id;

        if (isset($request->isYouth)) {
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

        $entryID = $entry->id;
        $attr['entryID'] = $entryID;
        $trial = Trial::findOrFail($attributes['trial_id']);
        $entries = Entry::all()
            ->where('trial_id', $trial_id)
            ->where('status', 0)
            ->where('created_by', $attributes['created_by']);

//        $entries = Entry::all()->where('IPaddress', $IPaddress)->where('trial_id', session('trial_id'))->where('email', $attributes['email']);
        return view('entries.register', ['entries' => $entries, 'trial' => $trial]);
    }

    public function delete(Request $request)
    {
        Entry::destroy($request->id);
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
            ->whereIn('status', [1, 4, 5, 7, 8, 9])
            ->orderBy('course')
            ->orderBy('class')
            ->orderBy('id')
            ->get();


        return view('entries.editRidingNumbers', ['entries' => $entries, 'trialid' => $trialid]);

    }

    public function saveRidingNumbers(Request $request)
    {
        $trialID = $request->trialID;


        $numbers = $request->input('ridingNumber');


        $entryIDs = $request->input('entryID');
        for ($i = 0; $i < count($numbers); $i++) {
            $entryID = $entryIDs[$i];
            $number = $numbers[$i];

            DB::table('entries')
                ->where('id', $entryID)
                ->update(['ridingNumber' => $number]);
        }
        return redirect("/trials/adminEntryList/{$trialID}");
    }

    public function printSignOnSheets($id)
    {
//        $id = 119;
        $trialDetails = DB::table('trials')->where('id', $id)->first();
        $venueID = $trialDetails->venueID;
        $venue = DB::table('venues')->where('id', $venueID)->first();
        $venueName = $venue->name;

        $rawDate = new DateTime($trialDetails->date);
        $date  = date_format($rawDate, "jS M, Y");
        $club = $trialDetails->club;

        $startList = DB::table('entries')
            ->where('trial_id', $trialDetails->id)
            ->whereIn('status', [0, 1, 4, 5, 7, 8, 9])
            ->orderBy('name')
            ->get();
        if (sizeof($startList) == 0) {
            exit("No entries to print");
        }
        $filename = "Sign-on $trialDetails->name.pdf";

        MYPDF::SetCreator('TM UK');

        PDF::SetAuthor('TrialMonster.uk');
        PDF::SetTitle('Sign-on sheet');
        PDF::SetImageScale(PDF_IMAGE_SCALE_RATIO);
        PDF::AddPage();
        $bMargin = PDF::GetBreakMargin();
        PDF::SetHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        PDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        PDF::SetHeaderMargin(0);
        PDF::SetFooterMargin(0);
        PDF::SetPrintFooter(false);
        PDF::SetAutoPageBreak(TRUE, 0);

        PDF::SetMargins(0, 0, 0);


        $authority = $trialDetails->authority;
//        info("Authority: $authority");
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
                $numberWidth = 3;
                $nameWidth = 46;
                $linesPerPage = 22;
                break;
            case 'ACU' :
                $img_file = storage_path('app/public/images/ACU_2025.png');
                $topMargin = 158;
                $bottomMargin = 10;
                $rowHeight = 6.65;
                $numberIndent = 15;
                $nameIndent = 18;
                $idIndent = 132;
                $idWidth = 19;
                $classIndent = 177;
                $numberWidth = 3;
                $nameWidth = 33;
                $linesPerPage = 19;
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
        PDF::Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);

        // set the starting point for the page content
        //        Add trial details
        switch ($trialDetails->authority) {
            case 'ACU':
                PDF::setLeftMargin(21);
                PDF::setY(38);
                PDF::Cell(0, 0, $trialDetails->name, 0, 1, 'L', false, null, 0, false, 'C'. 'M');
                PDF::setY(46);
                PDF::Cell(0, 0, $venueName, 0, 1, 'L', false, null, 0, false, 'C'. 'M');
                PDF::setY(54);
                PDF::setLeftMargin(29);
                PDF::Cell(100, 0, $club, 0, 0, 'L', false, null, 0, false, 'C'. 'M');
                PDF::Cell(0, 0, $date, 0, 0, 'L', false, null, 0, false, 'C'. 'M');
                PDF::setY(62);
                PDF::setLeftMargin(29);
                PDF::Cell(0, 0, $trialDetails->permit, 0, 0, 'L', false, null, 0, false, 'C'. 'M');
                break;

            case 'AMCA':
                PDF::setLeftMargin(26);
                PDF::setY(75);
                PDF::Cell(61, 0, $club, 0, 0, 'L', false, null, 0, false, 'C'. 'M');
                PDF::Cell(53, 0, $date, 0, 0, 'L', false, null, 0, false, 'C'. 'M');
                PDF::Cell(0, 0, $venueName, 0, 0, 'L', false, null, 0, false, 'C'. 'M');
                break;
        }

        PDF::SetPageMark();
        PDF::SetFontSize(10, true);
        PDF::SetTopMargin($topMargin);
        PDF::SetAutoPageBreak(false, $bottomMargin);

//        PDF::Write(0, "What's next?");
        $index = 0;



        $lineNumber = 1;
        if (sizeof($startList) > 0) {
            foreach ($startList as $entry) {
//            if($trialDetails-> == 5) {
//                $number = $rrCodes[$entry[0]];
//            } else {
                $number = $entry->ridingNumber;
//            }
                $name = ucwords(strtolower($entry->name), " \t\r\n\f\v'");
                $paid = $entry->status;
                if ($paid == 0 or $paid == 4 or $paid == 5 or $paid == 7) {
                    $name = "To pay - " . $name;
                }
                $id = $entry->licence;
                $class = $entry->class;

                if ($class == "Adult") {
                    $class = "";
                }

                // Number cell
                if ($number != 0) {
                    PDF::setX($numberIndent);
                    PDF::Cell($numberWidth, $rowHeight, $number, 0, 0, 'R', false, null, 0, false, 'C' . 'M');
                }
                // AMCA
                if ($authority == 'AMCA') {
                    // Name cell
                    PDF::setX($nameIndent);
                    PDF::Cell($nameWidth, $rowHeight, $name, 0, 0, 'L', false, null, 1, false, 'C' . 'M');

                    // ID cell
                    if ($id != 0) {
                        PDF::setX($idIndent);
                        PDF::Cell($idWidth, $rowHeight, $id, 0, 0, 'R', false, null, 0, false, 'C' . 'M');
                    }
                    // Class cell
                    PDF::setX($classIndent);
                    PDF::Cell(17, $rowHeight, $class, 0, 1, 'L', false, null, 1, 0, 'C' . 'M');
                } // ACU
                else if ($authority == 'ACU') {
                    // Name cell
                    PDF::setX($nameIndent);
                    PDF::Cell($nameWidth, $rowHeight, $name, 0, 1, 'L', false, null, 1, false, 'C' . 'M');
                }

                if ($lineNumber % $linesPerPage == 0) {
                    PDF::addPage();
                    PDF::Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
                    switch ($trialDetails->authority) {
                        case 'ACU':
                            PDF::setLeftMargin(21);
                            PDF::setY(38);
                            PDF::Cell(0, 0, $trialDetails->name, 0, 1, 'L', false, null, 0, false, 'C'. 'M');
                            PDF::setY(46);
                            PDF::Cell(0, 0, $venueName, 0, 1, 'L', false, null, 0, false, 'C'. 'M');
                            PDF::setY(54);
                            PDF::setLeftMargin(29);
                            PDF::Cell(100, 0, $club, 0, 0, 'L', false, null, 0, false, 'C'. 'M');
                            PDF::Cell(0, 0, $date, 0, 0, 'L', false, null, 0, false, 'C'. 'M');
                            PDF::setY(62);
                            PDF::setLeftMargin(29);
                            PDF::Cell(0, 0, $trialDetails->permit, 0, 0, 'L', false, null, 0, false, 'C'. 'M');
                            break;

                        case 'AMCA':
                            PDF::setLeftMargin(26);
                            PDF::setY(75);
                            PDF::Cell(61, 0, $club, 0, 0, 'L', false, null, 0, false, 'C'. 'M');
                            PDF::Cell(53, 0, $date, 0, 0, 'L', false, null, 0, false, 'C'. 'M');
                            PDF::Cell(0, 0, $venueName, 0, 0, 'L', false, null, 0, false, 'C'. 'M');
                            break;
                    }

                    PDF::setY($topMargin);
                }
                $lineNumber++;

            }
        }
        PDF::Close();
        PDF::Output(public_path($filename), 'F');
        PDF::reset();
        return response()->download($filename);
    }

    function nameize($str, $a_char = array("'", "-", " "))
    {
        //$str contains the complete raw name string
        //$a_char is an array containing the characters we use as separators for capitalization. If you don't pass anything, there are three in there as default.
        $string = strtolower($str);
        foreach ($a_char as $temp) {
            $pos = strpos($string, $temp);
            if ($pos) {
                //we are in the loop because we found one of the special characters in the array, so lets split it up into chunks and capitalize each one.
                $mend = '';
                $a_split = explode($temp, $string);
                foreach ($a_split as $temp2) {
                    //capitalize each portion of the string which was separated at a special character
                    $mend .= ucfirst($temp2) . $temp;
                }
                $string = substr($mend, 0, -1);
            }
        }
        return ucfirst($string);
    }


    public function storeMultiple(Request $request)
    {
//        dd(request()->all());
        $trial_id = $request->input('trialID');
        $ridingNumbers = $request->input('ridingNumber', null);
        $names = $request->input('name');
        $makes = $request->input('make');
        $sizes = $request->input('size');
        $types = $request->input('type');
        $courses = $request->input('course');
        $classs = $request->input('class');
        $isYouths = $request->input('isYouth');
        $statuss = $request->input('status');

        for($i = 0; $i < sizeof($names); $i++) {
            if(isset($names[$i]) && $names[$i] != "") {
            if(!isset($isYouths[$i])) {
                $isYouths[$i] = 0;
            }
                DB::table('entries')->insert([
                    'name' => $this->nameize($names[$i]),
                    'ridingNumber' => $ridingNumbers[$i],
                    'make' => $makes[$i],
                    'size' => $sizes[$i],
                    'type' => $types[$i],
                    'course' => $courses[$i],
                    'class' => $classs[$i],
                    'isYouth' => $isYouths[$i],
                    'status' => $statuss[$i],
                    'created_by' => Auth::user()->id,
                    'ipaddress' => $request->ip(),
                    'created_at' => date('Y-m-d H:i:s'),
                    'trial_id' => $trial_id,
                ]);
            }
        }

        return redirect("/trials/adminEntryList/{$trial_id}");
    }
}
class MYPDF extends PDF {
    //Page header
    public function Header() {
        // get the current page break margin

        info("Header \App\Http\Controllers\MYPDF");
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
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
