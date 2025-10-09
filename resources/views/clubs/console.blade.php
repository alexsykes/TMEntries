<x-club>
    {{--    Tabbed sections - https://www.w3schools.com/howto/howto_js_tabs.asp --}}
    <x-slot:heading>Club console</x-slot:heading>
    <div class="tab pl-8">
        <button class="tablinks border border-black border-b-0 rounded-t-lg   hover:bg-violet-500 p-1" id="defaultOpen"
                onclick="openSection(event, 'Trials')">
            Trials
        </button>

        <button class="tablinks border border-black border-b-0 rounded-t-lg   hover:bg-violet-500 p-1" id="defaultOpen"
                onclick="openSection(event, 'Profile')">
            Profile
        </button>

        <button class="tablinks border border-black border-b-0 rounded-t-lg   hover:bg-violet-500 p-1"
                onclick="openSection(event, 'Competitions')">
            Competitions
        </button>

        <button class="tablinks border border-black border-b-0 rounded-t-lg    hover:bg-violet-500 p-1  "
                onclick="openSection(event, 'Mailing Lists')">Mailing Lists
        </button>
    </div>
    <div id="Profile" class="tabcontent pt-0">
        <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="flex justify-between font-bold w-full pt-2 pb-2 pl-2 pr-4 rounded-t-xl  text-white bg-violet-600">
                <div>{{$club->name}}</div>
                <div>{{$club->area}}</div>
            </div>

            <div class="text-sm pt-2 pl-2 pr-2 pb-2">
                <div class=" ">@php echo $club->description; @endphp </div>
                <div class="pt-2 text-violet-800 font-bold">Scoring and section markers</div>
                <div class=" ">@php echo $club->section_markers; @endphp </div>

                <div class="pt-2 mt-2 text-violet-800 font-bold">Connections</div>
                @if($club->website)
                    <div class="
                      text-sm">Website: <a href="https://{{$club->website}}" target="_blank">{{$club->website}}</a>
                    </div>
                @endif
                @if($club->facebook)
                    <div class="  text-sm">Facebook: <a href="https://{{$club->facebook}}"
                                                        target="_blank">{{$club->facebook}}</a>
                    </div>
                @endif

            </div>
        </div>

        <div class="mt-4 ml-2" id="buttons">
            <a href="/club/profile/edit"
               class="rounded-md  bg-violet-600 px-3 py-2 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                Edit Profile
            </a>
        </div>
    </div>

    @if($series)
        <div id="Competitions" class="tabcontent pt-0">
            <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
                <div class=" font-bold w-full mt-0 pt-2 pb-2 pl-2 pr-4 rounded-t-xl  text-white bg-violet-600">
                    Competitions (Click on name to edit)
                </div>

                <div class="text-sm  pl-2 pr-2">
                    @foreach($series as $item)

                        <div class=" font-semibold pt-4 text-purple-700 underline"><a
                                    href="/series/edit/{{$item->id}}">{{$item->name}} trials
                                - {{$item->description}}</a></div>
                        <div class="">@php echo $item->notes; @endphp</div>

                        <div class="flex justify-between pb-2 font-bold w-full  ">
                            <div class="">Courses: {{$item->courses}}</div>
                            <div class="">Classes: {{$item->classes}}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
            <div class="mt-4 ml-2" id="buttons">
                <a href="/series/add"
                   class="rounded-md  bg-violet-600 px-3 py-2 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                    New Competition
                </a>
            </div>
        </div>
        @if($distributionLists)

            <div id="Mailing Lists" class="tabcontent pt-0">
                <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
                    <div class=" font-bold w-full mt-0 pt-2 pb-2 pl-2 pr-4 rounded-t-xl  text-white bg-violet-600">
                        Email distribution lists (Click on name to edit)
                    </div>

                    <div class="text-sm  pl-2 pr-2">
                        @php $index = 0; @endphp
                        @foreach($distributionLists as $item)

                            <div class="flex pb-2 w-full  ">
                                <div class="w-1/5 font-semibold text-purple-700 underline"><a
                                            href="/club/distribution/edit/{{$item->id}}">{{$item->name}}
                                        ({{$countItemsArray[$index]}})</a></div>
                                <div class="w-1/5">{{$item->description}}</div>
                                <div class="w-3/5 overflow-clip">{{$item->to}}</div>
                            </div>

                            @php $index++; @endphp
                        @endforeach
                    </div>
                </div>
                @endif
                <div class="mt-4 ml-2" id="buttons">
                    <a href="/club/distribution/add"
                       class="rounded-md  bg-violet-600 px-3 py-2 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                        New Distribution List
                    </a>
                </div>
            </div>

            <script>
                // Get the element with id="defaultOpen" and click on it
                document.getElementById("defaultOpen").click();
            </script>
</x-club>