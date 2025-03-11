<x-main>
    <x-slot:heading>
        {{$trial->name}}
    </x-slot:heading>
    <?php
    session(['trial_id' => $trial->id]);
    $latitude = $trial->venue->latitude;
    $longitude = $trial->venue->longitude;
    $markerArray = array();
    $marker = array($latitude, $longitude);
    array_push($markerArray, $marker);


    // Trial details
    $date = date_create($trial->date);
    $formattedDate = date_format($date, "jS F, Y");

    $closingDate = date_format(date_create($trial->closingDate),  "g:ia jS F, Y");
    $openingDate = date_format(date_create($trial->openingDate),  "g:ia jS F, Y");
    $hasClosingDate = $trial->hasClosingDate;
    $hasOpeningDate = $trial->hasOpeningDate;
    $hasEntryLimit = $trial->hasEntryLimit;
    $hasEodSurcharge = $trial->hasEodSurcharge;
    $hasWaitingList = $trial->hasWaitingList;
    $hasTimePenalty = $trial->hasTimePenalty;

    $isMultiDay = $trial->isMultiDay;
    $entryMethod = $trial->entryMethod;
    $entrySelectionBasis = $trial->entrySelectionBasis;

//    Class and course options
    $classlist = str_replace(',', ', ', $trial->classlist);
    $courselist = str_replace(',', ', ', $trial->courselist);

    switch ($trial->restriction) {
        case "Open":
            $rest = "an Open ";
            break;
        case "Centre":
            $rest = "a Centre restricted ";
            break;
        case "Club":
            $rest = "Closed to Club ";
        case "Other":
            $rest = "Restricted ";
            $rest .= "The trial will be restricted to ". $trial->otherRestriction;
            break;
        default:
            break;
    }

    switch ($trial->authority) {
        case "AMCA":
            $entryConditions = "";
            $machines  = "Please see club website for machine specification and restrictions";
            break;
        case "ACU":
            $entryConditions = "All riders and passengers must hold a current ACU/SACU Trials Registration Card or ACU/SACU Competition Licence. Any rider or passenger from another FMN must produce a licence issued by their FMN, together with start permission and proof of personal accident insurance.";
            $machines = "Motorcycles as per NSC Appendix D Category 1, Group A1 Solos and TSR 8";
        case "Other":
            $entryConditions = "Restricted ";
            break;
        default:
            break;
    }
    ?>
    <style>
        #map {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 100%;
        }
    </style>

    <x-button href="/entries/userdata/{{$trial_id}}">Register</x-button>
    <div class="text-sm mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="ml-4 mr-4 pt-2 font-semibold text-black text-center ">{{$trial->club}} - Affiliated to
            the {{$trial->authority}}</div>
        <div class="ml-4 mr-4 pt-2  text-black text-center ">Supplementary Regulations for the {{$trial->name}}</div>
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">PERMIT: </span> {{$trial->permit}}</div>
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span class="font-semibold">DATE: </span> {{$formattedDate}}
        </div>
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">ANNOUNCEMENT: </span>{{$trial->club}} will organise
            {{$rest}} trial for solo motorcycles, held under the Rules of the {{$trial->authority}}, the following
            Supplementary Regulations and any Final Instructions issued for the meeting.
            Please take the time to carefully read any specific Safety Procedures produced by {{$trial->club}} which
            form part of the Supplementary Regulations. There will also be a Riders Briefing which all riders must
            attend ten minutes before the official start time.
        </div>
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">ELIGIBILITY: </span>This trial will be {{$rest}} trial. {{$entryConditions}}
        </div>
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">MACHINES: </span>{{$machines}}
        </div>
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">START / VENUE: </span>{{$trial->startTime}} at {{$trial->venue->name}},  {{$trial->venue->postcode}}
        </div>
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">DIRECTIONS: </span><?php echo $trial->venue->directions ;
            if($trial->venue->notes != "") {
                echo $trial->venue->notes;
            }
?>
        </div>
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">WHAT3WORDS: </span>{{$trial->venue->w3w}}
        </div>
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">CLASSES: </span>{{$classlist}}
        </div>
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">COURSES: </span>{{$courselist}}
        </div>
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">OFFICIALS: </span>Secretary of the Meeting (To whom all correspondence regarding this event shall be addressed): {{$trial->contactName}} email: {{$trial->email}} phone: {{$trial->phone}}<br>Point of Contact for Child Protection Matters: Secretary of the Meeting.
        </div>
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">ENTRIES: </span>
        </div>

    </div>

</x-main>