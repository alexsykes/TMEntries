<x-club>
    <x-slot:heading>
        Information
    </x-slot:heading>

    @php
        $statusArray = array('Awaiting payment', 'Confirmed Entry','Awaiting Refund', 'Refunded', 'Reserve - awaiting payment', 'Reserve', 'Removed by admin', 'Manual Entry - to pay', 'Manual Entry - paid', 'Manual Entry - FoC' );

//        dd($entries);
    @endphp
    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="  w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Online entries</div>
        @foreach($purchases as $purchase)
@php
            $price = $purchase->stripe_price / 100;
            $name = $purchase->product_name;
            $quantity = $purchase->purchases;
            $total = $price * $quantity;

            @endphp
        <div class="flex justify-between text-sm w-full pl-4 pr-4">
           <div  class="table-cell min-w-10 text-left">{{$name}}</div>
           <div  class="table-cell min-w-10 text-left">{{$price}}</div>
           <div  class="table-cell w-40 text-end">{{$quantity}}</div>
           <div  class="table-cell w-40 text-end">£{{$total}}</div>
        </div>
{{--            <div>£{{$purchase->purchases * $purchase->purchases}}</div>--}}
        @endforeach
    </div>
    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="  w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Entry list</div>
        @foreach($entries as $entry)
            @php
            $entryStatus = $entry->status;
                $status = $statusArray[$entryStatus];
//                dd($status);
            @endphp
            <div class="flex justify-between text-sm w-full pl-4 pr-4">
                <div  class="table-cell w-1/4">{{$entry->name}}</div>
                <div  class="table-cell w-1/4">{{$entry->course}}</div>
                <div  class="table-cell w-1/4">{{$entry->class}}</div>
                <div  class="table-cell w-1/4 text-end">{{$status}}</div>
            </div>
        @endforeach
    </div>
</x-club>