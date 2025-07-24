<x-club>
    <script type="text/javascript">
        function yesNoCheck() {
            // var test = this.valueOf();
            selected = event.currentTarget.value;
            if (selected == "Test") {
                document.getElementById('testAddressDiv').style.display = 'block';
            } else document.getElementById('testAddressDiv').style.display = 'none';

            if (selected == "Trial Entrants") {
                document.getElementById('trialSelectDiv').style.display = 'block';
            } else document.getElementById('trialSelectDiv').style.display = 'none';
        }
    </script>
    @php
        $distributionArray = array("Test", "Trial Entrants", "Past Entrants");

//        dump($clubTrials);

//        dd($mail);
    @endphp
    <x-slot:heading>Send mail</x-slot:heading>
    <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="flex justify-between font-bold w-full mt-4 pt-2 pb-2 pl-2 pr-4 rounded-t-xl  text-white bg-violet-600">
            Preview - {{$mail->summary}}
        </div>
        <div id="mailPreview" class="text-sm m-4">
            <div id="subject"><span class="font-semibold">Subject: </span>{{$mail->subject}}
            </div>
            <div id="bodyText" class="mt-4">
                @php
                    echo $mail->bodyText;
                @endphp
            </div>
        </div>
    </div>

    <form action="/usermail/prepare" method="POST">
        @csrf
        <input type="hidden" value="{{$mail->id}}" name="mail_id" id="mail_id">
        <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="flex justify-between font-bold w-full mt-4 pt-2 pb-2 pl-2 pr-4 rounded-t-xl  text-white bg-violet-600">
                Mailshot
            </div>
            <div id="mailshot" class="text-sm m-4">
                <div id="distributionDiv" class=" col-span-3 mt-2">
                    <x-form-field>
                        <x-form-label for="distribution">Distribution</x-form-label>
                        <div class="mt-2 col-span-2">
                            @foreach($distributionArray as $option)
                                <input name="distribution" type="radio" id="distribution"
                                       onclick="Javascript:yesNoCheck()"
                                       value="{{$option}}">
                                <label class="pl-1 pr-4" for="distribution">{{$option}}</label>
                            @endforeach
                        </div>
                        @error('distribution')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </x-form-field>
                </div>
                <div id="testAddressDiv" class=" col-span-3 mt-2">
                    <x-form-field>
                        <x-form-label for="testAddress">Test address</x-form-label>
                        <div class="mt-2 col-span-2">
                            <x-form-input name="testAddress" type="email" id="testAddress" value=""
                                          placeholder="test@example.com"/>
                            <x-form-error name="testAddress"/>
                        </div>
                        @error('testAddress')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </x-form-field>
                </div>

                <div id="trialSelectDiv" class="hidden col-span-3 mt-2">
                <x-form-field>
                    <x-form-label class="pb-2" for="course">Trial</x-form-label>
                    <div class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 drop-shadow-lg outline-blue-700 ">
                        <div class="pb-2 pt-2    sm:col-span-2">
                            <select class="ml-2 bg-white  space-x-4 border-none" name="trial_id" id="trial_id">
                                @foreach($clubTrials as $trial)
                                    <option value="{{$trial->id}}" >{{$trial->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </x-form-field>
                </div>
            </div>
        </div>


        <div class="mt-4" id="buttons">
            <a href="/club/mails"
               class=" rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-violet-900 shadow-sm hover:bg-violet-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-900">Cancel</a>
            <button type="submit"
                    class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                Prepare
            </button>
        </div>
    </form>

</x-club>