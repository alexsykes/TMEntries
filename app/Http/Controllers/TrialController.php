<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Entry;
use App\Models\Purchase;
use App\Models\Series;
use App\Models\Trial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use PDF;
use Stripe\StripeClient;

class TrialController extends Controller
{
    //
    public function details($trial_id)
    {

        $ip = request()->ip();
        Log::info("Trial detail trialID:$trial_id - IP: $ip");
        $gmap_key = config('gmap.gmap_key');
        $trial = Trial::findorfail($trial_id);

        if ($trial->published == 0) {
            abort(404);
        }

        if ($trial->isResultPublished == 1) {
            abort(404);
        }

        $seriesID = $trial->series_id;
        $series = Series::where('id', $seriesID)->first();
        $numEntries = Entry::all()
            ->where('trial_id', $trial_id)
            ->whereIn('status', [1, 2, 4, 5, 7, 8, 9])
            ->count();

        $venue = $trial->venue();
        $clubID = $trial->club_id;
        $clubData = Club::where('id', $clubID)->first();
//        dd($clubData);
        return view('trials.details', compact('trial_id', 'gmap_key', 'venue', 'trial', 'numEntries', 'series', 'clubData'));
    }

    public function info($id)
    {
        $entries = DB::table('entries')
            ->where('trial_id', $id)
            ->orderBy('name')
            ->get();

        $entryfees = DB::table('products')
            ->leftJoin('prices', 'products.stripe_product_id', '=', 'prices.stripe_product_id')
            ->where('trial_id', $id)
            ->where('products.product_category', 'entry fee')
            ->select('products.product_name', 'products.purchases', 'prices.stripe_price')
            ->get();


        $products = DB::table('products')
            ->where('products.trial_id', $id)
            ->whereNot('products.product_category', 'entry fee')
            ->orderBy('products.product_name', 'ASC')
            ->select('products.product_name', 'products.stripe_product_description')
            ->get();

        $sales = DB::table('products')->selectRaw('sum(tme_purchases.quantity)AS quantity')
            ->join('purchases', 'products.stripe_product_id', '=', 'purchases.stripe_product_id')
            ->where('products.trial_id', $id)
            ->groupBy('products.stripe_product_id')
            ->orderBy('products.product_name', 'ASC')
            ->get();

////        dd($products, $sales);
//        $productIDArray = array();
//
//        foreach ($products as $product) {
//            array_push($productIDArray, $product->stripe_product_id);
//        }
//
//        if (sizeof($productIDArray) > 0) {
////            foreach ($productIDs as $productID) {
//                $purchases = DB::table('purchases')
//                    ->selectRaw('stripe_product_id, GROUP_CONCAT(entryIDs) AS entryIDs, sum(quantity) AS quantity')
//                    ->whereIn('stripe_product_id', $productIDArray)
//                    ->groupBy('stripe_product_id')
//                    ->get();
////            }
//        }

        $trial = DB::table('trials')
            ->where('id', $id)
            ->first();

        $venue = DB::table('venues')
            ->where('id', $trial->venueID)
            ->first();

        return view('trials.info', ['entries' => $entries, 'trial' => $trial, 'venue' => $venue, 'entryfees' => $entryfees, 'products' => $products, 'sales' => $sales]);
    }

    public function showTrialList()
    {
        $trials = DB::table('trials')->where('published', 1)
            ->whereTodayOrAfter('date', '>', date('Y-m-d'))
            ->orderBy('date')
            ->get();
        return view('trials.trial_list', ['trials' => $trials]);
    }

    public function adminTrials()
    {
        $user = Auth::user();
        $userID = $user->id;
        $trials = Trial::all()
            ->where('created_by', $userID)
            ->sortByDesc('date');

        return view('trials.admin_trial_list', ['trials' => $trials]);
    }

    /**
     *
     *  First stage of new trial - present form
     *  Form submitted to trials/save
     *
     *
     * */

    public function add()
    {
        $user = Auth::user();

        $isClubAdmin = $user->isClubUser;
        if (!$isClubAdmin) {
            return redirect('home');
        }

        $clubID = $user->club_id;
        $club = Club::find($clubID);

        $series = Series::where('clubID', $clubID)
            ->orderBy('name')
            ->get();

        $prefix = config('database.connections.mysql.prefix');
        $venues = DB::select('select id, name from ' . $prefix . 'venues order by name');
        $authorities = array("ACU", "AMCA", "Other");
        $selection = array('Order of Payment', 'Ballot', 'Selection', 'Other');
        $scoring = array('Observer', 'App', 'Sequential', 'Punch cards', 'Other');
        $stopAllowed = array('Stop permitted', 'Non-stop');
        $entryRestrictions = array('Closed to club', 'Centre', 'Open', 'Other Restriction');

        return view('trials.add_trial_detail', [
            'venues' => $venues,
            'authorities' => $authorities,
            'selection' => $selection,
            'scoring' => $scoring,
            'stopAllowed' => $stopAllowed,
            'entryRestrictions' => $entryRestrictions,
            'club' => $club,
            'series' => $series,
        ]);
    }

    public function edit($id)
    {
        $prefix = config('database.connections.mysql.prefix');
        $venues = DB::select('select id, name from ' . $prefix . 'venues order by name');
        $authorities = array("ACU", "AMCA", "Other");
        $selection = array('Order of Payment', 'Ballot', 'Selection', 'Other');
        $scoring = array('Observer', 'App', 'Sequential', 'Punch cards', 'Other');
        $stopAllowed = array('Stop permitted', 'Non-stop');
        $entryRestrictions = array('Closed to club', 'Centre', 'Open', 'Other Restriction');

        $trial = Trial::find($id);
        return view('trials.edit', [
            'trial' => $trial,
            'venues' => $venues,
            'authorities' => $authorities,
            'selection' => $selection,
            'scoring' => $scoring,
            'stopAllowed' => $stopAllowed,
            'entryRestrictions' => $entryRestrictions,
        ]);
    }

    public function toggleVisibility($id)
    {
//        dd($id);
        $trial = Trial::findorfail($id);
        $published = $trial->published;
        $trial->published = !$published;
        $trial->save();


        $trials = DB::table('trials')
            ->orderBy('date', 'desc')
            ->get();

        return redirect('adminTrials')->with('trials', $trials);
    }

    /**
     * Handles different tasks related to saving trial information.
     *
     * Depending on the 'task' parameter in the request, this method processes
     * trial details, trial data, entry data, scoring data, registration data, or fee data.
     * Each task validates the required input fields and updates or creates a trial record
     * in the database accordingly.
     *
     * 'detail' -
     * Validates initial data then sets up an 'empty' trial in databse.
     *
     *
     * @return RedirectResponse
     */
    public function save()
    {
//        dump(request('entryMethod'));
        $user = Auth::user();


        $isClubAdmin = $user->isClubUser;
        if (!$isClubAdmin) {
            return redirect('home');
        }

        $task = request('task');
        $clubID = $user->club_id;
//        dump($task);

        switch ($task) {
            case 'detail':
                $attrs = request()->validate([
                    'permit' => 'required',
                    'name' => 'required',
                    'club' => 'required',
                    'date' => ['required', Rule::date()->after(today()->addDays(1)),],
                    'startTime' => 'required',
                    'contactName' => 'required',
                    'email' => ['required', 'email',],
                    'phone' => ['required',],
                    'otherVenue' => Rule::requiredIf(request('venueID') == 0),
                    'numDays' => Rule::requiredIf(request('isMultiDay') == 1),
                ]);

                $attrs['club_id'] = $clubID;
                $attrs['created_by'] = $user->id;
                $attrs['status'] = request('status', "Open");
                $attrs['centre'] = request('centre');
                $attrs['extras'] = request('extras');
                $attrs['otherRestrictions'] = request('otherRestrictions');
                $attrs['notes'] = request('notes');
                $attrs['options'] = request('options');
                $attrs['customCourses'] = request('customCourses');
                $attrs['customClasses'] = request('customClasses');
                $attrs['onlineEntryLink'] = request('onlineEntryLink');
                $attrs['hasEodSurcharge'] = request('hasEodSurcharge', 0);
                $attrs['hasEntryLimit'] = request('hasEntryLimit', 0);
                $attrs['hasClosingDate'] = request('hasClosingDate', 0);
                $attrs['hasOpeningDate'] = request('hasOpeningDate', 0);
//                $attrs['hasNotes'] = request('hasNotes', 0);
                $attrs['hasTimePenalty'] = request('hasTimePenalty', 0);
                $attrs['hasWaitingList'] = request('hasWaitingList', 0);

                $attrs['isMultiDay'] = request('isMultiDay', 0);
                $attrs['numDays'] = request('numDays', 1);

                $attrs['numLaps'] = request('numLaps', 10);
                $attrs['numSections'] = request('numSections', 4);
                $attrs['numRows'] = request('numRows', 40);
                $attrs['numColumns'] = request('numColumns', 3);

                $attrs['youthEntryFee'] = request('youthEntryFee', 0);
                $attrs['adultEntryFee'] = request('adultEntryFee', 0);
                $attrs['eodSurcharge'] = request('eodSurcharge', 0);

                $attrs['penaltyDelta'] = request('penaltyDelta', 60);
                $attrs['startInterval'] = request('startInterval', 60);
                $attrs['entryLimit'] = request('entryLimit', 0);
                $attrs['venueID'] = request('venueID', 0);
                $attrs['otherVenue'] = request('otherVenue');
                $attrs['otherRestriction'] = request('otherRestriction');

                $attrs['closingDate'] = request('closingDate');
                $attrs['openingDate'] = request('openingDate');


                $attrs['entrySelectionBasis'] = "Order of Payment";
                $attrs['scoringMode'] = "Observer";

                $attrs['entryMethod'] = "TrialMonster";
                $attrs['coc'] = "";
                $attrs['authority'] = "AMCA";
                $attrs['classlist'] = "";
                $attrs['courselist'] = "";

                $attrs['series_id'] = request('series_id', '');

                $trial = Trial::create($attrs);
                return redirect("trials/addTrialDetail/{$trial->id}");

//

            case 'trialData':
                $id = request('trialID');
                $attrs = request()->validate([
                    'courselist' => Rule::requiredIf(request('customCourses') == ""),
                    'classlist' => Rule::requiredIf(request('customClasses') == ""),
                    'customCourses' => Rule::requiredIf(request('courselist') == ""),
                    'customClasses' => Rule::requiredIf(request('classlist') == ""),
                    'penaltyDelta' => Rule::requiredIf(request('hasTimePenalty') == 1),
                    'startInterval' => Rule::requiredIf(request('hasTimePenalty') == 1),
                ]);

                $attrs['hasTimePenalty'] = request('hasTimePenalty', 0);
                $attrs['startInterval'] = request('startInterval', 60);
                $attrs['penaltyDelta'] = request('penaltyDelta', 60);

                if (request('customClasses')) {
                    $array = explode(',', request('customClasses'));
                    $trimmedarray = array_map('trim', $array);
                    $attrs['customClasses'] = implode(',', $trimmedarray);
                }

                if (request('customCourses')) {
                    $array = explode(',', request('customCourses'));
                    $trimmedarray = array_map('trim', $array);
                    $attrs['customCourses'] = implode(',', $trimmedarray);
                }

                if (request('classlist')) {
                    $attrs['classlist'] = implode(',', request('classlist'));
                } else {
                    $attrs['classlist'] = '';
                }

                if (request('courselist')) {
                    $attrs['courselist'] = implode(',', request('courselist'));
                } else {
                    $attrs['courselist'] = '';
                }

                $trial = Trial::findorfail($id);
                $trial->update($attrs);

                return redirect("trials/addTrialEntry/{$trial->id}");
            case 'entryData':
                $id = request('trialID');
                $trial = Trial::findorfail($id);

                $attrs = request()->validate([
                    'entryMethod' => 'required',
                    'onlineEntryLink' => Rule::requiredIf(request('entryMethod') !== null && (in_array('Online', request('entryMethod')))),
                    'entryLimit' => Rule::requiredIf(request('hasEntryLimit') == 1),
                    'openingDate' => Rule::requiredIf(request('hasOpeningDate') == 1),
                    'closingDate' => Rule::requiredIf(request('hasClosingDate') == 1),
                    'entrySelectionBasis' => Rule::requiredIf(request('hasEntryLimit') == 1),
                    'notes' => Rule::requiredIf(request('hasNotes') == 1),
                ]);
                $attrs['entryMethod'] = implode(',', request('entryMethod', 'TrialMonster'));
                $attrs['hasEntryLimit'] = request('hasEntryLimit', 0);
                $attrs['hasOpeningDate'] = request('hasOpeningDate', 0);
                $attrs['hasClosingDate'] = request('hasClosingDate', 0);
                $attrs['hasWaitingList'] = request('hasWaitingList', 0);
                $attrs['hasNotes'] = request('hasNotes', 0);
                $trial->update($attrs);

                return redirect("trials/addTrialScoring/{$trial->id}");
            case 'scoringData':
                $id = request('trialID');
                $trial = Trial::findorfail($id);

                $attrs = request()->validate([
                    'scoringMode' => 'required',
                    'stopNonStop' => 'required',
                ]);

                $trial->update($attrs);

                return redirect("trials/addTrialRegs/{$trial->id}");

            case 'regData':
                $id = request('trialID');
                $trial = Trial::findorfail($id);

                $attrs = request()->validate([
                    'authority' => 'required',
                    'status' => 'required',
                    'coc' => 'required',
                    'centre' => Rule::requiredIf(request('authority') == "ACU"),
                    'otherRestriction' => Rule::requiredIf(request('status') == "Other Restriction"),
                ]);

                $attrs['notes'] = request('notes');
                $trial->update($attrs);
                return redirect("trials/addTrialFees/{$trial->id}");

            case 'feeData':
//                dd(request('adultEntryFee'));
                $id = request('trialID');
                $trial = Trial::findorfail($id);

                $attrs = request()->validate([
                    'adultEntryFee' => 'required',
                    'youthEntryFee' => 'required',
                    'eodSurcharge' => Rule::requiredIf(request('hasEodSurcharge') == 1),
                ]);
// Add fees to Stripe
                $trial->hasEodSurcharge = request('hasEodSurcharge', 0);
                $trial->update($attrs);
                $this->addStripeProducts($trial, $attrs['youthEntryFee'], $attrs['adultEntryFee']);

                return redirect("adminTrials");
            default:
                break;
        }
        return redirect('/adminTrials');
    }

    public function update()
    {
        $user = Auth::user();
        $userid = $user->id;


        $trialid = request('trialid');
        $attrs = request()->validate([
            'name' => 'required',
            'contactName' => 'required',
            'date' => ['required', Rule::date()->todayOrAfter(),],
            'startTime' => 'required',
            'club' => 'required',
            'email' => ['required', 'email',],
            'phone' => ['required',],
            'status' => 'required',
            'stopNonStop' => 'required',
            'entryMethod' => 'required',
            'permit' => 'required',
        ]);
        if (request('classlist')) {
            $attrs['classlist'] = implode(',', request('classlist'));
        } else {
            $attrs['classlist'] = '';
        }

        if (request('courselist')) {
            $attrs['courselist'] = implode(',', request('courselist'));
        } else {
            $attrs['courselist'] = '';
        }

        $user = Auth::user();
        $userid = $user->id;
        $attrs['created_by'] = $userid;
//        $attrs['trialid'] = $trialid;

        $attrs['status'] = request('status', "Open");
        $attrs['centre'] = request('centre');
        $attrs['coc'] = request('coc');
        $attrs['extras'] = request('extras');
        $attrs['otherRestrictions'] = request('otherRestrictions');
        $attrs['notes'] = request('notes');
        $attrs['options'] = request('options');
        $attrs['customCourses'] = request('customCourses');

        $attrs['customClasses'] = request('customClasses');


        if (request('customClasses')) {
            $array = explode(',', request('customClasses'));
            $trimmedarray = array_map('trim', $array);
            $attrs['customClasses'] = implode(',', $trimmedarray);
        }
        if (request('customCourses')) {
            $array = explode(',', request('customCourses'));
            $trimmedarray = array_map('trim', $array);
            $attrs['customCourses'] = implode(',', $trimmedarray);
        }

        $attrs['entryMethod'] = implode(',', request('entryMethod', 'TrialMonster'));
        $attrs['onlineEntryLink'] = request('onlineEntryLink');
        $attrs['hasEodSurcharge'] = request('hasEodSurcharge', 0);
        $attrs['hasEntryLimit'] = request('hasEntryLimit', 0);
        $attrs['hasClosingDate'] = request('hasClosingDate', 0);
        $attrs['hasOpeningDate'] = request('hasOpeningDate', 0);
        $attrs['hasTimePenalty'] = request('hasTimePenalty', 0);
        $attrs['hasWaitingList'] = request('hasWaitingList', 0);
        $attrs['hasNotes'] = request('hasNotes', 0);

        $attrs['isMultiDay'] = request('isMultiDay', 0);
        $attrs['numDays'] = request('numDays', 1);

        $attrs['numLaps'] = request('numLaps', 10);
        $attrs['numSections'] = request('numSections', 4);
        $attrs['numRows'] = request('numRows', 40);
        $attrs['numColumns'] = request('numColumns', 3);

        $attrs['youthEntryFee'] = request('youthEntryFee', 0);
        $attrs['adultEntryFee'] = request('adultEntryFee', 0);
        $attrs['eodSurcharge'] = request('eodSurcharge', 0);

        $attrs['penaltyDelta'] = request('penaltyDelta', 60);
        $attrs['startInterval'] = request('startInterval', 60);
        $attrs['entryLimit'] = request('entryLimit', 0);
        $attrs['venueID'] = request('venueID', 0);
        $attrs['otherVenue'] = request('otherVenue');
        $attrs['otherRestriction'] = request('otherRestriction');

        $attrs['closingDate'] = request('closingDate');
        $attrs['openingDate'] = request('openingDate');

//        dd(request('entrySelectionBasis'));
        $attrs['authority'] = request('authority');
        $attrs['entrySelectionBasis'] = request('entrySelectionBasis');
        $attrs['scoringMode'] = request('scoringMode');


        if (request()->submitbutton == "saveasnew") {
            $this->saveasnew($attrs);
        } else {

            $trial = Trial::findorfail($trialid);
            $trial->update($attrs);
        }
        return redirect('/adminTrials');
    }

    public function saveasnew($attrs)
    {
        $user = Auth::user();
        $userid = $user->id;
        $trial = Trial::create($attrs);
        $this->addStripeProducts($trial, $attrs['youthEntryFee'], $attrs['adultEntryFee']);
        info("new trial created by $userid");

        return redirect('/adminTrials');
    }

//    Add new trial

    private function addStripeProducts($trial, mixed $youthEntryFee = 15, mixed $adultEntryFee = 20)
    {
        $stripe_secret_key = config('cashier.secret');
        $stripe = new StripeClient("$stripe_secret_key");

        $stripe->products->create([
            'name' => 'Youth Entry Fee',
            'description' => 'Youth Entry Fee',
            'statement_descriptor' => 'Youth Entry Fee',
            'metadata' => [
                'category' => 'entry fee',
                'trialid' => $trial->id,
                'club' => $trial->club,
                'trialname' => $trial->name,
                'amount' => $youthEntryFee,
                'isYouth' => true,
            ],
            'default_price_data' => ['currency' => 'gbp',
                'unit_amount' => 100 * $youthEntryFee,
            ],
        ]);

        $stripe->products->create([
            'name' => 'Adult Entry Fee',
            'description' => 'Adult Entry Fee',
            'statement_descriptor' => 'Adult Entry Fee',
            'metadata' => [
                'category' => 'entry fee',
                'trialid' => $trial->id,
                'club' => $trial->club,
                'trialname' => $trial->name,
                'amount' => $adultEntryFee,
                'isYouth' => false,
            ],
            'default_price_data' => ['currency' => 'gbp',
                'unit_amount' => 100 * $adultEntryFee,
            ],
        ]);
        return;
    }

    public function addTrialTrial($id)
    {
        $trial = Trial::findOrFail($id);
        $series = Series::where('id', $trial->series_id)->first();
        return view('trials/add_trial_trial', ['trial' => $trial, 'series' => $series]);;
    }

    public function addTrialEntry($id)
    {
//        dd($id);
        $trial = Trial::findOrFail($id);
        return view('trials/add_trial_entry', ['trial' => $trial]);
    }

    public function addTrialScoring($id)
    {
        $trial = Trial::findOrFail($id);
        return view('trials/add_trial_scoring', ['trial' => $trial]);
    }

    public function addTrialRegs($id)
    {
        $trial = Trial::findOrFail($id);
        return view('trials/add_trial_regulations', ['trial' => $trial]);
    }

    public function addTrialFees($id)
    {
        $trial = Trial::findOrFail($id);
        return view('trials/add_trial_fees', ['trial' => $trial]);
    }

    public function entrylist($id)
    {
//        $entries = Entry::where('trial_id', $id)
//            ->where('status', 1)
//            ->get()
//            ->sortBy('name');

        $entries = Entry::where('trial_id', $id)
            ->whereIn('status', [1, 7, 8, 9])
            ->get()
            ->sortBy('name');

        $unconfirmed = Entry::where('trial_id', $id)
            ->where('status', 0)
            ->select('name')
            ->get();

        $trial = Trial::where('id', $id)->first();
        return view('trials.entrylist', ['entries' => $entries, 'unconfirmed' => $unconfirmed, 'trial' => $trial]);
    }

    public function adminEntryList($id)
    {

        $duplicates = Entry::where('trial_id', $id)
//            ->whereIn('status', [0, 1, 7, 8, 9 ])
            ->where('ridingNumber', '!=', 0)
            ->groupBy('ridingNumber')
            ->havingRaw('COUNT(ridingNumber) > 1')
            ->get('ridingNumber');

//        $entries = Entry::where('trial_id', $id)
//            ->get()
//            ->sortBy('status');

        $entries = DB::table('entries')
            ->where('trial_id', $id)
            ->whereIn('status', [0, 1, 7, 8, 9])
//            ->orderBy('status')
            ->orderBy('name')
            ->get();


        $eod = DB::table('entries')
            ->where('trial_id', $id)
            ->where('token', 'OTD')
            ->where('status', 7)
            ->orderBy('created_at')
            ->get();

        $cancelled = DB::table('entries')
            ->where('trial_id', $id)
            ->where('status', 6)
            ->get();

        $trial = Trial::where('id', $id)->first();
        return view('trials.admin_entry_list', ['entries' => $entries, 'trial' => $trial, 'duplicates' => $duplicates, 'eod' => $eod, 'cancelled' => $cancelled]);
    }

    public function store()
    {
        $attrs = request()->validate([
            'name' => 'required',
            'contactName' => 'required',
            'date' => ['required', Rule::date()->after(today()->addDays(1)),],
            'startTime' => 'required',
            'club' => 'required',
            'email' => ['required', 'email',],
            'phone' => ['required',],
            'status' => 'required',
            'stopNonStop' => 'required',
            'permit' => 'required',

        ]);
        if (request('classlist')) {
            $attrs['classlist'] = implode(',', request('classlist'));
        } else {
            $attrs['classlist'] = '';
        }

        if (request('courselist')) {
            $attrs['courselist'] = implode(',', request('courselist'));
        } else {
            $attrs['courselist'] = '';
        }


        $user = Auth::user();
        $userid = $user->id;
        $attrs['created_by'] = $userid;

        $attrs['status'] = request('status', "Open");
        $attrs['centre'] = request('centre');
        $attrs['coc'] = request('coc');
        $attrs['extras'] = request('extras');
        $attrs['otherRestrictions'] = request('otherRestrictions');
        $attrs['notes'] = request('notes');
        $attrs['options'] = request('options');
        $attrs['customCourses'] = request('customCourses');
        $attrs['customClasses'] = request('customClasses');
        $attrs['entryMethod'] = implode(',', request('entryMethod', 'TrialMonster'));
        $attrs['onlineEntryLink'] = request('onlineEntryLink');
        $attrs['hasEodSurcharge'] = request('hasEodSurcharge', 0);
        $attrs['hasEntryLimit'] = request('hasEntryLimit', 0);
        $attrs['hasClosingDate'] = request('hasClosingDate', 0);
        $attrs['hasOpeningDate'] = request('hasOpeningDate', 0);
//        $attrs['hasNotes'] = request('hasNotes', 0);
        $attrs['hasTimePenalty'] = request('hasTimePenalty', 0);
        $attrs['hasWaitingList'] = request('hasWaitingList', 0);

        $attrs['isMultiDay'] = request('isMultiDay', 0);
        $attrs['numDays'] = request('numDays', 1);

        $attrs['numLaps'] = request('numLaps', 10);
        $attrs['numSections'] = request('numSections', 4);
        $attrs['numRows'] = request('numRows', 40);
        $attrs['numColumns'] = request('numColumns', 3);

        $attrs['youthEntryFee'] = request('youthEntryFee', 0);
        $attrs['adultEntryFee'] = request('adultEntryFee', 0);
        $attrs['eodSurcharge'] = request('eodSurcharge', 0);

        $attrs['penaltyDelta'] = request('penaltyDelta', 60);
        $attrs['startInterval'] = request('startInterval', 60);
        $attrs['entryLimit'] = request('entryLimit', 0);
        $attrs['venueID'] = request('venueID', 0);
        $attrs['otherVenue'] = request('otherVenue');
        $attrs['otherRestriction'] = request('otherRestriction');

        $attrs['closingDate'] = request('closingDate');
        $attrs['openingDate'] = request('openingDate');

        $attrs['authority'] = request('authority');
        $attrs['entrySelectionBasis'] = request('entrySelectionBasis');
        $attrs['scoringMode'] = request('scoringMode');

//        dd($attrs);
        $trial = Trial::create($attrs);
//        $trialid = $trial->id;

        $this->addStripeProducts($trial, $attrs['youthEntryFee'], $attrs['adultEntryFee']);
//        dd($trialid);

        info("new trial created by $userid");
        return redirect('/adminTrials');
    }

    public function remove($id)
    {
        Trial::destroy($id);
        return redirect('/adminTrials');
    }

    public function programme($id)
    {

        $trial = DB::table('trials')->where('trials.id', $id)
            ->join('venues', 'trials.venueID', '=', 'venues.id')
            ->select('trials.*', 'venues.name as venueName')
            ->first();
//        dump($trial);
        $allCourses = array();
        $courses = $trial->courselist;
        $customCourses = $trial->customCourses;

        $allClasses = array();
        $classes = $trial->classlist;
        $customClasses = $trial->customClasses;

        if ($courses != '') {
            array_push($allCourses, $courses);
        }

        if ($customCourses != '') {
            array_push($allCourses, $customCourses);
        }

        if ($classes != '') {
            array_push($allClasses, $classes);
        }

        if ($customClasses != '') {
            array_push($allClasses, $customClasses);
        }

        $classlist = str_replace(',', ',', implode(',', $allClasses));
        $courselist = str_replace(',', ',', implode(',', $allCourses));

        $numsections = $trial->numSections;
        $numlaps = $trial->numLaps;

        $courses = explode(",", $courselist);
        $classes = explode(",", $classlist);

        $riderList = DB::table('entries')
            ->where('trial_id', $id)
            ->where('ridingNumber', '>', 0)
            ->orderBy('ridingNumber')
            ->get(['ridingNumber', 'name', 'class', 'course', 'make', 'size']);

        $filename = "$trial->name.pdf";

        MYPDF::SetCreator('TM UK');
        MYPDF::SetAuthor('TrialMonster.uk');
        MYPDF::SetTitle('Entry list');
//        MYPDF::SetImageScale(PDF_IMAGE_SCALE_RATIO);
//        MYPDF::SetHeaderData('',0,"Title", "other");
//        MYPDF::SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' Hi there!', PDF_HEADER_STRING);
        MYPDF::SetHeaderFont(array(PDF_FONT_NAME_MAIN, '', 48));
        MYPDF::SetPrintHeader(true);
        MYPDF::AddPage();

// set some text to print
        $txt = <<<EOD
Entry list - $trial->name

$trial->club are grateful to the landowners at $trial->venueName, observers, other officials and riders without whose support this trial could not go ahead.


EOD;

// print a block of text using Write()
        MYPDF::SetFontSize(14);
        MYPDF::Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

        MYPDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        MYPDF::SetHeaderMargin(130);
        MYPDF::SetFooterMargin(30);
        MYPDF::SetAutoPageBreak(TRUE, 15);

        MYPDF::SetMargins(0, 20, 0);

        $nameWidth = 40;
        $indent = 10;
        $rowHeight = 7;

//        MYPDF::Cell(0, 0,"Entry list - $trial->name",  0, 0);


        if (sizeof($riderList) > 0) {
            foreach ($riderList as $rider) {
                $name = $rider->name;
                $ridingNumber = $rider->ridingNumber;
                $class = $rider->class;
                $course = $rider->course;
                $make = trim($rider->make);
                $size = trim($rider->size);

                $bike = $make . " " . $size;

                MYPDF::setX($indent);
                MYPDF::Cell(10, $rowHeight, $ridingNumber, 0, 0, 'R', false, null, 1, false, 'C' . 'M');
                MYPDF::Cell($nameWidth, $rowHeight, $name, 0, 0, 'L', false, null, 1, false, 'C' . 'M');
                MYPDF::Cell($nameWidth, $rowHeight, $course, 0, 0, 'L', false, null, 1, false, 'C' . 'M');
                MYPDF::Cell($nameWidth, $rowHeight, $class, 0, 0, 'L', false, null, 1, false, 'C' . 'M');
                MYPDF::Cell(0, $rowHeight, $bike, 0, 1, 'L', false, null, 1, false, 'C' . 'M');

            }
        }

        PDF::Close();
        PDF::Output(public_path($filename), 'F');
        PDF::reset();
        return response()->download($filename);

//        return view('trials.programme', ['trial' => $trial]);
    }

}

class MYPDF extends PDF
{
    //Page header
    public function Header()
    {
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);

        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');

        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }


}

