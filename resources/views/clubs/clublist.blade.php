<x-main>
    <x-slot:heading>Club list</x-slot:heading>

    @foreach($clubs as $club)
        <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="flex justify-between font-bold w-full mt-4 pt-2 pb-2 pl-2 pr-4 rounded-t-xl  text-white bg-blue-600">
                <div>{{$club->name}}</div>
                <div>{{$club->area}}</div>
            </div>
            <div class="text-sm pt-2 pl-2 pr-2 pb-2">
                <div class=" ">@php echo $club->description; @endphp </div>
                <div class="pt-2 text-blue-800 font-bold">Section markers</div>
                <div class=" ">@php echo $club->section_markers; @endphp </div>

                <div class="pt-2 text-blue-800 font-bold">Connections</div>
            @if($club->website)
                    <div class=" text-sm">Website: <a href="https://{{$club->website}}" target="_blank"
                        @endphp">{{$club->website}}</a>
                    </div>
                @endif
                @if($club->facebook)
                    <div class="  text-sm">Facebook: <a href="https://{{$club->facebook}}" target="_blank"
                        @endphp">{{$club->facebook}}</a>
                    </div>
                @endif

                @if($club->series)
                    <div class="pt-2 text-blue-800 font-bold">Competitions</div>
                    @foreach($club->series as $series)
                        <div class="font-semibold">{{$series->name}}  - {{$series->description}}</div>
                        <div class="">@php echo $series->notes; @endphp</div>

                        <div class="flex justify-between pb-2  font-bold w-full  ">
                            <div class="">Courses: {{$series->courses}}</div>
                            <div class="">Classes: {{$series->classes}}</div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endforeach
</x-main>
