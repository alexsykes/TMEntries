<?php

namespace App\Http\Controllers;

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
            ->where('published', 1)
            ->whereBeforeToday('date')
            ->orderBy('date', 'desc')
            ->get(['name', 'club', 'date', 'id']);
// dd($pastTrials);
        return view('results.list', ['pastTrials' => $pastTrials]);
    }

    public function display($id)
    {
        $trials = DB::table('trials')
            ->where('id', $id)
            ->get();

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

        $results = $this->getResults($id, $courselist);
        if(sizeof($results) == 0) {
            abort(404);
        }
        return view('results.detail', ['trial' => $trial, 'courseResults' => $courseResults, 'courses' => $courses]);
    }

    private function getCourseResult($id, string $course)
    {
        $query = "SELECT id AS entryID, DATE_FORMAT(created_at, '%d/%m/%Y %h:%i%p') AS created_at, 
RANK() OVER (
        ORDER BY resultStatus ASC, total, cleans DESC, ones DESC, twos DESC, threes DESC, sequentialScores) AS pos,
id AS id, ridingNumber  AS rider, course AS course, name, class AS class, CONCAT(make,' ',size) AS machine, total, cleans, ones, twos, threes, fives, missed, resultStatus, sectionScores as sectionScores, sequentialScores AS sequentialScores, trial_id FROM tme_entries WHERE trial_id = $id AND resultStatus < 2 AND course = '" . $course . "'";
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
	total, cleans, ones, twos, threes, fives, missed, resultStatus, sectionScores as sectionScores, sequentialScores AS sequentialScores, trial_id 
	FROM tme_entries 
	WHERE trial_id = $id AND resultStatus < 2";

        $results = DB::select($query);
        return $results;
    }
}
