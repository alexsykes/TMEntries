<x-automail>
    @php

        $array = json_decode((string) $webContact->message);
        $message = $array[1];
        $type = $array[0];
 @endphp


    <div class="">
        <p>Type: <i>{{$type}}</i></p>
        <p>Message: <i>{{$message}}</i></p>
        <p>Respond</p>

        <p>Go to message: <a href="{{config('app.url')}}/webcontact/{{$webContact->id}}/edit/{{$webContact->token}}">Click
                here</a><br>Mark as Spam: <a
                    href="{{config('app.url')}}/webcontact/{{$webContact->id}}/spam/{{$webContact->token}}">Click
                here</a></p>

        <div>Kind Regards,</div>
        <div>TrialMonster Admin</div>
    </div>
</x-automail>