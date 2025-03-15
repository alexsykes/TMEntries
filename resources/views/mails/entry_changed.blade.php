<p>Your entry has now been updated and the current details are shown below.</p>
<table>
        <tr>
            <td>Ref: {{$entry->id}} </td>
            <td>{{$entry->name}}</td>
            <td>{{$entry->class}}</td>
            <td>{{$entry->course}}</td>
            <td>{{$entry->make}} &nbsp;{{$entry->size}}</td>
            <td><a href="{{config('app.url')}}/entry/useredit/?id={{$entry->id}}&token={{$newToken}}">Make changes</a></td>
        </tr>
</table>
<p>If you need to make any further changes or withdraw from the event, please click on the link shown following the entry.</p>
<ul>
    <li>Changes may be made to the Course, Class or Bike details.</li>
    <li>No changes may be made to the rider.</li>
    <li>Refunds may be requested until the day preceding the event and will be subject to an administration charge of £3</li>
</ul>
<p>You will receive an email acknowledgement following any further change or request.</p>
<p>In case of any other enquiries, please reply to this email quoting the Entry Reference.</p>
<p><b>Thank you for entering with TrialMonster</b></p>