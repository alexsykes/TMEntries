<x-automail>
    <div class="space-y-2">
        <div>Your Stripe payment has been processed and I am pleased to confirm that your entry or entries listed below
            are now confirmed.<br><b>Please remember to sign-in on arrival at the event.</b></div>

        @foreach ($entryData as $entry)
            <div class="box-content box-border">
            @php
                $dateFormatted = date_format(date_create($entry->date), "M jS");
            @endphp
            <div>{{$dateFormatted}} - {{$entry->trial}}</div>
            <div class="font-semibold">Entry Ref: {{$entry->id}} </div>
            <div class="font-semibold">Name: {{$entry->name}}</div>
            <div class="font-semibold">Class: {{$entry->class}}</div>
            <div class="font-semibold">Course: {{$entry->course}}</div>
            <div class="font-semibold">Machine: {{$entry->make}} &nbsp;{{$entry->size}}</div>

            <div><span class="text-blue-700 font-semibold underline underline-offset-4"><a
                            href="{{config('app.url')}}/entry/useredit/?id={{$entry->id}}&token={{$entry->token}}">Click here to change the entry above</a></span>
            </div>
            </div>
        @endforeach
        <div>If you need to make any changes or withdraw from the event, please click on the link shown following the
            entry.
        </div>
        <ul>
            <li class="list-disc list-inside m-2">Changes may be made to the Course, Class or Bike details.</li>
            <li class="list-disc list-inside m-2">No changes may be made to the rider.</li>
            <li class="list-disc list-inside m-2">Changes and refunds may be requested until the day preceding the
                event.
            </li>
            <li class="list-disc list-inside m-2">Refunds will be subject to an administration charge of £3</li>
        </ul>
        <div>You will receive an email acknowledgement following any change or request.</div>
        <div>In case of any other enquiries, please reply to this email quoting the Entry Reference.</div>
        <div></div>
        <b>Thank you for entering with TrialMonster</b></div>
    </div>
</x-automail>