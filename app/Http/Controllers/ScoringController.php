<?php

namespace App\Http\Controllers;

use App\Models\Trial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScoringController extends Controller
{
    //
    public function setup($trialID)
    {
        $trial = Trial::find($trialID);
        $numSections = $trial->numSections;
        $numLaps = $trial->numLaps;
        $numColumns = $trial->numColumns;
        $numRows = $trial->numRows;
        return view('scoring.setup', compact('trial', 'numSections', 'numLaps', 'numColumns', 'numRows'));
    }

    public function setupscoregrid(Request $request)
    {
//        Update trial
        $trial = Trial::find($request->trialID);
        if($trial->isScoringSetup) {
            return redirect("/scores/grid/{$trial->id}");
        }

        $trial->numSections = $request->numSections;
        $trial->numLaps = $request->numLaps;
        $trial->numColumns = $request->numColumns;
        $trial->numRows = $request->numRows;
        $trial->save();

        $numRiders = $trial->numRows * $trial->numColumns;
//        Setup scoring grid
        for ($rider = 1; $rider <= $numRiders; $rider++) {
            for ($section = 1; $section <= $request->numSections; $section++) {
                for ($lap = 1; $lap <= $request->numLaps; $lap++) {
                    DB::table('scores')->insert([
                        'trial_id' => $trial->id,
                        'rider' => $rider,
                        'section' => $section,
                        'lap' => $lap,
                    ]);
                }
            }
        }

        $trial->isScoringSetup = true;
        $trial->save();
        return redirect("/scores/grid/{$trial->id}");
    }

    public function grid( $trialID)
    {

//        DB::listen(function ($query) {
//            Log::info($query->sql, $query->bindings, $query->time);
//        });
        $trial = Trial::find($trialID);
//        dd($trial);
        $numSections = $trial->numSections;
        $numLaps  = $trial->numLaps;
        $numColumns = $trial->numColumns;
        $numRows = $trial->numRows;

        $scores = DB::table('scores')
            ->where('trial_id', $trial->id)
            ->orderBy('rider', 'asc')
            ->orderBy('section', 'asc')
            ->orderBy('lap', 'asc')
            ->get();

        $scores = DB::select("SELECT rider, GROUP_CONCAT( IF(score IS NULL, '.',score) ORDER BY section, lap SEPARATOR '') AS scoreData FROM tme_scores WHERE trial_id = {$trialID} GROUP BY rider  ORDER BY rider");
        return view('scoring.grid', ['scores' => $scores, 'trial' => $trial]);
    }

    public function sectionScores($id)
    {
        dd($id);
        return view('scores.section');
    }
    public function sectionScoresForRider($id)
    {
        dd($id);
        return view('scores.section');
    }
}
