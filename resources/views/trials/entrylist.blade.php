<x-main>

    @php
        $tmp = array();
        foreach ($unconfirmed as $entry) {
            array_push($tmp, $entry->name);
        }
        $names = implode(', ', $tmp);

        $tmp = array();
        foreach ($reserves as $entry) {
            array_push($tmp, $entry->name);
        }
        $reserveNames = implode(', ', $tmp);

        $numEntries = sizeof($entries) + sizeof($unconfirmed);

        $cookie_name = 'selectedTab';
        $selectedTab = 'alphaTab';
        if(isset($_COOKIE[$cookie_name])) {
            $selectedTab = $_COOKIE[$cookie_name]. "Tab";
        }
    @endphp


    <script>
        // Get the element with id="defaultOpen" and click on it
        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById("{{$selectedTab}}").click();
        });
    </script>
    <x-slot:heading>
        Entry list for {{$trial->name}}
    </x-slot:heading>

    <div id="tabButtons" class="tab pl-4">
        <button class="tablinks border border-black border-b-0 rounded-t-lg   hover:bg-blue-200  p-1 active"
                id="alphaTab"
                onclick="openSection(event, 'alpha')">
            Names
        </button>

        <button class="tablinks border border-black border-b-0 rounded-t-lg   hover:bg-blue-200  p-1"
                id="numericTab"
                onclick="openSection(event, 'numeric')">
            Numbers
        </button>


        <button class="tablinks border border-black border-b-0 rounded-t-lg   hover:bg-blue-200  p-1"
                id="sectionsTab"
                onclick="openSection(event, 'sections')">
            Groups
        </button>
    </div>

    <div id="alpha" class="block tabcontent pt-0">
        <div class=" mt-0 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Names</div>
            @if(sizeof($entries) != 0)
                <table class="w-full  pr-6 text-sm">
                    @foreach($entries as $entry)
                        @php
                            if($entry->class==="Adult") {
                                $class = "";
                            } else {
                                $class = $entry->class;
                            }
                        @endphp
                        <tr class="flex-auto odd:bg-white even:bg-gray-50  border-b ">
                            <td class="pl-2 pr-2 w-12 text-right table-cell">{{$entry->ridingNumber}}</td>
                            <td class="table-cell">{{$entry->name}}</td>
                            <td class="table-cell">{{$entry->course}}</td>
                            <td class="table-cell">{{$class}}</td>
                            <td class="table-cell">{{$entry->make}} {{$entry->size}}</td>
                            <td class="table-cell">{{$entry->startsAt}}</td>
                        </tr>
                    @endforeach
                </table>
            @endif

            @if(sizeof($reserves) != 0 )

                <div class="font-bold w-full pt-2 mt-2 pb-2 pl-4 pr-4   text-white bg-blue-600">Reserve list</div>
                <div class="w-full text-sm mt-2 pl-4 pr-6">   {{$reserveNames}} </div>

            @endif

            @if(sizeof($unconfirmed) != 0 )

                <div class="font-bold w-full pt-2 mt-2 pb-2 pl-4 pr-4   text-white bg-blue-600">Unconfirmed entries
                </div>
                <div class="w-full text-sm mt-2 pl-4 pr-6">   {{$names}} </div>

            @endif

            @if($numEntries == 0)
                <div class="w-full text-sm  pl-4 mt-2 pr-6">No entries have been received yet.</div>
            @endif
        </div>
    </div>
    <div id="numeric" class="block tabcontent pt-0">
        <div class=" mt-0 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Numbers</div>

            @if(sizeof($orderedList) != 0)
                <table class="w-full  pr-6 text-sm">
                    @foreach($orderedList as $entry)
                        @php
                            if($entry->class==="Adult") {
                                $class = "";
                            } else {
                                $class = $entry->class;
                            }
                        @endphp
                        <tr class="flex-auto odd:bg-white even:bg-gray-50  border-b ">
                            <td class="pl-2 pr-2 w-12 text-right table-cell">{{$entry->ridingNumber}}</td>
                            <td class="table-cell">{{$entry->name}}</td>
                            <td class="table-cell">{{$entry->course}}</td>
                            <td class="table-cell">{{$class}}</td>
                            <td class="table-cell">{{$entry->make}} {{$entry->size}}</td>
                        </tr>
                    @endforeach
                </table>

            @else
                <div class="w-full text-sm  pl-4 mt-2 pr-6">…will appear here when riding numbers have been
                    assigned
                </div>
            @endif
        </div>
    </div>
    <div id="sections" class="block tabcontent pt-0">
        <div class=" mt-0 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Groups</div>
            <div class="pl-4 space-y-2">
                @if(count($ridingGroups) >0)
                    @foreach($ridingGroups as $group)
                        @php
                            $startsAt = "Unallocated";
                            if($group->startsAt) {
                                $startsAt = "Section: ".$group->startsAt;
                            }
                        @endphp
                        <div class="font-semibold pt-2  text-blue-700">{{$startsAt}}</div>
                        {{--                @foreach($entries as $entry)--}}
                        <span>{{$group->entries}}</span>
                        {{--                @endforeach--}}
                    @endforeach

                @else
                    <div class="w-full text-sm  pl-0 mt-2 pr-6">…will appear here when riding groups have been
                        assigned
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-main>