<x-club>
    @php
        $maillist = $mailshot->distribution;
    @endphp
    <x-slot:heading>Send mail</x-slot:heading><div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
    <div class="flex justify-between font-bold w-full mt-4 pt-2 pb-2 pl-2 pr-4 rounded-t-xl  text-white bg-violet-600">
       Distribution
    </div>
        <div class="text-sm ml-4 mr-4">
            <div class="font-semibold text-violet-800 text-sm mt-2">
                This email will be sent out to the following addresses.
            </div>
            <div class="text-sm mt-2">
                {{$maillist}}
            </div>
            <div class="font-semibold text-violet-800 text-sm mt-2">
            Please confirm that you wish to send the email.
        </div>
        </div>
    </div>
        <form action="/usermail/send" method="POST">
            @csrf
            <input type="hidden" id="mail_id" name="mail_id" value="{{$mailshot->id}}">
        <div class="mt-4" id="buttons">
            <a href="{{ url()->previous() }}"
               class=" rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-violet-900 shadow-sm hover:bg-violet-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-900">Cancel</a>
            <button type="submit"
                    class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                Send
            </button>
        </div>
        </form>
</x-club>