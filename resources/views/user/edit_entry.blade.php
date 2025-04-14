<x-main>
    @php
        $statusOptions = array(    'Unconfirmed - will be confirmed when payment is completed', 'Confirmed', 'Withdrawn - paid awaiting refund', 'Refunded', 'Accepted - awaiting payment', 'Reserve', 'Removed', 'Manual entry - to pay', 'Manual entry - paid', 'Manual entry - FoC');

        $classes = explode(',',$entry->classlist);
        $courses = explode(',',$entry->courselist);
        $types = array("2 stroke", "4 stroke", "e-bike");
  @endphp
    <x-slot:heading>{{$entry->club}} {{$entry->trial_name}}</x-slot:heading>

    <div class="mb-2 ml-4 col-span-4">Entry status: {{$statusOptions[$entry->status]}}</div>
    <form action="user/entry/save" type="POST">
        @csrf
        <input type="hidden" name="entryID" id="entryID" value="{{$entry->id}}">
        <div class=" mt-0 mb-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Editing entry for {{$entry->name}}</div>


            @method('PATCH')


                        <div class="mt-6    mb-2 ml-4 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                            <x-form-field>
                                <x-form-label for="make">Make</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="make" type="text" id="make" value="{{$entry->make}}"
                                                  placeholder="Bike make/model" required/>
                                    <x-form-error name="make"/>
                                </div>
                                @error('make')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="size">Capacity</x-form-label>
                                <div class="mt-2">
                                    <x-form-input name="size" type="text" id="size" value="{{$entry->size}}"
                                                  placeholder="Bike engine size - leave empty for e-Bike"/>
                                    <x-form-error name="size"/>
                                </div>
                            </x-form-field>

                            <x-form-field>
                                <x-form-label class="pb-2" for="type">Type</x-form-label>

                                <div class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 drop-shadow-lg outline-blue-700 ">
                                    <div class="pb-2 pt-2    sm:col-span-2">
                                        <select class="ml-2 bg-white  space-x-4 border-none" name="type" id="type" required>
                                            <option value="">Select your engine type</option>
                                            @foreach($types as $type)
                                                <option value="{{$type}}" {{$type==$entry->type ? "selected" : ""}}>{{$type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </x-form-field>

                            <x-form-field>

                                <x-form-label class="pb-2" for="course">Course</x-form-label>
                                <div class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 drop-shadow-lg outline-blue-700 ">
                                    <div class="pb-2 pt-2    sm:col-span-2">
                                        <select class="ml-2 bg-white  space-x-4 border-none" name="course" id="course"
                                                required>
                                            <option value="">Select your course</option>
                                            @foreach($courses as $course)
                                                <option value="{{$course}}" {{$course==$entry->course ? "selected" : ""}}>{{$course}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </x-form-field>

                            <x-form-field>
                                <x-form-label class="pb-2" for="class">Class</x-form-label>

                                <div class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 drop-shadow-lg outline-blue-700 ">
                                    <div class="pb-2 pt-2 bg-white sm:col-span-2">
                                        <select class="ml-2  bg-white  space-x-4 border-none" name="class" id="class"
                                                required>
                                            <option value="">Select your class</option>
                                            @foreach($classes as $class)
                                                <option value="{{$class}}" {{$class==$entry->class ? "selected" : ""}}>{{$class}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </x-form-field>

                        </div>
                    </div>


                    <div class="mt-4" id="buttons">
                        <a href="/entries/userdata"
                           class="rounded-md bg-white px-3 py-2 text-sm  text-blue-600 shadow-sm hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>

                        <button type="submit"
                                class="rounded-md ml-2 bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            Update
                        </button>
                    </div>

    </form>
</x-main>