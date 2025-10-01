
<x-automail>
    <div class="space-y-2">
<div>Your Stripe payment has been processed and I am pleased to confirm that your entry or entries listed below are now confirmed.<br><b>Please remember to sign-in on arrival at the event.</b></div>
<table>
    @foreach ($entryData as $entry)
        @php
            $dateFormatted = date_format(date_create($entry->date), "M jS");
        @endphp
        <tr>
            <td><b>{{$dateFormatted}} - {{$entry->trial}}:</b></td>
            <td>{{$entry->name}}</td>
            <td>{{$entry->class}}</td>
            <td>{{$entry->course}}</td>
            <td>{{$entry->make}} &nbsp;{{$entry->size}}</td>
            <td>Entry Ref: {{$entry->id}} </td>
        </tr>
        <tr>
            <td colspan="6"><span class="text-blue-700 font-semibold underline underline-offset-4"><a href="{{config('app.url')}}/entry/useredit/?id={{$entry->id}}&token={{$entry->token}}">Click here to change the entry above</a></span></td></tr>
    @endforeach
</table>
<div>If you need to make any changes or withdraw from the event, please click on the link shown following the entry.</div>
<ul>
    <li>Changes may be made to the Course, Class or Bike details.</li>
    <li>No changes may be made to the rider.</li>
    <li>Changes and refunds may be requested until the day preceding the event. Refunds will be subject to an administration charge of £3</li>
</ul>
<div>You will receive an email acknowledgement following any change or request.</div>
<div>In case of any other enquiries, please reply to this email quoting the Entry Reference.</div>
<div></div><b>Thank you for entering with TrialMonster</b></div>
    </div>
</x-automail>