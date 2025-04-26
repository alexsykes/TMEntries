<x-club>
    <x-slot:heading>
        Create a new trial
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

    @endphp


    <form action="/trials/save" method="POST">
        @csrf
        <input type="hidden" name="task" id="task" value="regData">
        <input type="hidden" name="trialID" id="trialID" value="{{$trial->id}}">


        <div id="Regulations" class="pt-0 tabcontent">
            <div class="space-y-12">
                <div class="px-4 py-4 mt-0 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">


                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                        <div id="authorityDiv" class="col-span-3">
                            <x-form-field>
                                <x-form-label for="authority">Permit Authority</x-form-label>
                                <div class="mt-2">
                                    @foreach($authorityArray as $option)
                                        <input name="authority" type="radio" id="authority" value="{{$option}}"
                                               {{ (old('authority') == $option) ? ' checked' : '' }}
                                               required>
                                        <label class="pl-1 pr-4" for="authority">{{$option}}</label>
                                    @endforeach

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
                                                  value="{{old('centre')}}"
                                                  placeholder="Required if ACU permit" />

                                </div>
                                @error('centre')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>



                        <div id="statusDiv" class="mt-2 col-span-3">
                            <x-form-field>
                                <x-form-label for="status">Entry restrictions</x-form-label>
                                <div class="mt-2 col-span-2">
                                    @foreach($restrictionArray as $option)

                                        <input name="status" type="radio" id="status" value="{{$option}}"
                                               {{ (old('status') == $option) ? ' checked' : '' }}
                                               required>
                                        <label class="pl-1 pr-4" for="status">{{$option}}</label>
                                    @endforeach

                                </div>
                                @error('status')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>



                        <div id="otherRestrictionDiv" class="mt-2 col-span-3">
                            <x-form-field>
                                <x-form-label for="otherRestriction">Other Restriction</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="otherRestriction" type="text" id="otherRestriction"
                                                  value="{{old('otherRestriction')}}"
                                                  placeholder="Please give details" />

                                </div>
                                @error('otherRestriction')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>



                        <div id="cocDiv" class="mt-2 col-span-3">
                            <x-form-field>
                                <x-form-label for="coc">Clerk of Course</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="coc" type="text" id="coc" required
                                                  value="{{old('coc')}}"
                                                  placeholder="Clerk of the course (please include licence number)" />

                                </div>
                                @error('coc')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>



                        <div id="notesDiv" class="mt-4 col-span-full">
                            <x-form-field>
                                <x-form-label for="notes">Additional notes</x-form-label>
                                <div class="mt-2 ">
                                    <textarea name="notes" type="text" id="notes" placeholder="Add any additional notes">{{old('notes')}}</textarea>
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