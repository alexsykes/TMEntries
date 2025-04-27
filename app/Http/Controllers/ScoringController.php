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
        if($trial->isScoringSetup) {

//            dd($trial->isScoringSetup);
            return redirect("/scores/grid/{$trial->id}");
        }
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
        $riderNumbers = $this->getRiderNumbers($trialID);

        $scores = DB::select("SELECT rider, GROUP_CONCAT( IF(score IS NULL, '.',score) ORDER BY section, lap SEPARATOR '') AS scoreData FROM tme_scores WHERE trial_id = {$trialID} GROUP BY rider  ORDER BY rider");
        return view('scoring.grid', ['scores' => $scores, 'trial' => $trial, 'riderNumbers' => $riderNumbers]);
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
        $trial = Trial::find($trialID);
        $numLaps = $trial->numLaps;
        $scores = DB::select("SELECT  GROUP_CONCAT(id ORDER BY lap ASC)AS ids, GROUP_CONCAT(score ORDER BY section, lap SEPARATOR '') AS scores FROM tme_scores WHERE trial_id = {$trialID} AND section = {$section}  AND  rider	= {$rider}  GROUP BY rider  ORDER BY rider;");

        return view('scoring.editRiderSectionScore', ['scores' => $scores, 'rider' => $rider, 'section' => $section,'numLaps' => $numLaps,  'trialID' => $trialID]);
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

    public function getRiderNumbers($id){
        $riderNumbers = DB::table('entries')
            ->where('trial_id', $id)
            ->whereIn('status', [1, 7, 8, 9])
            ->get('ridingNumber');

        $riderNumberArray = array();

        foreach($riderNumbers as $riderNumber){
            array_push($riderNumberArray, $riderNumber->ridingNumber);
        }

        return $riderNumberArray;

    }

    public function getNonStarters($id, $allMissed){
    $riders = DB::table('entries')
        ->where('trial_id', $id)
        ->whereIn('status', [1, 7, 8, 9])
        ->where('name', '!=',"")
        ->where('sectionScores',$allMissed)
        ->get('name');

    $nonStarters = array();

    foreach($riders as $rider){
        array_push($nonStarters, $rider->name);
    }

    return$nonStarters;
}

    public function publish(Request $request)
    {
//        Get trial details
        $trialID = $request->trialID;
        $trial = DB::table('trials')->where('id', $trialID)->first();
        $numLaps = $trial->numLaps;
        $numSections = $trial->numSections;
        $numPossibleScores = $trial->numSections * $trial->numLaps;
        $cutoff = $numPossibleScores * 0.25;
        $allMissed = str_pad('', $numPossibleScores, 'x', STR_PAD_LEFT);


        $authority = $trial->authority;
        if ($authority == 'ACU') {
            $missedValue = 10;
        } else {
            $missedValue = 5;
        }

//        Fill missing scores with x
        DB::table('scores')
            ->where('trial_id', $trialID)
            ->whereNull('score')
            ->update(['score' => 'x']);

//        Get confirmed rider numbers
        $riderNumbers = $this->getRiderNumbers($trialID);
        $nonStarters = $this->getNonStarters($trialID, $allMissed);

//        Get rider scores
        $riderScores = Db::select("SELECT e.ridingNumber, GROUP_CONCAT(score ORDER BY section, lap SEPARATOR '') AS sectionScores, GROUP_CONCAT(score ORDER BY lap, section SEPARATOR '') AS sequentialScores FROM tme_entries e JOIN tme_scores s ON e.ridingNumber = s.rider AND e.trial_id = s.trial_id WHERE e.trial_id = $trialID GROUP BY ridingNumber");

//        then transfer all scores to entries
        foreach ($riderScores as $riderScore) {
            $sectionScores = $riderScore->sectionScores;
            $cleans = substr_count($sectionScores, '0', 0);
            $ones = substr_count($sectionScores, '1', 0);
            $twos = substr_count($sectionScores, '2', 0);
            $threes = substr_count($sectionScores, '3', 0);
            $fives = substr_count($sectionScores, '5', 0);
            $missed = substr_count($sectionScores, 'x', 0);
            $total = $ones + 2 * ($twos) + 3 * ($threes) + 5 * ($fives) + $missedValue * ($missed);
            $resultStatus = 0;
            if ($missed > $cutoff) {
                $resultStatus = 1;
            }
            if ($missed == $numPossibleScores) {
                $resultStatus = 2;
            }

            if (in_array($riderScore->ridingNumber, $nonStarters)) {
                $resultStatus = 2;
            }

            DB::table('entries')
                ->where('trial_id', $trialID)
                ->where('ridingNumber', $riderScore->ridingNumber)
                ->update(['sectionScores' => $riderScore->sectionScores,
                    'sequentialScores' => $riderScore->sequentialScores,
                    'cleans' => $cleans,
                    'ones' => $ones,
                    'twos' => $twos,
                    'threes' => $threes,
                    'fives' => $fives,
                    'missed' => $missed,
                    'total' => $total,
                    'updated_at' => now(),
                    'resultStatus' => $resultStatus
                ]);
        }

        $this->lockTrial($trialID);
        return redirect("/results/display/{$trialID}");
    }


    public function locktrial($trialID){
        DB::table('trials')
            ->where('id', $trialID)
            ->update(['isEntryLocked' => 1, 'isScoringLocked' => 1, 'isLocked' => 1,  'isResultPublished' => 1]);
    }
        public function confirmPublish(Request $request)
        {
        return view('scoring.confirmPublish', ['trialID' => $request->trialID]);;
        }

}
