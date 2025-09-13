<x-main>
    @php
//    dd($toPays);
        $statusOptions = array(    'Payment due', 'Confirmed (Payment received)', 'Withdrawn - paid awaiting refund', 'Refunded', 'Accepted - awaiting payment', 'Reserve', 'Cancelled', 'Manual entry - to pay', 'Manual entry - paid', 'Manual entry - FoC');
        $entryIDs = array();
$email = Auth::user()->email;
$noEditArray = array(2,3,4);
//        dd($entries, $toPays);
    @endphp
    <x-slot:heading>Entries for {{$email}}</x-slot:heading>


    @if(sizeof($todaysEntries) > 0)
        <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Today's Entries</div>
            <table class="w-full">
                @foreach($todaysEntries as $entry)
                    @php
                        if($entry->status == 0) {
                            array_push($entryIDs, $entry->id);
                            }
                    @endphp
                    <tr class="pr-4 odd:bg-white  even:bg-gray-50  border-b ">
                        <td class="pl-2 table-cell">{{$entry->name}}</td>
                        <td class="table-cell">{{$entry->course}}</td>
                        <td class="table-cell">{{$entry->class}}</td>
                        <td class="table-cell">{{$statusOptions[$entry->status]}}</td>
                    </tr>
                @endforeach
                <td class="pl-2 table-cell text-center text-blue-800 font-semibold" colspan="4">Requests for changes should be made with the organiser at Sign-on</td>
            </table>
        </div>
    @endif
    @if(sizeof($toPays) > 0)
    <div class=" mt-0 mb-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-red-600">Unconfirmed Entries - your entry is not confirmed until payment is completed</div>
        <table class="w-full">
            @foreach($toPays as $entry)
                @php
                    if($entry->status == 0) {
                        array_push($entryIDs, $entry->id);
                        }
                @endphp
                <tr class="pr-4 odd:bg-white  even:bg-gray-50 border-b ">
                    <td class="pl-2    table-cell">{{$entry->trial}}</td>
                    <td class="table-cell">{{$entry->name}}</td>
                    <td class="table-cell">{{$entry->course}}</td>
                    <td class="table-cell">{{$entry->class}}</td>
                    @if($entry->isEntryLocked == 1)
                        <td class="table-cell">Locked</td>
                    @else
                        <td class="table-cell">{{$statusOptions[$entry->status]}}</td>
                    @endif
                    <td class="table-cell"><a href="/users/entry/edit/{{$entry->id}}"><i class="fa-solid fa-pencil"></i></a></td>
                </tr>
            @endforeach
        </table>
    </div>

    <form action="/stripe/checkout" method="post">
        @csrf
        <button type="submit" class=" rounded-md  bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Checkout</button>
        <input type="hidden" id="entryIDs" name="entryIDs" value="{{implode(',',$entryIDs)}}" >
    </form>
@endif

    @if(sizeof($entries) > 0)
    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Current Entries</div>
        <table class="w-full">
            @foreach($entries as $entry)
                @php
                if($entry->status == 0) {
                    array_push($entryIDs, $entry->id);
                    }
                @endphp
                <tr class="pr-4 odd:bg-white  even:bg-gray-50  border-b ">
                    <td class="pl-2    table-cell">{{$entry->trial}}</td>
                    <td class="table-cell">{{$entry->name}}</td>
                    <td class="table-cell">{{$entry->course}}</td>
                    <td class="table-cell">{{$entry->class}}</td>
                    <td class="table-cell">{{$statusOptions[$entry->status]}}</td>
                    @if(($entry->isEntryLocked == 1) || ($entry->status == 6) || (in_array($entry->status, $noEditArray)))
                        <td class="table-cell"><i class="fa-solid fa-lock"></i></td>
                    @else
                        <td class="table-cell"><a href="/users/entry/edit/{{$entry->id}}"><i class="fa-solid fa-pencil"></i></a></td>
                    @endif
                </tr>
            @endforeach
        </table>
        <div class="text-center pt-2 text-blue-800 font-semibold">Click on the pencil to makes changes or cancel an entry</div>
    </div>
        @endif

</x-main>