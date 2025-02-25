<style>
    /* Style the tab */
    .tab {
        overflow: hidden;
    }

    /* Style the buttons that are used to open the tab content */
    .tab button {
        /*background-color: inherit;*/
        float: left;
        /*border: black;*/
        outline: none;
        cursor: pointer;
        /*padding: 14px 16px;*/
        transition: 0.3s;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        /*background-color: #ddd;*/
    }

    /* Create an active/current tablink class */
    .tab button.active {
        background-color: #fff;
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 6px 12px;
    }
</style>
<script>

    function openSection(evt, tabName) {
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }


    function toggle(checked, divName) {
        console.log("toggle called")
        var x = document.getElementById(divName);
        if (checked) {
            x.style.display = "inline-block";
        } else {
            x.style.display = "none";
        }
    }
</script>
<x-main>
    <x-slot:heading>
        Create a new trial
    </x-slot:heading>
    @php
        $courseArray = array("Expert", "Intermediate", "Novice", "50/50", "Easy", "Clubman", "Clubman A", "Clubman B", "Hard Novice");
        $classArray = array("Adult", "Youth", "Twinshock", "Pre-65", "Air-cooled Monoshock", "Over 40", "Over 50", "Youth A", "Youth B", "Youth C", "Youth D");
        $entryMethods = array("Enter on day", "TrialMonster", "Online");
        $entrySelectionArray = array("Order of Receipt", "Ballot", "Selection", "Other");
    @endphp


    @php
        //dd($selection);
    @endphp


    <div class="tab pl-4">
        <button class="tablinks    hover:bg-blue-200 p-2  " id="defaultOpen" onclick="openSection(event, 'Details')">Details</button>
        <button class="tablinks  hover:bg-blue-200 p-2  " onclick="openSection(event, 'Trial')">Trial</button>
        <button class="tablinks  hover:bg-blue-200 p-2  " onclick="openSection(event, 'Entries')">Entries</button>
        <button class="tablinks  hover:bg-blue-200 p-2  " onclick="openSection(event, 'Scoring')">Scoring</button>
        <button class="tablinks  hover:bg-blue-200 p-2  " onclick="openSection(event, 'Regulations')">Regulations</button>
        <button class="tablinks  hover:bg-blue-200 p-2  " onclick="openSection(event, 'Fees')">Fees</button>
    </div>
    <form action="/trials/store" method="POST">

        @csrf

        <div id="Details" class="tabcontent">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="px-4 py-4 mt-6 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                            <x-form-field>
                                <x-form-label for="name">Event Name</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="name" type="text" id="name"
                                                  placeholder="Name" required/>
                                    <x-form-error name="name"/>
                                </div>
                                @error('name')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="club">Organising Club</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="club" type="text" id="club"
                                                  placeholder="Club name" required/>
                                    <x-form-error name="club"/>
                                </div>
                                @error('club')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="date">Date</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="date" type="date" min="{{date('Y-m-d')}}" id="date"  required/>
                                    <x-form-error name="date"/>
                                </div>
                                @error('date')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="isMultiDay">Multi-day event</x-form-label>
                                <div class="mt-2">
                                    <input name="isMultiDay" type="checkbox" value="1" id="isMultiDay" onchange="toggle(checked, 'numDaysDiv')" />
                                    <x-form-error name="isMultiDay"/>
                                </div>
                                @error('date')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <div id="numDaysDiv" class="hidden col-span-full">
                                <x-form-field id="" name="" class="">
                                    <x-form-label for="numDays">Number of days</x-form-label>
                                    <div class="mt-2 col-span-2">
                                        <x-form-input name="numDays" type="number" id="numDays" value="1" min="1"/>
                                        <x-form-error name="numDays"/>
                                    </div>
                                    @error('email')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </x-form-field>
                            </div>

                            <x-form-field>
                                <x-form-label for="startTime">Start time</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="startTime" type="text" id="startTime" placeholder="Trial starting time" required/>
                                    <x-form-error name="startTime"/>
                                </div>
                                @error('email')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="contactName ">Contact name</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="contactName" type="text" id="contactName  " placeholder="Contact name " required/>
                                    <x-form-error name="contactName"/>
                                </div>
                                @error('email')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="email">Email</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="email" type="email" id="email" placeholder="Contact email" required/>
                                    <x-form-error name="email"/>
                                </div>
                                @error('email')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="phone">Phone</x-form-label>
                                <div class="mt-2 col-span-2 ">
                                    <x-form-input name="phone" type="text" id="phone" placeholder="Contact phone" required/>
                                    <x-form-error name="phone"/>
                                </div>
                                @error('phone')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>


                            <x-form-field class="mt-2 col-span-2 sm:col-span-3">
                                <x-form-label for="venue">Venue</x-form-label>
                                <div class="flex mt-2 rounded-md shadow-sm ring-1 ring-inset outline outline-1 -outline-offset-1 drop-shadow-lg outline-blue-700 focus-within:ring-2  focus-within:ring-inset focus-within:ring-blue-600 sm:max-w-md" >
                                    <select class="border-0  pl-2 pt-2  bg-transparent pb-1 space-x-4 :focus border-0" name="venueID" id="venueID">
                                        <option value="0">Other</option>
                                        @foreach($venues as $venue)
                                            <option value="{{$venue->id}}">{{$venue->name}}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="otherVenue">Venue if not listed</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="otherVenue" type="text" id="otherVenue" placeholder="Venue name" />
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
        </div>

        <div id="Trial" class="tabcontent">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="px-4 py-4 mt-6 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                            <x-form-field>
                                <x-form-label class="pr-0" for="courselist">Courses</x-form-label>
                                <div class="mt-2 pl-2 pr-0">
                                    @foreach($courseArray as $course)
                                        <div>
                                            <input  name="courselist[]" type="checkbox" id="courselist" value="{{$course}}"/>
                                            <label  class="pl-4 pr-0" for="courselist">{{$course}}
                                            </label>
                                        </div>
                                    @endforeach
                                    <x-form-error name="courselist[]"/>
                                </div>
                                @error('courselist')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>


                            <x-form-field>
                                <x-form-label for="customCourses">Custom courses</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="customCourses" type="checkboxes" id="customCourses"
                                                  placeholder="List of courses separated by commas" />
                                    <x-form-error name="customCourses"/>
                                </div>
                                @error('customCourses')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="classlist">Classes</x-form-label>
                                <div class="mt-2 pl-2 pr-2">
                                    @foreach($classArray as $class)

                                        <div>
                                            <input  name="classlist[]" type="checkbox" id="classlist" value="{{$class}}"/>
                                            <label  class="pl-4 pr-2" for="classlist">{{$class}}</label>
                                        </div>
                                    @endforeach
                                    <x-form-error name="classlist[]"/>
                                </div>
                                @error('classlist')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="customClasses">Custom classes</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="customClasses" type="text" id="customClasses"
                                                  placeholder="List of classes separated by commas" />
                                    <x-form-error name="customClasses"/>
                                </div>
                                @error('customClasses')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="hasTimePenalty">Time and Observation</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <input name="hasTimePenalty" type="checkbox" id="hasTimePenalty" />
                                    <x-form-error name="hasTimePenalty"/>
                                </div>
                                @error('hasTimePenalty')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="startInterval">Start interval (seconds)</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="startInterval" type="text" id="startInterval"
                                                  placeholder="Start interval in seconds" />
                                    <x-form-error name="startInterval"/>
                                </div>
                                @error('classlist')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="penaltyDelta">Penalty tariff</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="penaltyDelta" type="text" id="penaltyDelta"
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

        <div id="Entries" class="tabcontent">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="px-4 py-4 mt-6 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                            <x-form-field>
                                <x-form-label class="pr-0" for="courselist">How to enter</x-form-label>
                                <div class="mt-2 pl-2 pr-0">
                                    @foreach($entryMethods as $entryMethod)
                                        <div>
                                            <input  name="entryMethod[]" type="checkbox" id="entryMethod[]" value="{{$entryMethod}}"/>
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



                            <x-form-field>
                                <x-form-label for="name">Online entry link</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="onlineEntryLink" type="text" id="onlineEntryLink" placeholder="Entry URL here"/>
                                    <x-form-error name="onlineEntryLink"/>
                                </div>
                                @error('onlineEntryLink')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>



                            <x-form-field>
                                <x-form-label for="hasEntryLimit">Has entry limit</x-form-label>
                                <div class="mt-2">
                                    <input name="hasEntryLimit" type="checkbox" value="1" id="hasEntryLimit" onchange="toggle(checked, 'entryLimitDiv')" />
                                    <x-form-error name="openingDate"/>
                                </div>
                                @error('hasEntryLimit')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <div id="entryLimitDiv" class="hidden col-span-full">
                            <x-form-field>
                                <x-form-label for="club">Entry limit</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="entryLimit" type="text" id="entryLimit"
                                                  placeholder="Entry limit" />
                                    <x-form-error name="entryLimit"/>
                                </div>
                                @error('entryLimit')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                            </div>



                            <div  class=" col-span-full">
                                <x-form-field>
                                    <x-form-label for="entrySelectionBasis">Entry selection</x-form-label>
                                    <div class="mt-2 col-span-2">

                                        @foreach($entrySelectionArray as $option)
                                            <input name="entrySelectionBasis" type="radio" id="entrySelectionBasis" >
                                            <label class="pl-1 pr-4" for="{{$option}}">{{$option}}</label>
                                        @endforeach
                                        <x-form-error name="entrySelectionBasis"/>
                                    </div>
                                    @error('entryLimit')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </x-form-field>
                            </div>


                            <x-form-field>
                                <x-form-label for="hasWaitingList">Enable waiting list if entry full</x-form-label>
                                <div class="mt-2">
                                    <input name="hasWaitingList" type="checkbox" value="1" id="hasWaitingList"  />
                                    <x-form-error name="openingDate"/>
                                </div>
                                @error('hasWaitingList')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="hasOpeningDate">Has opening date/time for entries</x-form-label>
                                <div class="mt-2">
                                    <input name="hasOpeningDate" type="checkbox" value="1" id="hasOpeningDate" onchange="toggle(checked, 'openingDate')" />
                                    <x-form-error name="openingDate"/>
                                </div>
                                @error('hasOpeningDate')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <div id="openingDate" class="col-span-full">
                            <x-form-field >
                                <x-form-label for="openingDate">Opening date/time for entries</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="openingDate" type="datetime-local" min="{{date('Y-m-d')}}" id="openingDate" />
                                    <x-form-error name="openingDate"/>
                                </div>
                                @error('date')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                            </div>

                            <x-form-field>
                                <x-form-label for="hasClosingDate">Has closing date/time for entries</x-form-label>
                                <div class="mt-2">
                                    <input name="hasClosingDate" type="checkbox" value="1" id="hasClosingDate" onchange="toggle(checked, 'closingDate')" />
                                    <x-form-error name="openingDate"/>
                                </div>
                                @error('hasClosingDate')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <div id="closingDate" class="col-span-full">
                            <x-form-field>
                                <x-form-label for="closingDate">Closing date/time for entries</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="closingDate" type="datetime-local" min="{{date('Y-m-d')}}" id="closingDate" />
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
            </div>

        <div id="Scoring" class="tabcontent">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="px-4 py-4 mt-6 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">



                        </div>
                    </div>



                    </div>

                </div>
            </div>

        <div id="Regulations" class="tabcontent">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="px-4 py-4 mt-6 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div id="Fees" class="tabcontent">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="px-4 py-4 mt-6 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">



                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4" id="buttons">
            <a href="/adminTrials"
               class="rounded-md bg-white px-3 py-2 text-sm  text-blue-600 shadow-sm hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>

            <button type="submit"
                    class="rounded-md ml-2 bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                Save
            </button>
        </div>
    </form>
    <script>
        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>
</x-main>