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
        if($id<2){
            abort(404);
        }
        if($id == null ){
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


//    dump($courses, $customCourses, $classes, $customCourses);
        if($courses !='') {
            array_push($allCourses, $courses);
        }

        if($customCourses !='') {
            array_push($allCourses, $customCourses);
        }

        if($classes !='') {
            array_push($allClasses, $classes);
        }

        if($customClasses !='') {
            array_push($allClasses, $customClasses);
        }

        $classlist = str_replace(',',',',implode(',', $allClasses));
        $courselist   = str_replace(',',',',implode(',', $allCourses));

        $numsections = $trial->numSections;
        $numlaps = $trial->numLaps;

        $courses = explode(",", $courselist);

//        Check for YCMCC
        if($club_id == 5) {
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


        return view('results.detail', ['trial' => $trial, 'courseResults' => $courseResults, 'courses' => $courses, 'nonStarters' => $nonStarters, 'resultsByClass' => $resultsByClass]);
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

    private function getYCCourseResult($id, string $course)
    {
        $query = "SELECT id AS entryID, DATE_FORMAT(created_at, '%d/%m/%Y %h:%i%p') AS created_at, RANK() OVER ( ORDER BY resultStatus ASC, total, dob) AS pos,
id AS id, ridingNumber AS rider, course AS course, name, class AS class, CONCAT(make,' ',size) AS machine, total, cleans, ones, twos, threes, fives, missed, resultStatus, sectionScores, sequentialScores, trial_id FROM tme_entries WHERE trial_id = $id AND ridingNumber > 0 AND resultStatus < 2 AND course = '" . $course . "'";
        $courseResult = DB::select($query);
        return $courseResult;
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
        dump($scoreString);

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

        return redirect("/results/display/$trialID");
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

        $data = $this->getResultList($id);

        $trial = $data['trial'];
        $resultList = $data['results'];

        $allCourses = array();
        $courses = $trial->courselist;
        $customCourses = $trial->customCourses;
        $numLaps = $trial->numLaps;
        $numSections = $trial->numSections;

        $allClasses = array();
        $classes = $trial->classlist;
        $customClasses = $trial->customClasses;

        if($courses !='') {
            array_push($allCourses, $courses);
        }

        if($customCourses !='') {
            array_push($allCourses, $customCourses);
        }

        if($classes !='') {
            array_push($allClasses, $classes);
        }

        if($customClasses !='') {
            array_push($allClasses, $customClasses);
        }

        $classlist = str_replace(',',',',implode(',', $allClasses));
        $courselist   = str_replace(',',',',implode(',', $allCourses));
        $courses = explode(',', $courselist);
        $classes = explode(',', $classlist);


        $filename = "$trial->name.pdf";
        $filename= $this->filter_filename($filename);


//        PDF setup
        MYPDF::SetCreator('TM UK');
        MYPDF::SetAuthor('TrialMonster.uk');
        MYPDF::SetTitle('Entry list');
        MYPDF::SetHeaderFont(array(PDF_FONT_NAME_MAIN, '', 48));
        MYPDF::SetPrintHeader(true);
        MYPDF::AddPage('L', 'A4');
        $txt = <<<EOD
Provisional Results - $trial->name

$trial->club are grateful to the landowners at $trial->venue, observers, other officials and riders without whose support this trial could not go ahead.


EOD;

// print a block of text using Write()
        MYPDF::SetFontSize(10);
        MYPDF::Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

        MYPDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        MYPDF::SetHeaderMargin(130);
        MYPDF::SetFooterMargin(30);
        MYPDF::SetAutoPageBreak(TRUE, 15);
        MYPDF::SetCellHeightRatio(1.5);

        MYPDF::SetMargins(0, 20, 0);

        $nameWidth = 40;
        $indent = 10;
        $rowHeight = 9;

//        Results output starts here

        $i = 0;
        foreach ($courses as $course) {
            foreach ($classes as $class) {
                if(sizeof($resultList[$i][2]) > 0) {
//                    Output class header
//                    dump($course . ' - '. $class);
//                    Then result list
                    foreach ($resultList[$i][2] as $result) {
                        $this->printLine($result, $numSections, $numLaps);
                    }
                }
                $i++;
            }
        }


        PDF::Close();
        PDF::Output(public_path('results/'.$filename), 'F');
        PDF::reset();
        return response()->download('results/'.$filename);

    }

    private function getTrialDetails($id){
        $trialDetails = DB::table('trials')
            ->join('venues', 'trials.venueID', '=', 'venues.id')
            ->where('trials.id', $id)
            ->get(['trials.*', 'venues.name as venue']);
        return $trialDetails;
    }

    private function getResultList($id){
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

        if($courses !='') {
            array_push($allCourses, $courses);
        }

        if($customCourses !='') {
            array_push($allCourses, $customCourses);
        }

        if($classes !='') {
            array_push($allClasses, $classes);
        }

        if($customClasses !='') {
            array_push($allClasses, $customClasses);
        }

        $classlist = str_replace(',',',',implode(',', $allClasses));
        $courselist   = str_replace(',',',',implode(',', $allCourses));

        $numsections = $trial->numSections;
        $numlaps = $trial->numLaps;

        $courses = explode(",", $courselist);

//        Check for YCMCC
        if($club_id == 5) {
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

    function filter_filename($name) {
        // remove illegal file system characters https://en.wikipedia.org/wiki/Filename#Reserved_characters_and_words
        $name = str_replace(array_merge(
            array_map('chr', range(0, 31)),
            array('<', '>', ':', '"', '/', '\\', '|', '?', '*')
        ), '', $name);
        // maximise filename length to 255 bytes http://serverfault.com/a/9548/44086
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $name= mb_strcut(pathinfo($name, PATHINFO_FILENAME), 0, 255 - ($ext ? strlen($ext) + 1 : 0), mb_detect_encoding($name)) . ($ext ? '.' . $ext : '');
        return $name;
    }

    function printLine($result, $numSections, $numLaps) {
        $pos = $result->pos;
        $number = $result->rider;
        $name = $result->name;
        $machine = $result->machine;
        $total = $result->total;

        $sectionScores = str_split($result->sectionScores, $numLaps);
        $resultStatus = $result->resultStatus;
        if($resultStatus == 1) {
            $pos = "DNF";
        }

        PDF::setFontSize(9);

        PDF::setX(10);
        PDF::SetFont('', 'B', 9, '', true);
        PDF::Cell(9, 0, $pos, 0, 0, 'R', false, null, 0, false, 'C' . 'M');;
        PDF::SetFont('', '', 9, '', true);
        PDF::setX(19);
        PDF::Cell(9, 0, $number, 0, 0, 'R', false, null, 0, false, 'C' . 'M');
        PDF::setX(28);
        PDF::Cell(40, 0, $name, 0, 0, 'L', false, null, 1, false, 'C' . 'M');
        PDF::setX(68);
        PDF::Cell(32, 0, $machine, 0, 0, 'L', false, null, 1, false, 'C' . 'M');

        $startScores = 100;

//          Only print total for finishers
        if($resultStatus == 0) { $total = $total; } else { $total = ''; }
            PDF::SetFont('', 'B', 9, '', true);
            PDF::Cell(10, 0, $total, 0, 0, 'R', false, null, 0, false, 'C' . 'M');
            PDF::SetFont('', '', 9, '', true);

        $sectionWidth = 177 / $numSections;
        for($index = 1; $index <= $numSections; $index++) {

//            PDF::setX($startScores + 10 * $index);
            PDF::Cell($sectionWidth, 0, $sectionScores[$index-1], 0, 0, 'C', false, null, 0, false, 'C' . 'M');
        }

        PDF::Cell('','','','',1);
    }
}
class MYPDF extends PDF
{
    //Page header
    public function Header()
    {
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);

        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');

        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }


}
