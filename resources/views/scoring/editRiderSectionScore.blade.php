<x-club>
    <x-slot:heading>Rider {{$rider}}</x-slot:heading>

    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">
            Scores for Section {{$section}}</div>
    <form action="/scores/updateSectionScoreForRider" method="post">
        <input type="hidden" id="trialID" name="trialID" value="{{$trialID}}">
        <input type="hidden" id="scoreIDs" name="scoreIDs" value="{{$scores[0]->ids}}">
        @csrf

        <div id="entryLinkDiv" class="mt-2 ml-4 col-span-3">
            <x-form-field>
                <x-form-label for="scores">Scores</x-form-label>
                <div class="mt-2 col-span-2">
                    <x-form-input name="scores" type="text" id="scores"
                                  class="w-3"
                                  value="{{$scores[0]->scores}}"
                                  placeholder="Scores" />
                    <x-form-error name="scores"/>
                </div>
                @error('scores')
                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                @enderror
            </x-form-field>
        </div>
        <div class=" m-4" id="buttons">
            <a href="/scores/grid/{{$trialID}}"
               class="rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-violet-900 shadow-sm hover:bg-violet-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-900">Cancel</a>
            <button type="submit"
                    class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                Update
            </button>
        </div>

    </form>
    </div>
</x-club>