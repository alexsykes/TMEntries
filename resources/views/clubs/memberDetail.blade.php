<x-club>
    @php
        $name = $member->firstname." ".$member->lastname;
        $address = $member->address;
        $postcode = $member->postcode;
        $emergencyContact = "$member->emergency_contact ($member->emergency_number)";

    @endphp
    <x-slot:heading>{{$name}}@if($member->membership_type=="new")
            <i class="ml-2 fa-solid fa-star"></i>
        @endif</x-slot:heading>

    <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Member details
            for {{$name}}
        </div>
        <div class="ml-4">
            <div class="font-semibold mt-2">Address:</div>
            <div>{{$address}}</div>
            <div>{{$postcode}}</div>
            <div class="font-semibold mt-2">Contact:</div>
            <div>{{$member->email}}</div>
            <div>{{$member->phone}}</div>
            <div class="font-semibold mt-2">Emergency Contact:</div>
            <div><a>{{$emergencyContact}}</a></div>
            <div class="font-semibold mt-2">Socials:</div>
            <div>{{$member->social}}</div>
        </div>


    </div>

</x-club>