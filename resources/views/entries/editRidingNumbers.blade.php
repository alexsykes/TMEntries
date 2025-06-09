<x-club>
    <x-slot:heading>
        Riding numbers for {{$trialid}}
    </x-slot:heading>
    @php
        $statusOptions = array(    'Unconfirmed', 'Confirmed', 'Withdrawn - paid awaiting refund', 'Refunded', 'Accepted - awaiting payment', 'Reserve', 'Removed', 'Manual entry - to pay', 'Manual entry - paid', 'Manual entry - FoC');
 @endphp


<form action="/entries/saveRidingNumbers" method="post">
    <input type="hidden" name="trialID" id="trialID" value="{{$trialid}}">
    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 ">
        <div class=" font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Riding numbers</div>
    @csrf
    <div class=" pt-2 pb-2">
    <table class="w-full">
    @foreach($entries as $entry)
        <tr class="flex-auto text-sm  odd:bg-white  even:bg-gray-50  border-b ">
            <td class="pl-2"><input type="hidden" name="entryID[]" id="entryID[]" value="{{$entry->id}}"> {{$entry->name}}</td>
            <td> {{$entry->course}}</td>
            <td> {{$entry->class}}</td>
            <td> {{$statusOptions[$entry->status]}}</td>
            <td class="text-right pr-4"> <x-form-input class="w-8  text-right bg-white" type="text" name="ridingNumber[]" id="ridingNumber[]" value="{{$entry->ridingNumber}}"/></td>
        </tr>
    @endforeach
    </table>
    </div>
    </div>
    <div class="mt-2 pl-2 pt-2  mt-2" id="buttons">
            <a href="/trials/adminEntryList/{{$trialid}}"
               class="rounded-md bg-white px-3 py-2 text-sm font-light  text-blue-600 border border-blue-800 drop-shadow-xl hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>
        <button type="submit"
                class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
            Save
        </button>
    </div>
</form>
</x-club>