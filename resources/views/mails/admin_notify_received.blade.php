<x-automail>
    <div class="">
        <div>Your message was:</div>
        <p>Message: <i>{{$webContact->message}}</i></p>
        <p>Respond</p>

        <p>Go to message: <a href="{{config('app.url')}}/webcontact/{{$webContact->id}}/edit/{{$webContact->token}}">Click
                here</a><br>Mark as Spam: <a
                    href="{{config('app.url')}}/webcontact/{{$webContact->id}}/spam/{{$webContact->token}}">Click
                here</a></p>

        <div>Kind Regards,</div>
        <div>TrialMonster Admin</div>
    </div>
</x-automail>