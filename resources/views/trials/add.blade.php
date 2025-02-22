<x-main>
    <x-slot:heading>
        Create a new trial
    </x-slot:heading>


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

        function openSection(evt, cityName) {
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
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }


        function toggle(checked) {
            var x = document.getElementById("numDaysDiv");
            if (checked) {
                x.style.display = "inline-block";
            } else {
                x.style.display = "none";
            }
        }
    </script>
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
                                <input name="isMultiDay" type="checkbox" value="1" id="isMultiDay" onchange="toggle(checked)" />
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
                                    @foreach($venues as $venue)
                                        <option value="{{$venue->id}}">{{$venue->name}}</option>
                                    @endforeach
                                </select>
                                </div>
                            </x-form-field>

                        <x-form-field>
                            <x-form-label for="otherVenue">Venue if not listed</x-form-label>
                            <div class="mt-2 col-span-2">
                                <x-form-input name="otherVenue" type="text" id="otherVenue" placeholder="Venue name" required/>
                                <x-form-error name="otherVenue"/>
                            </div>
                            @error('otherVenue')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </x-form-field>
                    </div>



                </div>

                <div class="mt-4" id="buttons">
                    <a href="/adminTrials"
                       class="rounded-md bg-white px-3 py-2 text-sm  text-blue-600 shadow-sm hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>

                    <button type="submit"
                            class="rounded-md ml-2 bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                        Save
                    </button>

{{--                    <button type="submit"--}}
{{--                            class="rounded-md ml-2 bg-green-600 px-3 py-1 text-sm font-light  border border-green-800 text-white drop-shadow-lg hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">--}}
{{--                        Save as template--}}
{{--                    </button>--}}
                </div>
{{--                <div class="text-sm  font-semibold  mt-2 text-green-500">Save as template creates a Trial template </div>--}}
            </div>
        </div>
        </div>


        <div id="Trial" class="tabcontent">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="px-4 py-4 mt-6 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">





                            <x-form-field>
                                <x-form-label for="courselist">Courses</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="courselist" type="text" id="courselist"
                                                  placeholder="List of classes separated by commas" required/>
                                    <x-form-error name="courselist"/>
                                </div>
                                @error('courselist')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="classlist">Classes</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="classlist" type="text" id="classlist"
                                                  placeholder="List of classes separated by commas" required/>
                                    <x-form-error name="classlist"/>
                                </div>
                                @error('classlist')
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
                                                  placeholder="Start interval in seconds" required/>
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
                                                  placeholder="Number of seconds per point lost" required/>
                                    <x-form-error name="penaltyDelta"/>
                                </div>
                                @error('penaltyDelta')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>



                        </div>
                    </div>

                    <div class="mt-4" id="buttons">
                        <a href="/adminTrials"
                           class="rounded-md bg-white px-3 py-2 text-sm  text-blue-600 shadow-sm hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>

                        <button type="submit"
                                class="rounded-md ml-2 bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            Save
                        </button>

                        {{--                    <button type="submit"--}}
                        {{--                            class="rounded-md ml-2 bg-green-600 px-3 py-1 text-sm font-light  border border-green-800 text-white drop-shadow-lg hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">--}}
                        {{--                        Save as template--}}
                        {{--                    </button>--}}
                    </div>
                    {{--                <div class="text-sm  font-semibold  mt-2 text-green-500">Save as template creates a Trial template </div>--}}
                </div>
            </div>
        </div>


        <div id="Entries" class="tabcontent">
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
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="phone" type="text" id="phone" placeholder="Contact phone" required/>
                                    <x-form-error name="phone"/>
                                </div>
                                @error('phone')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="courselist">Courses</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="courselist" type="text" id="courselist"
                                                  placeholder="List of classes spearated by commas" required/>
                                    <x-form-error name="courselist"/>
                                </div>
                                @error('courselist')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="classlist">Classes</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="classlist" type="text" id="classlist"
                                                  placeholder="List of classes separated by commas" required/>
                                    <x-form-error name="classlist"/>
                                </div>
                                @error('classlist')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>


                        </div>
                    </div>

                    <div class="mt-4" id="buttons">
                        <a href="/adminTrials"
                           class="rounded-md bg-white px-3 py-2 text-sm  text-blue-600 shadow-sm hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>

                        <button type="submit"
                                class="rounded-md ml-2 bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            Save
                        </button>

                        {{--                    <button type="submit"--}}
                        {{--                            class="rounded-md ml-2 bg-green-600 px-3 py-1 text-sm font-light  border border-green-800 text-white drop-shadow-lg hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">--}}
                        {{--                        Save as template--}}
                        {{--                    </button>--}}
                    </div>
                    {{--                <div class="text-sm  font-semibold  mt-2 text-green-500">Save as template creates a Trial template </div>--}}
                </div>
            </div>
        </div>

        <div id="Scoring" class="tabcontent">
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
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="phone" type="text" id="phone" placeholder="Contact phone" required/>
                                    <x-form-error name="phone"/>
                                </div>
                                @error('phone')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="courselist">Courses</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="courselist" type="text" id="courselist"
                                                  placeholder="List of classes spearated by commas" required/>
                                    <x-form-error name="courselist"/>
                                </div>
                                @error('courselist')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="classlist">Classes</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="classlist" type="text" id="classlist"
                                                  placeholder="List of classes separated by commas" required/>
                                    <x-form-error name="classlist"/>
                                </div>
                                @error('classlist')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>


                        </div>
                    </div>

                    <div class="mt-4" id="buttons">
                        <a href="/adminTrials"
                           class="rounded-md bg-white px-3 py-2 text-sm  text-blue-600 shadow-sm hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>

                        <button type="submit"
                                class="rounded-md ml-2 bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            Save
                        </button>

                        {{--                    <button type="submit"--}}
                        {{--                            class="rounded-md ml-2 bg-green-600 px-3 py-1 text-sm font-light  border border-green-800 text-white drop-shadow-lg hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">--}}
                        {{--                        Save as template--}}
                        {{--                    </button>--}}
                    </div>
                    {{--                <div class="text-sm  font-semibold  mt-2 text-green-500">Save as template creates a Trial template </div>--}}
                </div>
            </div>
        </div>

        <div id="Regulations" class="tabcontent">
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
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="phone" type="text" id="phone" placeholder="Contact phone" required/>
                                    <x-form-error name="phone"/>
                                </div>
                                @error('phone')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="courselist">Courses</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="courselist" type="text" id="courselist"
                                                  placeholder="List of classes spearated by commas" required/>
                                    <x-form-error name="courselist"/>
                                </div>
                                @error('courselist')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="classlist">Classes</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="classlist" type="text" id="classlist"
                                                  placeholder="List of classes separated by commas" required/>
                                    <x-form-error name="classlist"/>
                                </div>
                                @error('classlist')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>


                        </div>
                    </div>

                    <div class="mt-4" id="buttons">
                        <a href="/adminTrials"
                           class="rounded-md bg-white px-3 py-2 text-sm  text-blue-600 shadow-sm hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>

                        <button type="submit"
                                class="rounded-md ml-2 bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            Save
                        </button>

                        {{--                    <button type="submit"--}}
                        {{--                            class="rounded-md ml-2 bg-green-600 px-3 py-1 text-sm font-light  border border-green-800 text-white drop-shadow-lg hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">--}}
                        {{--                        Save as template--}}
                        {{--                    </button>--}}
                    </div>
                    {{--                <div class="text-sm  font-semibold  mt-2 text-green-500">Save as template creates a Trial template </div>--}}
                </div>
            </div>
        </div>

        <div id="Fees" class="tabcontent">
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
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="phone" type="text" id="phone" placeholder="Contact phone" required/>
                                    <x-form-error name="phone"/>
                                </div>
                                @error('phone')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="courselist">Courses</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="courselist" type="text" id="courselist"
                                                  placeholder="List of classes spearated by commas" required/>
                                    <x-form-error name="courselist"/>
                                </div>
                                @error('courselist')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="classlist">Classes</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="classlist" type="text" id="classlist"
                                                  placeholder="List of classes separated by commas" required/>
                                    <x-form-error name="classlist"/>
                                </div>
                                @error('classlist')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>


                        </div>
                    </div>

                    <div class="mt-4" id="buttons">
                        <a href="/adminTrials"
                           class="rounded-md bg-white px-3 py-2 text-sm  text-blue-600 shadow-sm hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>

                        <button type="submit"
                                class="rounded-md ml-2 bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            Save
                        </button>

                        {{--                    <button type="submit"--}}
                        {{--                            class="rounded-md ml-2 bg-green-600 px-3 py-1 text-sm font-light  border border-green-800 text-white drop-shadow-lg hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">--}}
                        {{--                        Save as template--}}
                        {{--                    </button>--}}
                    </div>
                    {{--                <div class="text-sm  font-semibold  mt-2 text-green-500">Save as template creates a Trial template </div>--}}
                </div>
            </div>
        </div>
    </form>
    <script>
        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>
</x-main>