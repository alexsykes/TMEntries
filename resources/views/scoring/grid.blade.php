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
    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Score grid</div>
        <form action="/scores/save">
            @csrf
            <input type="hidden" id="trialID" name="trialID" value="{{$trial->id}}">

            <table class="m-4 w-full ">
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
                    <tr>
                        <td>{{$score->rider}}</td>
                            <?php
                            $sectionScores = str_split($score->scoreData, $numLaps);
                            for($section = 1; $section <= $numSections; $section++) {
                                $s = $section - 1;
                                $slug = "/scores/sectionScoresForRider/$trial->id/$score->rider/$section";
                                echo "<td><a href=\"$slug\">$sectionScores[$s]</a></td>";
                            }
                            ?>
                    </tr>
                @endforeach
            </table>
        </form>
    </div>
</x-club>