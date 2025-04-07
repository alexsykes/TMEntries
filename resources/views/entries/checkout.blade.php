<x-main xmlns="http://www.w3.org/1999/html">
    <x-slot:heading>Checkout
    </x-slot:heading>
    <form method="POST" action="/stripe/checkout">
        @csrf
        @php
            $adultEntryFee = "£".$trial['adultEntryFee'];
            $youthEntryFee = "£".$trial['youthEntryFee'];
            $trial_id = $trial['id'];
//            dd($trial_id);
        @endphp

        <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Your entries for {{$trial->name}} </div>
            <table class="w-full ml-4 mr-4"><tr><th>Ref</th><th>Name</th><th>Class</th><th>Course</th><th>Entry fee</th></tr>
                @foreach($entries as $entry)
                    <tr><td>{{$entry->id}}</td><td>{{$entry->name}}</td><td>{{$entry->class}}</td><td>{{$entry->course}}</td><td>{{ ($entry->isYouth == 1) ? $youthEntryFee : $adultEntryFee}}</td></tr>
                @endforeach
        `       </table>
            @php
                $entryIDarray = array();
                foreach($entries as $entry){
                    array_push($entryIDarray, $entry->id);
                }
                $entryIDs = implode(',', $entryIDarray);
            @endphp
            <input type="hidden" value="{{$entryIDs}}" id="entryIDs" name="entryIDs">
            <input type="hidden" value="{{$trial_id}}" id="trialID" name="trialID">
        </div>
        <div class="mt-4" id="buttons">
            <a href="/entries/register/{{$trial->id}}"  class="rounded-md bg-white px-3 py-2 text-sm  text-blue-600 shadow-sm hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Back</a>
            <button type="submit" class="rounded-md ml-2 bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Pay</button>
        </div>
    </form>
</x-main>