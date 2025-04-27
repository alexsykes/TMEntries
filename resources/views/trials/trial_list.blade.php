<x-main>
    <x-slot:heading>
        TrialMonster UK
    </x-slot:heading>
    <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">

        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Coming up…</div>
            <table class="w-full">

                @foreach($trials as $trial)
                   <tr class="flex-auto odd:bg-white  even:bg-gray-50  border-b ">
                        <td class="pl-2 text-sm hidden md:table-cell"><a href="/trial/details/{{$trial->id}}">{{$trial->date}}</a></td>
                        <td class="text-sm hidden md:table-cell"><a href="/trial/details/{{$trial->id}}">{{$trial->club}}</a></td>
                        <td class="text-sm pl-2 table-cell"><a href="/trial/details/{{$trial->id}}">{{$trial->name}}</a></td>
                        <td title="Entry list" class="table-cell" ><a href="/trial/{{$trial->id}}/entrylist"><span><i class="text-xl  fa-solid fa-list-ul"></i></span></a></td>
                        <td title="Register" class="table-cell "><a href="/trial/details/{{$trial->id}}"><span><i class="text-xl  fa-solid fa-circle-info"></i></span></a></td>
                    </tr>
                @endforeach
            </table>
        </div>
</x-main>