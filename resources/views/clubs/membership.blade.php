<x-main>
    <x-slot:heading>Club membership</x-slot:heading>
    @php
        $offset = DateInterval::createFromDateString('6 years');
                $now = date_create();
    $maxDob = $now->sub($offset)->format("Y-m-d");
    $membershipCategoryArray = explode(',', $club->membership_categories);
    $membershipTypeArray = array('Renewal', 'New');
//        $membershipCategoryArray = array('Competition',  'Observer', 'Life');
    $socialArray = array('No','FaceBook', 'WhatsApp', 'Other');

//        Validation stuff
    $accept = old('accept') == 'on' ? 'checked' : '';

    $socialSelected = old('social');
    $membershipCategorySelected = old('membership_category');
    $membershipTypeSelected = old('membership_type');
    @endphp
    <form action="/club/member/add" method="POST">
        @csrf
        <input type="hidden" name="club_id" value="{{$club_id}}" id="club_id">
        <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Please complete the
                following details
            </div>
            <div class="py-2 space-y-2 px-4">
                <x-form-field>
                    <x-form-label for="firstname">First Name</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="firstname" type="text" id="firstname" value="{{ old('firstname') }}"
                                      placeholder="First Name" required/>
                        <x-form-error name="firstname"/>
                    </div>
                    @error('firstname')
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="lastname">Family Name</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="lastname" type="text" id="lastname" value="{{ old('lastname') }}"
                                      placeholder="Last Name" required/>
                        <x-form-error name="lastname"/>
                    </div>
                    @error('lastname')
                    @enderror
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
                    <x-form-label for="email">Email</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="email" type="email" id="email" value="{{ old('email') }}"
                                      placeholder="Contact email address" required/>
                        <x-form-error name="email"/>
                    </div>
                    @error('email')
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="phone">Contact number</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="phone" type="text" id="phone" value="{{ old('phone') }}"
                                      placeholder="Contact number" required/>
                        <x-form-error name="phone"/>
                    </div>
                    @error('phone')
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="address">Address</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="address" type="text" id="address" value="{{ old('address') }}"
                                      placeholder="Address" required/>
                        <x-form-error name="address"/>
                    </div>
                    @error('address')
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="postcode">Postcode</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="postcode" type="text" id="postcode" value="{{ old('postcode') }}"
                                      placeholder="Postcode" required/>
                        <x-form-error name="postcode"/>
                    </div>
                    @error('postcode')
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="emergency_contact">Emergency Contact</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="emergency_contact" type="text" id="emergency_contact"
                                      value="{{ old('emergency_contact') }}"
                                      placeholder="Contact name" required/>
                        <x-form-error name="emergency_contact"/>
                    </div>
                    @error('emergency_contact')
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="emergency_number">Emergency Contact Number</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="emergency_number" type="text" id="emergency_number"
                                      value="{{ old('emergency_number') }}"
                                      placeholder="Contact number" required/>
                        <x-form-error name="emergency_number"/>
                    </div>
                    @error('emergency_number')
                    @enderror
                </x-form-field>


                <x-form-field>
                    <x-form-label for="acu_reg">ACU licence (optional)</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="acu_reg" type="text" id="acu_reg" value="{{ old('acu_reg') }}"
                                      placeholder="Optional"/>
                        <x-form-error name="acu_reg"/>
                    </div>
                    @error('acu_reg')
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="amca_reg">AMCA licence (optional)</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="amca_reg" type="text" id="amca_reg" value="{{ old('amca_reg') }}"
                                      placeholder="Optional"/>
                        <x-form-error name="amca_reg"/>
                    </div>
                    @error('amca_reg')
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label class="pr-0" for="social">Are you on social media?</x-form-label>
                    <div class="mt-2 pl-2 pr-0">
                        @foreach($socialArray as $social)
                            <div>
                                <input name="social[]" type="checkbox"
                                       value="{{$social}}"
                                        @php
                                            if(isset($socialSelected)) {
                                            $selected = in_array($social, $socialSelected) ? ' checked ' : '';
                                            echo $selected;
                                            }
                                        @endphp
                                />
                                <label class="pl-4 pr-0" for="social">{{$social}}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('social')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="membership_type">Membership Type</x-form-label>
                    <div class="mt-2 pl-2 pr-0">
                        @foreach($membershipTypeArray as $membershipType)
                            <div>
                                <input name="membership_type" type="radio"
                                       value="{{$membershipType}}"
                                        @php

                                            $checked = '';
                                                if(isset($membershipTypeSelected)) {
                                                if($membershipType == $membershipTypeSelected) {
                                                    $checked = ' checked ';
                                                }
                                                }
                                        @endphp
                                        {{$checked}}
                                />
                                <label class="pl-4 pr-0" for="membership_type">{{$membershipType}}
                                </label>
                            </div>
                        @endforeach

                    </div>
                    @error('membership_type')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>


                <x-form-field>
                    <x-form-label for="membership_category">Membership Category</x-form-label>
                    <div class="mt-2 pl-2 pr-0">
                        @foreach($membershipCategoryArray as $membershipCategory)
                            <div>
                                <input name="membership_category" type="radio"
                                       value="{{strtolower($membershipCategory)}}"

                                        @php

                                            $checked = '';
                                                if(isset($membershipCategorySelected)) {
                                                if(strtolower($membershipCategory) == $membershipCategorySelected) {
                                                    $checked = ' checked ';
                                                }
                                                }
                                        @endphp
                                        {{$checked}}
                                />
                                <label class="pl-4 pr-0" for="membership_category">{{ucfirst($membershipCategory)}}
                                </label>
                            </div>
                        @endforeach

                    </div>
                    @error('membership_category')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <div class="flex mt-4">
                        <input class="border-slate-600 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm border-1   mt-1"
                               id="accept" type="checkbox" name="accept" required="required" {{$accept}}>
                        <label class="block font-medium text-sm text-blue-700 ml-2 font-semibold" for="agree">
                            I accept the Conditions of Membership below
                        </label>
                    </div>
                </x-form-field>

            </div>

            <div id="buttons" class="py-4 px-4">
                <a href="/"
                   class=" rounded-md bg-blue-100 px-3 py-2 text-sm  drop-shadow-lg text-blue-900 shadow-sm hover:bg-blue-900 border border-blue-800  hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>
                <button type="submit"
                        class="rounded-md ml-2 bg-blue-600 px-3 py-2 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                    Register
                </button>
            </div>
        </div>

        <div class="text-sm px-4 py-2" id="thesmallprint">
            @php
                echo $club->conditions;
            @endphp

        </div>
        <div class="ml-4 text-red-600 font-semibold">
            Please note - your club membership will be confirmed once your annual payment is received.
        </div>

    </form>

</x-main>