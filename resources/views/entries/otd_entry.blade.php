<x-otd>
    <script>
        function toggle(checked) {
            var x = document.getElementById("dateInput");
            if (checked) {
                x.style.display = "inline-block";
            } else {
                x.style.display = "none";
            }
        }


        document.addEventListener("DOMContentLoaded", function(event) {
            // Your code to run since DOM is loaded and ready
            const input = document.querySelector('input[name="name"]');

            input.addEventListener('invalid', function (event) {
                if (event.target.validity.patternMismatch) {
                    event.target.setCustomValidity('Please enter your firstname and surname.');
                }
            })
            input.addEventListener('change', function (event) {
                event.target.setCustomValidity('');
            })
        });
    </script>
    @php
        $trial_id = $trial->id;

        $trial_date = date_create($trial->date);

        $offset = DateInterval::createFromDateString('4 years');
        $maxDob = $trial_date->sub($offset)->format("Y-m-d");

    $allCourses = array();
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


            $authority = $trial->authority;
            $types = array("2 stroke", "4 stroke", "e-bike");
    $entryIDs = array();

            $trial_date = date_create($trial->date);
        $offset = DateInterval::createFromDateString('4 years');
        $maxDob = $trial_date->sub($offset)->format("Y-m-d");

//    $userID = Auth::user()->id;
    //dump($entries);
    @endphp
    <x-slot:heading>
        Registration for {{$trial->name}}
    </x-slot:heading>

{{--    @error('')--}}
{{--    {{ $message }}--}}
{{--    @enderror--}}


    <form action="/otdCreate" method="POST">
        @csrf
        <input type="hidden" id="trial_id" name="trial_id" value="{{$trial_id}}">
        <input type="hidden" id="created_by" name="created_by" value="0">
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <div class=" mt-6 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                    <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Add an
                        Entry - {{$trial->name}}
                    </div>

                    <div class="mt-2 px-2 py-2 pb-4 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                        <x-form-field>
                            <x-form-label for="name">Name</x-form-label>
                            <div class="mt-2 ">
                                <x-form-input class="" name="name" type="text" id="name" :value="old('name')"
                                              pattern="^([a-zA-Z\-]{2,}\s[a-zA-Z]{1,}'?-?[a-zA-Z]{1,}\s?([a-zA-Z]{1,})?)"
                                              placeholder="Rider's name" required/>
                                <x-form-error name="name"/>
                            </div>
                            @error('name')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </x-form-field>

                        <x-form-field>
                            <x-form-label for="licence">{{$authority}} Licence</x-form-label>
                            <div class="mt-2 col-span-2">
                                <x-form-input name="licence" type="text" id="licence" :value="old('licence')"
                                              placeholder="Licence number - leave blank if no licence"/>
                                <x-form-error name="licence"/>
                            </div>
                        </x-form-field>

                        <div id="dateInput" class=" col-span-full">
                            <x-form-field>
                                <x-form-label for="dob">Date of Birth</x-form-label>
                                <div class="mt-2  max-w-40 col-span-full">
                                    <x-form-input type="date" max="{{$maxDob}}"  required name="dob" id="dob" :value="old('dob')"/>
                                </div>
                                @error('dob')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>

                        <x-form-field>
                            <x-form-label for="make">Make</x-form-label>
                            <div class="mt-2 col-span-2">
                                <x-form-input name="make" type="text" id="make" :value="old('make')"
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
                                <x-form-input name="size" type="text" id="size" :value="old('size')"
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
                                            <option value="{{$type}}">{{$type}}</option>
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
                                            <option value="{{trim($course)}}">{{trim($course)}}</option>
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
                                            <option value="{{$class}}">{{$class}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </x-form-field>
                    </div>
                </div>


                <div class="mt-4" id="buttons">
                    <a href="/"
                       class="rounded-md bg-white px-3 py-2 text-sm  text-blue-600 shadow-sm hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>
                    <button type="submit"
                            class="rounded-md ml-2 bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                        Register
                    </button>
                </div>
            </div>
        </div>
    </form>
</x-otd>