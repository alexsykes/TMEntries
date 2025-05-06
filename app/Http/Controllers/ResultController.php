<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Trial;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    //

    public function create_result($entry)
    {
        dd($entry);
    }

    public function list()
    {
        $pastTrials = DB::table('trials')
            ->join('venues', 'trials.venueID', '=', 'venues.id')
            ->where('published', 1)
            ->where('isResultPublished', 1)
//            ->whereBeforeToday('date')
            ->orderBy('date', 'desc')
            ->get(['trials.name', 'trials.club', 'date', 'trials.id', 'venues.name as venue']);
        return view('results.list', ['pastTrials' => $pastTrials]);
    }

    public function display($id)
    {
        $trials = DB::table('trials')
            ->join('venues', 'trials.venueID', '=', 'venues.id')
            ->where('trials.id', $id)
            ->get(['trials.*','venues.name as venue']);

        if ($trials->isEmpty()) {
            abort(404);
        }

        $trial = $trials[0];
        $courselist = $trial->courselist;
        $classlist = $trial->classlist;
        $numsections = $trial->numSections;
        $numlaps = $trial->numLaps;

        $courses = explode(",", $courselist);

        $courseResults = array();

        foreach ($courses as $course) {
            $courseResult = $this->getCourseResult($id, $course);
            array_push($courseResults, $courseResult);
        }

        $nonStarters = DB::table('entries')
            ->where('trial_id', $id)
            ->where('resultStatus', 2)
            ->orderBy('name')
            ->get('name');
        return view('results.detail', ['trial' => $trial, 'courseResults' => $courseResults, 'courses' => $courses, 'nonStarters' => $nonStarters]);
    }

    private function getCourseResult($id, string $course)
    {
        $query = "SELECT id AS entryID, DATE_FORMAT(created_at, '%d/%m/%Y %h:%i%p') AS created_at, 
RANK() OVER ( ORDER BY resultStatus ASC, total, cleans DESC, ones DESC, twos DESC, threes DESC, sequentialScores) AS pos,
id AS id, ridingNumber AS rider, course AS course, name, class AS class, CONCAT(make,' ',size) AS machine, total, cleans, ones, twos, threes, fives, missed, resultStatus, sectionScores, sequentialScores, trial_id FROM tme_entries WHERE trial_id = $id AND ridingNumber > 0 AND resultStatus < 2 AND course = '" . $course . "'";
        $courseResult = DB::select($query);
        return $courseResult;
    }

    private function getResults($id, $courselist)
    {

        $courseArray = explode(',', $courselist);
        $courselist = implode("','", $courseArray);

        $query = "SELECT id AS resultID, 
    RANK() OVER (
	PARTITION BY FIELD(course,'$courselist')
	ORDER BY FIELD(course,'$courselist'), resultStatus ASC, total, cleans DESC, ones DESC, twos DESC, threes DESC, sequentialScores
	) AS pos,
	id AS id, ridingNumber  AS rider, 
	course AS course, 
	name, 
	class AS class, CONCAT(make,' ',size) AS machine, 
	total, cleans, ones, twos, threes, fives, missed, resultStatus, sectionScores, sequentialScores, trial_id 
	FROM tme_entries 
	WHERE trial_id = $id AND resultStatus < 2";

        $results = DB::select($query);
        return $results;
    }

    public function edit($id){
        $entry = DB::table('entries')
            ->join('trials', 'entries.trial_id', '=', 'trials.id')
            ->where('entries.id', $id)
            ->get(['entries.*', 'trials.numSections', 'trials.numLaps', 'trials.classlist', 'trials.courselist', 'trials.customClasses', 'trials.customCourses', 'trials.isEntryLocked'])
        ->first();

        return view('results.edit', ['entry' => $entry]);
    }

    public function update (){
        $entryID = request('id');
        $entry = Entry::findOrFail($entryID);
        $trialID = $entry->trial_id;

        $trial = Trial::findOrFail($trialID);

        $sectionScores = request('scores');
        $numLaps = $trial->numLaps;
        $numSections = $trial->numSections;
        $numPossibleScores = $numLaps * $numSections;
        $cutoff = $numPossibleScores * 0.25;

        $scoreString = "";
        foreach ($sectionScores as $sectionScore) {
            $score = str_pad($sectionScore, $numLaps ,'x');
            $scoreString .= $score;
        }
        dump($scoreString);

        $scores = str_split( $scoreString, 1);
    $sequentialScores = "";
        for($lap = 0; $lap < $numLaps; $lap++) {
            for ($section = 0; $section < $numSections; $section++) {
                $offset =  $lap + ($numLaps * $section);
                $sequentialScores .= $scores[$offset];
            }
        }


        $entry->sequentialScores = $sequentialScores;
        $entry->sectionScores = $scoreString;
        $entry->course = request('course');
        $entry->class = request('class');
        $entry->make = request('make');
        $entry->type = request('type');
        $entry->size = request('size');
        $entry->name = request('name');

        $entry->cleans = substr_count($scoreString, '0', 0);
        $entry->ones = substr_count($scoreString, '1', 0);
        $entry->twos = substr_count($scoreString, '2', 0);
        $entry->threes = substr_count($scoreString, '3', 0);
        $entry->fives = substr_count($scoreString, '5', 0);
        $entry->missed = substr_count($scoreString, 'x', 0);

        $entry->total = $entry->ones + 2 * ($entry->twos) + 3 * ($entry->threes) + 5 * ($entry->fives) + 5 * ($entry->missed);
        $resultStatus = 0;

        if ($entry->missed > $cutoff) {
            $resultStatus = 1;
        }
        if ($entry->missed == $numPossibleScores) {
            $resultStatus = 2;
        }
        $entry->resultStatus = $resultStatus;

        $entry->save();

        $trial->updated_at = now();
        $trial->save();

        return redirect("/results/display/$trialID");
    }

}
