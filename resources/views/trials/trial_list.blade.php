<x-main>
    <x-slot:heading>
        TrialMonster UK
    </x-slot:heading>
    <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">

        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Coming up…</div>
            <table class="w-full">
                @foreach($trials as $trial)
                    @php
                        $date = date_format(date_create($trial->date), "M jS, Y");
                    @endphp
{{--                   <tr class="flex-auto pt-2 pb-2 odd:bg-white  even:bg-gray-50  border-b ">--}}
                <tr class="pr-4 odd:bg-white  even:bg-gray-50  border-b ">
                    <td class="text-sm hidden sm:table-cell  pl-4 pt-1 pb-1"><a href="/trial/details/{{$trial->id}}">{{$date}}</a></td>
                        <td class="text-sm hidden md:table-cell"><a href="/trial/details/{{$trial->id}}">{{$trial->club}}</a></td>
                        <td class="text-sm pl-2 table-cell"><a href="/trial/details/{{$trial->id}}">{{$trial->name}}</a></td>
                        <td title="Entry list" class="text-sm underline table-cell" ><a href="/trial/{{$trial->id}}/entrylist">Entry list</a></td>
{{--                        <td title="Enter" class="table-cell "><a href="/trial/details/{{$trial->id}}"><span><i class="text-xl  fa-solid fa-circle-info"></i></span></a></td>--}}
                    </tr>
                @endforeach
            </table>
        <div class="font-bold w-full pt-2 pl-4 pr-4 rounded-b-xl  text-blue-600">For full details  and to enter, click on the trial name</div>
        </div>
</x-main>