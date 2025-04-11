<x-main>
    <x-slot:heading>{{$trial->name}}</x-slot:heading>
@php
    $courselist = $trial->courselist;
    $classlist = $trial->classlist;
    $numsections = $trial->numSections;
    $numlaps = $trial->numLaps;
@endphp
    <div class="tab pl-8">
        <button class="tablinks border border-black border-b-0 rounded-t-lg   hover:bg-blue-500 p-1" id="defaultOpen" onclick="openSection(event, 'Results')">
            Results
        </button>
        <button class="tablinks border border-black border-b-0 rounded-t-lg    hover:bg-blue-500 p-1  " onclick="openSection(event, 'Scores')">Scores</button>
{{--        <button class="tablinks border border-black border-b-0 rounded-t-lg    hover:bg-blue-500 p-2  " onclick="openSection(event, 'Classes')">Class Results</button>--}}
    </div>
    <div id="Results" class="tabcontent pt-0 ">
        @for($course=0;  $course < sizeof($courses); $course++)
            <div class=" mt-0 mb-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
                <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">{{$courses[$course]}}</div>
                <table class="w-full">
                    <tr class="pr-4 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b ">
                        <th class="pl-2 text-right w-10  table-cell">&nbsp;</th>
                        <th class=" w-10 text-right table-cell pr-2">&nbsp;</th>
                        <th class="table-cell">&nbsp;</th>
                        <th class="hidden md:table-cell w-2/12">&nbsp;</th>
                        <th class=" hidden md:table-cell w-2/12">&nbsp;</th>
                        <th class="w-10 text-right table-cell">&nbsp;</th>
                        <th class="w-10 text-center table-cell">C</th>
                        <th class="w-10 text-center table-cell">1</th>
                        <th class="w-10 text-center table-cell">2</th>
                        <th class="w-10 text-center table-cell">3</th>
                        <th class="w-10 text-center table-cell">5</th>
                        <th class="pr-4 w-14 text-center table-cell">M</th>
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
                        <tr class="pr-4 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b ">
                            <td class="pl-2 text-right w-10  table-cell font-semibold">{{$pos}}</td>
                            <td class=" w-10 text-right table-cell pr-2">{{$courseResult->rider}}</td>
                            <td class="table-cell">{{$courseResult->name}}</td>
                            <td class="hidden md:table-cell w-2/12">{{$courseResult->machine}}</td>
                            <td class=" hidden md:table-cell w-2/12">{{ $class  }}</td>
                            <td class="w-10 font-semibold text-right table-cell">{{$total}}</td>
                            <td class="w-10 text-right table-cell">{{$courseResult->cleans}}</td>
                            <td class="w-10 text-right table-cell">{{$courseResult->ones}}</td>
                            <td class="w-10 text-right table-cell">{{$courseResult->twos}}</td>
                            <td class="w-10 text-right table-cell">{{$courseResult->threes}}</td>
                            <td class="w-10 text-right table-cell">{{$courseResult->fives}}</td>
                            <td class="pr-4 w-14 text-right table-cell">{{$courseResult->missed}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endfor
    </div>

    <div id="Scores" class="tabcontent pt-0 ">
        @for($course=0;  $course < sizeof($courses); $course++)
            <div class=" mt-0 mb-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
                <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">{{$courses[$course]}}</div>
                <table class="w-full">
                    <tr class="pr-4 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b ">
                        <th class="pl-2 text-right w-10  table-cell">&nbsp;</th>
                        <th class=" w-10 text-right table-cell pr-2">&nbsp;</th>
                        <th class="table-cell">&nbsp;</th>
                        <th class="table-cell">T</th>
                        @for($index = 1; $index <= $numsections; $index++)
                        <th class="pr-2 table-cell">{{$index}}</th>
                        @endfor
                    </tr>
                    @foreach($courseResults[$course] as $courseResult)
                        {{--                    {{dd($courseResult)}}--}}
                        @php
                            $sectionsScores = $courseResult->sectionScores;
                            $scoreArray = str_split($sectionsScores, $numlaps);
                        @endphp
                        <tr class="pr-4 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b ">
                            <td class="pl-2 text-right w-10  table-cell font-semibold">{{$courseResult->pos}}</td>
                            <td class=" w-10 text-right table-cell pr-2">{{$courseResult->rider}}</td>
                            <td class="table-cell">{{$courseResult->name}}</td>
                            <td class="w-10 pr-4 font-semibold text-right table-cell">{{$courseResult->total}}</td>

                            @for($index = 0; $index < $numsections; $index++)
                                <td class="w-10 pr-4 text-right table-cell">{{$scoreArray[$index]}}</td>
                            @endfor

                        </tr>
                    @endforeach
                </table>
            </div>
        @endfor
    </div>
    <script>
        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>
</x-main>