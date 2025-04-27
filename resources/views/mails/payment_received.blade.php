


<p>Your Stripe payment has been processed and I am pleased to confirm that your entry or entries listed below are now confirmed.</p>
<table>
    @foreach ($entryData as $entry)
        <tr>
            <td>Ref: {{$entry->id}} </td>
            <td>{{$entry->name}}</td>
            <td>{{$entry->class}}</td>
            <td>{{$entry->course}}</td>
            <td><a href="{{config('app.url')}}/entry/useredit/?id={{$entry->id}}&token={{$entry->token}}">Make changes</a></td>
        </tr>
    @endforeach
</table>
<p>If you need to make any changes or withdraw from the event, please click on the link shown following the entry.</p>
<ul>
    <li>Changes may be made to the Course, Class or Bike details.</li>
    <li>No changes may be made to the rider.</li>
    <li>Changes and refunds may be requested until the day preceding the event. Refunds will be subject to an administration charge of £3</li>
</ul>
<p>You will receive an email acknowledgement following any change or request.</p>
<p>In case of any other enquiries, please reply to this email quoting the Entry Reference.</p>
<p><b>Thank you for entering with TrialMonster</b></p>