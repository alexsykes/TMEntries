<?php

namespace App\Http\Controllers;

use App\Models\Trial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
        return view('trials.add');
    }

    public function edit($id) {
        $trial = Trial::find($id);
        return view('trials.edit', ['trial' => $trial]);
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
            'date' => ['required', Rule::date()->after(today()->addDays(1)),],
            'classlist' => 'required',
            'courselist' => 'required',
            'club' => 'required',
        ]);
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
