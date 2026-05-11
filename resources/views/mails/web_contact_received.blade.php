<x-automail>
    @php

        $array = json_decode((string) $webContact->message);
        $message = $array[1];
    @endphp
    <div class="">
        <div>Dear {{$webContact->name}},</div>
        <div>Thank you for your web contact made through TrialMonster. We will contact you once we are able to assist you.</div>

        <div>Your message was:</div>
        <p><i>{{$message}}</i></p>

        <div>Kind Regards,</div>
        <div>TrialMonster Admin</div>
    </div>
</x-automail>