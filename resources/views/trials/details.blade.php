<x-main>

    <script
            src="https://maps.googleapis.com/maps/api/js?key={{$gmap_key}}&loading=async&libraries=maps&v=weekly&libraries=marker"
            defer>
    </script>
    <x-slot:heading>
        {{$trial->name}}
    </x-slot:heading>
    <?php
    session(['trial_id' => $trial->id]);
//dd($clubData);

$methodOfMarking = $clubData->section_markers;
    $latitude = $trial->venue->latitude;
    $longitude = $trial->venue->longitude;
    $markerArray = array();
    $marker = array($latitude, $longitude);
    array_push($markerArray, $marker);

    // Trial details
//  Get parameters
    $hasClosingDate = $trial->hasClosingDate;
    $hasOpeningDate = $trial->hasOpeningDate;
    $hasEntryLimit = $trial->hasEntryLimit;
    $hasEodSurcharge = $trial->hasEodSurcharge;
    $hasWaitingList = $trial->hasWaitingList;
    $hasTimePenalty = $trial->hasTimePenalty;

    if($hasEntryLimit) {
        $entryLimit = $trial->entryLimit;
        $entriesLeft = $entryLimit - $numEntries;
    }

//  Entry opening and closing dates
    $date = date_create($trial->date);
    $formattedDate = date_format($date, "jS F, Y");
    $now = new DateTime();
    $openingDate = new DateTime($trial->openingDate);
    $closingDate = new DateTime($trial->closingDate);

    $closingDateFormatted = date_format($closingDate, "g:ia  F jS, Y");
    $openingDateFormatted = date_format($openingDate, "g:ia  F jS, Y");
    $entryStatus = "";
    $showButton = "";

//  Neither start nor finish
    if (($hasClosingDate == false) && ($hasOpeningDate == false)) {
        $showButton = "";
        $entriesOpen = true;
        $entryStatus .= "Entries are open until $formattedDate";
    }

//    Start date and finish
    if (($hasClosingDate == true) && ($hasOpeningDate == true)) {
//       Within range
        if (($openingDate < $now) && ($closingDate > $now)) {
            $showButton = "";
            $entryStatus .= "Entries are open until $closingDateFormatted";
        } //        Not open yet
        else if ($openingDate > $now) {
            $showButton = "hidden";
            $entryStatus .= "Registration will open at $openingDateFormatted";
        } //        Now closed
        else {
            $showButton = "hidden";
            $entryStatus = "Registration is now closed";
        }
    }

//    Start time only
    if (($hasClosingDate == false) && ($hasOpeningDate == true)) {
//        Not open yet
        if ($openingDate > $now) {
            $showButton = "hidden";
            $entryStatus .= "Registration will open at $openingDateFormatted";
        }
    }

//    Closing time only
    if (($hasClosingDate == true) && ($hasOpeningDate == false)) {
//        Not open yet
        if ($closingDate < $now) {
            $showButton = "hidden";
            $entryStatus = "Registration is now closed";
        }
    }

//    Entry limit
    if($hasEntryLimit && $entriesLeft == 0) {
        $showButton = "hidden";
        $entryStatus = "Registration is now closed as the entry limit has been reached ";
    }
    elseif ($hasEntryLimit && $entriesLeft == 1) {
        $entryStatus = "Final entry remaining!";
    }   elseif($hasEntryLimit && $entriesLeft <= 5) {
        $entryStatus = "Final $entriesLeft entries remaining! ";
    }

    if ($trial->stopNonStop == "Stop permitted") {
        $stopNonStop = "This trial will be a Stop Permitted trial.<br>";
    } else {
        $stopNonStop = "This trial will be a Non-Stop trial.<br>";
    }

    $isMultiDay = $trial->isMultiDay;
    $entryMethods = explode(',', $trial->entryMethod);

    $entryOptions = array();
    if (in_array("Enter on day", $entryMethods)) {
        if ($trial->hasEodSurcharge) {
            array_push($entryOptions, "Providing the entry limit is not reached, entry on the day will be available at this event and will be subject to a £$trial->eodSurcharge surcharge per entry.");
        } else {
            array_push($entryOptions, "Providing the entry limit is not reached, entry on the day will be available at this event.");
        }
    } if (in_array("TrialMonster", $entryMethods)) {
        array_push($entryOptions, "Enter through TrialMonster");
    } if (in_array('Online', $entryMethods)) {
        array_push($entryOptions, "Online entry is available - $trial->onlineEntryLink");
    }

    $entryOptionsHTML = implode('<br>', $entryOptions);
//    dd($entryOptionsHTML);
    $entrySelectionBasis = $trial->entrySelectionBasis;

//    Class and course options
//     Get all courses / classes
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
    $courseOptions = explode(',', $courselist);
    $classOptions = explode(',', $classlist);

    switch ($trial->restriction) {
        case "Open":
            $rest = "an Open ";
            break;
        case "Centre":
            $rest = "a Centre restricted ";
            break;
        case "Club":
            $rest = "a Closed to Club ";
        case "Other":
            $rest = "Restricted ";
            $rest .= "The trial will be restricted to " . $trial->otherRestriction;
            break;
        default:
            break;
    }

    switch ($trial->authority) {
        case "AMCA":
            $entryConditions = "";
            $machines = "Please see club website for machine specification and restrictions";
//            $methodOfMarking = "A machine will be deemed to be in the section when the front wheel has passed the Section Begins card and marks will be awarded until the back wheel has passed the Section Ends card. 0, 1, 2, 3, 5 system - Ties decided by most cleans, ones, twos, threes, furthest clean";
            break;
        case "ACU":
            $entryConditions = "All riders and passengers must hold a current ACU/SACU Trials Registration Card or ACU/SACU Competition Licence. Any rider or passenger from another FMN must produce a licence issued by their FMN, together with start permission and proof of personal accident insurance.";
            $machines = "Motorcycles as per NSC Appendix D Category 1, Group A1 Solos and TSR 8";
//            $methodOfMarking = "A machine will be deemed to be in an Observed Section or Sub-Section when the front wheel spindle has passed the
//‘Section Begins’ Card and until the front wheel spindle has passed the ‘Section Ends’ Card. Further information can be obtained from the ACU Handbook.";
        case "Other":
//            $methodOfMarking = "A machine will be deemed to be in an Observed Section or Sub-Section when the front wheel spindle has passed the
//‘Section Begins’ Card and until the front wheel spindle has passed the ‘Section Ends’ Card. Further information can be obtained from the ACU Handbook.";
            $entryConditions = "Restricted ";
            break;
        default:
            $entryConditions = "";
            $methodOfMarking="";
            break;
    }
    ?>
    <div class="text-blue-800 font-semibold text-center">{{$entryStatus}}</div>
    <x-button class="{{$showButton}}" href="/entries/register/{{$trial_id}}">Register</x-button>
    <div class="text-sm mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <gmp-map class="p-4  rounded-xl drop-shadow-lg "
                 center="{{$latitude}},{{$longitude}}"
                 zoom="12"
                 map-id="DEMO_MAP_ID"
                 style="height: 480px"
        >
            <gmp-advanced-marker
                    position="{{$latitude}},{{$longitude}}"
                    title="{{$trial->venue->name}}"
            ></gmp-advanced-marker>
        </gmp-map>

        <div class="text-base ml-4 mr-4 pt-2 font-semibold text-black text-center ">{{$trial->club}} - Affiliated to
            the {{$trial->authority}}</div>


        <div class="ml-4 mr-4 pt-0  text-black text-center ">Supplementary Regulations for the {{$trial->name}}</div>
    @if($series != null )
        <div class="ml-4 mr-4 mt-2 font-semibold">@php echo $series->notes; @endphp</div>
    @endif
        <div class="ml-4 mr-4 pt-2  text-black text-left ">{{$trial->club}} will organise
            {{$rest}} trial for solo motorcycles, held under the Rules of the {{$trial->authority}}, the following
            Supplementary Regulations and any Final Instructions issued for the meeting.
            Please take the time to carefully read any specific Safety Procedures produced by {{$trial->club}} which
            form part of the Supplementary Regulations. There will also be a Riders Briefing which all riders must
            attend ten minutes before the official start time.
        </div>
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">PERMIT: </span> {{$trial->permit}}</div>
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span class="font-semibold">DATE: </span> {{$formattedDate}}
        </div>
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">ELIGIBILITY: </span>This trial will be {{$rest}} trial. {{$entryConditions}}
        </div>
{{--        <div class="ml-4 mr-4 pt-2  text-black text-left "><span--}}
{{--                    class="font-semibold">MACHINES: </span>{{$machines}}--}}
{{--        </div>--}}
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">START / VENUE: </span>{{$trial->startTime}} at {{$trial->venue->name}}
            , {{$trial->venue->postcode}}
        </div>
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">DIRECTIONS: </span><?php echo $trial->venue->directions;
                                                             if ($trial->venue->notes != "") {
                                                                 echo $trial->venue->notes;
                                                             }
                                                             ?>
        </div>
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">WHAT3WORDS: </span><a
                    href="https://what3words.com/{{$trial->venue->w3w}}">{{$trial->venue->w3w}}&nbsp;<i
                        class="fa-solid fa-link"></i></a>
        </div>
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">CLASSES: </span>{{$classlist}}
        </div>
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">COURSES: </span>{{$courselist}}
        </div>
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">OFFICIALS: </span>Clerk of the Course: {{$trial->coc}}<br>Secretary of the
            Meeting (To whom all correspondence
            regarding this event shall be addressed): {{$trial->contactName}} <br><i class="fa-solid fa-envelope"></i>&nbsp;<a
                    href="mailto:{{$trial->email}}">{{$trial->email}}</a><br><i
                    class="fa-solid fa-phone"></i>&nbsp; {{$trial->phone}}<br>Point of Contact for Child Protection
            Matters: Secretary of the Meeting.
        </div>
        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">ENTRIES: </span>Adult entry fee: £{{$trial->adultEntryFee}}<br>Youth entry
            fee: £{{$trial->youthEntryFee}}<br>
            <?php echo $entryOptionsHTML;
            if ($trial->hasOpeningDate) {
                echo "<br>Opening date for entries: $openingDateFormatted";
            }
            if ($trial->hasClosingDate) {
                echo "<br>Closing date for entries: $closingDateFormatted";
            }
            ?>
        </div>
        <?php if ($trial->hasEntryLimit) { ?>

        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">ENTRY LIMIT: </span>This trial has a limited entry of {{$trial->entryLimit}}.
            In the event of the limit being exceeded, acceptance will be determined
            by <?php echo $entrySelectionBasis; ?>. Entrants will be informed by email once payment is received and
            their entry is confirmed.
        </div>

        <?php } ?>

        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">RESULTS: </span> Will be published on the <a href="http://trialmonster.uk">trialmonster.uk</a>
            website
        </div>

        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">COURSE: </span>Will comprise one or more laps of a course all on private land.
            No practising on this land before or after the event. The number of laps and sections will be announced at a
            riders’ briefing immediately before the event start.
        </div>

        <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                    class="font-semibold">SECTION MARKING AND SCORING: </span><?php echo "$stopNonStop $methodOfMarking"; ?>
        </div>
        @if($trial->hasNotes)

            <div class="ml-4 mr-4 pt-2  text-black text-left "><span
                        class="font-semibold">NOTES: </span><?php echo "$trial->notes"; ?>
            </div>
        @endif
    </div>

</x-main>