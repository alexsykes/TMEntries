<x-club>
    <x-slot:heading>Mails</x-slot:heading>

    <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="flex justify-between font-bold w-full mt-4 pt-2 pb-2 pl-2 pr-4 rounded-t-xl  text-white bg-violet-600">
            Mail list
        </div>
        <div>
            <div class="mt-2 mb-2 text-center font-semibold text-violet-700">Library emails are prepared emails which
                can be customised for club use. Editing one of these will create a copy which will then appear in your
                mail list.
            </div>
            <table class="w-full text-sm">
                @foreach($mails as $mail)
                    @php
                        if($mail->isLibrary) {
                            $type = "Library";
                        } else {
                            $type = "Mail";
                        }
                    @endphp
                    <tr class="flex-auto even:bg-white odd:bg-gray-50  border-b ">
                        <td class="table-cell pl-4 pt-1 pb-1">{{$type}}</td>
                        <td class="table-cell">{{$mail->category}}</td>
                        <td class="table-cell">{{$mail->subject}}</td>
                        <td class="table-cell">{{$mail->summary}}</td>
                        <td class="table-cell"><a href="/usermail/edit/{{$mail->id}}"><i class="fa-solid fa-pencil"></i></a>
                        </td>
                        <td class="table-cell"><a href="/usermail/preview/{{$mail->id}}"><i class="fa-solid fa-eye"></i></a>
                        </td>
                        <td class="table-cell"><a href="/usermail/sendMail/{{$mail->id}}"><i
                                        class="fa-solid fa-envelope"></i></a></td>
                        <td class="table-cell">
                            @if(!$mail->isLibrary)
                                <a href="/usermail/unpublish/{{$mail->id}}"><i
                                            class="fa-solid fa-trash text-red-600"></i></a>
                            @endif
                        </td>
                    </tr>

                @endforeach
            </table>
        </div>
    </div>
    <div class="mt-4" id="buttons">
        <a href="/usermail/add"
           class="rounded-md  ml-4 pt-2 pb-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
            Add a new mail
        </a>
    </div>

</x-club>