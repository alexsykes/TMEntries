<x-error_page>
    <x-slot:heading>Oops! What happened here…</x-slot:heading>
    <div class=" w-full bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="p-4 text-center font-semibold text-gray-500">{{config('app.name')}} has crashed.
            Please call back later
        </div>
        <div class="flex justify-between p-4 text-center">
            <div></div>
            <div class="text-gray-500">
                <img src="{{URL::asset('images/crash.png')}}" alt="System error">
            </div>
            <div></div>
        </div>
        <div class="p-0 pt-0 text-center text-red-500"><a
                    href="mailto:monster@trialmonster.uk?subject=Web%20Enquiry">Urgent enquiries - please click here</a></div>
    </div>
</x-error_page>
