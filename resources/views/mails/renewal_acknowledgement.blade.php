<x-automail>
    <div class="space-y-2">
        <div>Dear {{$member->firstname}},</div>

        <div>Thank you for renewing your membership with {{$club->name}}. We hope that you will continue to enjoy
            your trials with us.
        </div>
        <div>You will also find attached a copy of the AMCA Trials Rule Book</div>
        <div class="space-y-0">
            <div>Kind Regards,</div>
            <div>{{$club->membershipSecretary}},</div>
            <div>{{$club->name}} membership secretary</div>
        </div>
    </div>
</x-automail>