<x-main>
    @php
        $statusOptions = array(    'Unconfirmed - will be confirmed when payment is completed', 'Confirmed', 'Withdrawn - paid awaiting refund', 'Refunded', 'Accepted - awaiting payment', 'Reserve', 'Removed', 'Manual entry - to pay', 'Manual entry - paid', 'Manual entry - FoC');

        $allCourses = array();
        $courses = $entry->courselist;
    $customCourses = $entry->customCourses;

        $trial_date = date_create($entry->trialdate);
        $offset = DateInterval::createFromDateString('4 years');
        $maxDob = $trial_date->sub($offset)->format("Y-m-d");

    $allClasses = array();
    $classes = $entry->classlist;
    $status = $entry->status;
//    dd($classes);
    $customClasses = $entry->customClasses;
if($courses != "") {
    array_push($allCourses, $courses);
    }

if($customCourses != "") {
    array_push($allCourses, $customCourses);
    }
if($classes != ""){
    array_push($allClasses, $classes);
    }

if($customClasses != "") {
    array_push($allClasses, $customClasses);
    }
    $classlist = str_replace(',',',',implode(',', $allClasses));
    $courselist   = str_replace(',',',',implode(',', $allCourses));
    $courseOptions = explode(',', $courselist);
    $classOptions = explode(',', $classlist);

    $types = array("2 stroke", "4 stroke", "e-bike");

        //    Check for extras
        $hasExtras = is_null($membership) ? false : true;
                        $numOptions = sizeof($options);
                        $hasOptions = false;
                        if($numOptions > 0) {
                            $hasOptions = true;
                        }

// New stuff
    $status = $entry->status;

     $hasMembership = is_null($membership) ? false : true;

     $extraArray  = json_decode($entry->extras);
     $priceArray = array();
     $qtyArray = array();
     if(!is_null($extraArray)) {
         $priceArray = array_column($extraArray, 'priceID');
         $qtyArray = array_column($extraArray, 'qty');
     }
    @endphp
    <x-slot:heading>{{$entry->club}} {{$entry->trial_name}}</x-slot:heading>

    @if($entry->isEntryLocked)
        <div class=" mt-0 mb-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="text-blue-800 font-semibold col-span-4 ml-4 pt-2">This entry is locked. No changes can be
                made.
            </div>
        </div>
    @else
        <form action="/user/entry/update" method="post">
            @csrf
            @method('PATCH')
            <input type="hidden" name="entryID" id="entryID" value="{{$entry->id}}">
            <div class=" mt-0 mb-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
                <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Editing entry
                    for {{$entry->name}}</div>

                <div class="mt-2   mb-2 ml-4 ">
                    <div class="text-blue-800 mb-2 font-semibold">Entry status: {{$statusOptions[$entry->status]}}</div>

                    @if($entry->status == 0)

                        <x-form-field>
                            <x-form-label for="isYouth">Date of Birth</x-form-label>
                            <div class="mt-2">
                                <x-form-input type="date" max="{{$maxDob}}" name="dob" id="dob"
                                              value="{{$entry->dob}}"/>
                                <x-form-error name="dob"/>
                            </div>
                        </x-form-field>

                    @endif

                    <x-form-field>
                        <x-form-label for="make">Make</x-form-label>
                        <div class="mt-2">
                            <x-form-input name="make" type="text" id="make" value="{{$entry->make}}"
                                          placeholder="Bike make/model" required/>
                            <x-form-error name="make"/>
                        </div>
                        @error('make')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </x-form-field>
                </div>
                <div class="ml-4 mb-4">
                    <x-form-field>
                        <x-form-label for="size">Capacity</x-form-label>
                        <div class="mt-2">
                            <x-form-input name="size" type="text" id="size" value="{{$entry->size}}"
                                          placeholder="Bike engine size - leave empty for e-Bike"/>
                            <x-form-error name="size"/>
                        </div>
                    </x-form-field>


                </div>
                <div class="ml-4 mb-4">
                    <x-form-field>
                        <x-form-label class="pb-2" for="type">Type</x-form-label>

                        <div class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 drop-shadow-lg outline-blue-700 ">
                            <div class="pb-2 pt-2    sm:col-span-2">
                                <select class="ml-2 bg-white  space-x-4 border-none" name="type" id="type" required>
                                    <option value="">Select your engine type</option>
                                    @foreach($types as $type)
                                        <option value="{{$type}}" {{$type==$entry->type ? " selected " : ""}}>{{$type}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </x-form-field>

                </div>
                <div class="ml-4 mb-4">
                    <x-form-field>

                        <x-form-label class="pb-2" for="course">Course</x-form-label>
                        <div class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 drop-shadow-lg outline-blue-700 ">
                            <div class="pb-2 pt-2    sm:col-span-2">
                                <select class="ml-2 bg-white  space-x-4 border-none" name="course" id="course"
                                        required>
                                    <option value="">Select your course</option>
                                    @foreach($courseOptions as $course)
                                        <option value="{{$course}}" {{$course==$entry->course ? " selected " : ""}}>{{$course}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </x-form-field>

                </div>
                <div class="ml-4 mb-4">
                    <x-form-field>
                        <x-form-label class="pb-2" for="class">Class</x-form-label>

                        <div class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 drop-shadow-lg outline-blue-700 ">
                            <div class="pb-2 pt-2 bg-white sm:col-span-2">
                                <select class="ml-2  bg-white  space-x-4 border-none" name="class" id="class"
                                        required>
                                    <option value="">Select your class</option>
                                    @foreach($classOptions as $class)
                                        <option value="{{$class}}" {{$class==$entry->class ? " selected " : ""}}>{{$class}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </x-form-field>
                </div>
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


            @if($hasOptions && $status == 0)
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

                @if($status == 1)
                    <div class="ml-4 mb-4">
                        This entry can be withdrawn and a refund issued. Please note that an administration charge of £3
                        will be applied to any refunds.
                    </div>
                @endif

            <div class="mt-4" id="buttons">
                <a href="/user/entries"
                   class="rounded-md bg-white px-3 py-2 text-sm  text-blue-600 shadow-sm hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel
                    changes</a>

                <button type="submit"
                        name="action"
                        value="save"
                        class="rounded-md ml-2 bg-blue-600 px-3 py-1 text-sm border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                    Save changes
                </button>

                <button type="submit"
                        name="action"
                        value="withdraw"
                        class="rounded-md ml-2 bg-red-600 px-3 py-1 text-sm border border-red-800 text-white drop-shadow-lg hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                    Withdraw
                </button>
            </div>
        </form>
    @endif
</x-main>