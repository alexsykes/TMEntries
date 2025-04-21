<x-club>
    <x-slot:heading>Scoring grid</x-slot:heading>
    @php

        $numRows    = $trial->numRows;
        $numColumns = $trial->numColumns;
        $numSections = $trial->numSections;
        $numLaps = $trial->numLaps;

        $numRiders = $numRows * $numColumns;
    @endphp
    <style>
        table {
            table-layout: fixed;
        }
    </style>
    <form method="post" action="/scores/confirmPublish">
        @csrf
        <input type="hidden" id="trialID" name="trialID" value="{{$trial->id}}">
        <a href="/adminTrials"
           class="rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-violet-900 shadow-sm hover:bg-violet-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-900">Cancel</a>
        <button type="submit"
                class="rounded-md ml-2 bg-red-600 px-3 py-1 text-sm font-light  border border-red-800 text-white drop-shadow-lg hover:bg-red-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
            Publish
        </button>
    </form>
    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Score grid</div>
        {{--        <form action="/scores/save">--}}
        {{--            @csrf--}}
        {{--            <input type="hidden" id="trialID" name="trialID" value="{{$trial->id}}">--}}

        <table class=" w-full ">
            <tr>
                <th>&nbsp;</th>
                @php
                    for($section = 1; $section<=$numSections; $section++) {
                        $slug = "/scores/sectionScores/$trial->id/$section";
                        echo "<th><a href=\"$slug\">$section</a></th>";
                    }

                @endphp
            </tr>
            @foreach($scores as $score)
                @php
                    $rider = $score->rider;
                    if(!in_array($rider, $riderNumbers)) {
                @endphp
                <tr class="text-red-500 flex-auto odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b ">
                @php
                    } else {
                @endphp
                <tr class="flex-auto odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b">
                    @php
                        }
                    @endphp


                    <td class="pl-4 text-right  pr-2">{{$rider}}</td>
                        <?php
                        $sectionScores = str_split($score->scoreData, $numLaps);
                        for ($section = 1; $section <= $numSections; $section++) {
                            $s = $section - 1;
                            $slug = "/scores/sectionScoresForRider/$trial->id/$score->rider/$section";
                            echo "<td class=\"text-center\"><a href=\"$slug\">$sectionScores[$s]</a></td>";
                        }
                        ?>
                </tr>
            @endforeach
        </table>
        {{--        </form>--}}
    </div>
</x-club>