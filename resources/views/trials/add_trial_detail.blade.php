<x-club>
    <x-slot:heading>
        Create a new trial for {{$club->name}}
    </x-slot:heading>
    <script>
        function competitionSelected() {
            if (series_id.options[series_id.selectedIndex].text != "Other") {
                console.log(series_id.options[series_id.selectedIndex].text);
                document.getElementById("name").value = series_id.options[series_id.selectedIndex].text;
            } else {
                document.getElementById("name").value = "";
            }
        }
    </script>
    @php
        $courseArray = array("Expert", "Intermediate", "Hard Novice", "Novice", "50/50", "Easy", "Clubman", "Clubman A", "Clubman B");
        $classArray = array("Adult", "Youth", "Twinshock", "Pre-65", "Air-cooled Monoshock", "Over 40", "Over 50", "Youth A", "Youth B", "Youth C", "Youth D");
        $entryMethodArray = array("Enter on day", "TrialMonster", "Online");
        $entrySelectionArray = array("Order of Payment", "Ballot", "Selection", "Other");
        $scoringModeArray = array("Observer", "App", "Punch Cards", "Other");
        $stopAllowedArray = array("Stop permitted", "Non-stop");
        $authorityArray = array("ACU", "AMCA", "Other");
        $restrictionArray = array("Open", "Centre", "Closed to Club", "Other Restriction");

    @endphp


    <form action="/trials/save" method="POST">
        <input type="hidden" value="detail" id="task" name="task">
        <input type="hidden" name="club" id="club" value="{{$club->name}}">
        @csrf

        <div id="Details" class="tabcontent pt-0">
            <div class="space-y-12">
                <div class="px-4 py-4 mt-0 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                        <x-form-field>
                            <x-form-label for="permit">Permit</x-form-label>
                            <div class="mt-2 col-span-2">
                                <x-form-input name="permit" type="text" id="permit"
                                              placeholder="Permit number" value="{{old('permit')}}" required/>
                                <x-form-error name="permit"/>
                            </div>
                            @error('permit')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </x-form-field>

                        {{--                        <x-form-field>--}}
                        {{--                            <x-form-label for="club">Organising Club</x-form-label>--}}
                        {{--                            <div class="mt-2 col-span-2">--}}
                        {{--                                <x-form-input name="club" type="text" id="club"--}}
                        {{--                                              value="{{$club->name}}"--}}
                        {{--                                              placeholder="Club name" required/>--}}
                        {{--                                <x-form-error name="club"/>--}}
                        {{--                            </div>--}}
                        {{--                            @error('club')--}}
                        {{--                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>--}}
                        {{--                            @enderror--}}
                        {{--                        </x-form-field>--}}

                        <x-form-field class="mt-2 col-span-2 sm:col-span-3">
                            <x-form-label for="venue">Trial / Series</x-form-label>
                            <div class="flex mt-2 rounded-md shadow-sm ring-1 ring-inset outline outline-1 -outline-offset-1 drop-shadow-lg outline-blue-700 focus-within:ring-2  focus-within:ring-inset focus-within:ring-blue-600 sm:max-w-md" >
                                <select onchange="competitionSelected()" class="border-0  pl-2 pt-2  bg-transparent pb-1 space-x-4 :focus border-0" name="series_id" id="series_id">
                                    @foreach($series as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                    <option value="0">Other</option>
                                </select>
                            </div>
                            @error('venue')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </x-form-field>



                        <x-form-field>
                            <x-form-label for="name">Event Name</x-form-label>
                            <div class="mt-2 col-span-2">
                                <x-form-input name="name" type="text" id="name"
                                              placeholder="Name"
                                              value="{{old('name')}}"
                                              required/>
                                <x-form-error name="name"/>
                            </div>
                            @error('name')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </x-form-field>

                        <x-form-field>
                            <x-form-label for="date">Date</x-form-label>
                            <div class="mt-2 col-span-2">
                                <x-form-input name="date" type="date" min="{{date('Y-m-d')}}" id="date"
                                              value="{{old('date')}}"
                                              required/>
                                <x-form-error name="date"/>
                            </div>
                            @error('date')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </x-form-field>

                        <x-form-field>
                            <x-form-label for="isMultiDay">Multi-day event</x-form-label>
                            <div class="mt-2">
                                <input name="isMultiDay" type="checkbox" value="1" id="isMultiDay"
                                        {{old('isMultiDay') != null ? 'checked' :''}}/>
                                <x-form-error name="isMultiDay"/>
                            </div>
                            @error('isMultiDay')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </x-form-field>

                        <div id="numDaysDiv" class=" col-span-full">
                            <x-form-field id="" name="" class="">
                                <x-form-label for="numDays">Number of days</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="numDays" type="number" id="numDays"
                                                  value="{{old('numDays', 1)}}"
                                                  min="1"/>
                                    <x-form-error name="numDays"/>
                                </div>
                            </x-form-field>
                        </div>

                        <x-form-field>
                            <x-form-label for="startTime">Start time</x-form-label>
                            <div class="mt-2 col-span-2">
                                <x-form-input name="startTime" type="text" id="startTime" placeholder="Trial starting time"

                                              value="{{old('startTime')}}"
                                              required/>
                                <x-form-error name="startTime"/>
                            </div>
                            @error('startTime')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </x-form-field>

                        <x-form-field>
                            <x-form-label for="contactName ">Organiser</x-form-label>
                            <div class="mt-2 col-span-2">
                                <x-form-input name="contactName" type="text" id="contactName  " placeholder="Contact name "

                                              value="{{old('contactName')}}"
                                              required/>
                                <x-form-error name="contactName"/>
                            </div>
                            @error('contactName')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </x-form-field>

                        <x-form-field>
                            <x-form-label for="email">Email</x-form-label>
                            <div class="mt-2 col-span-2">
                                <x-form-input name="email" type="email" id="email" placeholder="Contact email"
                                              value="{{old('email')}}"
                                              required/>
                                <x-form-error name="email"/>
                            </div>
                            @error('email')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </x-form-field>

                        <x-form-field>
                            <x-form-label for="phone">Phone</x-form-label>
                            <div class="mt-2 col-span-2 ">
                                <x-form-input name="phone" type="text" id="phone" placeholder="Contact phone" value="{{old('phone')}}" required/>
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
                            @error('venue')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </x-form-field>

                        <x-form-field>
                            <x-form-label for="otherVenue">Venue if not listed</x-form-label>
                            <div class="mt-2 col-span-2">
                                <x-form-input name="otherVenue" type="text" id="otherVenue" placeholder="Venue name" value="{{old('otherVenue')}}"/>
                                <x-form-error name="otherVenue"/>
                            </div>

                        </x-form-field>
                    </div>
                </div>
            </div>
        </div>

        <div class="ml-4 mt-4" id="buttons">
            <a href="/adminTrials"
               class="rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-blue-900 shadow-sm hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>

            <button type="submit"
                    class="rounded-md ml-2 bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                Save
            </button>
        </div>
    </form>
</x-club>