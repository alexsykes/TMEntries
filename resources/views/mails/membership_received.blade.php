<x-automail>
    <div class="">
        <div>Membership payment has been received for the following rider.</div>
        @php

                echo "<div class=\"pl-4\">$data </div>";

        @endphp
        <div>Memberships can be confirmed by clicking on the link - <a href="https://trialmonster.uk/club/member/approve">https://trialmonster.uk/club/member/approve</a></div>
    </div>
</x-automail>