<x-automail>
    <div class="">
        <div>Dear {{$webContact->name}},</div>
        <div>Thank you for your web contact made through TrialMonster.</div>

        <div>Your message was:</div>
        <p><i>{{$webContact->message}}</i></p>

        <div>Our response is:</div>
        <p><i>{{$webContact->response}}</i></p>



        <div>Kind Regards,</div>
        <div>TrialMonster Admin</div>
    </div>
</x-automail>