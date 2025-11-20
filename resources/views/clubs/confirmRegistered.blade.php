<x-main>
    <x-slot:heading>Thank you</x-slot:heading>
    <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
        <div class="flex justify-between font-bold w-full mt-4 pt-2 pb-2 pl-2 pr-4 rounded-t-xl  text-white bg-blue-600">
            Thank you for registering your membership
        </div>
        <div class=" pt-2 pl-2 pr-2 pb-2"><span class="font-semibold">Observer and Life Members</span> - as your
            membership is Free of Charge, you do not need to do anything further.
        </div>
        <div class="  pl-2 pr-2 pb-2"><span class="font-semibold">Newcomers</span> to the club are invited to enjoy
            their first ride without becoming a club member. A membership fee should be paid, along with their entry
            fee, for any subsequent entries.
        </div>
        <div class="  pl-2 pr-2 pb-2"><span class="font-semibold">Renewals</span> - members who are renewing their
            membership should pay their membership along with their payment for their first trial of the year.
        </div>
        <div><img src="{{ asset('storage/images/checkout.png') }}"></div>
        <div class="pl-2 pr-2 pb-2">
            Payment should be made by clicking <span class="font-semibold">Add to your order</span> and, if necessary,
            adjusting the number of Club
            Memberships to be paid.
        </div>
    </div>
</x-main>