<x-automail>
    <div class="space-y-2">
        <div>A payment has been made which includes items other than entry fees.</div>
        <div>The rider(s) associated with the payment are: <span class="font-semibold">{{$riders}}</span>.</div>
        <div>The payments were for the following items:</div>
        @php
        echo $msg;
        @endphp
    </div>
</x-automail>