<x-club>
    <x-slot:heading>New Distribution List</x-slot:heading>
    <form action="/club/distribution/store" method="POST">
        @csrf
        <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Details</div>
            <div class="grid grid-cols-2 gap-4 px-4">
                <x-form-field>
                    <x-form-label for="name">Name</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="name" type="text" id="name" value=""
                                      placeholder="Name of list" required/>
                        <x-form-error name="name"/>
                    </div>
                    @error('name')
                    <p class="text-xs text-violet-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="description">Description</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="description" type="text" id="description" value=""
                                      placeholder="Brief description of the list" required/>
                        <x-form-error name="description"/>
                    </div>

                    @error('description')
                    <p class="text-xs text-violet-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="to">Email addresses</x-form-label>
                    <div class="mt-2">
                        <textarea name="to" id="to" rows="5"
                                  class=" block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                  required>
                            {{ old('to') }}
                        </textarea>
                        <x-form-error name="to"/>

                    </div>
                    @error('to')
                    <p class="text-xs text-violet-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <div id="buttons" class="py-2">
                    <a href="/club/profile"
                       class=" rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-violet-900 shadow-sm hover:bg-violet-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-900">Cancel</a>
                    <button type="submit"
                            class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                        Save
                    </button>
                </div>
    </form>

</x-club>
