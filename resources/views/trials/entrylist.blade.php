<x-main>
    @php
        $tmp = array();
        foreach ($unconfirmed as $entry) {
            array_push($tmp, $entry->name);
        }
        $names = implode(', ', $tmp);

        $numEntries = sizeof($entries) + sizeof($unconfirmed);
    @endphp
    <x-slot:heading>
        Entry list for {{$trial->name}}
    </x-slot:heading>
    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Confirmed entries</div>
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
                </tr>
            @endforeach
        </table>
        @endif
@if(sizeof($unconfirmed) != 0 )

    <div class="font-bold w-full pt-2 mt-2 pb-2 pl-4 pr-4   text-white bg-blue-600">Unconfirmed entries</div>
        <div class="w-full text-sm mt-2 pl-4 pr-6">   {{$names}} </div>

    @endif
        @if($numEntries == 0)
            <div  class="w-full text-sm  pl-4 mt-2 pr-6">No entries have been received yet.</div>
        @endif
    </div>
</x-main>