<x-admin>
    <x-slot:heading>Compose a new email</x-slot:heading>
    @php
        $mailCategoryArray = array("Trial Announcement", "Result Published", "General Announcement", 'Other');
        $distributionArray = array("Trial Entrants", "Past Entrants", "All Users");
    @endphp
    <form action="/mail/store" method="POST">
        @csrf
        <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-red-600">Email details</div>
            <div class="grid grid-cols-2 gap-4 px-4">
                <input type="hidden" name="isLibrary" value="true">

                <div id="mailCategoryDiv" class="col-span-3">
                    <x-form-field>
                        <x-form-label class="pr-0" for="mailCategory">Category</x-form-label>
                        <div class="mt-2 pl-2 pr-0">
                            @foreach($mailCategoryArray as $mailCategory)
                                <div>
                                    <input name="mailCategory[]" type="radio" id="mailCategory"
                                           value="{{$mailCategory}}"
                                            {{ (old('mailCategory')) ? ' checked' : '' }}
                                    />
                                    <label class="pl-4 pr-0" for="mailCategory">{{$mailCategory}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('mailCategory')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </x-form-field>
                </div>

                <x-form-field>
                    <x-form-label for="subject">Subject</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="subject" type="text" id="subject" value=""
                                      placeholder="Subject line - eg. Final Instructions" required/>
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
            <a href="/adminaccess"
               class=" rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-red-900 shadow-sm hover:bg-red-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-900">Cancel</a>
            <button type="submit"
                    class="rounded-md ml-2 bg-red-600 px-3 py-1 text-sm font-light  border border-red-800 text-white drop-shadow-lg hover:bg-red-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                Save
            </button>
        </div>
    </form>

</x-admin>