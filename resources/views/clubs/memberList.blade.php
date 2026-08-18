<x-club>
    @php
        $club_id = 5;
            $membershipTypeArray = array('Renewal', 'New');
            $membershipCategoryArray = array('Competition','Associate',  'Observer', 'Life');
            $socialArray = array('No','FaceBook', 'WhatsApp', 'Other');

    //        Validation stuff
            $accept = old('accept') == 'on' ? 'checked' : '';

            $socialSelected = old('social');
            $membershipCategorySelected = old('membership_category');
            $membershipTypeSelected = old('membership_type');
    @endphp
    <x-slot:heading>Membership List for {{$clubName->name}}</x-slot:heading>
    <div id="tabButtons" class="tab pl-4">
        <button class="tablinks border border-black border-b-0 rounded-t-lg   hover:bg-violet-500 p-1"
                id="competitionsTab"
                onclick="openSection(event, 'riders')">
            Competitors
        </button>

        <button class="tablinks border border-black border-b-0 rounded-t-lg    hover:bg-violet-500 p-1"
                id="observersTab"
                onclick="openSection(event, 'observers')">Observers
        </button>

        <button class="tablinks border border-black border-b-0 rounded-t-lg    hover:bg-violet-500 p-1"
                id="lifersTab"
                onclick="openSection(event, 'lifers')">Life members
        </button>
        <button class="tablinks border border-black border-b-0 rounded-t-lg   hover:bg-violet-500 p-1 active"
                id="allMembersTab"
                onclick="openSection(event, 'allMembers')">
            All
        </button>
        <button class="tablinks border border-black border-b-0 rounded-t-lg   hover:bg-violet-500 p-1 active"
                id="manualAddTab"
                onclick="openSection(event, 'manualAdd')">
            Add a new Member
        </button>
    </div>

    <div id="riders" style="" class="tabcontent pt-0">
        <div class="mx-auto max-w-7xl sm: lg:">
            <div
                class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 ">

                <div
                    class="flex justify-between font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">
                    <div class="">Competition
                        members
                    </div>
                    <div class=""><a href="/club/member/approve">Go to Approvals</a>
                    </div>
                </div>
                <table class="w-full">
                    <tbody>
                    <tr class="font-bold text-violet-700">
                        <th></th>
                        <th>Name</th>
                        <th class="max-sm:hidden">Email</th>
                        <th class="max-sm:hidden">Phone</th>
                        <th>Emergency Contact</th>
                        <th>Emergency Phone</th>
                        <th></th>
                    </tr>
                    @foreach($riders as $rider)
                        <tr @if($rider->confirmed) class="text-slate-800 " @else  class="text-red-500 " @endif>
                            <td class="pl-2">{{$rider->heritage_number}}</td>
                            <td><a href="/club/member/detail/{{$rider->id}}">
                                    @if($rider->membership_type == 'new')
                                        *
                                    @endif
                                    {{$rider->firstname}}
                                    &nbsp;{{$rider->lastname}}</a>
                            </td>
                            <td class="max-sm:hidden"><a href="mailto:{{$rider->email}}">{{$rider->email}}</a></td>
                            <td class="max-sm:hidden">{{$rider->phone}}</td>
                            <td>{{$rider->emergency_contact}}</td>
                            <td>{{$rider->emergency_number}}</td>
                            {{--                            <td class="pr-2">@if($rider->confirmed) &nbsp; @else <a href="/club/membership/confirm/{{$rider->id}}"><i class="fa-solid fa-check"></i></a>@endif--}}
                            {{--                            </td>--}}
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="text-center w-full pt-2">* - indicates new member</div>
                <div class="p-2 sm:hidden">Rotate phone to landscape view for full member details</div>
            </div>
        </div>
    </div>

    <div id="observers" style="display: none;" class="tabcontent pt-0">
        <div class="mx-auto max-w-7xl sm: lg:">
            <div
                class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 ">

                <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Observers
                </div>
                <table class="w-full">
                    <tbody>
                    <tr class="font-bold text-violet-700">
                        <th></th>
                        <th>Name</th>
                        <th class="max-sm:hidden">Email</th>
                        <th class="max-sm:hidden">Phone</th>
                        <th>Emergency Contact</th>
                        <th>Emergency Phone</th>
                    </tr>
                    @foreach($observers as $rider)
                        <tr @if($rider->confirmed) class="text-slate-800 " @else  class="text-red-500 " @endif>
                            <td class="pl-2">{{$rider->heritage_number}}</td>
                            <td><a href="/club/member/detail/{{$rider->id}}">
                                    @if($rider->membership_type == 'new')
                                        *
                                    @endif
                                    {{$rider->firstname}}
                                    &nbsp;{{$rider->lastname}}</a>
                            </td>
                            <td class="max-sm:hidden"><a href="mailto:{{$rider->email}}">{{$rider->email}}</a></td>
                            <td class="max-sm:hidden">{{$rider->phone}}</td>
                            <td>{{$rider->emergency_contact}}</td>
                            <td>{{$rider->emergency_number}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="text-center w-full pt-2">* - indicates new member</div>
                <div class="p-2 sm:hidden">Rotate phone to landscape view for full member details</div>
            </div>
        </div>
    </div>

    <div id="lifers" style="display: none;" class="tabcontent pt-0">
        <div class="mx-auto max-w-7xl sm: lg:">
            <div
                class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 ">

                <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Life members
                </div>
                <table class="w-full">
                    <tbody>
                    <tr class="font-bold text-violet-700">
                        <th></th>
                        <th>Name</th>
                        <th class="max-sm:hidden">Email</th>
                        <th class="max-sm:hidden">Phone</th>
                        <th>Emergency Contact</th>
                        <th>Emergency Phone</th>
                    </tr>
                    @foreach($lifers as $rider)
                        <tr @if($rider->confirmed) class="text-slate-800 " @else  class="text-red-500 " @endif>
                            <td class="pl-2">{{$rider->heritage_number}}</td>
                            <td><a href="/club/member/detail/{{$rider->id}}">
                                    @if($rider->membership_type == 'new')
                                        *
                                    @endif
                                    {{$rider->firstname}}
                                    &nbsp;{{$rider->lastname}}</a>
                            </td>
                            <td class="max-sm:hidden"><a href="mailto:{{$rider->email}}">{{$rider->email}}</a></td>
                            <td class="max-sm:hidden">{{$rider->phone}}</td>
                            <td>{{$rider->emergency_contact}}</td>
                            <td><a>{{$rider->emergency_number}}</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="text-center w-full pt-2">* - indicates new member</div>
                <div class="p-2 sm:hidden">Rotate phone to landscape view for full member details</div>
            </div>
        </div>
    </div>

    <div id="allMembers" style="display: none;" class="tabcontent pt-0">
        <div class="mx-auto max-w-7xl sm: lg:">
            <div
                class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 ">

                <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">All members
                </div>
                <table class="w-full">
                    <tbody>
                    <tr class="font-bold text-violet-700">
                        <th></th>
                        <th>Name</th>
                        <th class="max-sm:hidden">Email</th>
                        <th class="max-sm:hidden">Phone</th>
                        <th>Emergency Contact</th>
                        <th>Emergency Phone</th>
                    </tr>
                    @foreach($allmembers as $rider)
                        <tr @if($rider->confirmed) class="text-slate-800 " @else  class="text-red-500 " @endif>
                            <td class="pl-2">{{$rider->heritage_number}}</td>
                            <td><a href="/club/member/detail/{{$rider->id}}">
                                    @if($rider->membership_type == 'new')
                                        *
                                    @endif
                                    {{$rider->firstname}}
                                    &nbsp;{{$rider->lastname}}</a>
                            </td>
                            <td class="max-sm:hidden"><a href="mailto:{{$rider->email}}">{{$rider->email}}</a></td>
                            <td class="max-sm:hidden">{{$rider->phone}}</td>
                            <td>{{$rider->emergency_contact}}</td>
                            <td>{{$rider->emergency_number}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="text-center w-full pt-2">* - indicates new member</div>
                <div class="p-2 sm:hidden">Rotate phone to landscape view for full member details</div>
            </div>
        </div>

    </div>

    <div id="manualAdd" style="display: none;" class="tabcontent pt-0">
        <div class="mx-auto max-w-7xl ">
            <div
                class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 ">
                <div class="flex sm:grid-cols-2"></div>
                <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Member Detail
                </div>
                <form action="/club/member/addManual" method="post">
                    @csrf

                    <input type="hidden" name="club_id" value="{{$club_id}}" id="club_id">

                    <div class="pl-2 pr-2 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                        <div id="firstNameDiv" class="col-span-3">
                            <x-form-field>
                                <x-form-label for="firstname">First Name</x-form-label>
                                <div class="mt-2 col-span-1">
                                    <x-form-input name="firstname" type="text" id="firstname"
                                                  value="{{ old('firstname') }}"
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
                                                  value="{{ old('lastname') }}"
                                                  placeholder="Last Name" required/>
                                    <x-form-error name="lastname"/>
                                </div>
                                @error('lastname')
                                @enderror
                            </x-form-field>
                        </div>

                        <div id="emailDiv" class="col-span-3">
                            <x-form-field>
                                <x-form-label for="email">Email</x-form-label>
                                <div class="mt-2 sm:col-span-1">
                                    <x-form-input name="email" type="email" id="email" value="{{ old('email') }}"
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
                                    <x-form-input name="phone" type="text" id="phone" value="{{ old('phone') }}"
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
                                    <x-form-input name="address" type="text" id="address" value="{{ old('address') }}"
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
                                                  value="{{ old('postcode') }}"
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
                                                        if(isset($membershipCategorySelected)) {
                                                        if(strtolower($membershipCategory) == $membershipCategorySelected) {
                                                            $checked = ' checked ';
                                                        }
                                                        }
                                                @endphp
                                                {{$checked}}
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
                        </div>


                        <div id="emergNameDiv" class="col-span-3">
                            <x-form-field>
                                <x-form-label for="emergency_contact">Emergency Contact</x-form-label>
                                <div class="mt-2 sm:col-span-1">
                                    <x-form-input name="emergency_contact" type="text" id="emergency_contact"
                                                  value="{{ old('emergency_contact') }}"
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
                                                  value="{{ old('emergency_number') }}"
                                                  placeholder="Contact number"/>
                                    <x-form-error name="emergency_number"/>
                                </div>
                                @error('emergency_number')
                                @enderror
                            </x-form-field>
                        </div>

                        <div id="paidDiv" class="col-span-3">
                            <x-form-field>
                                <div class="flex ml-2 mt-2 col-span-full">
                                    <x-form-label for="confirmed">Mark as Paid</x-form-label>
                                    <input class="ml-2" type="checkbox" name="confirmed" id="confirmed" value="1"/>
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
                        </div>


                    </div>

                    <div id="buttons" class="py-4 px-2">
                        {{--                            <a href="/"--}}
                        {{--                               class=" rounded-md bg-violet-100 px-3 py-2 text-sm  drop-shadow-lg text-violet-900 shadow-sm hover:bg-violet-900 border border-violet-800  hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-900">Cancel</a>--}}
                        <button type="submit"
                                class="rounded-md ml-2 bg-violet-600 px-3 py-2 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        // Get the element with id="defaultOpen" and click on it
        document.getElementById("competitionsTab").click();
    </script>
</x-club>
