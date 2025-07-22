<x-club>
    @php
        $addressArray = array("Entry List", "Unconfirmed Entries", "Previous Entrants");
    @endphp
    <x-slot:heading>Add recipients</x-slot:heading>
    <form action="/usermail/storeAddressList" method="POST">
        @csrf
        <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Groups</div>

            <div class="grid grid-cols-2 gap-4 px-4">
                <div id="categoryDiv" class=" col-span-3 mt-2">
                    <x-form-field>
                        <x-form-label for="category">Category</x-form-label>
                        <div class="mt-2 col-span-2">
                            @foreach($addressArray as $option)
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

            </div>
        </div>

    <div id="buttons" class="py-2">
        <a href="/clubaccess"
           class=" rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-violet-900 shadow-sm hover:bg-violet-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-900">Cancel</a>
        <button type="submit"
                class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
            Save Address List
        </button>
    </div>
    </form>
</x-club>