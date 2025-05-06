<x-club>

    <x-slot:heading>
        Club Control Panel
    </x-slot:heading>
    @php
        // dd($trials);
    @endphp
    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="flex justify-between  w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">
            <div class="font-bold">Trial List</div>

            <div class="flex justify-between">
                <div class=" pl-4 pr-4 text-sm">Scoring <span><i
                                class="ml-1 mr-3 fa-solid fa-pencil "></i></span></div>
                <div class=" pl-4 pr-4 text-sm">Entries <span><i
                                class="ml-1 mr-3 fa-solid fa-list-ol "></i></span></div>
                <div class=" pl-4 pr-4 text-sm">Show/Hide<span><i
                                class="ml-1 mr-3 fa-solid fa-eye "></i></span></div>
                <div class=" pl-4 pr-4 text-sm">Trial Setup<span><i
                                class="ml-1 mr-3 fa-solid fa-gear "></i></span></div>
                <div class=" pl-4 pr-4 text-sm">Info<span><i
                                class="ml-1 mr-3 fa-solid fa-circle-info"></i></span></div>

            </div>
        </div>
        <table class="w-full text-sm">
            @foreach($trials as $trial)
                @php
                    if ($trial->published) {
                        $publishIMG = "fa-solid fa-eye text-violet-800";
                    } else {
                         $publishIMG = "fa-solid fa-eye-slash text-gray-600";
                    }

                @endphp
                <tr class="flex-auto odd:bg-white even:bg-gray-50  border-b ">
                    <td class="pl-4  pt-1  pb-1   hidden  md:table-cell">{{$trial->date}}</td>
                    <td class="hidden md:table-cell">{{$trial->club}}</td>
                    <td class="pl-2 table-cell">{{$trial->name}}</td>

                    <td class="pl-2 table-cell">
                        <a href="/scores/setup/{{$trial->id}}">
                            @if( $trial->isScoringLocked == 0 )
                                <span><i class="fa-solid fa-pencil text-violet-800"></i></span>
                            @else
                                <span><i class="fa-solid fa-pencil text-red-600"></i></span>
                            @endif
                        </a>
                    </td>
                    <td class="pl-2 table-cell"><a href="/trials/adminEntryList/{{$trial->id}}">
                            @if( $trial->isEntryLocked == 0 )
                                <span><i class="fa-solid fa-list-ol text-violet-800"></i></span>
                            @else
                                <span><i class="fa-solid fa-list text-red-600"></i></span>
                            @endif
                        </a>
                    </td>

                    <td class="pl-2 table-cell"><a href="/trials/toggleVisibility/{{$trial->id}}"><span><i
                                        class="{{$publishIMG}}"></i></span></a></td>

                    <td class="pl-2 table-cell"><a href="/trials/edit/{{$trial->id}}">
                            @if( $trial->isLocked == 0 )
                            <span><i class="fa-solid text-violet-800 fa-gear"></i></span>
                        @else
                            <span><i class="fa-solid text-red-600 fa-gear"></i></span>
                        @endif
                        </a>
                    </td>

                    <td class="pl-2 table-cell"><a href="/trials/info/{{$trial->id}}"><span><i
                                        class="fa-solid fa-circle-info text-violet-800"></i></span></a></td>

                </tr>
            @endforeach
        </table>
    </div>
    <div class="flex justify-between">
    <div class="mt-4" id="buttons">
        <a href="trials/add"
           class="rounded-md  ml-4 pt-2 pb-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
            Add a new trial
        </a>
    </div>
    <div class="pt-4 text-sm text-red-600">Red link - You can visit the page but no changes can be made.</div>
    </div>
</x-club>