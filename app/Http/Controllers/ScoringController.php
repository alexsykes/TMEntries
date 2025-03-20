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
        if ($trial->isScoringSetup) {
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

    public function grid($trialID)
    {
        $trial = Trial::find($trialID);

        $scores = DB::select("SELECT rider, GROUP_CONCAT( IF(score IS NULL, '.',score) ORDER BY section, lap SEPARATOR '') AS scoreData FROM tme_scores WHERE trial_id = {$trialID} GROUP BY rider  ORDER BY rider");
        return view('scoring.grid', ['scores' => $scores, 'trial' => $trial]);
    }

    public function sectionScores($trialid, $section)
    {
        $trial = Trial::find($trialid);

        $scores = DB::select("SELECT rider, GROUP_CONCAT(id ORDER BY lap ASC)AS ids, GROUP_CONCAT(score ORDER BY section, lap SEPARATOR '') AS scores FROM tme_scores WHERE trial_id = {$trialid} AND section = {$section}  GROUP BY rider  ORDER BY rider	;");
//        dd($scores);
        return view('scoring.section_score_grid', ['scores' => $scores, 'trial' => $trial, 'section' => $section]);
    }

    public function sectionScoresForRider($trialID, $rider, $section)
    {
        $scores = DB::table('scores')
            ->where('trial_id', $trialID)
            ->where('rider', $rider)
            ->where('section', $section)
            ->orderBy('lap', 'asc')
            ->get(['score', 'id']);
        $scores = DB::select("SELECT  GROUP_CONCAT(id ORDER BY lap ASC)AS ids, GROUP_CONCAT(score ORDER BY section, lap SEPARATOR '') AS scores FROM tme_scores WHERE trial_id = {$trialID} AND section = {$section}  AND  rider	= {$rider}  GROUP BY rider  ORDER BY rider;");

        return view('scoring.editRiderSectionScore', ['scores' => $scores, 'rider' => $rider, 'section' => $section, 'trialID' => $trialID]);
    }

    public function updateSectionScores(Request $request)
    {
        $trialID = $request->trialID;
        $scores = $request->scores;
        $scoreIDs = $request->scoreIDs;
//        $numLaps = 4;
//        dd($trialID, $scores, $scoreIDs);

        for ($index = 0; $index < count($scores); $index++) {
            $riderScoresForSection = $scores[$index];
            $scoreIDsForSection = $scoreIDs[$index];

            if ($riderScoresForSection) {
                $riderscoresForLap = str_split($riderScoresForSection);
                $scoreIdsForLap = explode(',', $scoreIDsForSection);
//                dump(sizeof($scoreIdsForLap), sizeof($riderscoresForLap));

                for ($lap = 0; $lap < sizeof($riderscoresForLap); $lap++) {
                    DB::table('scores')
                        ->where('id', $scoreIdsForLap[$lap])
                        ->update([
                            'score' => $riderscoresForLap[$lap],
                            'updated_at' => now(),
                        ]);
                }
            }

        }
        return redirect("/scores/grid/{$trialID}");
    }

    public function updateSectionScoreForRider(Request $request)
    {
        $trialID = $request->trialID;
        $ids = explode(',', $request->scoreIDs);
        $scores = $request->scores;

        for ($index = 0; $index < sizeof($ids); $index++) {
            if (strlen($scores) <= $index) {
                $score = null;
            } else {
                $score = $scores[$index];
            }
            DB::table('scores')
                ->where('id', $ids[$index])
                ->update([
                    'score' => $score,
                    'updated_at' => now(),/**/
                ]);
        }

        return redirect("/scores/grid/{$trialID}");
    }
}
