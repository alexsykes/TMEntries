<x-automail>    <div class="mt-2 font-semibold text-lg text-blue-800">Act now to secure your entry.</div>
    <div class="font-semibold">This offer is valid for 48 hours and will lapse at the end of that time.</div>
    <div>We are pleased to inform you that we are now able to offer an entry for the <b>{{$entryData['trialClub'] }} {{$entryData['trialName']}}</b>  on <b>{{$entryData['date']}}</b> as detailed below.</div>
    <div class="mt-2 font-semibold">EntryID: {{$entryData['entryID']}} Name: {{$entryData['rider']}} - Class: {{$entryData['class']}} - Course: {{$entryData['course']}}</div>
    <div class="mt-2 font-semibold text-lg text-blue-800">What do I need to do?</div>
    <div>You will receive an email with an invoice from Stripe which links to their payment website. Once you have paid this invoice, your entry will be confirmed</div>
    <div class="mt-2 font-semibold text-lg text-blue-800">I haven't received an invoice!</div>
    <div>The invoice may be in your Junk mail. Payment can also be made using this direct link - <a class="font-semibold underline" href="{{$entryData['url']}}">click here</a></div>
    <div>If you have any other queries, please Reply-to this email.</div>
    <div class="mt-2 font-semibold text-lg text-blue-800">Thank you for using TrialMonster</div>
</x-automail>
