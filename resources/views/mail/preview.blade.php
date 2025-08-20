<x-club>
    <x-slot:heading>Mail Preview</x-slot:heading>
    <div class=" bg-gray-100 border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">{{$mail->summary}}</div>


        <div class="text-sm bg-white p-2  border-solid border border-gray-300 m-4"><span class="font-bold">From: </span>monster@trialmonster.uk</div>
        <div class="text-sm bg-white p-2  border-solid border border-gray-300 m-4"><span class="font-bold">Attachment: </span>{{$mail->originalName}}</div>
            <div class="text-sm bg-white p-2  border-solid border border-gray-300 m-4"><span class="font-bold">Subject: </span>{{$mail->subject}}</div>
            <div class="text-sm bg-white p-2  border-solid border border-gray-300 m-4">@php
            echo $mail->bodyText;
            @endphp</div>
    </div>
    <div id="buttons" class="py-2 mt-2">
        <a href="/club/mails"
           class=" rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-violet-700 shadow-sm hover:bg-violet-700 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-700">Cancel</a>
        <a href="/usermail/sendMail/{{$mail->id}}"
           class=" ml-2 rounded-md bg-violet-700 px-3 py-2 text-sm  drop-shadow-lg text-white shadow-sm hover:bg-violet-700 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-700">Add Addresses</a>
    </div>
</x-club>