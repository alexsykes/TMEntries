<x-mail>
    <div class="space-y-2">
<div class="font-semibold text-lg  text-blue-800">You have an unconfirmed entry for {{$trial->name}}</div>
<div class="">We are writing to let you know that only five spaces remain before the entry limit for this trial is reached. Please note that entries on TrialMonster are not confirmed until payment is received. As the entry limit is approaching, we would advise that you act quickly.</div>
    <div class="">Link to your entry - <span class="text-blue-700 font-semibold underline underline-offset-4"><a href="{{config('app.url')}}/user/entries">Click here</a></span></div>
    <div class="font-semibold text-lg text-blue-800">Thank you for entering with TrialMonster</div>
    </div>
</x-mail>