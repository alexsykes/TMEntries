<x-main>
    @php
        $statusOptions = array(    'Payment due', 'Confirmed (Payment received)', 'Withdrawn - paid awaiting refund', 'Refunded', 'Accepted - awaiting payment', 'Reserve', 'Removed', 'Manual entry - to pay', 'Manual entry - paid', 'Manual entry - FoC');
        $entryIDs = array();
    @endphp
    <x-slot:heading>Entries </x-slot:heading>
    <div class=" mt-0 mb-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Current Entries</div>
        <table class="w-full">
            @foreach($entries as $entry)
                @php
                if($entry->status == 0) {
                    array_push($entryIDs, $entry->id);
                    }
                @endphp
                <tr class="pr-4 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b ">
                    <td class="pl-2    table-cell">{{$entry->trial}}</td>
                    <td class="table-cell">{{$entry->name}}</td>
                    <td class="table-cell">{{$entry->course}}</td>
                    <td class="table-cell">{{$entry->class}}</td>
                    <td class="table-cell">{{$statusOptions[$entry->status]}}</td>
                    <td class="table-cell"><a href="/users/entry/edit/{{$entry->id}}">Edit</a></td>
                </tr>
            @endforeach
        </table>
    </div>
        @php
            if(sizeof($entryIDs) > 0){ @endphp
                <form action="/stripe/checkout" method="post">
            @csrf
            <button type="submit" class=" rounded-md  bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Checkout</button>
                    <input type="hidden" id="entryIDs" name="entryIDs" value="{{implode(',',$entryIDs)}}" >
        </form>
    @php
    }
    @endphp
</x-main>