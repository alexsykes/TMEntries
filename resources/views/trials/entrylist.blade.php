<x-main>
    @php
        $tmp = array();
        foreach ($unconfirmed as $entry) {
            array_push($tmp, $entry->name);
        }
        $names = implode(', ', $tmp);
    @endphp
    <x-slot:heading>
        Entry list for {{$trial->name}}
    </x-slot:heading>
    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Confirmed entries</div>
        <table class="w-full  pr-6 text-sm">
            @foreach($entries as $entry)
                <tr class="flex-auto odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b ">
                    <td class="table-cell">{{$entry->number}}</td>
                    <td class="table-cell">{{$entry->name}}</td>
                    <td class="table-cell">{{$entry->course}}</td>
                    <td class="table-cell">{{$entry->class}}</td>
                    <td class="table-cell">{{$entry->make}} {{$entry->size}}</td>
                </tr>
            @endforeach
        </table>

@if(sizeof($unconfirmed) != 0 )

    <div class="font-bold w-full pt-2 mt-2 pb-2 pl-4 pr-4   text-white bg-blue-600">Unconfirmed entries</div>
        <div class="w-full text-sm  pl-4 pr-6">   {{$names}} </div>

    @endif
    </div>
</x-main>