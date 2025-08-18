<?php

namespace App\Http\Controllers;

use App\Models\Trial;
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
        $updated_at = $trial->updated_at;

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
        $trialName = trim($trial->name);
        $filename = "$trial->id $trialName.pdf";
        $filename = str_replace(' ', '_', $filename);
        $filename = $this->filter_filename($filename);

//        PDF setup
        MYPDFG::SetCreator('TM UK');
        MYPDFG::SetAuthor('TrialMonster.uk');
        MYPDFG::SetTitle('Entry list');
        MYPDFG::SetHeaderFont(array(PDF_FONT_NAME_MAIN, '', 8));
        MYPDFG::SetFooterFont(array(PDF_FONT_NAME_MAIN, '', 8));
        MYPDFG::SetPrintHeader(false);
        MYPDFG::SetPrintFooter(true);
        MYPDFG::AddPage('L', 'A4');
        $txt = <<<EOD
$trial->name

$trial->club are grateful to the landowners at $trial->venue, observers, other officials and riders without whose support this trial could not go ahead.
EOD;

// print a block of text using Write()
        MYPDFG::SetFontSize(10);
        MYPDFG::SetFont(PDF_FONT_NAME_MAIN, 'B', 12);
        MYPDFG::Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

        MYPDFG::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        MYPDFG::SetHeaderMargin(15);
        MYPDFG::SetFooterMargin(15);
        MYPDFG::SetAutoPageBreak(TRUE, 20);
        MYPDFG::SetCellHeightRatio(1.5);

        MYPDFG::SetMargins(0, 20);

        MYPDFG::setHeaderCallback(function () {
            MYPDFG::SetXY(10,10);
        });

        MYPDFG::setFooterCallback(function () {
            MYPDFG::Cell(0,0, 'x indicates a missed section :: o indicates an omitted section which is not included in the scoring', 0, true, 'C');
            MYPDFG::Cell(0, 0, 'Provisional Results updated '. now(), 0, false, 'L', 0, '', 0, false, 'T', 'M');
            MYPDFG::Cell(0, 0, 'Page ' . MYPDFG::getAliasNumPage() . ' of ' . MYPDFG::getAliasNbPages(), 0, true, 'R', 0, '', 0, false, 'T', 'M');
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
                            MYPDFG::AddPage('L', 'A4');
                            $this->printClassHeader($course, $class, $rowHeight, $numSections);
                        }
                    }
                }
                $i++;
            }
        }

        MYPDFG::Close();
        MYPDFG::Output(public_path('pdf/results/' . $filename), 'F');
        MYPDFG::reset();
//        return response()->download('pdf/results/' . $filename);
        return;

    }

    public function createResultPDF($id){
            $this->saveResultsPDF($id);
    }

    function printClassHeader($course, $class, $rowHeight, $numSections)
    {
        $y = MYPDFG::GetY();
        MYPDFG::SetY($y + $rowHeight);
//                    Output class header
        MYPDFG::SetFont('', 'B', 9, '', true);
        MYPDFG::setX(10);
        MYPDFG::Cell(0, 0, "$course - $class", 0, 0, 'L', false, null, 0, false, 'C' . 'M');
        MYPDFG::setX(100);
        MYPDFG::Cell(10, 0, "Total", 0, 0, 'C', false, null, 0, false, 'C' . 'M');

        $sectionWidth = 177 / $numSections;
        for ($index = 1; $index <= $numSections; $index++) {
            MYPDFG::Cell($sectionWidth, 0, $index, 0, 0, 'C', false, null, 0, false, 'C' . 'M');
        }
        MYPDFG::Cell('', '', '', '', 1);
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
            PDF::Cell($sectionWidth, 0, str_replace('o', '', $sectionScores[$index - 1]), 0, 0, 'C', false, null, 0, false, 'C' . 'M');
//            PDF::Cell($sectionWidth, 0, $sectionScores[$index - 1], 0, 0, 'C', false, null, 0, false, 'C' . 'M');
        }
//      Add line break
        PDF::Cell('', '', '', '', 1);
        $remaining = 210 - MYPDFG::getY();
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

class MYPDFG extends PDF
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
//        $this->SetY(-15);
//        // Set font
//        $this->SetFont('helvetica', 'I', 8);
//        // Page number
//        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }


}

