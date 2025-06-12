<x-club>
    <x-slot:heading>{{$clubName}} Competitions</x-slot:heading>
@php
 @endphp
    <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Competition</div>

        <table class="w-full">
            @foreach($series as $serie)
                <tr class="flex-auto odd:bg-white  even:bg-gray-50  border-b ">
                    <td class="text-sm pl-2 table-cell">{{$serie->name}}</td>
                    <td class="text-sm pl-2 table-cell">{{$serie->courses}}</td>
                    <td class="text-sm pl-2 table-cell">{{$serie->classes}}</td>
{{--                    <td class="text-sm pl-2 table-cell"><a href="/series/detail/{{$serie->id}}"><span class="fa-solid fa-eye"></span></a></td>--}}
                    <td class="text-sm pl-2 table-cell"><a href="/series/edit/{{$serie->id}}"><span class="fa-solid fa-pencil"></span></a></td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="mt-4" id="buttons">

        <a href="/series/add"
           class="rounded-md  bg-violet-600 px-3 py-2 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
            Add a new competition
        </a>
    </div>
</x-club>
