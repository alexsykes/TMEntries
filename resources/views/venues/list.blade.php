<x-admin    >
    <x-slot:heading>
        Venues
    </x-slot:heading>
    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-red-600">Venue list</div>
        <table class="w-full text-sm">
            @foreach($venues  as $venue)
                <div id="triallist" class="flex-auto">
                    <tr class="pr-4 odd:bg-white  even:bg-gray-50  border-b ">
                        <td class="pl-2 md:table-cell">{{$venue->name}}</td>
                        <td class="hidden md:table-cell">{{$venue->landowner}}</td>
                        <td class="hidden md:table-cell">{{$venue->phone}}</td>
                        <td class="table-cell"><a href="/venues/edit/{{$venue->id}}"><span><i class="fa-solid fa-pencil"></i></span></a></td>
                    </tr>
                </div>
            @endforeach
        </table>
    </div>
    <div class="mt-4" id="buttons">

        <a href="venues/add"
           class="rounded-md  bg-red-600 px-3 py-2 text-sm font-light  border border-red-800 text-white drop-shadow-lg hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
            Add a new venue
        </a>
    </div>
</x-admin>