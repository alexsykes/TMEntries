<x-club >
    <x-slot:heading>Updating {{$series->name}}</x-slot:heading>
    @php
        //dd($clubs);
    @endphp
    <form action="/series/update" method="POST">
        @csrf
        @method('PATCH')
        <input type="hidden" name="id" value="{{$series->id}}">
        <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Series</div>
            <div class="grid grid-cols-2 gap-4 px-4">


                <x-form-field>
                    <x-form-label for="name">Series Name</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="name" type="text" id="name" value="{{$series->name}}"
                                      placeholder="Name" required/>
                        <x-form-error name="name"/>
                    </div>
                    @error('name')
                    <p class="text-xs text-violet-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>


                <x-form-field>
                    <x-form-label for="description">Description</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="description" type="text" id="description" value="{{$series->description}}"
                                      required
                                      placeholder="A brief description of the series" />
                        <x-form-error name="description"/>
                    </div>
                    @error('description')
                    <p class="text-xs text-violet-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="classes">Classes</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="classes" type="text" id="classes" value="{{$series->classes}}"
                                      placeholder="List of classes - separated by commas" required/>
                        <x-form-error name="classes"/>
                    </div>
                    @error('classes')
                    <p class="text-xs text-violet-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="courses">Courses</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="courses" type="text" id="courses" value="{{$series->courses}}"
                                      placeholder="List of courses - separated by commas" required/>
                        <x-form-error name="courses"/>
                    </div>
                    @error('courses')
                    <p class="text-xs text-violet-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>



                <x-form-field>
                    <x-form-label for="notes">Notes</x-form-label>
                    <div class="text-sm">Please give full details of the series - machine eligibiity, number of rounds, how scores are awarded etc.</div>
                    <div class="mt-2 ">
                        <textarea class="withEditor" name="notes" type="text" id="notes" >{{$series->notes}}</textarea>
                    </div>
                    @error('notes')
                    <p class="text-xs text-violet-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>
            </div>
        </div>

        <div id="buttons" class="py-2">
            <a href="/series/list"
               class=" rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-violet-900 shadow-sm hover:bg-violet-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-900">Cancel</a>
            <button type="submit"
                    class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                Update
            </button>
        </div>
    </form>

</x-club>
