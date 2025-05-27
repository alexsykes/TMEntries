<x-admin>
    <x-slot:heading>Add a new Club</x-slot:heading>
    <form action="/club/store" method="POST">
        @csrf
    <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-red-600">Clubs</div>
            <div class="grid grid-cols-2 gap-4 px-4">
                <x-form-field>
                    <x-form-label for="name">Club Name</x-form-label>
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
                    <x-form-label for="area">Region</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="area" type="text" id="area" value=""
                                      placeholder="Where are you located?" required/>
                        <x-form-error name="area"/>
                    </div>
                    @error('area')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="phone">Contact number</x-form-label>
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
                    <x-form-label for="email">Email</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="email" type="email" id="email" value=""
                                      placeholder="Contact email address" required/>
                        <x-form-error name="email"/>
                    </div>
                    @error('email')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="website">Website</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="website" type="text" id="website" value=""
                                      placeholder="Website link" />
                        <x-form-error name="website"/>
                    </div>
                    @error('website')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>



                <x-form-field>
                    <x-form-label for="facebook">Facebook</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="facebook" type="text" id="facebook" value=""
                                      placeholder="Facebook link" />
                        <x-form-error name="facebook"/>
                    </div>
                    @error('facebook')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="section_markers">Section markers</x-form-label>
                    <div class="mt-2">
                        <textarea name ="section_markers" id="section_markers" rows="5"  class=" block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"  required >{{ old('section_markers') }}</textarea>
                        <x-form-error name="section_markers" />
                    </div>
                </x-form-field>

                <x-form-field>
                    <x-form-label for="description">Notes</x-form-label>
                    <div class="mt-2 ">
                        <textarea class="withEditor" name="description" type="text" id="description" ></textarea>
                    </div>
                    @error('description')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

            </div>
    </div>

            <div id="buttons" class="py-2">
                <a href="/clubs/list"
                   class=" rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-red-900 shadow-sm hover:bg-red-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-900">Cancel</a>
                <button type="submit"
                        class="rounded-md ml-2 bg-red-600 px-3 py-1 text-sm font-light  border border-red-800 text-white drop-shadow-lg hover:bg-red-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                    Save
                </button>
            </div>
        </form>

</x-admin>
