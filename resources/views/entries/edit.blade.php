<x-main>
    <x-slot:heading>
        Editing entry id: {{$entry->id}}
    </x-slot:heading>
    {{--@dd($options, $merchandise)--}}
    @php

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
        $hasMerchandise = is_null($merchandise) ? false : true;

        $numOptions = sizeof($options);
        $hasOptions = false;
        if($numOptions > 0) {
            $hasOptions = true;
        }

        $extraArray  = json_decode($entry->extras);

             $priceArray = array();
             $qtyArray = array();
             if(!is_null($extraArray)) {
         $priceIDArray = array_column($extraArray, 'priceID');
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
        $status = $entry->status;

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
    {{--    <form action="/entries/update/{{$id}}" method="POST">--}}
    <form action="/entries/update" method="POST">
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

            @if($hasMembership && $status == 0 )
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
                                    <input name="membership" class="p-2" type="checkbox"

                                           value="{{$priceID}}"
                                            @php
                                                if(in_array($priceID, $priceIDArray)){
                                                echo " checked ";
                                                }
                                            @endphp
                                    />
                                </div>
                            </div>
                        </x-form-field>
                    </div>
                </div>
            @endif
            @if($hasMerchandise && $status == 0 )
                @if(sizeof($merchandise) > 0)
                    {{--@dump($priceArray)--}}
                    <div class=" mt-6 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Add
                            Extras
                        </div>
                        <div class=" px-2 py-2 pb-4 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                            @foreach($merchandise as $item)
                                {{--    @dump($item)--}}
                                @php
                                    $productIndex = $loop->index;
                                @endphp
                                <x-form-field>
                                    @php
                                        $priceArray = explode(',', $item->price);
                                    //    Get price - assumes all item prices identical
                                        $price = $priceArray[0]/100;
                                        if($price == 0) {
                                    $price = "Free of Charge";
                                        } else {
                                    $price = "£".$price;
                                        }
                                    @endphp

                                    @if($item->numOptions == 1)
                                        {{--    @dump($item->priceIDs)--}}
                                        {{--    @dump($priceIDArray)--}}

                                        <x-form-label for="product{{$productIndex}}">{{$item->product_name}}
                                            - {{$price}}</x-form-label>
                                        <input name="prodIDs[]" type="hidden" value="product{{$productIndex}}">
                                        <input name="product{{$productIndex}}" type="checkbox"
                                               value="{{$item->priceIDs}}"
                                               id="extra1"
                                                @php
                                                    if(in_array($item->priceIDs, $priceIDArray)){
                                                    echo " checked ";
                                                        }


                                                @endphp
                                        />
                                        <x-form-error name="product{{$productIndex}}"/>

                                    @else
                                        <x-form-label for="product{{$productIndex}}">{{$item->product_name}}
                                            - {{$price}}</x-form-label>
                                        <div>Please select <span class="font-semibold">one</span></div>
                                        @php
                                            $options = explode(',',$item->options);
                                            $productIDs = explode(',', $item->productIDs);
                                            $priceIDs = explode(',', $item->priceIDs);
                                        @endphp
                                        <input name="prodIDs[]" type="hidden" value="product{{$productIndex}}">
                                        @foreach($options as $option)
                                            @php
                                                $index = $loop->index;
                                                $priceIDitem = $priceIDs[$index];
                                            //    dump($priceIDitem);
                                            @endphp
                                            <input name="product{{$productIndex}}" type="radio" id="extra{{$index}}"
                                                   required
                                                   value="{{$priceIDitem}}"
                                                    @php
                                                        if(in_array($priceIDitem, $priceIDArray)){
                                                     echo " checked ";
                                                         }
                                                    @endphp
                                            >
                                            <label class="pl-1 pr-4" for="extra">{{$option}}</label>

                                        @endforeach
                                    @endif
                                </x-form-field>
                            @endforeach()
                        </div>
                    </div>
                @endif
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