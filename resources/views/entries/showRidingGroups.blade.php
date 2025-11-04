<x-club>
    @php
            @endphp
    <x-slot:heading>
        Starting sections - {{$trial->name}}
    </x-slot:heading>
    <div class="space-y-4">
        <div class="px-4 py-0 pb-2 mt-6 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
            @foreach($ridingGroups as $group)
                @php

//                    $names = explode(',', $group->names);
//                    $numbers = explode(',', $group->numbers);
//                    $entries = explode(',', $group->entries);
//dd($entries);
                    $startsAt = "Unallocated";
                    if($group->startsAt) {
                        $startsAt = "Section: ".$group->startsAt;
                    }
                @endphp
                <div class="font-semibold pt-2  text-violet-700">{{$startsAt}}</div>
{{--                @foreach($entries as $entry)--}}
                    <span>{{$group->entries}}</span>
{{--                @endforeach--}}
            @endforeach

            <div class="mt-4 mb-4" id="buttons">
                <a href="/trials/adminEntryList/{{$trial->id}}"
                   class="rounded-md bg-white px-3 py-2 text-sm font-light  text-blue-600 border border-blue-800 drop-shadow-xl hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>
{{--                <a href="/trials/printStartingSections/{{$trial->id}}"--}}
{{--                   class="rounded-md bg-white px-3 py-2 text-sm font-light  text-blue-600 border border-blue-800 drop-shadow-xl hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-blue-900">Print</a>--}}
            </div>
        </div>
    </div>
</x-club>