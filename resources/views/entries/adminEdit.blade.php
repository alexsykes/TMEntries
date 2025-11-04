<x-club>
    <x-slot:heading>
        Edit entryID: {{$entry->id}}
    </x-slot:heading>
    @php
    $statusOptions = array(    'Unconfirmed', 'Confirmed', 'Withdrawn - paid awaiting refund', 'Refunded', 'Accepted - awaiting payment', 'Reserve', 'Removed', 'Manual entry - to pay', 'Manual entry - paid', 'Manual entry - FoC');

//    $classes = explode(',',$trial->classlist);
//    $courses = explode(',',$trial->courselist);

    $allCourses = array();
    $numSections = $trial->numSections;
    $courses = $trial->courselist;
    $customCourses = $trial->customCourses;

    $allClasses = array();
    $classes = $trial->classlist;
    $customClasses = $trial->customClasses;

    if($courses !='') {
    array_push($allCourses, $courses);
    }

    if($customCourses !='') {
    array_push($allCourses, $customCourses);
    }

    if($classes !='') {
    array_push($allClasses, $classes);
    }

    if($customClasses !='') {
    array_push($allClasses, $customClasses);
    }

    $classlist = str_replace(',',',',implode(',', $allClasses));
    $courselist   = str_replace(',',',',implode(',', $allCourses));
    $courseOptions = explode(',', $courselist);
    $classOptions = explode(',', $classlist);


    $types = array("2 stroke", "4 stroke", "e-bike");
    @endphp
    @if ($errors->any())

        <div class="alert alert-danger">

            <ul>

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif
        <form action="/admin/entries/update" method="POST">
            <input type="hidden" name="entryID" id="entryID" value="{{$entry->id}}"/>
            <input type="hidden" name="trialID" id="trialID" value="{{$trial->id}}"/>
            @csrf
            @method('PATCH')
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="px-4 py-4 mt-6 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">

                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">


                            <x-form-field>
                                <x-form-label for="name">Name</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="name" type="text" id="name" value="{{$entry->name}}"
                                                  placeholder="Rider's name" required/>
                                    <x-form-error name="name"/>
                                </div>
                                @error('name')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>


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
                                            @foreach($courseOptions as $course)
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
                                            @foreach($classOptions as $class)
                                                <option value="{{$class}}" {{$class==$entry->class ? "selected" : ""}}>{{$class}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </x-form-field>
                            <x-form-field>
                                <x-form-label class="pb-2" for="class">Status</x-form-label>

                                <div class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 drop-shadow-lg outline-blue-700 ">
                                    <div class="pb-2 pt-2 bg-white sm:col-span-2">
                                        <select class="ml-2  bg-white  space-x-4 border-none" name="status" id="status"
                                                required>
                                            <option value="">Status</option>
                                            @foreach($statusOptions as $key => $status)
                                                <option value="{{$key}}" {{$key==$entry->status ? "selected" : ""}}>{{$status}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </x-form-field>

                            <x-form-field>
                                <x-form-label for="startsAt">Section</x-form-label>
                                <div class="mt-2">
                                    <x-form-input name="startsAt" min="1" max="{{$numSections}}" type="number" id="startsAt" value="{{$entry->startsAt}}"
                                                  placeholder="Section"/>
                                    <x-form-error name="startsAt"/>
                                </div>
                            </x-form-field>

                        </div>
                    </div>


                    <div class="mt-4" id="buttons">
                        <a href="/trials/adminEntryList/{{$trial->id}}"
                           class="rounded-md bg-white px-3 py-2 text-sm font-light  text-blue-600 border border-blue-800 drop-shadow-xl hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>

                        <button type="submit"
                                class="rounded-md ml-2 bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </form>


</x-club>