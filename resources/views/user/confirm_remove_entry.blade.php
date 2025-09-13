<x-main>
    <?php
    $status = $entry->status;
//    dd($status);
    ?>
    <x-slot:heading>
        {{--        Entry for {{$entry->name}} at {{$entry->trial->name}}--}}
    </x-slot:heading>

    <div class="space-y-4">
        <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Entry withdrawal for {{$entry->name}}</div>
            <div class="p-4">
                Please confirm that you wish to withdraw from this event.
                @if($status == 1)
                Please note that a service charge of £3 will be deducted from your entry fee. The balance of your entry fee will be refunded to your account.
                    @endif
            </div>
        </div>
        <div class="flex pl-0 pr-0 justify-between" id="buttons">
            <div class="">
                <a href="/user/entries"
                   class="rounded-md bg-white px-3 py-2 text-sm font-light  text-blue-600 border border-blue-800 drop-shadow-xl hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>

            </div>
            <div>
                <a href="/user/withdraw/{{$entry->id}}"
                   class="rounded-md  bg-red-600 px-3 py-2 text-sm font-light  border border-white text-white drop-shadow-xl hover:bg-red-500 focus-visible:outline focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-red-600">
                    Confirm withdrawal
                </a>
            </div>
        </div>
    </div>
</x-main>