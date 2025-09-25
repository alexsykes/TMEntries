<x-mail>
<div class="font-semibold text-lg text-blue-700">A vacancy has arisen</div>
    <div>We are pleased to inform you that we are now able to offer an entry for the <b>{{$entryData['trialClub'] }} {{$entryData['trialName']}}</b>  on <b>{{$entryData['date']}}</b> as detailed below.</div>
    <div class="mt-2 font-semibold">Entrant name: {{$entryData['rider']}} - Class: {{$entryData['class']}} - Course: {{$entryData['course']}}</div>
    <div class="mt-2 font-semibold text-lg text-blue-700">What do I need to do?</div>
    <div>You will receive an email with an invoice from Stripe which expires after three days. Once you have paid this invoice, your entry will be confirmed</div>
    <div class="mt-2 font-semibold text-lg text-blue-700">Thank you for using TrialMonster</div>

</x-mail>
