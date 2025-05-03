<x-admin>
    <x-slot:heading>Add a new venue</x-slot:heading>
    <form action="/venues/add" method="post">
        <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            @csrf
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-red-600">Details</div>
            <div class="grid grid-cols-2 gap-4 px-4">
                <x-form-field>
                    <x-form-label for="name">Venue Name</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="name" type="text" id="name" value=""
                                      placeholder="Name" required/>
                        <x-form-error name="name"/>
                    </div>
                    @error('name')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="landowner">Landowner</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="landowner" type="text" id="landowner" value=""
                                      placeholder="Landowner's name" required/>
                        <x-form-error name="landowner"/>
                    </div>
                    @error('landowner')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="address">Address</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="address" type="text" id="address" value=""
                                      placeholder="Address" required/>
                        <x-form-error name="address"/>
                    </div>
                    @error('landowner')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="postcode">Postcode</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="postcode" type="text" id="postcode" value=""
                                      placeholder="Postcode" required/>
                        <x-form-error name="postcode"/>
                    </div>
                    @error('postcode')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="w3w">What 3 Words</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="w3w" type="text" id="w3w" value=""
                                      placeholder="What 3 Words code" />
                        <x-form-error name="w3w"/>
                    </div>
                    @error('w3w')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="phone">Contact Phone</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="phone" type="text" id="phone" value=""
                                      placeholder="Contact number" required/>
                        <x-form-error name="phone"/>
                    </div>
                    @error('phone')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="club">Club</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="club" type="text" id="club" value=""
                                      placeholder="Club (if applicable)" />
                        <x-form-error name="club"/>
                    </div>
                    @error('club')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="centre">Centre</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="centre" type="text" id="centre" value=""
                                      placeholder="Centre (if applicable)" />
                        <x-form-error name="centre"/>
                    </div>
                    @error('centre')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="latitude">Latitude</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="latitude" type="text" id="latitude" value=""
                                      placeholder="eg. 12.345678" required/>
                        <x-form-error name="latitude"/>
                    </div>
                    @error('latitude')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="longitude">Longitude</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="longitude" type="text" id="longitude" value=""
                                      placeholder="eg. 1.234567" required/>
                        <x-form-error name="longitude"/>
                    </div>
                    @error('longitude')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="directions">Directions</x-form-label>
                    <div class="mt-2 ">
                        <textarea name="directions" type="text" id="notes" ></textarea>
                    </div>
                    @error('directions')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="notes">Notes</x-form-label>
                    <div class="mt-2 ">
                        <textarea name="notes" type="text" id="notes" ></textarea>
                    </div>
                    @error('notes')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>
            </div>
        </div>

        <div id="buttons" class="py-2">
            <a href="/admin/venues"
               class="rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-red-900 shadow-sm hover:bg-red-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-900">Cancel</a>
            <button type="submit"
                    class="rounded-md ml-2 bg-red-600 px-3 py-1 text-sm font-light  border border-red-800 text-white drop-shadow-lg hover:bg-red-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                Save
            </button>
        </div>
    </form>
</x-admin>