<x-admin>
    <x-slot:heading>Trial list</x-slot:heading>
    @php
        //        dump($trials);
    @endphp
    <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="flex justify-between  w-full pt-2 pb-2 pl-4 pr-2 rounded-t-xl  text-white bg-red-600">
            <div class="font-bold">Trial List</div>
            <div class="flex">
                <div class="hidden text-sm sm:block text-center">Trial</div>
                <div class="hidden pl-2 text-sm sm:block text-center">Scoring</div>
                <div class="hidden pl-2 text-sm sm:block text-center">Entries</div>
                <div class="hidden pl-2 text-sm sm:block text-center">Results</div>
                <div class="w-12 text-center text-sm sm:hidden">T</div>
                <div class="w-12 text-center text-sm sm:hidden">S</div>
                <div class="w-12 text-center text-sm sm:hidden">E</div>
                <div class="w-12 text-center text-sm sm:hidden">R</div>
            </div>
        </div>
        <div class="table  w-full text-sm">
            @foreach($trials as $trial)
                @php
//                    dump($trial);
                $rawDate = new DateTime($trial->date);
                $date  = date_format($rawDate, "jS F, Y");
                @endphp

                <div class="border-b table-row pr-2">
                    <div class="hidden border-b pt-1 pb-1 pl-2 md:table-cell  ">{{$date}}</div>
                    <div class="hidden border-b  pr-2 pl-2 md:table-cell ">{{$trial->club}}</div>
                    <div class="border-b pt-1 pb-1 pr-2 pl-2 table-cell">{{$trial->name}}</div>
                    <div class="border-b w-12   table-cell text-center">
                        <a class="table-cell w-full" href="/admin/trial/edit/{{$trial->id}}"><span><i class="fa-solid fa-gear text-black"></i></span></a></div>
                    <div class="border-b w-12   table-cell text-center">
                        <a href="/admin/trial/toggleLock/{{$trial->id}}">
                            @if( $trial->isLocked == 0 )<span><i class="fa-solid fa-lock-open text-green-500"></i></span>

                            @else
                                <span><i class="fa-solid fa-lock text-red-600"></i></span>
                            @endif
                        </a>
                    </div>

                    <div class="border-b w-12   table-cell text-center">
                        <a href="/admin/trial/toggleScoring/{{$trial->id}}">
                            @if( $trial->isScoringLocked == 0 )
                                <span><i class="fa-solid fa-lock-open text-green-500"></i></span>

                            @else
                                <span><i class="fa-solid fa-lock text-red-600"></i></span>
                            @endif
                        </a>
                    </div>
                    <div class="border-b w-12   table-cell text-center">
                        <a href="/admin/trial/toggleEntry/{{$trial->id}}">
                            @if( $trial->isEntryLocked == 0 )<span><i class="fa-solid fa-lock-open text-green-500"></i></span>

                            @else
                                <span><i class="fa-solid fa-lock text-red-600"></i></span>
                            @endif
                        </a>
                    </div>

                    <div class="border-b w-12   table-cell text-center">
                        <a href="/admin/trial/toggleResultPublished/{{$trial->id}}">
                            @if( $trial->isResultPublished == 0 )
                                <span><i class="fa-solid fa-lock-open text-green-500"></i></span>

                            @else
                                <span><i class="fa-solid fa-lock text-red-600"></i></span>
                            @endif
                        </a>
                    </div>


                </div>

            @endforeach
        </div>
    </div>

</x-admin>