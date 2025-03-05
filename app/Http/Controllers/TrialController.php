<?php

namespace App\Http\Controllers;

use App\Models\Trial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TrialController extends Controller
{
    //

    public function showTrialList() {

        $trials = DB::table('trials')->where('published', 1)
            ->where('date', '>', date('Y-m-d'))
            ->orderBy('date', 'desc')
            ->get();
        return view('trials.trial_list', ['trials' => $trials]);
    }

    public function adminTrials() {
        $trials = Trial::all()->sortBy('date');
        return view('trials.admin_trial_list', ['trials' => $trials]);
    }

    public function add()  {
        $prefix = config('database.connections.mysql.prefix');
        $venues = DB::select('select id, name from '.$prefix.'venues order by name');
        $authorities = array("ACU","AMCA","Other");
        $selection = array('Order of Payment','Ballot','Selection','Other');
        $scoring = array('Observer', 'App', 'Sequential', 'Punch cards', 'Other');
        $stopAllowed = array('Stop permitted', 'Non-stop');
        $entryRestrictions = array('Closed to club', 'Centre', 'Open', 'Other Restriction' );

        return view('trials.add', [
            'venues' => $venues,
            'authorities' => $authorities,
            'selection' => $selection,
            'scoring' => $scoring,
            'stopAllowed' => $stopAllowed,
            'entryRestrictions' => $entryRestrictions,
        ]);
    }

    public function edit($id) {
        $prefix = config('database.connections.mysql.prefix');
        $venues = DB::select('select id, name from '.$prefix.'venues order by name');
        $authorities = array("ACU","AMCA","Other");
        $selection = array('Order of Payment','Ballot','Selection','Other');
        $scoring = array('Observer', 'App', 'Sequential', 'Punch cards', 'Other');
        $stopAllowed = array('Stop permitted', 'Non-stop');
        $entryRestrictions = array('Closed to club', 'Centre', 'Open', 'Other Restriction' );

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
    public function toggleVisibility($id) {
//        dd($id);
        $trial = Trial::findorfail($id);
        $published = $trial->published;
        $trial->published = !$published;
        $trial->save();


        $trials = Trial::all()->sortBy('date');
        return view('trials.admin_trial_list', ['trials' => $trials]);
    }

    public function update()
    {
        $user = Auth::user();
        $userid = $user->id;

//        $action = request('action');
        $trialid = request('trialid');
        $attrs = request()->validate([
            'name' => 'required',
            'contactName' => 'required',
            'date' => ['required', Rule::date()->after(today()->addDays(1)),],
            'startTime' => 'required',
            'club' => 'required',
            'email' => ['required','email', ],
            'phone' => ['required', ],
            'status' => 'required',
            'stopNonStop' => 'required',
            'entryMethod' => 'required',
        ]);
        if(request('classlist')){
            $attrs['classlist'] = implode(',', request('classlist'));
        } else {
            $attrs['classlist'] = '';
        }

        if(request('courselist')){
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
        $attrs['hasNotes'] = request('hasNotes', 0);
        $attrs['hasTimePenalty'] = request('hasTimePenalty', 0);
        $attrs['hasWaitingList'] = request('hasWaitingList', 0);

        $attrs['isMultiDay'] = request('isMultiDay',0);
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
//        $attrs['entrySelectionBasis'] = request('entrySelectionBasis', '');
        $attrs['scoringMode'] = request('scoringMode');


//        dd($trialid);

//    case 'save':
                $trial = Trial::findorfail($trialid);
                $trial->update($attrs);

//        switch ($action) {
//            case 'save':
//                $trial = Trial::findorfail($trialid);
//                $trial->update($attrs);
//                break;
//            case 'saveasnew':
//                $attrs['created_by'] = $userid;
//                Trial::create($attrs);
//                break;
//        }
        return redirect('/adminTrials');
    }

    public function store()
    {
//    dd(request());

        $attrs = request()->validate([
            'name' => 'required',
            'contactName' => 'required',
            'date' => ['required', Rule::date()->after(today()->addDays(1)),],
            'startTime' => 'required',
            'club' => 'required',
            'email' => ['required','email', ],
            'phone' => ['required', ],
            'status' => 'required',
            'stopNonStop' => 'required',
        ]);
        if(request('classlist')){
            $attrs['classlist'] = implode(',', request('classlist'));
        } else {
            $attrs['classlist'] = '';
        }

        if(request('courselist')){
            $attrs['courselist'] = implode(',', request('courselist'));
        } else {
            $attrs['courselist'] = '';
        }

        $user = Auth::user();
        $userid = $user->id;
        $attrs['created_by'] = $userid;

        $attrs['status'] = request('status', "Open");
        $attrs['centre'] = request('centre');
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
        $attrs['hasNotes'] = request('hasNotes', 0);
        $attrs['hasTimePenalty'] = request('hasTimePenalty', 0);
        $attrs['hasWaitingList'] = request('hasWaitingList', 0);

        $attrs['isMultiDay'] = request('isMultiDay',0);
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
        $attrs['entrySelectionBasis'] = request('entrySelectionBasis', '');
        $attrs['scoringMode'] = request('scoringMode');

//        dd($attrs);
        $trial = Trial::create($attrs);
//        $trialid = $trial->id;

        $this->addStripeProducts($trial, $attrs['youthEntryFee'], $attrs['adultEntryFee']);
//        dd($trialid);
        return redirect('/adminTrials');
    }

    public function remove($id) {
        Trial::destroy($id);
        return redirect('/adminTrials');
    }

    public function saveasnew() {
        dd(request());
    }

    private function addStripeProducts($trial, mixed $youthEntryFee = 15, mixed $adultEntryFee = 20)
    {
        $stripe_secret_key = config('stripe.stripe_secret_key');
        $stripe = new \Stripe\StripeClient("$stripe_secret_key");

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
            ],
            'default_price_data' => ['currency' => 'gbp',
                'unit_amount' => 100 * $adultEntryFee,
            ],
        ]);
    }

}
