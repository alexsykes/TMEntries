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
        @csrf
        <input type="hidden" name="task" id="task" value="feeData">
        <input type="hidden" name="trialID" id="trialID" value="{{$trial->id}}">
        <div id="Fees" class="pt-0 tabcontent">
            <div class="space-y-12">
                <div class="px-4 py-4 mt-0 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                        <div id="adultEntryFeeDiv" class="col-span-3">
                            <x-form-field>
                                <x-form-label for="adultEntryFee">Adult entry fee</x-form-label>
                                <div class="mt-2 col-span-3">
                                    <x-form-input name="adultEntryFee" type="text" id="adultEntryFee" required
                                                  placeholder="Omit £ signs eg. 25.00" />
                                    <x-form-error name="adultEntryFee"/>
                                </div>
                                @error('Adult entry fee')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>

                        <div id="youthEntryFeeDiv" class="col-span-3">
                            <x-form-field>
                                <x-form-label for="youthEntryFee">Youth entry fee</x-form-label>
                                <div class="mt-2 col-span-3">
                                    <x-form-input name="youthEntryFee" type="text" id="youthEntryFee" required
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
                                <div class="mt-2 col-span-3">
                                    <input name="hasEodSurcharge" type="checkbox" value="1" id="hasEodSurcharge"  />
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
                                                  placeholder="Omit £ signs eg. 5.00" />
                                    <x-form-error name="eodSurcharge"/>
                                </div>
                                @error('eodSurcharge')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>

                        <div class="text-gray-500 font-semibold col-span-full">Additional items for purchase such as T-shirts, Pub Supper or Camping can be added. Please contact TrialMonster - admin@trialmonster.uk - with details.</div>

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