<x-mail>
    <div class="space-y-2">
        <div class="font-semibold text-lg  text-red-500">Change of Status - {{$trial->name}}</div>
        <div class="">This email is to let you know that the entry limit for this trial has been reached. As you had an Unconfirmed Entry, this has now been changed to Reserve status.</div>
        <div>Please note that entries on TrialMonster are only confirmed when payment is received.</div>
        <div class="">Link to your entry - <span class="text-blue-700 font-semibold underline underline-offset-4"><a href="{{config('app.url')}}/user/entries">Click here</a></span></div>
        <div class=""><span class="font-semibold">Do I need to do anything now?</span> You do not need to respond to this email. If a withdrawal is received, reserve riders are allocated places in strict order of registration. Should we be able to offer you an entry, you will be contacted by email.</div>
        <div class="font-semibold text-lg text-blue-800">Thank you for entering with TrialMonster</div>
    </div>
</x-mail>