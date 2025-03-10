
<table>
    @foreach ($entryData as $entry)
        <tr>
            <td>Ref: {{$entry->id}} </td>
            <td>{{$entry->name}}</td>
            <td>{{$entry->class}}</td>
            <td>{{$entry->course}}</td>
            <td><a href="https://trialmonster.uk/entry/edit/id={{$entry->id}}&token={{$entry->token}}">Make changes</a></td>
        </tr>
    @endforeach
</table>

<p>In case of any enquiries, please reply to this email quoting the Entry Reference.<br><b>Thank you for entering with
        TrialMonster</b></p>