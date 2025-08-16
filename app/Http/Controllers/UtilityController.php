<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class UtilityController extends Controller
{

    public function getTrialDetails($id) {
        $trialDetails = DB::table('trials')
            ->join('venues', 'trials.venueID', '=', 'venues.id')
            ->where('trials.id', $id)
            ->get(['trials.*', 'venues.name as venue']);
        return $trialDetails;
    }
    public function saveResultsPDF($id)
    {
        $resultController = new ResultController();
        $data = $resultController->getResultList($id);

        $trial = $data['trial'];
        $name = $trial->name;
        $resultList = $data['results'];

        $allCourses = array();
        $courses = $trial->courselist;
        $customCourses = $trial->customCourses;
        $numLaps = $trial->numLaps;
        $numSections = $trial->numSections;

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
        $courses = explode(',', $courselist);
        $classes = explode(',', $classlist);

    $trialName = trim($trial->name);
        $filename = "$trialName.pdf";
        $filename = $this->filter_filename($filename);

//        PDF setup
        MYPDF::SetCreator('TM UK');
        MYPDF::SetAuthor('TrialMonster.uk');
        MYPDF::SetTitle('Entry list');
        MYPDF::SetHeaderFont(array(PDF_FONT_NAME_MAIN, '', 8));
        MYPDF::SetFooterFont(array(PDF_FONT_NAME_MAIN, '', 8));
        MYPDF::SetPrintHeader(false);
        MYPDF::SetPrintFooter(true);
        MYPDF::AddPage('L', 'A4');
        $txt = <<<EOD
$trial->name

$trial->club are grateful to the landowners at $trial->venue, observers, other officials and riders without whose support this trial could not go ahead.
EOD;

// print a block of text using Write()
        MYPDF::SetFontSize(10);
        MYPDF::SetFont(PDF_FONT_NAME_MAIN, 'B', 12);
        MYPDF::Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

        MYPDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        MYPDF::SetHeaderMargin(15);
        MYPDF::SetFooterMargin(15);
        MYPDF::SetAutoPageBreak(TRUE, 20);
        MYPDF::SetCellHeightRatio(1.5);

        MYPDF::SetMargins(0, 20);

        MYPDF::setHeaderCallback(function () {
            MYPDF::SetXY(10,10);
        });

        MYPDF::setFooterCallback(function () {
            MYPDF::Cell(0,0, 'x indicates a missed section :: o indicates an omitted section which is not included in the scoring', 0, true, 'C');
            MYPDF::Cell(0, 0, 'Provisional Results updated '. now(), 0, false, 'L', 0, '', 0, false, 'T', 'M');
            MYPDF::Cell(0, 0, 'Page ' . MYPDF::getAliasNumPage() . ' of ' . MYPDF::getAliasNbPages(), 0, true, 'R', 0, '', 0, false, 'T', 'M');
        });

        $rowHeight = 9;

//        Results output starts here

        $i = 0;
        foreach ($courses as $course) {
            foreach ($classes as $class) {

                if (sizeof($resultList[$i][2]) > 0) {
                    $this->printClassHeader($course, $class, $rowHeight, $numSections);
//                    Then result list
                    foreach ($resultList[$i][2] as $result) {
                        $remaining = $this->printLine($result, $numSections, $numLaps);

                        if($remaining < 40){
                            MYPDF::AddPage('L', 'A4');
                            $this->printClassHeader($course, $class, $rowHeight, $numSections);
                        }
                    }
                }
                $i++;
            }
        }

        MYPDF::Close();
        MYPDF::Output(public_path('pdf/results/' . $filename), 'F');
        MYPDF::reset();
        return response()->download('pdf/results/' . $filename);

    }
    function printClassHeader($course, $class, $rowHeight, $numSections)
    {
        $y = MYPDF::GetY();
        MYPDF::SetY($y + $rowHeight);
//                    Output class header
        MYPDF::SetFont('', 'B', 9, '', true);
        MYPDF::setX(10);
        MYPDF::Cell(0, 0, "$course - $class", 0, 0, 'L', false, null, 0, false, 'C' . 'M');
        MYPDF::setX(100);
        MYPDF::Cell(10, 0, "Total", 0, 0, 'C', false, null, 0, false, 'C' . 'M');

        $sectionWidth = 177 / $numSections;
        for ($index = 1; $index <= $numSections; $index++) {
            MYPDF::Cell($sectionWidth, 0, $index, 0, 0, 'C', false, null, 0, false, 'C' . 'M');
        }
        MYPDF::Cell('', '', '', '', 1);
    }
    function printLine($result, $numSections, $numLaps)
    {
        $pos = $result->pos;
        $number = $result->rider;
        $name = $result->name;
        $machine = $result->machine;
        $total = $result->total;

        $sectionScores = str_split($result->sectionScores, $numLaps);
        $resultStatus = $result->resultStatus;
        if ($resultStatus == 1) {
            $pos = "DNF";
        }

        PDF::setFontSize(9);

        PDF::setX(10);
        PDF::SetFont('', 'B', 9, '', true);
        PDF::Cell(9, 0, $pos, 0, 0, 'R', false, null, 0, false, 'C' . 'M');
        PDF::SetFont('', '', 9, '', true);
        PDF::setX(19);
        PDF::Cell(9, 0, $number, 0, 0, 'R', false, null, 0, false, 'C' . 'M');
        PDF::setX(28);
        PDF::Cell(40, 0, $name, 0, 0, 'L', false, null, 1, false, 'C' . 'M');
        PDF::setX(68);
        PDF::Cell(32, 0, $machine, 0, 0, 'L', false, null, 1, false, 'C' . 'M');

        $startScores = 100;
//          Only print total for finishers
        if ($resultStatus == 0) {
            $total = $total;
        } else {
            $total = '';
        }
        PDF::SetFont('', 'B', 9, '', true);
        PDF::Cell(10, 0, $total, 0, 0, 'R', false, null, 0, false, 'C' . 'M');
        PDF::SetFont('', '', 9, '', true);

        $sectionWidth = 177 / $numSections;
        for ($index = 1; $index <= $numSections; $index++) {

//            PDF::setX($startScores + 10 * $index);
            PDF::Cell($sectionWidth, 0, $sectionScores[$index - 1], 0, 0, 'C', false, null, 0, false, 'C' . 'M');
        }
//      Add line break
        PDF::Cell('', '', '', '', 1);
        $remaining = 210 - MYPDF::getY();
        return $remaining;
    }
    function filter_filename($name)
    {
        $name = str_replace(array_merge(
            array_map('chr', range(0, 31)),
            array('<', '>', ':', '"', '/', '\\', '|', '?', '*')
        ), '', $name);
        // maximise filename length to 255 bytes http://serverfault.com/a/9548/44086
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $name = mb_strcut(pathinfo($name, PATHINFO_FILENAME), 0, 255 - ($ext ? strlen($ext) + 1 : 0), mb_detect_encoding($name)) . ($ext ? '.' . $ext : '');
        return $name;
    }

    function nameize($str, $a_char = array("'", "-", " "))
    {
        //$str contains the complete raw name string
        //$a_char is an array containing the characters we use as separators for capitalization. If you don't pass anything, there are three in there as default.
        $string = strtolower($str);
        foreach ($a_char as $temp) {
            $pos = strpos($string, $temp);
            if ($pos) {
                //we are in the loop because we found one of the special characters in the array, so lets split it up into chunks and capitalize each one.
                $mend = '';
                $a_split = explode($temp, $string);
                foreach ($a_split as $temp2) {
                    //capitalize each portion of the string which was separated at a special character
                    $mend .= ucfirst($temp2) . $temp;
                }
                $string = substr($mend, 0, -1);
            }
        }
        return ucfirst($string);
    }
}

