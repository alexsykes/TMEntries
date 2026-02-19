<x-main>
    <x-slot:heading>
        Editing entry id: {{$entry->id}}
    </x-slot:heading>

    @php

        //        dd($entry);
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
                //    Check for extras
                        $hasMembership = is_null($membership) ? false : true;

                        $numOptions = sizeof($options);
                        $hasOptions = false;
                        if($numOptions > 0) {
                            $hasOptions = true;
                        }

                $extraArray  = json_decode($entry->extras);
                        $priceArray = array();
                        $qtyArray = array();
                        if(!is_null($extraArray)) {
                            $priceArray = array_column($extraArray, 'priceID');
                            $qtyArray = array_column($extraArray, 'qty');
                        }

                $classlist = str_replace(',',',',implode(',', $allClasses));
                $courselist   = str_replace(',',',',implode(',', $allCourses));
                $courseOptions = explode(',', $courselist);
                $classOptions = explode(',', $classlist);
                        $id = $entry->id;
                        $selected_licence = $entry->licence;
                        $selected_isYouth = $entry->isYouth;
                        if($selected_isYouth == '1') { $isYouthCB = " checked "; } else { $isYouthCB = ""; }
                        $selected_name = $entry->name;
                        $selected_make = $entry->make;
                        $selected_type = $entry->type;
                        $selected_size = $entry->size;
                        $selected_dob = $entry->dob;
                        $selected_class = $entry->class;
                        $selected_course = $entry->course;
                        $authority = $trial->authority;

                        $types = array("2 stroke", "4 stroke", "e-bike");

                        $trial_date = date_create($trial->date);
                        $offset = DateInterval::createFromDateString('4 years');
                        $maxDob = $trial_date->sub($offset)->format("Y-m-d");

                        //    Check for extras
                        $hasExtras = is_null($membership) ? false : true;

    @endphp

    <script>
        function toggle(checked) {
            var x = document.getElementById("dateInput");
            if (checked) {
                x.style.display = "inline-block";
            } else {
                x.style.display = "none";
            }
        }

        document.addEventListener("DOMContentLoaded", function (event) {
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
    <form action="/entries/update/{{$id}}" method="POST">
        <input type="hidden" name="id" id="id" value="{{$id}}"/>
        @csrf
        @method('PATCH')
        <div class="px-4 py-2 mt-2 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">

            <div class="mt-2 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                <x-form-field>
                    <x-form-label for="name">Name</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="name" type="text" id="name" value="{{$selected_name}}"
                                      pattern="^([a-zA-Z\-]{2,}\s[a-zA-Z]{1,}'?-?[a-zA-Z]{1,}\s?([a-zA-Z]{1,})?)"
                                      placeholder="Rider's name" required/>
                        <x-form-error name="name"/>
                    </div>
                    @error('name')

                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>


                <x-form-field>
                    <x-form-label for="licence">{{$trial->authority}} Licence</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="licence" type="text" id="licence" value="{{$selected_licence}}"
                                      placeholder="Licence number - leave blank if no licence"/>
                        <x-form-error name="licence"/>
                    </div>
                </x-form-field>

                <x-form-field>
                    <x-form-label for="isYouth">Under-18</x-form-label>
                    <div class="ml-2 mt-2 col-span-full">
                        <input type="checkbox" name="isYouth" id="isYouth" value="1"
                               {{$isYouthCB}} class="isYouth"/>
                        <x-form-error name="isYouth"/>
                    </div>
                </x-form-field>

                <div id="dateInput" class=" col-span-full">
                    <x-form-field>
                        <x-form-label for="dob">Date of Birth</x-form-label>
                        <div class="mt-2  max-w-40 col-span-full">
                            <x-form-input type="date" name="dob" id="dob" max={{$maxDob}} value="{{$selected_dob}}"
                                          required/>
                        </div>
                        @error('dob')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </x-form-field>
                </div>

                <x-form-field>
                    <x-form-label for="make">Make</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="make" type="text" id="make" value="{{$selected_make}}"
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
                        <x-form-input name="size" type="text" id="size" value="{{$selected_size}}"
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
                                    <option value="{{$type}}" {{$type===$selected_type ? " selected " : ""}}>{{$type}}</option>
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
                                    <option value="{{$course}}" {{$course==$selected_course ? " selected " : ""}}>{{$course}}</option>
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
                                    <option value="{{$class}}" {{$class==$selected_class ? " selected " : ""}}>{{$class}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </x-form-field>
            </div>

            @if($hasMembership)
                <div class=" mt-6 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                    <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Add
                        Membership
                    </div>
                    <div class=" px-2 py-2 pb-4 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                        @php

                            $membershipFee = $membership->price / 100;

                            $priceID = $membership->stripe_price_id;
                            if(in_array($priceID, $priceArray)) {
                                $checked = " checked ";
                            } else {
                                $checked = "";
                            }

                        @endphp
                        <x-form-field>
                            <div class="flex col-span-3 justify-normal">
                                <div class="font-semibold text-blue-700">{{$membership->name}}
                                    (£{{ $membershipFee  }})
                                </div>

                                <div class="pl-2">
                                    <input name="checkbox[]" class="p-2" type="checkbox"
                                           {{$checked}}
                                           value="{{$priceID}}"
                                    />
                                </div>
                            </div>
                        </x-form-field>
                    </div>
                </div>
            @endif


            @if($hasOptions)
                <div class=" mt-6 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                    <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Add
                        Merchandise
                    </div>
                    <div class=" px-2 py-2 pb-4 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                        @foreach($options as $option)
                            @php
                                $price = $option->price / 100;
                                if($option->hasQuantity == 1) {
                                    $type = "number";
                                } else {
                                    $type = "checkbox";
                                }
                            @endphp
                            @if($type=="checkbox")
                                <x-form-field>
                                    @php
                                        $priceID = $option->stripe_price_id;
                                        if(in_array($priceID, $priceArray)) {
                                            $checked = " checked ";
                                        } else {
                                            $checked = "";
                                        }
                                    @endphp

                                    <div class="flex justify-normal col-span-3">
                                        <div class="font-semibold text text-blue-700">{{$option->name}}
                                            (£{{ $price  }})
                                        </div>

                                        <div class="pl-2">
                                            <input name="checkbox[]" type="checkbox"
                                                   value="{{$option->stripe_price_id}}"
                                                    {{ $checked }}
                                            />
                                        </div>
                                    </div>
                                </x-form-field>

                            @elseif($type="number")
                                <x-form-field>                                    @php
                                        $priceID = $option->stripe_price_id;
                                        if(in_array($priceID, $priceArray)) {
                                            $index = array_search($priceID, $priceArray);
                                            $quantity = $qtyArray[$index];
                                        } else {
                                            $quantity = 0;
                                        }
                                    @endphp
                                    <div class="flex col-span-3 justify-normal space-x-4">
                                        <div class="pt-2 font-semibold text text-blue-700">{{$option->name}}
                                            (£{{ $price  }})
                                        </div>
                                        <div class="">
                                            <input type="hidden" value="{{$option->stripe_price_id}}" name="priceID[]">
                                            <input type="number" min="0" id="quantity" name="quantity[]"
                                                   class="w-24 sm:w-full"
                                                   value="{{$quantity}}"
                                                   placeholder="Quantity">
                                        </div>
                                    </div>
                                </x-form-field>

                            @endif
                        @endforeach()
                    </div>
                </div>
            @endif
        </div>
        <div class="mt-4" id="buttons">
            <a href="/entries/register/{{$trial->id}}"
               class="rounded-md bg-white px-3 py-2 text-sm  text-blue-600 shadow-sm hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>

            <button type="submit"
                    class="rounded-md ml-2 bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                Save
            </button>
        </div>
    </form>
</x-main>