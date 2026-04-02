<x-automail>
    <div class="">
        <div>Membership payments have been received for the following rider(s).</div>
        @php
            foreach($data as $item) {
                echo "<div class=\"pl-4\">$item </div>";
            }
        @endphp
        <div>Memberships can be confirmed by clicking on the link - <a href="https://trialmonster.uk/club/member/approve">https://trialmonster.uk/club/member/approve</a></div>
    </div>
</x-automail>