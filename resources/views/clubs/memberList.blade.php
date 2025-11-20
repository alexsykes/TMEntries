<x-club>
    <x-slot:heading>Membership List for {{$clubName->name}}</x-slot:heading>
    <div id="tabButtons" class="tab pl-4">
        <button class="tablinks border border-black border-b-0 rounded-t-lg   hover:bg-violet-500 p-1 active"
                id="profileTab"
                onclick="openSection(event, 'allMembers')">
            All
        </button>

        <button class="tablinks border border-black border-b-0 rounded-t-lg   hover:bg-violet-500 p-1"
                id="competitionsTab"
                onclick="openSection(event, 'riders')">
            Riders
        </button>

        <button class="tablinks border border-black border-b-0 rounded-t-lg    hover:bg-violet-500 p-1"
                id="mailinglistTab"
                onclick="openSection(event, 'observers')">Observers
        </button>

        <button class="tablinks border border-black border-b-0 rounded-t-lg    hover:bg-violet-500 p-1"
                id="mailinglistTab"
                onclick="openSection(event, 'lifers')">Life members
        </button>
    </div>

    <div id="allMembers" style=";" class="tabcontent pt-0">
        <div class="mx-auto max-w-7xl sm: lg:">
            <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 ">

                <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">All members
                </div>
                <table class="w-full">
                    <tbody>
                    <tr class="font-bold text-violet-700">
                        <th>Name</th>
                        <th class="max-sm:hidden">Email</th>
                        <th class="max-sm:hidden">Phone</th>
                        <th>Emergency Contact</th>
                        <th>Emergency Phone</th>
                    </tr>
                    @foreach($allmembers as $rider)
                        <tr @if($rider->confirmed) class="text-slate-800 " @else  class="text-red-500 " @endif>
                            <td class="pl-2"><a href="/club/member/detail/{{$rider->id}}">{{$rider->firstname}}
                                    &nbsp;{{$rider->lastname}}</a></td>
                            <td class="max-sm:hidden"><a href="mailto:{{$rider->email}}">{{$rider->email}}</a></td>
                            <td class="max-sm:hidden">{{$rider->phone}}</td>
                            <td>{{$rider->emergency_contact}}</td>
                            <td>{{$rider->emergency_number}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="riders" style="display: none;" class="tabcontent pt-0">
        <div class="mx-auto max-w-7xl sm: lg:">
            <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 ">

                <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Riding members
                </div>
                <table class="w-full">
                    <tbody>
                    <tr class="font-bold text-violet-700">
                        <th>Name</th>
                        <th class="max-sm:hidden">Email</th>
                        <th class="max-sm:hidden">Phone</th>
                        <th>Emergency Contact</th>
                        <th>Emergency Phone</th>
                    </tr>
                    @foreach($riders as $rider)
                        <tr @if($rider->confirmed) class="text-slate-800 " @else  class="text-red-500 " @endif>
                            <td class="pl-2"><a href="/club/member/detail/{{$rider->id}}">{{$rider->firstname}}
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
            </div>
        </div>
    </div>

    <div id="observers" style="display: none;" class="tabcontent pt-0">
        <div class="mx-auto max-w-7xl sm: lg:">
            <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 ">

                <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Observers
                </div>
                <table class="w-full">
                    <tbody>
                    <tr class="font-bold text-violet-700">
                        <th>Name</th>
                        <th class="max-sm:hidden">Email</th>
                        <th class="max-sm:hidden">Phone</th>
                        <th>Emergency Contact</th>
                        <th>Emergency Phone</th>
                    </tr>
                    @foreach($observers as $rider)
                        <tr @if($rider->confirmed) class="text-slate-800 " @else  class="text-red-500 " @endif>
                            <td class="pl-2"><a href="/club/member/detail/{{$rider->id}}">{{$rider->firstname}}
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
            </div>
        </div>
    </div>

    <div id="lifers" style="display: none;" class="tabcontent pt-0">
        <div class="mx-auto max-w-7xl sm: lg:">
            <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 ">

                <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Life members
                </div>
                <table class="w-full">
                    <tbody>
                    <tr class="font-bold text-violet-700">
                        <th>Name</th>
                        <th class="max-sm:hidden">Email</th>
                        <th class="max-sm:hidden">Phone</th>
                        <th>Emergency Contact</th>
                        <th>Emergency Phone</th>
                    </tr>
                    @foreach($lifers as $rider)
                        <tr @if($rider->confirmed) class="text-slate-800 " @else  class="text-red-500 " @endif>
                            <td class="pl-2"><a href="/club/member/detail/{{$rider->id}}">{{$rider->firstname}}
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
            </div>
        </div>
    </div>

    <div class="sm:hidden">Rotate phone to landscape view for full member details</div>
</x-club>