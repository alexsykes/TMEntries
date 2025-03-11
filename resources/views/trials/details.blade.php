<x-main>
    <x-slot:heading>
        {{$trial->name}}
    </x-slot:heading>
    <?php
    //                dump($trial);
    $latitude = $trial->venue->latitude;
    $longitude = $trial->venue->longitude;
    $markerArray = array();
    $marker = array($latitude, $longitude);
    array_push($markerArray, $marker);


    // Trial details
    $date = date_create($trial->date);
    $formattedDate = date_format($date, "jS F, Y");
    session(['trial_id' => $trial->id]);

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
            break;
        case "ACU":
            $entryConditions = "All riders and passengers must hold a current ACU/SACU Trials Registration Card or ACU/SACU Competition Licence. Any rider or passenger from another FMN must produce a licence issued by their FMN, together with start permission and proof of personal accident insurance.";
            break;
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
    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
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

    </div>

</x-main>