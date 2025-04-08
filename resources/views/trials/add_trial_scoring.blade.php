<x-club>
    <x-slot:heading>
        Create a new trial
    </x-slot:heading>
    @php
        $courseArray = array("Expert", "Intermediate", "Hard Novice", "Novice", "50/50", "Easy", "Clubman", "Clubman A", "Clubman B");
        $classArray = array("Adult", "Youth", "Twinshock", "Pre-65", "Air-cooled Monoshock", "Over 40", "Over 50", "Youth A", "Youth B", "Youth C", "Youth D");
        $entryMethodArray = array("Enter on day", "TrialMonster", "Online");
        $entrySelectionArray = array("Order of Payment", "Ballot", "Selection", "Other");
        $scoringModeArray = array("Observer", "Electronic", "Punch Cards", "Other");
        $stopAllowedArray = array("Stop permitted", "Non-stop");
        $authorityArray = array("ACU", "AMCA", "Other");
        $restrictionArray = array("Open", "Centre", "Closed to Club", "Other Restriction");

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

    <form action="/trials/save" method="POST">
        <input type="hidden" name="task" id="task" value="scoringData">
        <input type="hidden" name="trialID" id="trialID" value="{{$trial->id}}">
        @csrf
        <div id="Scoring" class="pt-0 tabcontent">
            <div class="space-y-12">
                <div class="px-4 py-4 mt-0 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">



                        <div id="scoringModeDiv" class="col-span-3">
                            <x-form-field>
                                <x-form-label for="scoringMode">Scoring mode</x-form-label>
                                <div class="mt-2">
                                    @foreach($scoringModeArray as $option)
                                        <input name="scoringMode" type="radio" id="scoringMode" value="{{$option}}" required>
                                        <label class="pl-1 pr-4" for="scoringMode">{{$option}}</label>
                                    @endforeach
                                    <x-form-error name="scoringMode"/>
                                </div>
                                @error('entryLimit')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>

                        <div id="stopNonStopDiv" class="col-span-3">
                            <x-form-field>
                                <x-form-label for="stopNonStop">Stop permitted/Non-stop</x-form-label>
                                <div class="mt-2 col-span-3">
                                    @foreach($stopAllowedArray as $option)
                                        <input name="stopNonStop" type="radio" id="stopNonStop" value="{{$option}}" required>
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
                                <div class="mt-2 col-span-3">
                                    <x-form-input name="numSections" type="text" id="numSections"
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
                                <div class="mt-2 col-span-3">
                                    <x-form-input name="numLaps" type="text" id="numLaps"
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
                                <div class="mt-2 col-span-3">
                                    <x-form-input name="numRows" type="text" id="numRows"
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
                                                  placeholder="Number of columns" />
                                    <x-form-error name="numColumns"/>
                                </div>
                                @error('numColumns')
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