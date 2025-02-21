<?php

namespace App\Http\Controllers;

use App\Models\Trial;

class TrialController extends Controller
{
    //

    public function showTrialList() {
        $trials = Trial::all();
        return view('trials.trial_list', ['trials' => $trials]);
    }

    public function adminTrials() {
        $trials = Trial::all();
        return view('trials.admin_trial_list', ['trials' => $trials]);
    }

    public function details($id) {
        $trial = Trial::find($id);
        return view('trials.details', ['trial' => $trial]);
    }
}
