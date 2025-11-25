<x-club>
    <x-slot:heading>{{$club->name}}</x-slot:heading>
    <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Members awaiting
            approval
        </div>
        @php
            $paid = explode(',', $paidNames->confirmed_list);
        //    dd($paid)
        @endphp
        @if(sizeof($membersToApprove) > 0)
            <div class="table w-full">
                <form action="/club/member/approve" method="POST">
                    @csrf

                    <div class="p-4 table-header-group font-semibold text-violet-800">
                        <div class="pl-2 table-cell text-center">Name</div>
                        <div class="pl-2 table-cell text-center">Phone</div>
                        <div class="pl-2 table-cell text-center max-sm:hidden">Email</div>
                        <div class="pl-2 table-cell text-center ">Category</div>
                        <div class="p-2 table-cell text-center ">Approve</div>
                    </div>
                    @foreach($membersToApprove as $member)
                        <div class="p-2 w-full table-row">
                            <div class="pl-2 table-cell">{{$member->firstname}} {{$member->lastname}}</div>
                            <div class="pl-2 table-cell">{{$member->phone}}</div>
                            <div class="pl-2 table-cell max-sm:hidden">{{$member->email}}</div>
                            <div class="pl-2 table-cell ">{{ucfirst($member->membership_category)}}</div>
                            <div class="p-2 table-cell text-center"><input type="checkbox" name="approved[]"
                                                                           value="{{$member->id}}"></div>
                        </div>
                    @endforeach

                    <div class="p-4">
                        <div class="font-semibold text-violet-700">Payments have been received from…</div>
                        <div class=" sm:columns-5">
                        @foreach($paid as $name)
                            <div class="text-sm">{{$name}}</div>
                        @endforeach
                        </div>
                    </div>
                    <div id="buttons" class="py-2 pl-2">
                        {{--                    <button class="rounded-md ml-2 bg-white px-3 py-1 text-sm font-light  border border-violet-800  drop-shadow-lg text-violet-900 shadow-sm hover:bg-violet-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-900"--}}
                        {{--                            onclick="history.back()">Cancel--}}
                        {{--                    </button>--}}
                        <button type="submit"
                                class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                            Approve Selected
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="p-2 w-full text-violet-800 font-semibold text-center">There are no members currently awaiting
                approval
            </div>
        @endif
    </div>
</x-club>