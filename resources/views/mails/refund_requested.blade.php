<x-automail>
    @if($reason == 'user_request')
    <p><b>We have received a request from your email address to withdraw the entry detailed below. If you did not request this refund, please reply to this email immediately.</b></p>
        <p>Your entry withdrawal has been processed and your request has been forwarded to Stripe for a refund.</p>
    @elseif($reason == 'cancellation')
        <p><b>Due to weather conditions, we have reluctantly been forced to cancel this event. A full refund will be made to your account</b></p>
    @endif
<table>
    <tr>
        <td>Ref: {{$entry->id}} </td>
        <td>{{$entry->name}}</td>
        <td>{{$entry->class}}</td>
        <td>{{$entry->course}}</td>
    </tr>
</table>

<p>You will receive an email acknowledgement following completion of the refund.</p>
<p>In case of any other enquiries, please reply to this email quoting the Entry Reference.</p>
<p><b>Thank you for entering with TrialMonster</b></p>
</x-automail>