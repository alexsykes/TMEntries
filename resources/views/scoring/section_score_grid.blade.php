<x-club>
    <x-slot:heading>Section Scores</x-slot:heading>
    @php
        $numLaps = $trial->numLaps;
        $numColumns = $trial->numColumns;
        $numRows = $trial->numRows;
        $trialID = $trial->id;

        $sequence = 0;

    @endphp
    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">
            Section {{$section}}</div>
        <form action="/scores/updateSectionScores" method="post">
            @method('PATCH')
            <input type="hidden" id="trialID" name="trialID" value="{{$trialID}}">
            <input type="hidden" id="section" name="section" value="{{$section}}">
            @csrf
            <div class=" m-4" id="topbuttons">
                <a href="/scores/grid/{{$trial->id}}"
                   class="rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-violet-900 shadow-sm hover:bg-violet-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-900">Cancel</a>

                <button type="submit"
                        class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                    Save
                </button>
            </div>
            <table class=" w-full">
                @for($row = 1; $row <= $numRows; $row++)

                    <tr class="flex-auto even:bg-white odd:dark:bg-gray-900 odd:bg-gray-50 even:dark:bg-gray-800 border-b">
                        @for($column = 0; $column < $numColumns; $column++)
                            @php
                                $index = $row + ($column * $numRows);
                            @endphp
                            <td class="pl-2 font-semibold text-violet-800">{{$index}}</td>
                            <td><input class="border w-24 p-1" name="scores[]"
                                       pattern="[Xox01235]{0,<?php echo $numLaps; ?>}"
                                       id="scores[]"
                                       tabIndex="{{$index}}"
                                       title="Please check that you have no more than {{$numLaps}} scores."
                                       type="text"
                                       value="{{$scores[$index-1]->scores}}">
                                <input type="hidden" name="scoreIDs[]" id="scoreIDs[]"
                                       value="{{$scores[$index-1]->ids}}">
                            </td>
                        @endfor

                    </tr>

                @endfor
            </table>
            <div class=" m-4" id="buttons">
                <a href="/scores/grid/{{$trial->id}}"
                   class="rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-violet-900 shadow-sm hover:bg-violet-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-900">Cancel</a>

                <button type="submit"
                        class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                    Save
                </button>
            </div>
        </form>

    </div>


</x-club>