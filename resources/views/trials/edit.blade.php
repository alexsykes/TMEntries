<x-club>
    <x-slot:heading>
        Edit Trial: {{$trial->name}}
    </x-slot:heading>
    @php
        $courseArray = array("Expert", "Intermediate", "Hard Novice", "Novice", "50/50", "Easy", "Clubman", "Clubman A", "Clubman B");
        $classArray = array("Adult", "Youth", "Twinshock", "Pre-65", "Air-cooled Monoshock", "Over 40", "Over 50", "Youth A", "Youth B", "Youth C", "Youth D");
        $entryMethodArray = array("Enter on day", "TrialMonster", "Online");
        $entrySelectionArray = array("Order of Payment", "Ballot", "Selection", "Other");
        $scoringModeArray = array("Observer", "App", "Punch Cards", "Other");
        $stopAllowedArray = array("Stop permitted", "Non-stop");
        $authorityArray = array("ACU", "AMCA", "Other");
        $restrictionArray = array("Open", "Centre", "Closed to Club", "Other Restriction");
//        dump($trial);

    $savedEntryMethods = explode(',',$trial->entryMethod);
    $isLocked = $trial->isLocked;
//    dd($savedEntryMethods);
    @endphp
    @if ($errors->any())
        <div class="text-red-500">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="tab pl-8">
        <button class="tablinks border border-black border-b-0 rounded-t-lg   hover:bg-violet-500 p-2" id="defaultOpen" onclick="openSection(event, 'Details')">Detail</button>
        <button class="tablinks border border-black border-b-0 rounded-t-lg    hover:bg-violet-500 p-2  " onclick="openSection(event, 'Trial')">Trial</button>
        <button class="tablinks border border-black border-b-0 rounded-t-lg    hover:bg-violet-500 p-2  " onclick="openSection(event, 'Entries')">Entries</button>
        <button class="tablinks border border-black border-b-0 rounded-t-lg    hover:bg-violet-500 p-2  " onclick="openSection(event, 'Scoring')">Scoring</button>
        <button class="tablinks border border-black border-b-0 rounded-t-lg    hover:bg-violet-500 p-2  " onclick="openSection(event, 'Regulations')">Regulations</button>
        <button class="tablinks border border-black border-b-0 rounded-t-lg    hover:bg-violet-500 p-2  " onclick="openSection(event, 'Fees')">Fees</button>
    </div>
    <form action="/trials/update" method="POST">
        @method('PATCH')
        @csrf

        <div id="Details" class="tabcontent pt-0 ">
            <input type="hidden" value="{{$trial->id}}" name="trialid">
            <input type="hidden" value="{{$trial->club_id}}" name="club_id">
            <input type="hidden" value="{{$trial->series_id}}" name="series_id">
            <div class="space-y-2">
                    <div class="px-4 py-4 mt-0 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                        <div class=" grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                            <x-form-field>
                                <x-form-label for="permit">Permit</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="permit" type="text" id="permit" value="{{$trial->permit}}"
                                                  placeholder="Permit" required/>
                                    <x-form-error name="permit"/>
                                </div>
                                @error('permit')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="name">Event Name</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="name" type="text" id="name" value="{{$trial->name}}"
                                                  placeholder="Name" required/>
                                    <x-form-error name="name"/>
                                </div>
{{--                                @error('name')--}}
{{--                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>--}}
{{--                                @enderror--}}
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="club">Organising Club</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="club" type="text" id="club" value="{{$trial->club}}"
                                                  placeholder="Club name" required/>
                                    <x-form-error name="club"/>
                                </div>
{{--                                @error('club')--}}
{{--                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>--}}
{{--                                @enderror--}}
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="date">Date</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="date" type="date" min="{{date('Y-m-d')}}" id="date" value="{{$trial->date}}" required/>
                                    <x-form-error name="date"/>
                                </div>
{{--                                @error('date')--}}
{{--                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>--}}
{{--                                @enderror--}}
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="isMultiDay">Multi-day event</x-form-label>
                                <div class="mt-2">
                                    <input @php if ($trial->isMultiDay== 1){echo "checked"; } @endphp

                                           name="isMultiDay" type="checkbox" value="1" id="isMultiDay"  />
                                    <x-form-error name="isMultiDay"/>
                                </div>
{{--                                @error('date')--}}
{{--                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>--}}
{{--                                @enderror--}}
                            </x-form-field>

                            <div id="numDaysDiv" class=" col-span-full">
                                <x-form-field id="" name="" class="">
                                    <x-form-label for="numDays">Number of days</x-form-label>
                                    <div class="mt-2 col-span-2">
                                        <x-form-input name="numDays" type="number" id="numDays" value="{{$trial->numDays}}" min="1"/>
                                        <x-form-error name="numDays"/>
                                    </div>
{{--                                    @error('email')--}}
{{--                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>--}}
{{--                                    @enderror--}}
                                </x-form-field>
                            </div>

                            <x-form-field>
                                <x-form-label for="startTime">Start time</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="startTime" type="text" id="startTime" placeholder="Trial starting time" value="{{$trial->startTime}}" required/>
                                    <x-form-error name="startTime"/>
                                </div>
                                @error('startTime')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="contactName ">Organiser</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="contactName" type="text" id="contactName  " placeholder="Contact name" value="{{$trial->contactName}}" required/>
                                    <x-form-error name="contactName"/>
                                </div>
                                @error('email')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="email">Email</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="email" type="email" id="email" placeholder="Contact email" value="{{$trial->email}}" required/>
                                    <x-form-error name="email"/>
                                </div>
                                @error('email')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="phone">Phone</x-form-label>
                                <div class="mt-2 col-span-2 ">
                                    <x-form-input name="phone" type="text" id="phone" placeholder="Contact phone" value="{{$trial->phone}}" required/>
                                    <x-form-error name="phone"/>
                                </div>
                                @error('phone')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>


                            <x-form-field class="mt-2 col-span-2 sm:col-span-3">
                                <x-form-label for="venue">Venue</x-form-label>
                                <div class="flex mt-2 rounded-md shadow-sm ring-1 ring-inset outline outline-1 -outline-offset-1 drop-shadow-lg outline-violet-700 focus-within:ring-2  focus-within:ring-inset focus-within:ring-violet-600 sm:max-w-md" >
                                    <select class="border-0  pl-2 pt-2  bg-transparent pb-1 space-x-4 " name="venueID" id="venueID">
                                        <option value="0">Other</option>
                                        @foreach($venues as $venue)
                                            <option
                                                    <?php if($trial->venueID == $venue->id ) { ?> selected = "selected"<?php } ?>

                                            value="{{$venue->id}}">{{$venue->name}}

                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="otherVenue">Venue if not listed</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="otherVenue" type="text" id="otherVenue" value="{{$trial->otherVenue}}" placeholder="Venue name" />
                                    <x-form-error name="otherVenue"/>
                                </div>
                                @error('otherVenue')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>



                    </div>
                </div>
        </div>

        <div id="Trial" class="tabcontent pt-0">
            <div class="space-y-2">
                    <div class="px-4 py-4 mt-0 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                        <div class="text-violet-700 font-semibold mb-4">Please list the Courses and Classes in the order in which you would like them to appear in the results. Courses/classes in each list should be separated by commas.</div>
                        <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                            <div id="courseDataDiv" class="col-span-3">
{{--                            <x-form-field>--}}
{{--                                <x-form-label class="pr-0" for="courselist">Courses</x-form-label>--}}
{{--                                <div class=" pl-2 pr-0">--}}
{{--                                    @foreach($courseArray as $course)--}}
{{--                                        <div>--}}
{{--                                            <input--}}
{{--                                                    <?php--}}
{{--                                                $courseArraySaved = explode(",", $trial->courselist);--}}
{{--//                                                        dd($courseArraySaved);--}}
{{--                                            if(in_array($course, $courseArraySaved)) { ?> checked <?php } ?>--}}
{{--                                                    name="courselist[]" type="checkbox" id="courselist" value="{{$course}}"/>--}}
{{--                                            <label  class="pl-4 pr-0" for="courselist">{{$course}}--}}
{{--                                            </label>--}}
{{--                                        </div>--}}
{{--                                    @endforeach--}}
{{--                                    <x-form-error name="courselist[]"/>--}}
{{--                                </div>--}}
{{--                                @error('courselist')--}}
{{--                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>--}}
{{--                                @enderror--}}
{{--                            </x-form-field>--}}


                            <x-form-field>
                                <x-form-label for="customCourses">Courses</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="customCourses" type="text" id="customCourses" value="{{$trial->customCourses}}"
                                                  placeholder="List of courses separated by commas" />
                                    <x-form-error name="customCourses"/>
                                </div>
                                @error('customCourses')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>
                            <div id="classDataDiv" class="col-span-3">
{{--                            <x-form-field>--}}
{{--                                <x-form-label for="classlist">Classes</x-form-label>--}}
{{--                                <div class=" pl-2 pr-2">--}}
{{--                                    @foreach($classArray as $class)--}}
{{--                                        <div><input--}}
{{--                                                    <?php--}}
{{--                                                $classArraySaved = explode(",", $trial->classlist);--}}
{{--//                                                        dd($courseArraySaved);--}}
{{--                                            if(in_array($class, $classArraySaved)) { ?> checked <?php } ?>--}}
{{--                                                    name="classlist[]" type="checkbox" id="classlist" value="{{$class}}"/>--}}
{{--                                            <label  class="pl-4 pr-2" for="classlist">{{$class}}</label>--}}
{{--                                        </div>--}}
{{--                                    @endforeach--}}
{{--                                    <x-form-error name="classlist[]"/>--}}
{{--                                </div>--}}
{{--                                @error('classlist')--}}
{{--                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>--}}
{{--                                @enderror--}}
{{--                            </x-form-field>--}}

                            <x-form-field>
                                <x-form-label for="customClasses">Classes</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="customClasses" type="text" id="customClasses" value="{{$trial->customClasses}}"
                                                  placeholder="List of classes separated by commas" />
                                    <x-form-error name="customClasses"/>
                                </div>
                                @error('customClasses')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                            </div>

                            <div id="hasTimePenaltyDiv" class="col-span-full">
                            <x-form-field>
                                <x-form-label for="hasTimePenalty">Time and Observation</x-form-label>
                                <div class="mt-2 col-span-full">
                                    <input
                                            @php if ($trial->hasTimePenalty== 1){echo "checked"; } @endphp

                                            name="hasTimePenalty" type="checkbox" id="hasTimePenalty" value="1" />
                                    <x-form-error name="hasTimePenalty"/>
                                </div>
                                @error('hasTimePenalty')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field></div>

                            <div id="startIntervalDiv" class="col-span-3">
                            <x-form-field>
                                <x-form-label for="startInterval">Start interval (seconds)</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="startInterval" type="text" id="startInterval" value="{{$trial->startInterval}}"
                                                  placeholder="Start interval in seconds" />
                                    <x-form-error name="startInterval"/>
                                </div>
                                @error('startInterval')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                            </div>


                            <div id="penaltyDiv" class="col-span-3">
                            <x-form-field>
                                <x-form-label for="penaltyDelta">Penalty tariff</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="penaltyDelta" type="text" id="penaltyDelta" value="{{$trial->penaltyDelta}}"
                                                  placeholder="Number of seconds per point lost" />
                                    <x-form-error name="penaltyDelta"/>
                                </div>
                                @error('penaltyDelta')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>
                        </div>
                    </div>
            </div>
        </div>

        <div id="Entries" class="tabcontent pt-0">
            <div class="space-y-12">
                    <div class="px-4 py-4 mt-0 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                            <div id="entryMethodDiv" class=" col-span-3">
                            <x-form-field>
                                <x-form-label class="pr-0" for="courselist">How to enter</x-form-label>
                                <div class="pl-2 pr-0">
                                    @foreach($entryMethodArray as $entryMethod)
                                        <div>
                                            <input
                                                    <?php
                                            if(in_array($entryMethod, $savedEntryMethods)) { echo ' checked'; } ?>

                                                    name="entryMethod[]" type="checkbox" id="entryMethod[]"  value="{{$entryMethod}}" />
                                            <label  class="pl-4 pr-0" for="entryMethod">{{$entryMethod}}
                                            </label>
                                        </div>
                                    @endforeach
                                    <x-form-error name="entryMethod[]"/>
                                </div>
                                @error('entryMethod')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                            </div>


                            <div id="entryLinkDiv" class="col-span-3">
                            <x-form-field>
                                <x-form-label for="name">Online entry link</x-form-label>
                                <div class="mt-2 col-span-3">
                                    <x-form-input
                                            name="onlineEntryLink"
                                            type="text" id="onlineEntryLink"
                                            value="{{$trial->onlineEntryLink}}"
                                            placeholder="Entry URL here"/>
                                    <x-form-error name="onlineEntryLink"/>
                                </div>
                                @error('onlineEntryLink')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                            </div>


                            <div id="hasEntryLimitDiv" class="mt-2 col-span-3">
                            <x-form-field>
                                <x-form-label for="hasEntryLimit">Has entry limit</x-form-label>
                                <div class="mt-2">
                                    <input
                                            @php if ($trial->hasEntryLimit== 1){echo "checked"; } @endphp
                                            name="hasEntryLimit" type="checkbox" value="1" id="hasEntryLimit"/>
                                    <x-form-error name="openingDate"/>
                                </div>
                                @error('hasEntryLimit')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                            </div>

                            <div id="entryLinkDiv" class="mt-2 col-span-3">
                                <x-form-field>
                                    <x-form-label for="club">Entry limit</x-form-label>
                                    <div class="mt-2 col-span-2">
                                        <x-form-input name="entryLimit" type="text" id="entryLimit"

                                                      value="{{$trial->entryLimit}}"
                                                      placeholder="Entry limit" />
                                        <x-form-error name="entryLimit"/>
                                    </div>
                                    @error('entryLimit')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </x-form-field>
                            </div>



                            <div id="entrySelectionBasisDiv" class=" col-span-3 mt-2">
                                <x-form-field>
                                    <x-form-label for="entrySelectionBasis">Entry selection</x-form-label>
                                    <div class="mt-2 col-span-2">

                                        @foreach($entrySelectionArray as $option)
                                            <input name="entrySelectionBasis"
                                                   <?php if($option == $trial->entrySelectionBasis) { ?>checked="checked" <?php } ?>
                                                   type="radio"
                                                   id="entrySelectionBasis"
                                                   value="{{$option}}">
                                            <label class="pl-1 pr-4" for="entrySelectionBasis">{{$option}}</label>
                                        @endforeach
                                        <x-form-error name="entrySelectionBasis"/>
                                    </div>
                                    @error('entryLimit')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </x-form-field>
                            </div>


                            <div id="hasWaitingListDiv" class=" col-span-3 mt-2">
                            <x-form-field>
                                <x-form-label for="hasWaitingList">Enable waiting list if entry full</x-form-label>
                                <div class="mt-2">
                                    <input
                                            @php if ($trial->hasWaitingList== 1){echo "checked"; } @endphp

                                            name="hasWaitingList" type="checkbox" value="1" id="hasWaitingList"  />
                                    <x-form-error name="openingDate"/>
                                </div>
                                @error('hasWaitingList')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                            </div>


                            <div id="hasOpeningDateDiv" class=" col-span-3 mt-2">
                            <x-form-field>
                                <x-form-label for="hasOpeningDate">Has opening date/time for entries</x-form-label>
                                <div class="mt-2">
                                    <input
                                            @php if ($trial->hasOpeningDate == 1){echo "checked"; } @endphp
                                            name="hasOpeningDate" type="checkbox" value="1" id="hasOpeningDate" >                                  <x-form-error name="openingDate"/>
                                </div>
                                @error('hasOpeningDate')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                            </div>


                            <div id="openingDateDiv" class=" col-span-3 mt-2">
                                <x-form-field >
                                    <x-form-label for="openingDate">Opening date/time for entries</x-form-label>
                                    <div class="mt-2 col-span-2">
                                        <x-form-input name="openingDate" type="datetime-local" min="{{date('Y-m-d')}}" id="openingDate" value="{{$trial->openingDate}}"/>
                                        <x-form-error name="openingDate"/>
                                    </div>
                                    @error('date')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </x-form-field>
                            </div>


                            <div id="hasClosingDateDiv" class=" col-span-3 mt-2">
                            <x-form-field>
                                <x-form-label for="hasClosingDate">Has closing date/time for entries</x-form-label>
                                <div class="mt-2">
                                    <input
                                            @php if ($trial->hasClosingDate== 1){ echo "checked"; } @endphp
                                            name="hasClosingDate" type="checkbox" value="1" id="hasClosingDate"   >
                                    <x-form-error name="openingDate"/>
                                </div>
                                @error('hasClosingDate')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                            </div>


                            <div id="closingDateDiv" class=" col-span-3 mt-2">
                                <x-form-field>
                                    <x-form-label for="closingDate">Closing date/time for entries</x-form-label>
                                    <div class="mt-2 col-span-2">
                                        <x-form-input name="closingDate" type="datetime-local" min="{{date('Y-m-d')}}" id="closingDate" value="{{$trial->closingDate}}"/>
                                        <x-form-error name="closingDate"/>
                                    </div>
                                    @error('date')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </x-form-field>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        <div id="Scoring" class="tabcontent pt-0">
            <div class="space-y-2">
                    <div class="px-4 py-4 mt-0 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">


                        <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                            <div id="scoringModeDiv" class="col-span-3">
                                <x-form-field>
                                    <x-form-label for="scoringMode">Scoring mode</x-form-label>
                                    <div class="mt-2">
                                        @foreach($scoringModeArray as $option)
                                            <input name="scoringMode"
                                                   <?php if($option == $trial->scoringMode) { ?>checked="checked" <?php } ?>
                                                   type="radio" id="scoringMode" value="{{$option}}">
                                            <label class="pl-1 pr-4" for="scoringMode">{{$option}}</label>
                                        @endforeach
                                        <x-form-error name="scoringMode"/>
                                    </div>
                                    @error('entryLimit')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </x-form-field>
                            </div>

                            <div id="stopNonStopDiv" class=" col-span-3">
                                <x-form-field>
                                    <x-form-label for="stopNonStop">Stop permitted/Non-stop</x-form-label>
                                    <div class="mt-2 col-span-3">
                                        @foreach($stopAllowedArray as $option)
                                            <input name="stopNonStop"
                                                   <?php if($option == $trial->stopNonStop) { ?>checked="checked" <?php } ?>
                                                   type="radio" id="stopNonStop" value="{{$option}}" required>
                                            <label class="pl-1 pr-4" for="{{$option}}">{{$option}}</label>
                                        @endforeach
                                        <x-form-error name="stopNonStop"/>
                                    </div>
                                    @error('entryLimit')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </x-form-field>
                            </div>

                        <div id="numSectionsDiv" class="mt-4 col-span-3">
                            <x-form-field>
                                <x-form-label for="numSections">Number of sections</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="numSections" type="text" id="numSections"
                                                  value="{{$trial->numSections}}"
                                                  placeholder="Number of sections" />
                                    <x-form-error name="numSections"/>
                                </div>
                                @error('numSections')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>

                        <div id="numLapsDiv" class="mt-4 col-span-3">
                            <x-form-field>
                                <x-form-label for="numLaps">Number of laps</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="numLaps" type="text" id="numLaps"
                                                  value="{{$trial->numLaps}}"
                                                  placeholder="Number of laps" />
                                    <x-form-error name="numLaps"/>
                                </div>
                                @error('numLaps')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>

                        <div id="numRowsDiv" class="mt-4 col-span-3">
                            <x-form-field>
                                <x-form-label for="numRows">Number of rows in scoresheet</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="numRows" type="text" id="numRows"
                                                  value="{{$trial->numRows}}"
                                                  placeholder="Number of rows" />
                                    <x-form-error name="numRows"/>
                                </div>
                                @error('numRows')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>

                            <div id="numColumnsDiv" class="mt-4 col-span-3">
                                <x-form-field>
                                    <x-form-label for="numColumns">Number of columns in scoresheet</x-form-label>
                                    <div class="mt-2 col-span-2">
                                        <x-form-input name="numColumns" type="text" id="numColumns"
                                                      value="{{$trial->numColumns}}"
                                                      placeholder="Number of columns" />
                                        <x-form-error name="numColumns"/>
                                    </div>
                                    @error('numColumns')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </x-form-field>
                            </div>

                            <div id="fiftyFiftyDiv" class="mt-4 col-span-3">
                                <x-form-field>
                                    <x-form-label for="fifty_fifty">50/50 Sections</x-form-label>
                                    <div class="mt-2 col-span-2">
                                        <x-form-input name="fifty_fifty" type="text" id="fifty_fifty"
                                                      value="{{$trial->fifty_fifty}}"
                                                      placeholder="50/50 sections - separated by commas" />
                                        <x-form-error name="fifty_fifty"/>
                                    </div>
                                    @error('fifty_fifty')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </x-form-field>
                            </div>

                    </div>
                </div>
        </div>
        </div>

        <div id="Regulations" class="tabcontent pt-0">
            <div class="space-y-2">
                    <div class="px-4 py-4 mt-0 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">


                        <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                            <div id="authorityDiv" class=" col-span-3">
                                <x-form-field>
                                    <x-form-label for="authority">Permit Authority</x-form-label>
                                    <div class="mt-2">
                                        @foreach($authorityArray as $option)
                                            <input name="authority"
                                                   <?php if($option == $trial->authority) { ?>checked="checked" <?php } ?>
                                                   type="radio" id="authority" value="{{$option}}" required>
                                            <label class="pl-1 pr-4" for="authority">{{$option}}</label>
                                        @endforeach
                                        <x-form-error name="authority"/>
                                    </div>
                                    @error('authority')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </x-form-field>
                            </div>


                            <div id="centreDiv" class=" col-span-3">
                                <x-form-field>
                                    <x-form-label for="centre">Centre</x-form-label>
                                    <div class="mt-2 col-span-3">
                                        <x-form-input name="centre" type="text" id="centre"
                                                      value="{{$trial->centre}}"
                                                      placeholder="Optional" />
                                        <x-form-error name="centre"/>
                                    </div>
                                    @error('centre')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </x-form-field>
                            </div>


                            <div id="statusDiv" class="mt-2 col-span-3">
                                <x-form-field>
                                    <x-form-label for="status">Entry restrictions</x-form-label>
                                    <div class="mt-2 col-span-3">
                                        @foreach($restrictionArray as $option)
                                            <input
                                                    <?php if($option == $trial->status) { ?>checked="checked" <?php } ?>
                                            name="status" type="radio" id="status" value="{{$option}}" required>
                                            <label class="pl-1 pr-4" for="status">{{$option}}</label>
                                        @endforeach
                                        <x-form-error name="status"/>
                                    </div>
                                    @error('status')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </x-form-field>
                            </div>

                            <div id="otherRestrictionDiv" class="mt-2 col-span-3">
                                <x-form-field>
                                    <x-form-label for="coc">Other Restriction</x-form-label>
                                    <div class="mt-2 col-span-3">
                                        <x-form-input name="otherRestriction" type="text" id="otherRestriction"
                                                      value="{{$trial->otherRestriction}}"
                                                      placeholder="Please give details" />
                                        <x-form-error name="otherRestriction"/>
                                    </div>
                                    @error('otherRestriction')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </x-form-field>
                            </div>



                            <div id="cocDiv" class="mt-4 col-span-3">
                                <x-form-field>
                                    <x-form-label for="coc">Clerk of Course</x-form-label>
                                    <div class="mt-2 col-span-2">
                                        <x-form-input name="coc" type="text" id="coc" required
                                                      value="{{$trial->coc}}"
                                                      placeholder="Clerk of the course (please include licence number)" />
                                        <x-form-error name="coc"/>
                                    </div>
                                    @error('coc')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </x-form-field>
                            </div>



                            <x-form-field>
                                <x-form-label for="hasNotes">Additional notes</x-form-label>
                                <div class="mt-2">
                                    <input @php if ($trial->hasNotes== 1){echo "checked"; } @endphp

                                           name="hasNotes" type="checkbox" value="1" id="hasNotes"  />
                                    <x-form-error name="hasNotes"/>
                                </div>
                                @error('hasNotes')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <div id="notesDiv" class="mt-2 col-span-full">
                                <x-form-field>
                                    <x-form-label for="notes">Notes</x-form-label>
                                    <div class="mt-2 ">
                                        <textarea class="withEditor" name="notes" type="text" id="notes" >{{$trial->notes}}</textarea>
                                    </div>
                                    @error('notes')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </x-form-field>
                            </div>
                        </div>
                    </div>
            </div>
        </div>

        <div id="Fees" class="tabcontent pt-0">
            <div class="space-y-12">
                    <div class="px-4 py-4 mt-0 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                            <div id="adultEntryFeeDiv" class=" col-span-3">
                                <x-form-field>
                                    <x-form-label for="adultEntryFee">Adult entry fee</x-form-label>
                                    <div class="mt-2 col-span-3">
                                        <x-form-input name="adultEntryFee" type="text" id="adultEntryFee" required
                                                      value="{{$trial->adultEntryFee}}"
                                                      placeholder="Omit £ signs eg. 25.00" />
                                        <x-form-error name="adultEntryFee"/>
                                    </div>
                                    @error('Adult entry fee')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </x-form-field>
                            </div>

                            <div id="youthEntryFeeDiv" class=" col-span-3">
                                <x-form-field>
                                    <x-form-label for="youthEntryFee">Youth entry fee</x-form-label>
                                    <div class="mt-2 col-span-3">
                                        <x-form-input name="youthEntryFee" type="text" id="youthEntryFee" required
                                                      value="{{$trial->youthEntryFee}}"
                                                      placeholder="Omit £ signs eg. 25.00" />
                                        <x-form-error name="youthEntryFee"/>
                                    </div>
                                    @error('youthEntryFee')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </x-form-field>
                            </div>

                            <div id="hasEodSurchargeDiv" class="mt-4 col-span-3">
                                <x-form-field>
                                    <x-form-label for="hasEodSurcharge">Surcharge for Entry on the Day</x-form-label>
                                    <div class="mt-2">
                                        <input
                                                @php if ($trial->hasEodSurcharge == 1){echo "checked"; } @endphp
                                                name="hasEodSurcharge" type="checkbox" value="1" id="hasEodSurcharge" />
                                        <x-form-error name="hasEodSurcharge"/>
                                    </div>
                                    @error('hasEodSurcharge')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </x-form-field>
                            </div>

                            <div id="eodSurchargeDiv" class="mt-4 col-span-3">
                                <x-form-field>
                                    <x-form-label for="eodSurcharge">Surcharge</x-form-label>
                                    <div class="mt-2 col-span-3">
                                        <x-form-input name="eodSurcharge" type="text" id="eodSurcharge"
                                                      value="{{$trial->eodSurcharge}}"
                                                      placeholder="Omit £ signs eg. 5.00" />
                                        <x-form-error name="eodSurcharge"/>
                                    </div>
                                    @error('eodSurcharge')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </x-form-field>
                            </div>

                            <div class="text-violet-800 font-semibold col-span-full">Additional items for purchase such as T-shirts, Pub Supper or Camping can be added. Please contact TrialMonster - monster@trialmonster.uk - with details.</div>

                        </div>
                    </div>
                </div>
        </div>

        <div class="flex ml-4 mr-4  mt-4 justify-between" id="buttons">
            <div>
            <a href="/adminTrials"
               class="rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-violet-900 shadow-sm hover:bg-violet-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-900">Cancel</a>
@if(!$isLocked)
            <button type="submit" name="submitbutton" value="apply"
                    class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                Update
            </button>
    @endif
            </div>
{{--<div>--}}
{{--    <button type="submit" name="submitbutton" value="saveasnew"--}}
{{--                    class="rounded-md ml-2 bg-green-600 px-3 py-1 text-sm font-light  border border-green-800 text-white drop-shadow-lg hover:bg-green-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">--}}
{{--                Add as New Trial--}}
{{--            </button>--}}
{{--        </div>--}}
        </div>
    </form>
    <script>
        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>
</x-club>