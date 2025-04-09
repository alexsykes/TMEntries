<x-club>
    <x-slot:heading>
        Create a new trial
    </x-slot:heading>
    @php
        $courseArray = array("Expert", "Intermediate", "Hard Novice", "Novice", "50/50", "Clubman", "Clubman A", "Clubman B", "Easy");
        $classArray = array("Adult", "Youth", "Twinshock", "Pre-65", "Air-cooled Monoshock", "Over 40", "Over 50", "Youth A", "Youth B", "Youth C", "Youth D");
        $entryMethodArray = array("Enter on day", "TrialMonster", "Online");
        $entrySelectionArray = array("Order of Payment", "Ballot", "Selection", "Other");
        $scoringModeArray = array("Observer", "Electronic", "Punch Cards", "Other");
        $stopAllowedArray = array("Stop permitted", "Non-stop");
        $authorityArray = array("ACU", "AMCA", "Other");
        $restrictionArray = array("Open", "Centre", "Closed to Club", "Other Restriction");

    @endphp
    <form action="/trials/save" method="POST">
        <input type="hidden" name="task" id="task" value="entryData">
        <input type="hidden" name="trialID" id="trialID" value="{{$trial->id}}">

        @csrf

        <div id="Entries" class="tabcontent pt-0">
            <div class="space-y-12">
                <div class="px-4 py-4 mt-0 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                        <div id="entryMethodDiv" class="col-span-3">
                            <x-form-field>
                                <x-form-label class="pr-0" for="entryMethod">How to enter</x-form-label>
                                <div class="mt-2 pl-2 pr-0">
                                    @foreach($entryMethodArray as $entryMethod)
                                        <div>
                                            <input  name="entryMethod[]" type="checkbox" id="entryMethod[]" value="{{$entryMethod}}" />
                                            <label  class="pl-4 pr-0" for="entryMethod">{{$entryMethod}}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('entryMethod')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>

                        <div id="entryLinkDiv" class="col-span-3">
                            <x-form-field>
                                <x-form-label for="name">Online entry link</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="onlineEntryLink" type="text" id="onlineEntryLink" placeholder="Entry URL here"/>
                                </div>
                                @error('onlineEntryLink')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>

                        <div id="hasEntryLinkDiv" class="mt-2 col-span-3">
                            <x-form-field>
                                <x-form-label for="hasEntryLimit">Has entry limit</x-form-label>
                                <div class="mt-2">
                                    <input name="hasEntryLimit" type="checkbox" value="1" id="hasEntryLimit"  />
                                </div>
                                @error('hasEntryLimit')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>

                        <div id="entryLimitDiv" class=" col-span-3">
                            <x-form-field>
                                <x-form-label for="club">Entry limit</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="entryLimit" type="text" id="entryLimit"
                                                  placeholder="Entry limit" />
                                </div>
                                @error('entryLimit')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>



                        <div  id="entrySelectionBasisDiv"  class=" col-span-3 mt-2">
                            <x-form-field>
                                <x-form-label for="entrySelectionBasis">Entry selection</x-form-label>
                                <div class="mt-2 col-span-2">
                                    @foreach($entrySelectionArray as $option)
                                        <input name="entrySelectionBasis" type="radio" id="entrySelectionBasis" value="{{$option}}">
                                        <label class="pl-1 pr-4" for="entrySelectionBasis">{{$option}}</label>
                                    @endforeach
                                </div>
                                @error('entrySelectionBasis')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>

                        <div id="hasWaitingListDiv" class=" col-span-3 mt-2">
                            <x-form-field>
                                <x-form-label for="hasWaitingList">Enable waiting list if entry full</x-form-label>
                                <div class="mt-2">
                                    <input name="hasWaitingList" type="checkbox" value="1" id="hasWaitingList"  />
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
                                    <input name="hasOpeningDate" type="checkbox" value="1" id="hasOpeningDate"  />
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
                                    <x-form-input name="openingDate" type="datetime-local" min="{{date('Y-m-d')}}" id="openingDate" />
                                </div>
                                @error('openingDate')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>

                        <div id="hasClosingDateDiv" class=" col-span-3 mt-2">
                            <x-form-field>
                                <x-form-label for="hasClosingDate">Has closing date/time for entries</x-form-label>
                                <div class="mt-2">
                                    <input name="hasClosingDate" type="checkbox" value="1" id="hasClosingDate" />
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
                                    <x-form-input name="closingDate" type="datetime-local" min="{{date('Y-m-d')}}" id="closingDate" />
                                </div>
                                @error('closingDate')
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