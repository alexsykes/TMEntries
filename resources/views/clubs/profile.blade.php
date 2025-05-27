<x-club>
    <x-slot:heading>Profile</x-slot:heading>

    <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="flex justify-between font-bold w-full mt-4 pt-2 pb-2 pl-2 pr-4 rounded-t-xl  text-white bg-violet-600">
            <div>{{$club->name}}</div>
            <div>{{$club->area}}</div>
        </div>
        <div class="text-sm pt-2 pl-2 pr-2 pb-2">
            <div class=" ">@php echo $club->description; @endphp </div>
            <div class="pt-2 text-violet-800 font-bold">Section markers</div>
            <div class=" ">@php echo $club->section_markers; @endphp </div>

            <div class="pt-2 text-violet-800 font-bold">Connections</div>
            @if($club->website)
                <div class="
                      text-sm">Website: <a href="https://{{$club->website}}" target="_blank"
                    @endphp">{{$club->website}}</a>
                </div>
            @endif
            @if($club->facebook)
                <div class="  text-sm">Facebook: <a href="https://{{$club->facebook}}" target="_blank"
                    @endphp">{{$club->facebook}}</a>
                </div>
            @endif

            @if($series)
                <div class="pt-2 text-violet-800 font-bold">Competitions</div>
                @foreach($series as $item)
                    <div class=" font-semibold">{{$item->name}} trials - {{$item->description}}</div>
                    <div class="">@php echo $item->notes; @endphp</div>

                    <div class="flex justify-between pb-2 font-bold w-full  ">
                        <div class="">Courses: {{$item->courses}}</div>
                        <div class="">Classes: {{$item->classes}}</div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>    <div class="mt-4" id="buttons">

        <a href="/club/profile/edit/{{$club->id}}"
           class="rounded-md  bg-violet-600 px-3 py-2 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
            Edit Profile
        </a>
    </div>
</x-club>