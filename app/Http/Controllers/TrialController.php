<?php

namespace App\Http\Controllers;

use App\Models\Trial;
use App\Models\Venue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class TrialController extends Controller
{
    //

    public function showTrialList() {
        $trials = Trial::all();
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
        $trial = Trial::find($id);
        return view('trials.edit', ['trial' => $trial]);
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

        $action = request('action');
        $trialid = request('trialid');
        $attrs = request()->validate([
            'name' => 'required',
            'date' => ['required',Rule::date()->after(today()->addDays(1)),],
            'classlist' => 'required',
            'courselist' => 'required',
            'club'  => 'required',
        ]);

        switch ($action) {
            case 'save':
                $trial = Trial::findorfail($trialid);
                $trial->update($attrs);
                break;
            case 'saveasnew':
                $attrs['created_by'] = $userid;
                Trial::create($attrs);
                break;
        }
        return redirect('/adminTrials');
    }

    public function store()
    {

        $attrs = request()->validate([
            'name' => 'required',
            'contactName' => 'required',
            'date' => ['required', Rule::date()->after(today()->addDays(1)),],
            'classlist' => 'required',
            'courselist' => 'required',
            'startTime' => 'required',
            'club' => 'required',
            'email' => ['required','email', ],
            'phone' => ['required', ],
        ]);

        $user = Auth::user();
        $userid = $user->id;
        $attrs['created_by'] = $userid;
//        dd($attrs);
        Trial::create($attrs);

        return redirect('/adminTrials');
    }

    public function remove($id) {
        Trial::destroy($id);
        return redirect('/adminTrials');
    }

    public function saveasnew() {
        dd(request());
    }
}
