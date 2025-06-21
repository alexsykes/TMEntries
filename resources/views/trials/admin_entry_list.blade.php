<x-club>
    <x-slot:heading>
        {{$trial->name}}
    </x-slot:heading>

    @php

        $isEntryLocked = $trial->isEntryLocked;
$isTrialLocked = $trial->isLocked;
if($isEntryLocked || $isTrialLocked) {
    $lock = true;
} else {
    $lock = false;
}


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


            $allCourses = array();
$courses = $trial->courselist;
$customCourses = $trial->customCourses;

$allClasses = array();
$classes = $trial->classlist;
$customClasses = $trial->customClasses;

if($courses !='') {
array_push($allCourses, $courses);
}

if($customCourses !='') {
array_push($allCourses, $customCourses);
}

if($classes !='') {
array_push($allClasses, $classes);
}

if($customClasses !='') {
array_push($allClasses, $customClasses);
}

$classlist = str_replace(',',',',implode(',', $allClasses));
$courselist   = str_replace(',',',',implode(',', $allCourses));
$courseOptions = explode(',', $courselist);
$classOptions = explode(',', $classlist);
    @endphp

    <div class="mx-auto max-w-7xl px-4  sm:px-6 lg:px-8">
        <div class="mt-2 ml-0" id="buttons1">
            @if(!$lock)
                <a href="/admin/entries/editRidingNumbers/{{$trial->id}}"
                   class="rounded-md ml-2 bg-violet-600 px-3 py-2 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">Edit
                    Riding Numbers</a>

            @endif
            <a href="/admin/entries/printSignOnSheets/{{$trial->id}}"
               class="rounded-md ml-2 bg-violet-600 px-3 py-2 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">Signing-on
                Sheets</a>
        </div>

        @if(sizeof($eod) > 0)
            <form action="/otd/saveNumbers" method="post">
                @csrf
                <input type="hidden" id="trialid" name="trialid" value="{{$trial->id}}">
                <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
                    <div class="flex justify-between font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">
                        <div>EOD entry list</div>
                    </div>

                    <table class="w-full text-sm">
                        @foreach($eod as $entry)
                            @php
                                if($entry->isYouth) {
                                    $entryFee = "Youth entry fee";
                                } else {
                                    $entryFee = "Adult entry fee";
                                }
                            @endphp
                            <tr class="flex-auto  ">
                                <td class="table-cell pl-2 py-1"><input
                                            class="m-1  w-12 bg-white  space-x-4 border-spacing-1 border-violet-700 rounded-md drop-shadow-lg pl-2 pr-2 pt-1 pb-1 border outline-1 -outline-offset-1"
                                            type="text" id="ridingNumber[]" name="ridingNumber[]"/>
                                    <input type="hidden" value="{{$entry->id}}" name="entryID[]" id="entryID[]"></td>
                                <td>{{$entry->name}}</td>
                                <td>{{$entryFee}}</td>
                                <td>{{$entry->course}}</td>
                                <td>{{$entry->class}}</td>
                            </tr>
                        @endforeach
                    </table>
                    <button type="submit"
                            class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                        Save
                    </button>
                </div>
            </form>
        @endif

        @if(sizeof($duplicateArray) > 0)
            <div class="text-center w-full font-semibold mt-2 text-red-500">
                You have duplicated riding numbers!
            </div>
        @endif
        <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="flex justify-between font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">
                <div>Entry list</div>
            </div>
            <table class="w-full text-sm">
                @foreach($entries as $entry)
                    @if((in_array($entry->ridingNumber, $duplicateArray)) || ($entry->status == 0) || ($entry->status == 7))
                        <tr class="flex-auto text-red-500 odd:bg-white  even:bg-gray-50  border-b ">
                    @else

                        <tr class="flex-auto odd:bg-white even:bg-gray-50  border-b ">
                            @endif
                            <td class="text-right pr-2 w-12 py-1">{{$entry->ridingNumber}}</td>
                            <td>{{$entry->name}}</td>
                            <td>{{$entry->class}}</td>
                            <td>{{$entry->course}}</td>
                            <td>{{$statusOptions[$entry->status]}}</td>
                            @if(!$lock)
                                <td><a href="/admin/entry/edit/{{$entry->id}}"><span><i
                                                    class="fa-solid fa-gear"></i></span></a>
                                </td>
                                <td><a href="/admin/entry/cancel/{{$entry->id}}"><span><i
                                                    class="fa-solid fa-ban"></i></span></a></td>
                            @endif
                        </tr>
                        @endforeach
            </table>
        </div>
        @if(!$lock)
            <form action="/admin/entries/storeMultiple" method="POST">
                <div class="mt-4   bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
                    <div class=" font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Add Manual
                        Entries
                    </div>

                    <input type="hidden" id="trialID" name="trialID" value="{{$trial->id}}">
                    @csrf

                    <table class="w-full text-sm pt-0" style="padding: 0px;">


                        @for($i=0; $i<10; $i++)
                            <tr class="flex-auto">
                                <td class="table-cell pl-2 py-1"><input
                                            class="m-1  w-12 bg-white  space-x-4 border-spacing-1 border-violet-700 rounded-md drop-shadow-lg pl-2 pr-2 pt-1 pb-1 border outline-1 -outline-offset-1"
                                            type="text" id="ridingNumber[]" name="ridingNumber[]"/></td>

                                <td class="table-cell py-1"><input
                                            class="m-1  bg-white  space-x-4 border-spacing-1 border-violet-700 rounded-md drop-shadow-lg pl-2 pr-2 pt-1 pb-1 border outline-1 -outline-offset-1"
                                            type="text" id="name[]" name="name[]" placeholder="Rider"/></td>

                                <td class="table-cell py-1"><input
                                            class="m-1  w-24  bg-white  space-x-4 border-spacing-1 border-violet-700 rounded-md drop-shadow-lg pl-2 pr-2 pt-1 pb-1 border outline-1 -outline-offset-1"
                                            type="text" id="make[]" name="make[]" placeholder="Make"/></td>

                                <td class="table-cell py-1"><input
                                            class="m-1  w-12 bg-white  space-x-4 border-spacing-1 border-violet-700 rounded-md drop-shadow-lg pl-2 pr-2 pt-1 pb-1 border outline-1 -outline-offset-1"
                                            type="text" id="size[]" name="size[]" placeholder="Size"/></td>
                                <td class="table-cell">
                                    <select class="m-1  bg-white  space-x-4 border-spacing-1 border-violet-700 rounded-md drop-shadow-lg pl-2 pr-2 pt-1 pb-1 border outline-1 -outline-offset-1"
                                            id="type[]" name="type[]">
                                        @foreach($types as $type)
                                            <option value="{{$type}}">{{$type}}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="table-cell pl-2">
                                    <select class="m-1  bg-white  space-x-4 border-spacing-1 border-violet-700 rounded-md drop-shadow-lg pl-2 pr-2 pt-1 pb-1 border outline-1 -outline-offset-1"
                                            id="course[]" name="course[]">
                                        @foreach($courseOptions as $course)
                                            <option value="{{$course}}">{{$course}}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="table-cell pl-2">
                                    <select class="m-1  bg-white  space-x-4 border-spacing-1 border-violet-700 rounded-md drop-shadow-lg pl-2 pr-2 pt-1 pb-1 border outline-1 -outline-offset-1"
                                            id="class[]" name="class[]">
                                        @foreach($classOptions as $class)
                                            <option value="{{$class}}">{{$class}}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="table-cell">
                                    <select class="m-1  bg-white  space-x-4 border-spacing-1 border-violet-700 rounded-md drop-shadow-lg pl-2 pr-2 pt-1 pb-1 border outline-1 -outline-offset-1"
                                            id="status[]" name="status[]">
                                        <option value="8">Cash</option>
                                        <option value="7">Pay on day</option>
                                        <option value="9">FoC</option>
                                    </select>
                                </td>


                                <td class="table-cell">
                                    <input class="m-1  bg-white  space-x-4 border-spacing-1 border-violet-700 rounded-md drop-shadow-lg pl-2 pr-2 pt-1 pb-1 border outline-1 -outline-offset-1"
                                           type="date" id="dob[]" name="dob[]" value="1">
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
        @endif

    </div>
</x-club>