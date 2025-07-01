<x-main>
    <x-slot:heading>{{$trial->name}}</x-slot:heading>
    @php
        $owner = (Auth::id());
            $canEdit = false;
        if(($owner == $trial->created_by)  && ($trial->isResultPublished)) {
            $canEdit = true;
        }

            $fifty_fifty = $trial->fifty_fifty;
        $hasFifty = false;
        $fiftyArray = array();
            If($fifty_fifty != "") {
                $hasFifty = true;
                $fiftyArray = explode(",", $fifty_fifty);
//                dump($fiftyArray);
            }
            $courselist = $trial->courselist;
            $classlist = $trial->classlist;
            $numsections = $trial->numSections;
            $numlaps = $trial->numLaps;
                        $rawDate = new DateTime($trial->date);
                        $date  = date_format($rawDate, "jS F, Y");
                        $rawDate = new DateTime($trial->updated_at);
                        $updated  = date_format($rawDate, "H:ia jS F, Y");
            $nonStarterArray = array();
        foreach($nonStarters as $notStarter) {
            array_push($nonStarterArray, $notStarter->name);
        }
            $nonStarterList = implode(', ', $nonStarterArray);

        //        dump($trial);
    @endphp

    <div class="tab pl-8">
        <button class="tablinks border border-black border-b-0 rounded-t-lg   hover:bg-blue-500 p-1" id="defaultOpen"
                onclick="openSection(event, 'Results')">
            Course Results
        </button>
        <button class="tablinks border border-black border-b-0 rounded-t-lg   hover:bg-blue-500 p-1" id="defaultOpen"
                onclick="openSection(event, 'Class')">
            Class Results
        </button>
        <button class="tablinks border border-black border-b-0 rounded-t-lg    hover:bg-blue-500 p-1  "
                onclick="openSection(event, 'New Scores')">Scores
        </button>
        {{--        <button class="tablinks border border-black border-b-0 rounded-t-lg    hover:bg-blue-500 p-2  " onclick="openSection(event, 'Classes')">Class Results</button>--}}
    </div>
    <div id="Results" class="tabcontent pt-0 ">
        @for($course=0;  $course < sizeof($courses); $course++)
            @if(sizeof($courseResults[$course]) > 0)

                <div class=" mt-0 mb-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
                    <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">{{$courses[$course]}}</div>
                    <table class="w-full text-sm">
                        <tr class="pr-4 odd:bg-white  even:bg-gray-50  border-b ">
                            <th class="pl-2 text-right w-10  table-cell">&nbsp;</th>
                            <th class=" w-10 text-right table-cell pr-2">&nbsp;</th>
                            <th class="table-cell">&nbsp;</th>
                            <th class="hidden md:table-cell w-2/12">&nbsp;</th>
                            <th class=" hidden md:table-cell w-2/12">&nbsp;</th>
                            <th class="w-10 text-center table-cell">Total</th>
                            <th class="w-10 text-center table-cell">C</th>
                            <th class="w-10 text-center table-cell">1</th>
                            <th class="w-10 text-center table-cell">2</th>
                            <th class="w-10 text-center table-cell">3</th>
                            <th class="w-10 text-center table-cell">5</th>
                            <th class="pr-4 w-14 text-center table-cell">M</th>
                            @if($canEdit)
                                <th class="table-cell">&nbsp;</th>
                            @endif
                        </tr>
                        @foreach($courseResults[$course] as $courseResult)
                            {{--                    {{dd($courseResult)}}--}}
                            @php
                                $class = $courseResult->class;
                                if ($class=="Adult" )  {
                                    $class="";
                                }
                                $dnf = $courseResult->resultStatus;
                                $pos = $dnf == 0 ? $courseResult->pos : "DNF";
                                $total = $dnf == 0 ? $courseResult->total : "";
                            @endphp
                            <tr class="pr-4 odd:bg-white  even:bg-gray-50  border-b ">
                                <td class="pl-2 text-right w-10  table-cell font-semibold">{{$pos}}</td>
                                <td class=" w-10 text-right table-cell pr-2">{{$courseResult->rider}}</td>
                                <td class="table-cell">{{$courseResult->name}}</td>
                                <td class="hidden md:table-cell w-2/12">{{$courseResult->machine}}</td>
                                <td class=" hidden md:table-cell w-2/12">{{ $class  }}</td>
                                <td class="w-10 font-semibold text-center table-cell">{{$total}}</td>
                                <td class="w-10 text-center table-cell">{{$courseResult->cleans}}</td>
                                <td class="w-10 text-center table-cell">{{$courseResult->ones}}</td>
                                <td class="w-10 text-center table-cell">{{$courseResult->twos}}</td>
                                <td class="w-10 text-center table-cell">{{$courseResult->threes}}</td>
                                <td class="w-10 text-center table-cell">{{$courseResult->fives}}</td>
                                <td class="pr-4 w-14 text-center table-cell">{{$courseResult->missed}}</td>
                                @if($canEdit)
                                    <td class=" table-cell"><span><a href="/result/edit/{{$courseResult->entryID}}"><i
                                                        class=" fa-solid fa-pencil "/></a></span></td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif
        @endfor

        {{--        <div class="pl-2 pr-2 text-sm">Non starters: {{$nonStarterList}}</div>--}}
    </div>
    <div id="Class" class="tabcontent pt-0 ">
        @for($index=0;  $index < sizeof($resultsByClass); $index++)
            @php  $course = $resultsByClass[$index][0];
        $class = $resultsByClass[$index][1];
        $classResults = $resultsByClass[$index][2];
        $title = "$course - $class";

//        dump($classResults);
            @endphp
            @if(sizeof($classResults) > 0)

                <div class=" mt-0 mb-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
                    <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">{{$title}}</div>
                    <table class="w-full text-sm">
                        <tr class="pr-4 odd:bg-white  even:bg-gray-50  border-b ">
                            <th class="pl-2 text-right w-10  table-cell">&nbsp;</th>
                            <th class=" w-10 text-right table-cell pr-2">&nbsp;</th>
                            <th class="table-cell">&nbsp;</th>
                            <th class="hidden md:table-cell w-2/12">&nbsp;</th>
                            <th class="w-10 text-center table-cell">Total</th>
                            <th class="w-10 text-center table-cell">C</th>
                            <th class="w-10 text-center table-cell">1</th>
                            <th class="w-10 text-center table-cell">2</th>
                            <th class="w-10 text-center table-cell">3</th>
                            <th class="w-10 text-center table-cell">5</th>
                            <th class="pr-4 w-14 text-center table-cell">M</th>
                            @if($canEdit)
                                <th class="table-cell">&nbsp;</th>
                            @endif
                        </tr>
                        @foreach($classResults as $courseResult)

                            @php

                                $dnf = $courseResult->resultStatus;
                                $pos = $dnf == 0 ? $courseResult->pos : "DNF";
                                $total = $dnf == 0 ? $courseResult->total : "";
                            @endphp
                            <tr class="pr-4 odd:bg-white  even:bg-gray-50  border-b ">
                                <td class="pl-2 text-right w-10  table-cell font-semibold">{{$pos}}</td>
                                <td class=" w-10 text-right table-cell pr-2">{{$courseResult->rider}}</td>
                                <td class="table-cell">{{$courseResult->name}}</td>
                                <td class="hidden md:table-cell w-2/12">{{$courseResult->machine}}</td>
                                <td class="w-10 font-semibold text-center table-cell">{{$total}}</td>
                                <td class="w-10 text-center table-cell">{{$courseResult->cleans}}</td>
                                <td class="w-10 text-center table-cell">{{$courseResult->ones}}</td>
                                <td class="w-10 text-center table-cell">{{$courseResult->twos}}</td>
                                <td class="w-10 text-center table-cell">{{$courseResult->threes}}</td>
                                <td class="w-10 text-center table-cell">{{$courseResult->fives}}</td>
                                <td class="pr-4 w-14 text-center table-cell">{{$courseResult->missed}}</td>
                                @if($canEdit)
                                    <td class=" table-cell"><span><a href="/result/edit/{{$courseResult->entryID}}"><i
                                                        class=" fa-solid fa-pencil "/></a></span></td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif
        @endfor

        {{--        <div class="pl-2 pr-2 text-sm">Non starters: {{$nonStarterList}}</div>--}}
    </div>

    <div id="Scores" class="tabcontent pt-0 ">
        @php
            if(sizeof($courses) > 0) {
        @endphp
        @for($course=0;  $course < sizeof($courses); $course++)
            @if(sizeof($courseResults[$course]) > 0)
                <div class=" mt-0 mb-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
                    <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">{{$courses[$course]}}</div>
                    <table class="w-full text-sm">
                        <tr class="pr-4 odd:bg-white  even:bg-gray-50  border-b ">
                            <th class="pl-2 text-right w-10  table-cell">&nbsp;</th>
                            <th class=" w-10 text-right table-cell pr-2">&nbsp;</th>
                            <th class="table-cell">&nbsp;</th>
                            <th class="w-10 pr-4 font-semibold text-center table-cell">T</th>
                            @for($index = 1; $index <= $numsections; $index++)
                                <th class="w-10 pr-4 table-cell text-center">{{$index}}</th>
                            @endfor
                        </tr>
                        @foreach($courseResults[$course] as $courseResult)
                            @php
                                $sectionsScores = $courseResult->sectionScores;
                                $scoreArray = str_split($sectionsScores, $numlaps);
                                $dnf = $courseResult->resultStatus;
                                $pos = $dnf == 0 ? $courseResult->pos : "DNF";
                                $total = $dnf == 0 ? $courseResult->total : "";
                            @endphp
                            <tr class="pr-4 odd:bg-white  even:bg-gray-50  border-b ">
                                <td class="pl-2 text-right w-10  table-cell font-semibold">{{$pos}}</td>
                                <td class=" w-10 text-right table-cell pr-2">{{$courseResult->rider}}</td>
                                <td class="table-cell">{{$courseResult->name}}</td>
                                <td class="w-10 pr-4 font-semibold text-center table-cell">{{$courseResult->total}}</td>

                                @for($index = 0; $index < $numsections; $index++)
                                    <td class="w-10 pr-4 text-center table-cell">{{$scoreArray[$index]}}</td>
                                @endfor

                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif
        @endfor
        @php
            }
        @endphp
    </div>
    <div id="New Scores" class="tabcontent pt-0 ">

        @if(sizeof($resultsByClass) > 0)

            @for($course=0;  $course < sizeof($resultsByClass); $course++)
                @if(sizeof($resultsByClass[$course]) > 0)
                    @foreach($resultsByClass as $classResultArray)
                        @if(sizeof($classResultArray[2]) > 0)
                            @php
                                $course = $classResultArray[0];
                                $class = $classResultArray[1];
                                $resultArray = $classResultArray[2];

                                if($course == "50/50") {
                                    $fifty = true;
                                } else { $fifty = false; }
                            @endphp

                            <div class=" mt-0 mb-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
                                @if($hasFifty && $course == "50/50")
                                    <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">@php echo "$course - $class"; @endphp - bold scores indicate harder sections</div>

                                    @else
                                <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">@php echo "$course - $class"; @endphp</div>
                                @endif
                                <table class="w-full text-sm">
                                    <tr class="pr-4 odd:bg-white  even:bg-gray-50  border-b ">
                                        <th class="pl-2 text-right w-10  table-cell">&nbsp;</th>
                                        <th class=" w-10 text-right table-cell pr-2">&nbsp;</th>
                                        <th class="table-cell">&nbsp;</th>
                                        <th class="w-10 pr-4 font-semibold text-center table-cell">T</th>
                                        @for($index = 1; $index <= $numsections; $index++)
                                            <th class="w-10 pr-4 table-cell text-center">{{$index}}</th>
                                        @endfor
                                    </tr>

                                    @foreach($resultArray as $result)
                                        @php
                                            $sectionsScores = $result->sectionScores;
                                            $scoreArray = str_split($sectionsScores, $numlaps);
                                            $dnf = $result->resultStatus;
                                            $pos = $dnf == 0 ? $result->pos : "DNF";
                                            $total = $dnf == 0 ? $result->total : "";
                                        @endphp
                                        <tr class="pr-4 odd:bg-white  even:bg-gray-50  border-b ">
                                            <td class="pl-2 text-right w-10  table-cell font-semibold">{{$pos}}</td>
                                            <td class=" w-10 text-right table-cell pr-2">{{$result->rider}}</td>
                                            <td class="table-cell">{{$result->name}}</td>
                                            <td class="w-10 pr-4 font-semibold text-center table-cell">{{$total}}</td>

                                            @for($index = 0; $index < $numsections; $index++)
                                                @if((in_array($index + 1, $fiftyArray) && $fifty))
                                                <td class="w-10 pr-4 text-center font-bold  table-cell">{{$scoreArray[$index]}}</td>
                                                @else
                                                    <td class="w-10 pr-4 text-center table-cell">{{$scoreArray[$index]}}</td>
                                                @endif
                                            @endfor

                                        </tr>

                                    @endforeach

                                </table>
                            </div>
                        @endif
                    @endforeach
                @endif
            @endfor
        @endif
    </div>


    <div class="text-black  pt-0 text-sm">
        <div>{{$trial->club}}</div>
        <div>{{$trial->venue}}</div>
        <div>Permit: {{$trial->permit}}</div>
        <div>Last updated: {{$updated}}</div>
    </div>
    <script>
        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>
</x-main>