<x-main>
    <script>
        function toggleOtherClub(checked, div) {
            let x = document.getElementById(div);
            if (checked) {
                x.style.display = "none";
            } else {
                x.style.display = "block";
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
    <style>
        .number {
            width: 6em;
        }
    </style>

    @php
        $trial_id = $trial->id;
        $trial_date = date_create($trial->date);
        $offset = DateInterval::createFromDateString('6 years');
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

    $userID = Auth::user()->id;

//    Check for extras
        $hasMembership = is_null($membership) ? false : true;

    @endphp
    <x-slot:heading>
        Registration for {{$trial->name}}
    </x-slot:heading>

    @error('')
    {{ $message }}
    @enderror
    @if(sizeof($entries) > 0)
        <div
            class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-red-600">Unconfirmed Entries -
                your entry is not confirmed until payment is completed
            </div>

            <table class="w-full">
                @foreach($entries as $entry)
                    @php
                        $entryID = $entry->id ;
                        array_push($entryIDs, $entryID);
                    @endphp
                    <tr class="odd:bg-white  even:bg-gray-50  border-b">
                        <td class="pl-2">{{$entryID}}</td>
                        <td class="pl-2">{{$entry->name}}</td>
                        <td class="pl-2 hidden sm:table-cell">{{$entry->course}}</td>
                        <td class="pl-2 hidden sm:table-cell">{{$entry->class}}</td>
                        <td class="pl-2 hidden md:table-cell">{{$entry->make}} {{$entry->size}}</td>
                        <td class="pl-2"><a href="/entries/edit/{{$entryID}}"><i class="fa-solid fa-pen-to-square"></i></a>
                        </td>
                        <td class="pl-2"><a href="/entries/delete/{{$entryID}}"><i
                                    class="fa-solid fa-ban text-orange-700"></i></a></td>
                    </tr>
                @endforeach
                @php
                    $entryIDstring = implode(',', $entryIDs)
                @endphp
            </table>
        </div>

        <form action="/stripe/checkout" method="post">
            @csrf
            <button type="submit"
                    class="mt-2 rounded-md  bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                Go to Payment
            </button>
            <input type="hidden" id="entryIDs" name="entryIDs" value="{{implode(',',$entryIDs)}}">
        </form>
    @endif

    @if(sizeof($offers) > 0)
        <div
            class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-green-600">Reserves - you have
                been offered a plece. Please check your emails and pay the invoice
            </div>

            <table class="w-full">
                @foreach($offers as $entry)
                    @php
                        $entryID = $entry->id ;
                        array_push($entryIDs, $entryID);
                    @endphp
                    <tr class="odd:bg-white  even:bg-gray-50  border-b">
                        <td class="pl-2">{{$entryID}}</td>
                        <td class="pl-2">{{$entry->name}}</td>
                        <td class="pl-2 hidden sm:table-cell">{{$entry->course}}</td>
                        <td class="pl-2 hidden sm:table-cell">{{$entry->class}}</td>
                        <td class="pl-2 hidden md:table-cell">{{$entry->make}} {{$entry->size}}</td>
                        <td class="pl-2"><a href="/entries/edit/{{$entryID}}"><i class="fa-solid fa-pen-to-square"></i></a>
                        </td>
                        <td class="pl-2"><a href="/entries/delete/{{$entryID}}"><i
                                    class="fa-solid fa-ban text-orange-700"></i></a></td>
                    </tr>
                @endforeach
            </table>
        </div>
    @endif

    @if(sizeof($reserves) > 0)
        <div
            class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-red-600">Reserves - you will
                receive an email notification if a space becomes available.
            </div>

            <table class="w-full">
                @foreach($reserves as $entry)
                    @php
                        $entryID = $entry->id ;
                        array_push($entryIDs, $entryID);
                    @endphp
                    <tr class="odd:bg-white  even:bg-gray-50  border-b">
                        <td class="pl-2">{{$entryID}}</td>
                        <td class="pl-2">{{$entry->name}}</td>
                        <td class="pl-2 hidden sm:table-cell">{{$entry->course}}</td>
                        <td class="pl-2 hidden sm:table-cell">{{$entry->class}}</td>
                        <td class="pl-2 hidden md:table-cell">{{$entry->make}} {{$entry->size}}</td>
                        <td class="pl-2"><a href="/entries/edit/{{$entryID}}"><i class="fa-solid fa-pen-to-square"></i></a>
                        </td>
                        <td class="pl-2"><a href="/entries/delete/{{$entryID}}"><i
                                    class="fa-solid fa-ban text-orange-700"></i></a></td>
                    </tr>
                @endforeach
            </table>
        </div>
    @endif


    <form autocomplete="off" action="/entries/store" method="POST">
        @csrf
        <input autocomplete="false" name="hidden" type="text" class="hidden">
        <input type="hidden" id="trial_id" name="trial_id" value="{{$trial_id}}">
        <input type="hidden" id="created_by" name="created_by" value="{{$userID}}">
        <div class="space-y-12">
            <div
                class=" mt-6 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Add an
                    Entry
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
                        <x-form-label class="pb-2" for="class">Club membership</x-form-label>

                        <div class="">
                            <caption>I am a current member of {{ $clubName }}</caption>
                            <input class="ml-2" type="checkbox" name="isClubMember"
                                   onchange="toggleOtherClub(checked, 'otherClubDiv')" value="{{ $clubID }}">
                        </div>

                        <div id="otherClubDiv" class="mt-2">
                            <div>
                                <caption>I am a current member of</caption>
                            </div>
                            <div>
                                <x-form-input class="" type="text" name="otherClub" value=""
                                              placeholder="Club name - leave blank if no club"/>
                            </div>
                        </div>
                        {{--                           <div>--}}
                        {{--                               <caption>I am not a member of a club</caption>--}}
                        {{--                            <input class="ml-2" type="checkbox" name="noClub" value="0">--}}
                        {{--                        </div>--}}

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
                                <x-form-input type="date" max="{{$maxDob}}" required name="dob" id="dob"
                                              :value="old('dob')"/>
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
                                          autocomplete="off"
                                          placeholder="Bike engine size - leave empty for e-Bike"/>
                            <x-form-error name="size"/>
                        </div>
                    </x-form-field>

                    <x-form-field>
                        <x-form-label class="pb-2" for="type">Type</x-form-label>

                        <div
                            class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 drop-shadow-lg outline-blue-700 ">
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
                        <div
                            class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 drop-shadow-lg outline-blue-700 ">
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

                        <div
                            class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 drop-shadow-lg outline-blue-700 ">
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
        </div>
        {{-- Check for merchandise--}}
        @if(sizeof($merchandise) > 0)
            <div
                class=" mt-6 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Add Extras
                </div>
                <div class=" px-2 py-2 pb-4 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                    @foreach($merchandise as $item)
                        {{--@dump($merchandise)--}}
                        @php
                            $productIndex = $loop->index;
                        @endphp
                        <x-form-field>
                            @php
                                $priceArray = explode(',', $item->price);
                                $price = $priceArray[0]/100;
                                if($price == 0) {
                                    $price = "Free of Charge";
                                } else {
                                    $price = "£".$price;
                                }
                            @endphp

                            {{--                            Check box - single opt-in/out --}}
                            @if($item->numOptions == 1)
                                <x-form-label for="product{{$productIndex}}">{{$item->product_name}}
                                    - {{$price}}</x-form-label>
                                <input name="prodIDs[]" type="hidden" value="product{{$productIndex}}">
                                <input name="product{{$productIndex}}" type="checkbox" value="{{$item->priceIDs}}"
                                       id="extra1"
                                    {{old('extra1') != null ? 'checked' :''}}
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
                                    @endphp
                                    <input name="product{{$productIndex}}" type="radio" id="extra{{$index}}" required
                                           value="{{$priceIDs[$index]}}"
                                        {{ (old('extra') == $option) ? ' checked' : '' }}
                                    >
                                    <label class="pl-1 pr-4" for="extra">{{$option}}</label>

                                @endforeach
                            @endif
                        </x-form-field>
                    @endforeach()
                </div>
            </div>
        @endif

        @if($hasMembership)
            <div
                class=" mt-6 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Add Membership
                </div>
                <div class=" px-2 py-2 pb-4 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                    @php
                        $membershipFee = $membership->price / 100;
                        $productID = $membership->stripe_product_id;
                        $priceID = $membership->stripe_price_id;
                    @endphp
                    <x-form-field>
                        <div class="flex col-span-3 justify-normal">
                            <div class="font-semibold text-blue-700">{{$membership->name}}
                                (£{{ $membershipFee  }})
                            </div>

                            <div class="pl-2">
                                <input name="membership" class="p-2" type="checkbox"
                                       value="{{$priceID}}"
                                />
                            </div>
                        </div>
                    </x-form-field>
                </div>
            </div>
        @endif

        <div class="mt-4" id="buttons">
            <a href="/"
               class="rounded-md bg-white px-3 py-2 text-sm  text-blue-600 shadow-sm hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>
            <button type="submit"
                    class="rounded-md ml-2 bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                Register
            </button>
        </div>
    </form>
</x-main>
