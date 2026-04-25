<x-club>
    @php
        //    dump($member);
                $offset = DateInterval::createFromDateString('6 years');
                $now = date_create();
                $maxDob = $now->sub($offset)->format("Y-m-d");
                $memberSocials = explode(',', $member->social);
//                $dob = $member->dob;
//                dump($dob);

                $membershipCategoryArray = explode(',', $club->membership_categories);
                $membershipTypeArray = array('Renewal', 'New');
                $socialArray = array('No','FaceBook', 'WhatsApp', 'Other');

    @endphp

    <x-slot:heading>Update {{$member->firstname}} {{$member->lastname}} ({{$member->id}})</x-slot:heading>

    <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">You can update the
            following details
        </div>
        <form action="/club/member/update" method="post">
            <input type="hidden" value="{{$member->id}}" name="id">
            @method('PATCH')
            @csrf
            <div class="pl-2 pr-2 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                <div id="firstNameDiv" class="col-span-3">
                    <x-form-field>
                        <x-form-label for="firstname">First Name</x-form-label>
                        <div class="mt-2 col-span-1">
                            <x-form-input name="firstname" type="text" id="firstname"
                                          value="{{ $member->firstname }}"
                                          placeholder="First Name" required/>
                            <x-form-error name="firstname"/>
                        </div>
                        @error('firstname')
                        @enderror
                    </x-form-field>
                </div>


                <div id="lastNameDiv" class="col-span-3">
                    <x-form-field>
                        <x-form-label for="lastname">Family Name</x-form-label>
                        <div class="mt-2 sm:col-span-1 ">
                            <x-form-input name="lastname" type="text" id="lastname"
                                          value="{{ $member->lastname }}"
                                          placeholder="Last Name" required/>
                            <x-form-error name="lastname"/>
                        </div>
                        @error('lastname')
                        @enderror
                    </x-form-field>
                </div>

                <div id="dateInput" class=" col-span-full">
                    <x-form-field>
                        <x-form-label for="dob">Date of Birth</x-form-label>
                        <div class="mt-2  max-w-40 col-span-full">
                            <x-form-input type="date" max="{{$maxDob}}" name="dob" id="dob"
                                          value="{{$member->dob}}"/>
                        </div>
                        @error('dob')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </x-form-field>
                </div>

                <div id="emailDiv" class="col-span-3">
                    <x-form-field>
                        <x-form-label for="email">Email</x-form-label>
                        <div class="mt-2 sm:col-span-1">
                            <x-form-input name="email" type="email" id="email"
                                          value="{{ $member->email }}"
                                          placeholder="Contact email address" required/>
                            <x-form-error name="email"/>
                        </div>
                        @error('email')
                        @enderror
                    </x-form-field>
                </div>


                <div id="phoneDiv" class="col-span-3">
                    <x-form-field>
                        <x-form-label for="phone">Contact number</x-form-label>
                        <div class="mt-2 sm:col-span-1">
                            <x-form-input name="phone" type="text" id="phone"
                                          value="{{ $member->phone }}"
                                          placeholder="Contact number" required/>
                            <x-form-error name="phone"/>
                        </div>
                        @error('phone')
                        @enderror
                    </x-form-field>
                </div>


                <div id="addressDiv" class="col-span-3">
                    <x-form-field>
                        <x-form-label for="address">Address</x-form-label>
                        <div class="mt-2 sm:col-span-1">
                            <x-form-input name="address" type="text" id="address"
                                          value="{{ $member->address }}"
                                          placeholder="Address"/>
                            <x-form-error name="address"/>
                        </div>
                        @error('address')
                        @enderror
                    </x-form-field>
                </div>


                <div id="postcodeDiv" class="col-span-3">
                    <x-form-field>
                        <x-form-label for="postcode">Postcode</x-form-label>
                        <div class="mt-2 sm:col-span-1">
                            <x-form-input name="postcode" type="text" id="postcode"
                                          value="{{ $member->postcode }}"
                                          placeholder="Postcode"/>
                            <x-form-error name="postcode"/>
                        </div>
                        @error('postcode')
                        @enderror
                    </x-form-field>
                </div>


                <div id="memTypeDiv" class="col-span-3">
                    <x-form-field>
                        <x-form-label for="membership_type">Membership Type</x-form-label>
                        <div class="mt-2 pl-2 pr-0">
                            @foreach($membershipTypeArray as $membershipType)
                                <div>
                                    <input name="membership_type" type="radio"
                                           value="{{$membershipType}}"
                                            @php

                                                $checked = '';
//                                                    if(isset($membershipTypeSelected)) {
                                                    if(strtolower($membershipType) == $member->membership_type) {
                                                        $checked = ' checked ';
//                                                    }
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
                </div>


                <div id="memCatDiv" class="col-span-3">
                    <x-form-field>
                        <x-form-label for="membership_category">Membership Category</x-form-label>
                        <div class="mt-2 pl-2 pr-0">
                            @foreach($membershipCategoryArray as $membershipCategory)
                                <div>
                                    <input name="membership_category" type="radio"
                                           value="{{strtolower($membershipCategory)}}"
                                            @php
                                                $checked = '';
                                                    if(strtolower($membershipCategory) == $member->membership_category) {
                                                        $checked = ' checked ';
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
                </div>


                <div id="emergNameDiv" class="col-span-3">
                    <x-form-field>
                        <x-form-label for="emergency_contact">Emergency Contact</x-form-label>
                        <div class="mt-2 sm:col-span-1">
                            <x-form-input name="emergency_contact" type="text" id="emergency_contact"
                                          value="{{ $member->emergency_contact }}"
                                          placeholder="Contact name"/>
                            <x-form-error name="emergency_contact"/>
                        </div>
                        @error('emergency_contact')
                        @enderror
                    </x-form-field>
                </div>


                <div id="emNumDiv" class="col-span-3">
                    <x-form-field>
                        <x-form-label for="emergency_number">Emergency Contact Number</x-form-label>
                        <div class="mt-2 sm:col-span-1">
                            <x-form-input name="emergency_number" type="text" id="emergency_number"
                                          value="{{ $member->emergency_number }}"
                                          placeholder="Contact number"/>
                            <x-form-error name="emergency_number"/>
                        </div>
                        @error('emergency_number')
                        @enderror
                    </x-form-field>
                </div>


                <div id="acuDiv" class="col-span-3">
                    <x-form-field>
                        <x-form-label for="acu_reg">ACU licence (optional)</x-form-label>
                        <div class="mt-2 col-span-2">
                            <x-form-input name="acu_reg" type="text" id="acu_reg"
                                          value="{{ $member->acu_reg }}"
                                          placeholder="Optional"/>
                            <x-form-error name="acu_reg"/>
                        </div>
                        @error('acu_reg')
                        @enderror
                    </x-form-field>
                </div>


                <div id="amca_div" class="col-span-3">
                    <x-form-field>
                        <x-form-label for="amca_reg">AMCA licence (optional)</x-form-label>
                        <div class="mt-2 col-span-2">
                            <x-form-input name="amca_reg" type="text" id="amca_reg"
                                          value="{{ $member->amca_reg }}"
                                          placeholder="Optional"/>
                            <x-form-error name="amca_reg"/>
                        </div>
                        @error('amca_reg')
                        @enderror
                    </x-form-field>
                </div>

                <div id="paidDiv" class="col-span-3">
                    <x-form-field>
                        <div class="flex ml-2 mt-2 col-span-full">
                            <x-form-label for="confirmed">Mark as Paid</x-form-label>
                            <input class="ml-2" type="checkbox" name="confirmed" id="confirmed" value="1"
                                    @php
                                        if($member->confirmed) {
                                            echo " checked ";
                                        }
                                    @endphp/>
                        </div>
                    </x-form-field>
                </div>


                <div id="socialsDiv" class="col-span-3">
                    <x-form-field>
                        <x-form-label class="pr-0" for="social">Are you on social media?</x-form-label>
                        <div class="mt-2 pl-2 pr-0">
                            @foreach($socialArray as $social)
                                <div>
                                    <input name="social[]" type="checkbox"
                                           value="{{$social}}"
                                            @php
                                                $selected = in_array($social, $memberSocials) ? ' checked ' : '';
                                                echo $selected;
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
                </div>


            </div>

            <div id="buttons" class="py-4 px-2">
                {{--                            <a href="/"--}}
                {{--                               class=" rounded-md bg-violet-100 px-3 py-2 text-sm  drop-shadow-lg text-violet-900 shadow-sm hover:bg-violet-900 border border-violet-800  hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-900">Cancel</a>--}}
                <button type="submit"
                        class="rounded-md ml-2 bg-violet-600 px-3 py-2 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-club>