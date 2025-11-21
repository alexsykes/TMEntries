<x-automail>
    <div class="space-y-2">
        <div>Dear {{$member->firstname}},</div>

        <div>Thank you for your membership payment. We hope that you will enjoy your trials with us.</div>

        <div>Your YCMCC membership number is {{$member->id}}</div>
        <div>You will also find attached a copy of the club rules and the AMCA Trials Rule Book</div>
        <div class="space-y-0">
            <div>Kind Regards,</div>
            <div>Amanda Newhouse,</div>
            <div>YCMCC membership secretary</div>
        </div>
    </div>
</x-automail>