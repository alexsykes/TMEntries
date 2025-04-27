<x-club>
    <x-slot:heading>
       Club Control Panel
    </x-slot:heading>
    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="flex justify-between  w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">
            <div class="font-bold">Trial List</div>
            <div class="flex">
                <div class="text-sm">Scoring <span><i
                                class="ml-1 mr-3 fa-solid fa-pencil text-white"></i></span></div>
                <div class="text-sm">Entries <span><i
                                class="ml-1 mr-3 fa-solid fa-list-ol text-white"></i></span></div>
                <div class="text-sm">Show/Hide<span><i
                                class="ml-1 mr-3 fa-solid fa-eye text-white"></i></span></div>
                <div class="text-sm">Trial Setup<span><i
                                class="ml-1 mr-3 fa-solid fa-gear text-white"></i></span></div>
            </div>
        </div>
        <table class="w-full text-sm">
            @foreach($trials as $trial)
                @php
                    if ($trial->published) {
                        $publishIMG = "fa-solid fa-eye text-black";
                    } else {
                         $publishIMG = "fa-solid fa-eye-slash text-orange-700";
                    }

                @endphp
                <tr class="flex-auto odd:bg-white even:bg-gray-50  border-b ">
                    <td class="pl-4  pt-1  pb-1   hidden  md:table-cell">{{$trial->date}}</td>
                    <td class="hidden md:table-cell">{{$trial->club}}</td>
                    <td class="pl-2 table-cell">{{$trial->name}}</td>

                    <td class="pl-2 table-cell">
                        @if( $trial->isScoringLocked == 0 )
                            <a href="/scores/setup/{{$trial->id}}"><span><i
                                            class="fa-solid fa-pencil text-black"></i></span></a></td>
                    @else
                        <span><i class="fa-solid fa-lock text-orange-700"></i></span>
                        @endif
                        </td>
                        <td class="pl-2 table-cell">
                            @if( $trial->isEntryLocked == 0 )
                                <a href="/trials/adminEntryList/{{$trial->id}}"><span><i
                                                class="fa-solid fa-list-ol text-black"></i></span></a>
                            @else
                                <span><i class="fa-solid fa-lock text-orange-700"></i></span>
                            @endif
                        </td>

                        <td class="pl-2 table-cell"><a href="/trials/toggleVisibility/{{$trial->id}}"><span><i
                                            class="{{$publishIMG}}"></i></span></a></td>
                        <td class="pl-2 table-cell"><a href="/trials/edit/{{$trial->id}}"><span><i
                                            class="fa-solid fa-gear"></i></span></a></td>

                </tr>
            @endforeach
        </table>
    </div>
    <div class="mt-4" id="buttons">

        <a href="trials/add"
           class="rounded-md  ml-4 pt-2 pb-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
            Add a new trial
        </a>
    </div>
</x-club>