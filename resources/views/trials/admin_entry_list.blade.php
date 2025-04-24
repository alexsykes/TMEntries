<x-club>
    <x-slot:heading>
        {{$trial->name}}
    </x-slot:heading>

    <div class="mx-auto max-w-7xl px-4  sm:px-6 lg:px-8">
        @php
        $duplicateArray = array();
        foreach($duplicates as $duplicate)
            {
                array_push($duplicateArray, $duplicate->ridingNumber);
            }
            $statusOptions = array(    'Unconfirmed', 'Confirmed', 'Withdrawn - paid awaiting refund', 'Refunded', 'Accepted - awaiting payment', 'Reserve', 'Removed', 'Manual entry - to pay', 'Manual entry - paid', 'Manual entry - FoC');
            $manualStatusOptions = array(  'Manual entry - to pay', 'Manual entry - paid', 'Manual entry - FoC');
            $classes = explode(',',$trial->classlist);
            $courses = explode(',',$trial->courselist);
            $authority = $trial->authority;
            $types = array("2 stroke", "4 stroke", "e-bike");
            $entryOptions = array( 'Manual entry - to pay', 'Manual entry - paid', 'Manual entry - FoC');
        @endphp
        <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="flex justify-between font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">
                <div>Entry list</div>
            </div>
                <table class="w-full text-sm">
                    @foreach($entries as $entry)
                        @if(in_array($entry->ridingNumber, $duplicateArray))
                            <tr class="flex-auto text-red-500 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b ">

                        @else

                            <tr class="flex-auto odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b ">
                        @endif
                            <td class="text-right pr-2 w-12 py-1">{{$entry->ridingNumber}}</td>
                            <td>{{$entry->name}}</td>
                            <td>{{$entry->class}}</td>
                            <td>{{$entry->course}}</td>
                            <td>{{$statusOptions[$entry->status]}}</td>
                            <td><a href="/admin/entry/edit/{{$entry->id}}"><span><i class="fa-solid fa-gear"></i></span></a>
                            </td>
                            <td><a href="/admin/entry/cancel/{{$entry->id}}"><span><i
                                                class="fa-solid fa-ban"></i></span></a></td>
                        </tr>
                    @endforeach
                </table>
            {{--    <a href="{{ route('stripe.index') }}" class="btn mt-5 bg">Continue Shopping</a>--}}
        </div>
        <div class="mt-4 ml-0" id="buttons1">

            <a href="/admin/entries/editRidingNumbers/{{$trial->id}}"
               class="rounded-md ml-2 bg-violet-600 px-3 py-2 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">Edit Riding Numbers</a>
            <a href="/admin/entries/printSignOnSheets/{{$trial->id}}"
               class="rounded-md ml-2 bg-violet-600 px-3 py-2 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">Signing-on Sheets</a>
        </div>

        <form action="/admin/entries/storeMultiple" method="POST">
        <div class="mt-4   bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class=" font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Add Manual Entries</div>

                <input type="hidden" id="trialID" name="trialID" value="{{$trial->id}}">
                @csrf

                <table class="w-full text-sm pt-0" style="padding: 0px;">


                    @for($i=0; $i<10; $i++)
                        <tr class="flex-auto">
                            <td class="table-cell pl-2 py-1"><input class="m-1  w-12 bg-white  space-x-4 border-spacing-1 border-violet-700 rounded-md drop-shadow-lg pl-2 pr-2 pt-1 pb-1 border outline-1 -outline-offset-1"  type="text" id="ridingNumber[]" name="ridingNumber[]"/></td>
                            <td class="table-cell py-1"><input class="m-1  bg-white  space-x-4 border-spacing-1 border-violet-700 rounded-md drop-shadow-lg pl-2 pr-2 pt-1 pb-1 border outline-1 -outline-offset-1"  type="text" id="name[]" name="name[]" placeholder="Rider"/></td>

                            <td class="table-cell py-1"><input class="m-1  bg-white  space-x-4 border-spacing-1 border-violet-700 rounded-md drop-shadow-lg pl-2 pr-2 pt-1 pb-1 border outline-1 -outline-offset-1"  type="text" id="make[]" name="make[]" placeholder="Make"/></td>
                            <td class="table-cell py-1"><input class="m-1  w-12 bg-white  space-x-4 border-spacing-1 border-violet-700 rounded-md drop-shadow-lg pl-2 pr-2 pt-1 pb-1 border outline-1 -outline-offset-1"  type="text" id="size[]" name="size[]" placeholder="Size"/></td>
                            <td class="table-cell">
                                <select class="m-1  bg-white  space-x-4 border-spacing-1 border-violet-700 rounded-md drop-shadow-lg pl-2 pr-2 pt-1 pb-1 border outline-1 -outline-offset-1" id="type[]" name="type[]">
                                    @foreach($types as $type)
                                        <option value="{{$type}}">{{$type}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="table-cell pl-2">
                                <select class="m-1  bg-white  space-x-4 border-spacing-1 border-violet-700 rounded-md drop-shadow-lg pl-2 pr-2 pt-1 pb-1 border outline-1 -outline-offset-1" id="class[]" name="class[]">
                                    @foreach($classes as $class)
                                        <option value="{{$class}}">{{$class}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="table-cell pl-2">
                                <select class="m-1  bg-white  space-x-4 border-spacing-1 border-violet-700 rounded-md drop-shadow-lg pl-2 pr-2 pt-1 pb-1 border outline-1 -outline-offset-1" id="course[]" name="course[]">
                                    @foreach($courses as $course)
                                        <option value="{{$course}}">{{$course}}</option>
                                    @endforeach
                                </select>
                            </td>

                            <td class="table-cell">
                                <select class="m-1  bg-white  space-x-4 border-spacing-1 border-violet-700 rounded-md drop-shadow-lg pl-2 pr-2 pt-1 pb-1 border outline-1 -outline-offset-1" id="status[]" name="status[]">
                                    <option value="8">Cash</option>
                                    <option value="7">Pay on day</option>
                                    <option value="9">FoC</option>
                                </select>
                            </td>

                            <td class="table-cell"><label for="isYouth">Under 18</label>
                                <input class="m-1  bg-white  space-x-4 border-spacing-1 border-violet-700 rounded-md drop-shadow-lg pl-2 pr-2 pt-1 pb-1 border outline-1 -outline-offset-1"  type="checkbox" id="isYouth[]" name="isYouth[]" value="1">
                            </td>

                        </tr>

                    @endfor
                </table>
        </div>
                <div class="mt-4" id="buttons">

                    <button type="submit"
                            class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                        Add Entries
                    </button>
                </div>

            </form>


    </div>
</x-club>