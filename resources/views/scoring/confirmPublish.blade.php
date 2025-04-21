<x-club>
    <x-slot:heading>Confirm Publishing</x-slot:heading>

    <form method="post" action="/scores/publish">
        @csrf
        <input type="hidden" id="trialID" name="trialID" value="{{$trialID}}">
        <a href="/scores/grid/{{$trialID}}"
           class="rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-violet-900 shadow-sm hover:bg-violet-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-900">Cancel</a>
        <button type="submit"
                class="rounded-md ml-2 bg-red-600 px-3 py-1 text-sm font-light  border border-red-800 text-white drop-shadow-lg hover:bg-red-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
            Publish
        </button>
        <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="p-2">
    <div class="text-red-600 font-semibold mb-2">Warning -- once you hit the Publish button, you will not be able to go back to the main scoring grid.</div>
    <div>Please confirm that you have completed data entry. Have you…</div>
    <div><ul class="list-disc list-inside m-2"><li>added all entry details? Late entries? Course changes?</li>
            <li>scores for all sections?</li>
            <li>checked for missing / stray scores?</li>
            <li>entered 'o' for omitted courses/sections?</li>
        </ul></div>
    <div>&nbsp;</div>
    <div>You will still be able to change details of a rider's entry and section scores</div>
        </div>
        </div>
</x-club>