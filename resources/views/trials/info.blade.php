<x-club>
    <x-slot:heading>
        Information
    </x-slot:heading>

    @php
        $statusArray = array('Awaiting payment', 'Confirmed Entry','Awaiting Refund', 'Refunded', 'Reserve - awaiting payment', 'Reserve', 'Removed by admin', 'Manual Entry - to pay', 'Manual Entry - paid', 'Manual Entry - FoC' );
$numSalesItems = sizeof($sales);
    @endphp

    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="  w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  font-semibold text-white bg-violet-600">{{$venue->name}}</div>
            <div class="sm: flex justify-between text-sm w-full pl-4 pr-4 pt-2">
                <div class="sm: table-cell w-1/2"><b>Landowner:</b> {{$venue->landowner}}</div>
                <div class="sm: table-cell text-right w-1/2"><b>Phone:</b> {{$venue->phone}}</div>
            </div>

        <div class="flex justify-between text-sm w-full pl-4 pr-4 pt-2">
            <div class="sm: table-cell w-1/2"><b>Address:</b> {{$venue->address}}</div>
            <div class="sm: table-cell text-right w-1/2"><b>Postcode:</b> {{$venue->postcode}}</div>
        </div>

        <div class="flex justify-between text-sm w-full pl-4 pr-4 pt-2">
            <div class="sm: table-cell w-full">@php echo "<b>Directions:</b> ".$venue->directions; @endphp</div>
        </div>

        <div class="flex justify-between text-sm w-full pl-4 pr-4 pt-2">
            <div class="sm: table-cell w-full">@php echo "<b>What3Words:</b> ".$venue->w3w; @endphp</div>
        </div>
    </div>



    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="  w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  font-semibold text-white bg-violet-600">Online entries</div>
        @foreach($entryfees as $purchase)
            @php
                $price = $purchase->stripe_price / 100;
                $name = $purchase->product_name;
                $quantity = $purchase->purchases;
                $total = $price * $quantity;

            @endphp
            <div class="flex justify-between text-sm w-full pl-4 pr-4">
                <div class="table-cell min-w-10 text-left">{{$name}}</div>
                <div class="table-cell min-w-10 text-left">£{{$price}}</div>
                <div class="table-cell w-40 text-end">{{$quantity}}</div>
                <div class="table-cell w-40 text-end">£{{$total}}</div>
            </div>
            {{--            <div>£{{$purchase->purchases * $purchase->purchases}}</div>--}}
        @endforeach
    </div>


    @if($numSalesItems > 0)
    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="  w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  font-semibold text-white bg-violet-600">Options</div>
        @for($i = 0; $i < $numSalesItems; $i++)
            @php
            $sale = $sales[$i];
            $product = $products[$i];
////                $price = $purchase->stripe_price / 100;
                $name = $product->product_name;
                $description = $product->stripe_product_description;
                $quantity = $sale->quantity;

            @endphp
            <div class="flex justify-between text-sm w-full pl-4 pr-4">
                <div class="table-cell min-w-10 text-left">{{$name}}</div>
                <div class="table-cell min-w-10 text-left">{{$description}}</div>
                <div class="table-cell w-40 text-end">{{$quantity}}</div>
            </div>
        @endfor
    </div>
    @endif
    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="  w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  font-semibold text-white bg-violet-600">Entry list</div>
        @foreach($entries as $entry)
            @php
                $entryStatus = $entry->status;
                    $status = $statusArray[$entryStatus];
    //                dd($status);
            @endphp
            <div class="flex justify-between text-sm w-full pl-4 pr-4">
                <div class="table-cell w-1/4">{{$entry->name}}</div>
                <div class="table-cell w-1/4">{{$entry->course}}</div>
                <div class="table-cell w-1/4">{{$entry->class}}</div>
                <div class="table-cell w-1/4 text-end">{{$status}}</div>
            </div>
        @endforeach
    </div>
</x-club>