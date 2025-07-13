<x-club>
    <x-slot:heading>Compose email</x-slot:heading>
    @php
        $mailCategoryArray = array("Trial Announcement", "Result Published", "General Announcement", 'Other');
        $distributionArray = array("Trial Entrants", "Past Entrants", "All Users");
    @endphp
    <form action="/mail/store" method="POST">
        @csrf
        <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Clubs</div>
            <div class="grid grid-cols-2 gap-4 px-4">
                <input type="hidden" name="id" value="">


                <div id="distributionDiv" class="col-span-3">
                    <x-form-field>
                        <x-form-label class="pr-0" for="distribution">Distribution List</x-form-label>
                        <div class="mt-2 pl-2 pr-0">
                            @foreach($distributionArray as $distribution)
                                <div>
                                    <input name="distribution[]" type="checkbox" id="distribution[]"
                                           value="{{$distribution}}"
                                            {{ (is_array(old('distribution')) and in_array($distribution, old('distribution'))) ? ' checked' : '' }}
                                    />
                                    <label class="pl-4 pr-0" for="distribution">{{$distribution}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('distribution')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </x-form-field>
                </div>

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
            <a href="/clubaccess"
               class=" rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-violet-900 shadow-sm hover:bg-violet-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-900">Cancel</a>
            <button type="submit"
                    class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                Save
            </button>
        </div>
    </form>

</x-club>