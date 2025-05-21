<x-admin>
    <x-slot:heading>Club list</x-slot:heading>

    @php
        //    dump($results);
    @endphp
    <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-red-600">Clubs</div>
        <table class="w-full">
            @foreach($clubs as $club)

                <tr class="pr-4 odd:bg-white  even:bg-gray-50  border-b ">
{{--                    <td class="text-sm hidden md:table-cell  pl-4 pt-1 pb-1">{{$club->id}}</td>--}}
                    <td class="text-sm hidden md:table-cell  pl-4 pt-1 pb-1"><a href="/club/detail/{{$club->id}}">{{$club->name}}</a></td>
                    <td class="text-sm hidden md:table-cell  pl-4 pt-1 pb-1"><a href="/club/detail/{{$club->id}}">{{$club->area}}</a></td>
                    <td class="text-sm hidden md:table-cell  pl-4 pt-1 pb-1"><a href="mailto:{{$club->email}}?subject=Web Enquiry">{{$club->email}}</a></td>
                    <td class="text-sm hidden md:table-cell  pl-4 pt-1 pb-1"><a href="/club/edit/{{$club->id}}">Edit</a></td>
{{--                    <td class="table-cell text-sm  pl-4">--}}
{{--                        <a href="/results/display/{{$club->id}}"><span><i class="ml-1 mr-3 fa-solid fa-eye "></i></span></a>--}}
{{--                    </td>--}}
{{--                    <td class="table-cell text-sm  pl-4">--}}
{{--                        <a href="/results/edit/{{$club->id}}"><span><i class="ml-1 mr-3 fa-solid fa-pencil"></i></span></a>--}}
{{--                    </td>--}}
                </tr>
            @endforeach
        </table>

    </div>
    <div class="mt-4" id="buttons">

        <a href="/clubs/add"
           class="rounded-md  bg-red-600 px-3 py-2 text-sm font-light  border border-red-800 text-white drop-shadow-lg hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
            Add a new club
        </a>
    </div>
</x-admin>
