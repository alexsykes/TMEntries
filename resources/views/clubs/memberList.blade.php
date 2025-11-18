<x-club>
    <x-slot:heading>Membership List for {{$clubName->name}}</x-slot:heading>
    <div class=" mt-4 mb-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <table class="w-full table-auto ">
            <thead>
            <tr class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  bg-violet-600   text-white ">
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Emergency Contact</th>
                <th>Emergency Phone</th>
            </tr>
            </thead>
            <tbody>

            @foreach($allmembers as $rider)
                <tr @if($rider->confirmed) class="text-slate-800 "  @else  class="text-red-500 "   @endif>
                    <td><a href="/club/member/detail/{{$rider->id}}">{{$rider->firstname}}&nbsp;{{$rider->lastname}}</a></td>
                    <td><a href="mailto:{{$rider->email}}">{{$rider->email}}</a></td>
                    <td>{{$rider->phone}}</td>
                    <td>{{$rider->emergency_contact}}</td>
                    <td>{{$rider->emergency_number}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class=" mt-4 mb-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <table class="w-full table-auto ">
            <thead>
            <tr class="font-bold w-full mt-4  pb-2 pl-2 pr-4 rounded-t-xl  text-white bg-violet-600">
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Emergency Contact</th>
                <th>Emergency Phone</th>
            </tr>
            </thead>
            <tbody>

            @foreach($riders as $rider)
                <tr @if($rider->confirmed) class="text-slate-800 "  @else  class="text-red-500 "   @endif>
                    <td><a href="/club/member/detail/{{$rider->id}}">{{$rider->firstname}}&nbsp;{{$rider->lastname}}</a></td>
                    <td><a href="mailto:{{$rider->email}}">{{$rider->email}}</a></td>
                    <td>{{$rider->phone}}</td>
                    <td>{{$rider->emergency_contact}}</td>
                    <td>{{$rider->emergency_number}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class=" mt-4 mb-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <table class="w-full table-auto ">
            <thead>
            <tr class="font-bold w-full mt-4  pb-2 pl-2 pr-4 rounded-t-xl  text-white bg-violet-600">
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Emergency Contact</th>
                <th>Emergency Phone</th>
            </tr>
            </thead>
            <tbody>

            @foreach($observers as $rider)
                <tr @if($rider->confirmed) class="text-slate-800 "  @else  class="text-red-500 "   @endif>
                <td><a href="/club/member/detail/{{$rider->id}}">{{$rider->firstname}}&nbsp;{{$rider->lastname}}</a></td>
                <td><a href="mailto:{{$rider->email}}">{{$rider->email}}</a></td>
                    <td>{{$rider->phone}}</td>
                    <td>{{$rider->emergency_contact}}</td>
                    <td>{{$rider->emergency_number}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class=" mt-4 mb-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <table class="w-full table-auto ">
            <thead>
            <tr class="font-bold w-full mt-4  pb-2 pl-2 pr-4 rounded-t-xl  text-white bg-violet-600">
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Emergency Contact</th>
                <th>Emergency Phone</th>
            </tr>
            </thead>
            <tbody>

            @foreach($lifers as $rider)
                <tr @if($rider->confirmed) class="text-slate-800 "  @else  class="text-red-500 "   @endif>
                    <td><a href="/club/member/detail/{{$rider->id}}">{{$rider->firstname}}&nbsp;{{$rider->lastname}}</a></td>
                    <td><a href="mailto:{{$rider->email}}">{{$rider->email}}</a></td>
                    <td><a>{{$rider->phone}}</a></td>
                    <td>{{$rider->emergency_contact}}</td>
                    <td><a>{{$rider->emergency_number}}</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-club>