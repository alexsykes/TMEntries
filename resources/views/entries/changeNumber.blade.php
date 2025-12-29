<x-club>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $numLaps = $trial->numLaps;
        $numSections = $trial->numSections;

        $sectionScores = $entry->sectionScores;
        $sectionScoreArray = str_split($sectionScores, $numLaps);
        $sectionScoreString = implode(" | ", $sectionScoreArray);

        $currentScores = "";
        for($i=0 ; $i < $numSections; $i++ ) {
            $section = $i + 1;
            $currentScores .= "Section $section: $sectionScoreArray[$i] ";
        }
    @endphp
    <script>
        function displayScores(resultArray, numLaps, numSections) {
            let container = document.getElementById('container');
            let sectionScoresInput = document.getElementById('sectionScoresInput');
            let sequentialScoresInput = document.getElementById('sequentialScoresInput');
            let numScores = resultArray.length;

            sectionScores = [];
            for (i = 0; i < numSections; i++) {
                sectionScore = "";
                for (ii = 0; ii < numLaps; ii++) {
                    index = ii + (i * numLaps)
                    sectionScore += resultArray[index]['score'];
                }
                sectionScores.push(sectionScore);
            }
            sectionScoresString = sectionScores.join(" | ");
            sectionScoresForForm = sectionScores.join("");

            sequentialScores = [];
            for (lap = 0; lap < numLaps; lap++) {
                lapScore = "";
                for (section = 0; section < numSections; section++) {
                    index = lap + (section * numLaps);
                    lapScore += resultArray[index]['score'];
                }
                sequentialScores.push(lapScore);
            }
            sequentialScoresForForm = sequentialScores.join("");


            console.log(sectionScoresForForm);
            console.log(sequentialScoresForForm);
            sectionScoresInput.value = sectionScoresForForm;
            sequentialScoresInput.value = sequentialScoresForForm;

            container.innerHTML = "<div>Recorded scores for this number are: " + sectionScoresString + "</div>";
        }

        function displayButton() {
            let updateForm = document.getElementById('updateForm');
            updateForm.style.display = "block";
            let ridingNumber = parseInt(document.getElementById('newNumber').value);
            let newNumberInput = document.getElementById('newNumberInput');
            newNumberInput.value = ridingNumber;
        }

        async function getExistingScores(ridingNumber, trialID, numLaps, numSections) {
            let url = '/fetchScores';
            // let redirect = '/sitemap';
            let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch(url, {
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": token
                    },
                    method: 'post',
                    credentials: "same-origin",
                    body: JSON.stringify({
                        trialID: trialID,
                        ridingNumber: ridingNumber
                    })

                });
                if (!response.ok) {
                    throw new Error(`Response status: ${response.status}`);
                }

                const result = await response.json();
                displayScores(result, numLaps, numSections);
                displayButton();
            } catch (error) {
                console.error(error.message);
            }
        }

        function getSectionScores() {
            let ridingNumber = parseInt(document.getElementById('newNumber').value);
            let trialID = parseInt(document.getElementById('trialID').value);

            if (ridingNumber > 0) {
                getExistingScores(ridingNumber, trialID, {{$numLaps}}, {{$numSections}});
            }
        }
    </script>
    <x-slot:heading>
        Editing Riding number for: {{$entry->name}} - currently #{{$entry->ridingNumber}}
    </x-slot:heading>
    <div class="border-b border-gray-900/10 pb-12">
        <div class="mt-6 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
            <div class=" font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Current scores
            </div>
            <div class=" p-4 ">
                <div class="w-full">
                    {{$sectionScoreString}}
                </div>

                <input type="hidden" value="{{$trial->id}}" id="trialID" name="trialID">
                <x-form-field>
                    <x-form-label class="mt-4" for="newNumber">New number</x-form-label>
                    <div class="mt-2">
                        <x-form-input
                                onfocusout="getSectionScores()"
                                name="newNumber"
                                id="newNumber"
                                value="{{old('newNumber')}}"
                                placeholder="Enter new riding number"/>
                        <x-form-error name="newNumber"/>
                    </div>
                </x-form-field>

            </div>
            <div class=" p-2 pl-4 ">
                <div id="container" class="w-full">
                </div>
            </div>
            <div id="updateForm" class="hidden pb-4">
                <form action="/riderNumber/update" method="post">
                    @method('PATCH')
                    @csrf
                    <input type="hidden" name="entryID" value="{{$entry->id}}">
                    <input type="hidden" name="newNumber" id="newNumberInput" value="">
                    <input type="hidden" name="sectionScores" id="sectionScoresInput" value="">
                    <input type="hidden" name="sequentialScores" id="sequentialScoresInput" value="">

                    <div class="ml-4" id="buttons">
                        <a href="/result/edit/{{$entry->id}}"
                           class="rounded-md bg-violet-100 px-3 py-2 text-sm  text-violet-600 shadow-sm hover:bg-violet-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-900">Cancel</a>
                        <button type="submit"
                                class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                            Change Number
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-club>