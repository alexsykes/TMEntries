<x-main>
    <?php
    $trial = $entry->trial;
    $id = $entry->id;
//    $classlist = $trial->classlist;
//    $courselist = $trial->courselist;
//    $classes = explode(',',$classlist);
//    $courses = explode(',',$courselist);

    $allCourses = array();
    $courses = $trial->courselist;
    $customCourses = $trial->customCourses;

    $allClasses = array();
    $classes = $trial->classlist;
    $customClasses = $trial->customClasses;


//    dump($courses, $customCourses, $classes, $customCourses);
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

//    dd($allCourses, $allClasses);
    $classlist = str_replace(',',',',implode(',', $allClasses));
    $courselist   = str_replace(',',',',implode(',', $allCourses));
    $courseOptions = explode(',', $courselist);
    $classOptions = explode(',', $classlist);

    $types = array("2 stroke", "4 stroke", "e-bike");

    ?>
    <x-slot:heading>
{{--        Entry for {{$entry->name}} at {{$entry->trial->name}}--}}
    </x-slot:heading>

        <form action="/entries/userupdate" method="POST">
            <input type="hidden" name="id" id="id" value="{{$id}}"/>
            @csrf
            @method('PATCH')
            <div class="space-y-4">
                    <div class="px-4 py-4 mt-6 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                        <div class="text-blue-700 text-m font-bold">Entry update - {{$entry->name}} </div>
                        <div class="text-sm p-2">You may make changes to the entry details below. Should you wish to withdraw your entry, a refund will be processed and your entry fee will be refunded to your account. All refunds are subject to our standard administration charge of £3</div>
                        <div class="font-semibold">You will receive an email confirmation of any changes you make.</div>
                        <div class="mt-2 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

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
                    </div>


                    <div class="flex pl-0 pr-0 justify-between" id="buttons">
                        <div class="">
                        <a href="/"
                           class="rounded-md bg-white px-3 py-2 text-sm font-light  text-blue-600 border border-blue-800 drop-shadow-xl hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>

                        <button type="submit"
                                class="rounded-md ml-4 bg-blue-600 px-3 py-2 text-sm font-light  border border-white text-white drop-shadow-xl hover:bg-blue-500 focus-visible:outline focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            Update
                        </button>
                        </div>
                        <div>
                        <a href="/entry/withdrawConfirm/{{$entry->id}}"
                                class="rounded-md  bg-red-600 px-3 py-2 text-sm font-light  border border-white text-white drop-shadow-xl hover:bg-red-500 focus-visible:outline focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-red-600">
                            Withdraw
                        </a>
                        </div>
                    </div>
                </div>
        </form>

</x-main>