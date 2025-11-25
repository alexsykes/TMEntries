<x-main>
    <x-slot:heading>Thank you</x-slot:heading>
    <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
        <div class="flex justify-between font-bold w-full mt-4 pt-2 pb-2 pl-2 pr-4 rounded-t-xl  text-white bg-blue-600">
            Thank you for registering your membership
        </div>
        <div class="p-4 space-y-2 bg-slate-50">
            <div class="font-semibold text-lg text-center">What do I do next?</div>
            <div class="">If you are either an <span
                        class="font-semibold">Observer or Life Member</span> - you do not need to do anything further,
                as
                your membership is Free of Charge.
            </div>
            <div class=" ">If you are a <span class="font-semibold">Newcomer</span> to the club, you are
                invited to enjoy
                your first ride without becoming a club member. A membership fee should be paid, along with your entry
                fee, when paying for your second entry.
            </div>
            <div class=" "><span class="font-semibold">Renewals</span> - members who are renewing their
                membership should pay their membership along with payment for their first trial of the year.
            </div>
            <div class="font-semibold text-lg text-center">How to pay…</div>
            <div>When you enter your next trial on TrialMonster, you will see an additional item - <span
                        class="font-semibold">Add to your order</span> - at the Checkout. Click on the <span
                        class="font-semibold">+ Add</span> button and adjust the quantity to the number of memberships
                you wish to pay.
                <div class="flex justify-center items-center  pt-4"><img class="shadow-2xl"
                                                                         src="{{ asset('storage/images/checkout_2.png') }}">
                </div>
                <div class="flex pt-4 text-red-500 justify-center items-center font-semibold">Note - this is an image -
                    not a checkout link!
                </div>
                <div class="flex pt-4  justify-center items-center font-semibold">You will receive a confirmation email
                    when your membership has been processed.
                </div>
            </div>
        </div>
    </div>
</x-main>