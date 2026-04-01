<x-automail>
    <div class="">
        <div>Membership payments have been received for the following rider(s).</div>
        @php
            foreach($data as $item) {
                echo "<div class=\"pl-4\">$item </div>";
            }
        @endphp
    </div>
</x-automail>