<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Trial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDF;

class ResultController extends Controller
{
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
        $ip = request()->ip();
        Log::info("Results trialID:$id - IP: $ip");
        if ($id < 2) {
            abort(404);
        }
        if ($id == null) {
            abort(404);
        }

        $trials = DB::table('trials')
            ->join('venues', 'trials.venueID', '=', 'venues.id')
            ->where('trials.id', $id)
            ->get(['trials.*', 'venues.name as venue']);

        if ($trials->isEmpty()) {
            abort(404);
        }

        $trial = $trials[0];
        $courselist = $trial->courselist;
        $classlist = $trial->classlist;

        $created_by = $trial->created_by;
        $club_id = $trial->club_id;

        $allCourses = array();
        $courses = $trial->courselist;
        $customCourses = $trial->customCourses;

        $allClasses = array();
        $classes = $trial->classlist;
        $customClasses = $trial->customClasses;

        $utilityController = new UtilityController();
        $trialName = trim($trial->name);
        $filename = "$trialName.pdf";
        $filename = $utilityController->filter_filename($filename);


//    dump($courses, $customCourses, $classes, $customCourses);
        if ($courses != '') {
            array_push($allCourses, $courses);
        }

        if ($customCourses != '') {
            array_push($allCourses, $customCourses);
        }

        if ($classes != '') {
            array_push($allClasses, $classes);
        }

        if ($customClasses != '') {
            array_push($allClasses, $customClasses);
        }

        $classlist = str_replace(',', ',', implode(',', $allClasses));
        $courselist = str_replace(',', ',', implode(',', $allCourses));

        $numsections = $trial->numSections;
        $numlaps = $trial->numLaps;

        $courses = explode(",", $courselist);

//        Check for YCMCC
        if ($club_id == 5) {
            $resultsByClass = $this->getYCResultsByClass($id, $courselist, $classlist);
            $courseResults = array();
            foreach ($courses as $course) {
                $courseResult = $this->getYCCourseResult($id, $course);
                array_push($courseResults, $courseResult);
            }

        } else {
            $resultsByClass = $this->getResultsByClass($id, $courselist, $classlist);

            $courseResults = array();
            foreach ($courses as $course) {
                $courseResult = $this->getCourseResult($id, $course);
                array_push($courseResults, $courseResult);
            }
        }

//        dd($resultsByClass);
        $nonStarters = DB::table('entries')
            ->where('trial_id', $id)
            ->where('resultStatus', 2)
            ->orderBy('name')
            ->get('name');


        return view('results.detail', ['trial' => $trial, 'courseResults' => $courseResults, 'courses' => $courses, 'nonStarters' => $nonStarters, 'resultsByClass' => $resultsByClass, 'filename' => $filename]);
    }

    private function getYCResultsByClass($id, $courselist, $classlist)
    {
        $classes = explode(',', $classlist);
        $courses = explode(',', $courselist);
        $resultsArray = array();

        foreach ($courses as $course) {
            foreach ($classes as $class) {
                $resultArray = array();
                array_push($resultArray, $course);
                array_push($resultArray, $class);
                $sql = "SELECT id AS entryID, RANK() OVER ( ORDER BY resultStatus ASC, total, dob ASC) AS pos, ridingNumber AS rider, course AS course, name, class AS class, CONCAT(make,' ',size) AS machine, total, cleans, ones, twos, threes, fives, missed, sectionScores  , resultStatus FROM tme_entries WHERE trial_id = $id AND course = '$course' AND class = '$class' AND resultStatus < 2 AND ridingNumber > 0 ORDER BY resultStatus ASC, total, dob ASC";
                $results = DB::select($sql);
                array_push($resultArray, $results);
                array_push($resultsArray, $resultArray);
            }
        }
        return $resultsArray;
    }

    private function getYCCourseResult($id, string $course)
    {
        $query = "SELECT id AS entryID, DATE_FORMAT(created_at, '%d/%m/%Y %h:%i%p') AS created_at, RANK() OVER ( ORDER BY resultStatus ASC, total, dob) AS pos,
id AS id, ridingNumber AS rider, course AS course, name, class AS class, CONCAT(make,' ',size) AS machine, total, cleans, ones, twos, threes, fives, missed, resultStatus, sectionScores, sequentialScores, trial_id FROM tme_entries WHERE trial_id = $id AND ridingNumber > 0 AND resultStatus < 2 AND course = '" . $course . "'";
        $courseResult = DB::select($query);
        return $courseResult;
    }

    private function getResultsByClass($id, $courselist, $classlist)
    {
        $classes = explode(',', $classlist);
        $courses = explode(',', $courselist);
        $resultsArray = array();

        foreach ($courses as $course) {
            foreach ($classes as $class) {
                $resultArray = array();
                array_push($resultArray, $course);
                array_push($resultArray, $class);
                $sql = "SELECT id AS entryID, RANK() OVER ( ORDER BY resultStatus ASC, total, cleans DESC, ones DESC, twos DESC, threes DESC, sequentialScores) AS pos, ridingNumber AS rider, course AS course, name, class AS class, CONCAT(make,' ',size) AS machine, total, cleans, ones, twos, threes, fives, missed, sectionScores, resultStatus FROM tme_entries WHERE trial_id = $id AND course = '$course' AND class = '$class' AND resultStatus < 2 AND ridingNumber > 0 ORDER BY resultStatus ASC, total, cleans DESC, ones DESC, twos DESC, threes DESC, sequentialScores";
                $results = DB::select($sql);
                array_push($resultArray, $results);
                array_push($resultsArray, $resultArray);
            }
        }
        return $resultsArray;
    }

    private function getCourseResult($id, string $course)
    {
        $query = "SELECT id AS entryID, DATE_FORMAT(created_at, '%d/%m/%Y %h:%i%p') AS created_at, RANK() OVER ( ORDER BY resultStatus ASC, total, cleans DESC, ones DESC, twos DESC, threes DESC, sequentialScores) AS pos,
id AS id, ridingNumber AS rider, course AS course, name, class AS class, CONCAT(make,' ',size) AS machine, total, cleans, ones, twos, threes, fives, missed, resultStatus, sectionScores, sequentialScores, trial_id FROM tme_entries WHERE trial_id = $id AND ridingNumber > 0 AND resultStatus < 2 AND course = '" . $course . "'";
        $courseResult = DB::select($query);
        return $courseResult;
    }

    public function edit($id)
    {
        $entry = DB::table('entries')
            ->join('trials', 'entries.trial_id', '=', 'trials.id')
            ->where('entries.id', $id)
            ->get(['entries.*', 'trials.numSections', 'trials.numLaps', 'trials.classlist', 'trials.courselist', 'trials.customClasses', 'trials.customCourses', 'trials.isEntryLocked'])
            ->first();

        return view('results.edit', ['entry' => $entry]);
    }

    public function update()
    {
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
            $score = str_pad($sectionScore, $numLaps, 'x');
            $scoreString .= $score;
        }

        $scores = str_split($scoreString, 1);
        $sequentialScores = "";
        for ($lap = 0; $lap < $numLaps; $lap++) {
            for ($section = 0; $section < $numSections; $section++) {
                $offset = $lap + ($numLaps * $section);
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

        $this->getResultsPDF($trialID);

        return redirect("/results/display/$trialID");
    }



    public function getResultList($id)
    {
        $trial = DB::table('trials')
            ->join('venues', 'trials.venueID', '=', 'venues.id')
            ->select('trials.*', 'venues.name as venue')
            ->where('trials.id', $id)
            ->first();

        $club_id = $trial->club_id;

        $allCourses = array();
        $courses = $trial->courselist;
        $customCourses = $trial->customCourses;

        $allClasses = array();
        $classes = $trial->classlist;
        $customClasses = $trial->customClasses;

        if ($courses != '') {
            array_push($allCourses, $courses);
        }

        if ($customCourses != '') {
            array_push($allCourses, $customCourses);
        }

        if ($classes != '') {
            array_push($allClasses, $classes);
        }

        if ($customClasses != '') {
            array_push($allClasses, $customClasses);
        }

        $classlist = str_replace(',', ',', implode(',', $allClasses));
        $courselist = str_replace(',', ',', implode(',', $allCourses));

        $numsections = $trial->numSections;
        $numlaps = $trial->numLaps;

        $courses = explode(",", $courselist);

//        Check for YCMCC
        if ($club_id == 5) {
            $resultsByClass = $this->getYCResultsByClass($id, $courselist, $classlist);
//            $courseResults = array();
//            foreach ($courses as $course) {
//                $courseResult = $this->getYCCourseResult($id, $course);
//                array_push($courseResults, $courseResult);
//            }

        } else {
            $resultsByClass = $this->getResultsByClass($id, $courselist, $classlist);
//
//            $courseResults = array();
//            foreach ($courses as $course) {
//                $courseResult = $this->getCourseResult($id, $course);
//                array_push($courseResults, $courseResult);
//            }
        }
        $data = array("results" => $resultsByClass, "trial" => $trial);
        return $data;
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

    public function getResultsPDF($id){
        $utilityController = new UtilityController();
       $result = $utilityController->saveResultsPDF($id);
      echo $result;
    }


}
