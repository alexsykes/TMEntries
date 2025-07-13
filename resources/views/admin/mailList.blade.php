<x-admin>
    <x-slot:heading>Mails</x-slot:heading>

    <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="flex justify-between  w-full pt-2 pb-2 pl-4 pr-2 rounded-t-xl  text-white bg-red-600">

            <div class="font-bold">Mail List</div>
        </div>

        <table class=" w-full text-sm">
            <tr class="pr-4 odd:bg-white  even:bg-gray-50  border-b ">
                <td class="pl-2 table-cell">Subject</td>
                <td class=" pl-2 table-cell">Summary</td>
                <td class=" pl-2 table-cell">&nbsp;</td>
                <td class=" pl-2 table-cell">&nbsp;</td>
            </tr>
            @foreach($mails as $mail)
                <tr class="pr-4 odd:bg-white  even:bg-gray-50  border-b ">
                    <td class="pl-2 table-cell">{{$mail->subject}}</td>
                    <td class=" table-cell">{{$mail->summary}}</td>
                    <td class="table-cell"><a href="/mail/edit/{{$mail->id}}"><span><i class="fa-solid fa-pencil"></i></span></a></td>
                    <td class="table-cell"><a href="/mail/preview/{{$mail->id}}"><span><i class="fa-solid fa-eye"></i></span></a></td>
                </tr>

            @endforeach
        </table>
    </div>
    <div class="mt-4" id="buttons">

        <a href="/mail/add"
           class="rounded-md  bg-red-600 px-3 py-2 text-sm font-light  border border-red-800 text-white drop-shadow-lg hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
            Add a new mail
        </a>
    </div>

</x-admin>