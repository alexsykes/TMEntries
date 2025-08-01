<x-club>

{{--    <script type="text/javascript">--}}

{{--        function yesNoCheck() {--}}
{{--            // var test = this.valueOf();--}}
{{--            selected = event.currentTarget.value;--}}
{{--            if (selected == "Test") {--}}
{{--                document.getElementById('testAddressDiv').style.display = 'block';--}}
{{--            }--}}
{{--            else document.getElementById('testAddressDiv').style.display = 'none';--}}

{{--        }--}}

{{--    </script>--}}

    <x-slot:heading>Compose email</x-slot:heading>
    @php
        $categoryArray = array("Trial Announcement", "Result Published", "General Announcement", 'Other');
//        $addressToArray = array("Test", "Entry List", "Unconfirmed Entries", "Previous Entrants", "Other");
    @endphp
    <form action="/usermail/store" method="POST">
        @csrf
        <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Email content</div>
            <div class="grid grid-cols-2 gap-4 px-4">


{{--                <div id="addressToDiv" class=" col-span-3 mt-2">--}}
{{--                    <x-form-field>--}}
{{--                        <x-form-label for="addressTo">To</x-form-label>--}}
{{--                        <div class="mt-2 col-span-2">--}}
{{--                            @foreach($addressToArray as $option)--}}
{{--                                <input name="addressTo" type="radio" id="addressTo"--}}
{{--                                       onclick="Javascript:yesNoCheck()"--}}
{{--                                       value="{{$option}}"--}}
{{--                                        {{ (old('addressTo') == $option) ? ' checked' : '' }}--}}
{{--                                >--}}
{{--                                <label class="pl-1 pr-4" for="addressTo">{{$option}}</label>--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                        @error('addressTo')--}}
{{--                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>--}}
{{--                        @enderror--}}
{{--                    </x-form-field>--}}
{{--                </div>--}}

{{--                <div id="testAddressDiv" class=" col-span-3 mt-2">--}}
{{--                <x-form-field>--}}
{{--                    <x-form-label for="testAddress">Test address</x-form-label>--}}
{{--                    <div class="mt-2 col-span-2">--}}
{{--                        <x-form-input name="testAddress" type="email" id="testAddress" value=""--}}
{{--                                      placeholder="test@example.com" />--}}
{{--                        <x-form-error name="testAddress"/>--}}
{{--                    </div>--}}
{{--                    @error('testAddress')--}}
{{--                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>--}}
{{--                    @enderror--}}
{{--                </x-form-field>--}}
{{--                </div>--}}


                <div id="categoryDiv" class=" col-span-3 mt-2">
                    <x-form-field>
                        <x-form-label for="category">Category</x-form-label>
                        <div class="mt-2 col-span-2">
                            @foreach($categoryArray as $option)
                                <input name="category" type="radio" id="category"
                                       value="{{$option}}"
                                        {{ (old('category') == $option) ? ' checked' : '' }}
                                >
                                <label class="pl-1 pr-4" for="category">{{$option}}</label>
                            @endforeach
                        </div>
                        @error('category')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </x-form-field>
                </div>

                <x-form-field>
                    <x-form-label for="summary">Summary - brief description of email message</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="summary" type="text" id="summary" value=""
                                      placeholder="eg. Membership reminder" required/>
                        <x-form-error name="summary"/>
                    </div>
                    @error('summary')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="subject">Email Subject line</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="subject" type="text" id="subject" value=""
                                      placeholder="eg. Final Instructions" required/>
                        <x-form-error name="subject"/>
                    </div>
                    @error('subject')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="bodyText">Email body</x-form-label>
                    <div class="mt-2 ">
                        <textarea class="withEditor" name="bodyText" type="text" id="bodyText">

                        </textarea>
                    </div>
                    @error('description')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>
            </div>
        </div>
        <div id="buttons" class="py-2">
            <a href="/club/mails"
               class=" rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-violet-900 shadow-sm hover:bg-violet-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-900">Cancel</a>
            <button type="submit"
                    class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                Save
            </button>
        </div>
    </form>

</x-club>