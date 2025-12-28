<x-club>
    <x-slot:heading>Editing result for EntryID: {{$entry->id}}</x-slot:heading>
    @php
        $numSections = $entry->numSections;
        $numLaps = $entry->numLaps;
        $sectionScores = $entry->sectionScores;
        $types = array("2 stroke", "4 stroke", "e-bike");

        $allCourses = array();
        $courses = $entry->courselist;
        $customCourses = $entry->customCourses;

        $allClasses = array();
        $classes = $entry->classlist;
        $customClasses = $entry->customClasses;

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

        $sectionScores = str_split($sectionScores, $numLaps);
        //        dump($sectionScores);
    @endphp
    <form action="/results/update" method="POST">
        @method('PATCH')
        <input type="hidden" name="id" value="{{$entry->id}}">
        <input type="hidden" name="numLaps" value="{{$entry->numLaps}}">
        @csrf
        <div class="space-y-12">
            <div class="mt-0 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                <div class="flex justify-between  w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl font-semibold  text-white bg-violet-600">
                    <div>Rider {{$entry->ridingNumber}}</div>
                    <div><a href="/entry/changeNumber/{{$entry->id}}">Change Riding Number</a></div>
                </div>
                <div class="grid grid-cols-1 p-4 gap-x-6 gap-y-4 sm:grid-cols-6">
                    <x-form-field>
                        <x-form-label for="name">Name</x-form-label>
                        <div class="mt-2 col-span-2">
                            <x-form-input name="name" type="text" id="name"
                                          placeholder="Rider name" value="{{$entry->name}}" required/>
                            <x-form-error name="name"/>
                        </div>
                        @error('name')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </x-form-field>

                    <x-form-field>
                        <x-form-label for="make">Make</x-form-label>
                        <div class="mt-2 col-span-2">
                            <x-form-input name="make" type="text" id="make"
                                          placeholder="make" value="{{$entry->make}}" required/>
                            <x-form-error name="make"/>
                        </div>
                        @error('make')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </x-form-field>


                    <x-form-field>
                        <x-form-label for="size">Capacity</x-form-label>
                        <div class="mt-2 col-span-2">
                            <x-form-input name="size" type="text" id="size"
                                          placeholder="size" value="{{$entry->size}}" />
                            <x-form-error name="size"/>
                        </div>
                        @error('size')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </x-form-field>

                    <x-form-field>
                        <x-form-label class="pb-2" for="course">Type</x-form-label>

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

                </div>
                <div id="scoregrid" class="grid grid-cols-2 p-4 gap-x-6 gap-y-4 sm:grid-cols-6">
                    @foreach($sectionScores as $sectionScore)
                        <div class="mt-2 col-span-1">
                            <div class="w-20 text-blue-700 font-semibold sm:col-span-1 ">
                                Section {{$loop->iteration}}</div>
                            <x-form-input class="w-20" name="scores[]" type="text" id="scores[]"
                                          placeholder="Section scores" value="{{$sectionScore}}"
                                          pattern="[Xox01235]{0,<?php echo $numLaps; ?>}"
                                          required/>
                            <x-form-error name="scores[]"/>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="flex ml-4 mr-4  mt-4 justify-between" id="buttons">
            <div>
                <a href="/results/display/{{$entry->trial_id}}"
                   class="rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-violet-900 shadow-sm hover:bg-violet-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-900">Cancel</a>

                <button type="submit" name="submitbutton" value="apply"
                        class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                    Update
                </button>
            </div>
        </div>
    </form>
</x-club>