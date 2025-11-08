<x-automail>
    @php
        $timestamp =  date_create("2013-01-03 15:00:00");
       $day =   date_format($timestamp, 'l');
       $time =   date_format($timestamp, 'ga');

    @endphp
    <div class="space-y-2">
        <div class="font-semibold text-lg  text-blue-800">You have an unconfirmed entry for {{$trial->name}}</div>
        <div class="">Please note that entries on TrialMonster are only confirmed when payment is received.</div>
        <div class="">As the closing date for entries is tomorrow, {{$day}}, at {{$time}}, we would advise
            that you act quickly to avoid losing your place.
        </div>
        <div class="">Link to your entry - <span class="text-blue-700 font-semibold underline underline-offset-4"><a
                        href="{{config('app.url')}}/user/entries">Click here</a></span></div>
        <div class="font-semibold text-lg text-blue-800">Thank you for entering with TrialMonster</div>
    </div>
</x-automail>