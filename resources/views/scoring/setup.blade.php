<x-club>
    <x-slot:heading>Scoring setup</x-slot:heading>
        <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Confirm settings below then click Setup</div>
            <div class="pl-4 pr-4 pt-2 py-2">
<form action="/scores/setup/" method="post">
    <input type="hidden" name="trialID" id="trialID" value="{{$trial->id}}">
    @csrf
            <x-form-field>
                <x-form-label for="numLaps" class="pt-2 text-violet-700">Number of Sections</x-form-label>
                <div class="mt-2 col-span-2">
                    <x-form-input name="numSections" type="text" id="numSections"
                                  value="{{$trial->numSections}}"
                                  placeholder="Number of Sections" required/>
                    <x-form-error name="numSections"/>
                </div>
                @error('numSections')
                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                @enderror
            </x-form-field>



            <x-form-field>
                <x-form-label for="numLaps"  class="pt-2 text-violet-700">Number of Laps</x-form-label>
                <div class="mt-2 col-span-2">
                    <x-form-input name="numLaps" type="text" id="numLaps"
                                  value="{{$trial->numLaps}}"
                                  placeholder="Number of Laps" required/>
                    <x-form-error name="numLaps"/>
                </div>
                @error('numLaps')
                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                @enderror
            </x-form-field>

            <x-form-field>
                <x-form-label for="numColumns"  class="pt-2 text-violet-700">Number of Scorecard Columns</x-form-label>
                <div class="mt-2 col-span-2">
                    <x-form-input name="numColumns" type="text" id="numColumns"
                                  value="{{$trial->numColumns}}"
                                  placeholder="Number of columns on scorecard" required/>
                    <x-form-error name="numColumns"/>
                </div>
                @error('numColumns')
                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                @enderror
            </x-form-field>

            <x-form-field>
                <x-form-label for="numRows" class="pt-2 text-violet-700">Number of Scorecard Rows</x-form-label>
                <div class="mt-2 col-span-2">
                    <x-form-input name="numRows" type="text" id="numRows"
                                  value="{{$trial->numRows}}"
                                  placeholder="Number of rows on scorecard" required/>
                    <x-form-error name="numRows"/>
                </div>
                @error('numRows')
                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                @enderror
            </x-form-field>

    <div class="ml-0 mt-4" id="buttons">
        <a href="/adminTrials"
           class="rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-violet-900 shadow-sm hover:bg-violet-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-900">Cancel</a>

        <button type="submit"
                class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
            Setup
        </button>
    </div>


</form>
        </div>
    </div>
    </div>

</x-club>