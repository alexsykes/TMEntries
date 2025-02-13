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
}
