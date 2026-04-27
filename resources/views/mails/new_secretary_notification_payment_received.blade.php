<x-automail>
    <div class="">
        <div>A payment has been made which includes items other than entry fees.</div>
        <div>The rider(s) associated with the payment are: <span class="font-semibold"></span>.</div>
        <div>The payments were for the following items:</div>
        @php
        foreach($data as $item) {
            echo "<div class=\"pl-4\">$item </div>";
        }
         @endphp
    </div>
</x-automail>