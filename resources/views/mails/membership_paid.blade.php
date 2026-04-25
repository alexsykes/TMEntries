<x-automail>
    <div class="">
        <div>Dear {{$name}}.</div>
        <div>Thank you for your membership payment received with your recent entry for one of our trials.</div>
        <div class=" text-red-500"><b>If you have not already done so, would you now complete the <span class="font-semibold">Club Membership form</span>
            with your current contact details. Simply
            <span class="font-semibold"><a href="{{config('app.url')}}/clubs/membershipForm/{{$club_id}}"> click here</a></span>.
            </b>
        </div>
        <div>Kind Regards,</div>
        <div>{{$clubName}}</div>
    </div>
</x-automail>