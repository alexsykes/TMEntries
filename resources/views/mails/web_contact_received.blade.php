<x-automail>
    <div class="">
        <div>Dear {{$webContact->name}},</div>
        <div>Thank you for your web contact made through TrialMonster. We will contact you once we are able to assist you.</div>

        <div>Your message was:</div>
        <p><i>{{$webContact->message}}</i></p>

        <div>Kind Regards,</div>
        <div>TrialMonster Admin</div>
    </div>
</x-automail>