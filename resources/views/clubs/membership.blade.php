<x-main>
    <x-slot:heading>Club membership</x-slot:heading>
    @php
        $membershipTypeArray = array('Renewal', 'New');
        $membershipCategoryArray = array('Competition', 'Observer', 'Life');
        $socialArray = array('No','FaceBook', 'WhatsApp', 'Other');
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
                        <x-form-input name="firstname" type="text" id="firstname" value=""
                                      placeholder="First Name" required/>
                        <x-form-error name="firstname"/>
                    </div>
                    @error('firstname')
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="lastname">Family Name</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="lastname" type="text" id="lastname" value=""
                                      placeholder="Last Name" required/>
                        <x-form-error name="lastname"/>
                    </div>
                    @error('lastname')
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="email">Email</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="email" type="email" id="email" value=""
                                      placeholder="Contact email address" required/>
                        <x-form-error name="email"/>
                    </div>
                    @error('email')
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="phone">Contact number</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="phone" type="text" id="phone" value=""
                                      placeholder="Contact number" required/>
                        <x-form-error name="phone"/>
                    </div>
                    @error('phone')
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="address">Address</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="address" type="text" id="address" value=""
                                      placeholder="Address" required/>
                        <x-form-error name="address"/>
                    </div>
                    @error('address')
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="postcode">Postcode</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="postcode" type="text" id="postcode" value=""
                                      placeholder="Postcode" required/>
                        <x-form-error name="postcode"/>
                    </div>
                    @error('postcode')
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="emergency_contact">Emergency Contact</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="emergency_contact" type="text" id="emergency_contact" value=""
                                      placeholder="Contact name" required/>
                        <x-form-error name="emergency_contact"/>
                    </div>
                    @error('emergency_contact')
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="emergency_number">Emergency Contact Number</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="emergency_number" type="text" id="emergency_number" value=""
                                      placeholder="Contact number" required/>
                        <x-form-error name="emergency_number"/>
                    </div>
                    @error('emergency_number')
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label class="pr-0" for="social">Are you on social media?</x-form-label>
                    <div class="mt-2 pl-2 pr-0">
                        @foreach($socialArray as $social)
                            <div>
                                <input name="social[]" type="checkbox"
                                       value="{{$social}}"
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
                                />
                                <label class="pl-4 pr-0" for="membership_category">{{$membershipCategory}}
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
                    <x-text-input id="accept"
                                  class="border-1 border-blue-600  mt-1"
                                  type="checkbox"
                                  name="accept" required/>
                    <x-input-label class="ml-2 font-semibold" for="accept"
                                   :value="__('I accept the Conditions of Membership below')"/>
                </div>
                </x-form-field>

            </div>
        </div>

        <div class="text-sm px-4 py-2" id="thesmallprint">
            <x-form-label>Conditions of Membership</x-form-label>
            <div>
                Annual Competition Membership is £10 payable through Stripe Payments. Payment should be made when
                entering your first trial of each year. Observer and Life Members are exempt from payment.
            </div>

            <div>
                Details of trials are available from the website - <a href="https://www.yorksclassictrials.co.uk">Click
                    here</a>
            </div>
            <div class="font-semibold pt-2">Privacy Policy and Data Protection (GDPR)</div>
            <div>Yorkshire Classic Motor Cycle Club Ltd (YCMCC) is a non-profit making Company limiting its
                members to £1 liability each in the unlikely event of bankruptcy.
            </div>
            <div>
                YCMCC organise motorcycle events under permits from the AMCA (not ACU), backed up or modified by
                the Club’s own rules.
            </div>
            <div class="font-semibold pt-2">
                Personal Data Collection and Retention
            </div>
            <div>
                Personal data is collected through membership application or event entry forms either on paper
                forms or electronically.
            </div>
            <div class="font-semibold pt-2">
                Event Entry Forms
            </div>
            <div>
                These forms are normally on paper and held by the Event or Club Secretary for a maximum period
                of one month. They will be disposed of by shredding to avoid them being found by unauthorised
                third parties.
            </div>
            <div class="font-semibold pt-2">
                Membership Data
            </div>
            <div>
                This data is used purely for the purpose of staying in touch and keeping members informed of
                YCMCC and related activities. It will not be shared with third parties either commercial or
                private for any purpose unless required by law. Paper membership application forms are retained
                by the Membership Secretary and also recorded on spreadsheets for each calendar year as
                electronic records of contact details. They are kept on the Membership Secretary’s personal
                computer protected by password, firewall and anti-viral software. Club officials may be supplied
                with these details from time to time as necessary to carry out YCMCC business.
                Member’s home addresses, email addresses and mobile phone numbers will be used to distribute
                club news and information.
            </div>
            <div class="font-semibold pt-2">
                Agreement to Retain Personal Data
            </div>
            <div>
                All members will be required to sign a 2019 or later revised membership form, a copy of the
                electronic record or accept by email to their Personal Data being stored and retained as
                described in this document.
            </div>
            <div>
                Any member requiring all or part of his or her data to be removed should send a request to the
                Membership Secretary detailing which part/s they require to be removed.
            </div>

        </div>
        {{--            <div class="ml-4 text-blue-800 font-semibold">--}}
        {{--                You will now be taken to the Stripe Checkout. Please have your payment card details ready--}}
        {{--            </div>--}}
        <div class="ml-4 text-red-600 font-semibold">
            Please note - your club membership will be confirmed once your annual payment is received.
        </div>

        <div id="buttons" class="py-4 px-4">
            <a href="/"
               class=" rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-blue-900 shadow-sm hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>
            <button type="submit"
                    class="rounded-md ml-2 bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                Register
            </button>
        </div>
    </form>

</x-main>