<x-automail>
<p><b>We have received a request from your email address to add the entry detailed below for {{$trial->club}} trial on {{$trial->date}}. As the entry list for the trial is currently full, this entry has been placed on a waiting list.  If you did not submit this entry, please reply to this email immediately.</b></p>
<p>The details of the entry are:</p>
<table>
    <tr>
        <td>Ref: {{$entry->id}} </td>
        <td>{{$entry->name}}</td>
        <td>{{$entry->class}}</td>
        <td>{{$entry->course}}</td>
    </tr>
</table>
<p>You can cancel or make changes to your entry - <a href="{{config('app.url')}}/user/entries">click here</a></p>
<p><b>If a vacancy arises, you will receive an email notification and an invoice for your entry fee.</b></p>
<p>In case of any other enquiries, please reply to this email quoting the Entry Reference.</p>
<p><b>Thank you for entering with TrialMonster</b></p>
</x-automail>