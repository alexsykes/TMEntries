<x-club>
    <x-slot:heading>Mails</x-slot:heading>
    <div class="tab pl-8">
        @php
            $categories = array_keys($mailData);

            $defaultOpen = $categories[0].'Tab';

            $cookie_name = 'selectedTab';
            $selectedTab = 'alphaTab';
            if(isset($_COOKIE[$cookie_name])) {
                $defaultOpen = $_COOKIE[$cookie_name]. "Tab";
            }

                for($i=0 ; $i<sizeof($categories); $i++) {

                    $category = $categories[$i];
                    $mails = $mailData[$category];
        @endphp
        {{--        Start of tab row --}}
        <button class="tablinks border border-black border-b-0 rounded-t-lg   hover:bg-violet-500 hover:text-white p-1"
                id="{{$category."Tab"}}"
                onclick="openSection(event, '{{$category}}')">
            {{$category}}
        </button>

        @php } @endphp
    </div>
    <div>
        @php
            $categories = array_keys($mailData);
                for($i=0 ; $i<sizeof($categories); $i++) {

                    $category = $categories[$i];
                    $mails = $mailData[$category];
        @endphp


        <div id="{{$category}}" class="tabcontent pt-0 ">
            <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
                <div>
                    <div class="flex justify-between font-bold w-full mt-0 pt-2 pb-2 pl-2 pr-4 rounded-t-xl  text-white bg-violet-600">{{$category}}</div>
                    <table class="overflow-y-auto w-full text-sm">
                        @foreach($mails as $mail)
                            <tr class="flex-auto even:bg-white odd:bg-gray-50  border-b ">
                                <td class="pl-2 table-cell"><a
                                            href="/usermail/edit/{{$mail->id}}">{{$mail->subject}}</a></td>
                                <td class="table-cell"><a href="/usermail/edit/{{$mail->id}}">{{$mail->summary}}</a>
                                </td>
                                {{--                        <td class="table-cell"><a href="/usermail/edit/{{$mail->id}}"><i--}}
                                {{--                                        class="fa-solid fa-pencil"></i></a>--}}
                                {{--                        </td>--}}
                                <td class="table-cell"><a href="/usermail/preview/{{$mail->id}}"><i
                                                class="fa-solid fa-eye"></i></a>
                                </td>
                                <td class="table-cell"><a href="/usermail/sendMail/{{$mail->id}}"><i
                                                class="fa-solid fa-envelope"></i></a></td>
                                <td class="table-cell">
                                    <a href="/usermail/unpublish/{{$mail->id}}"><i
                                                class="fa-solid fa-trash text-red-600"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        @php } @endphp

        {{--            <table class="w-full text-sm">--}}
        {{--                @foreach($mails as $mail)--}}

        {{--                    <tr class="flex-auto even:bg-white odd:bg-gray-50  border-b ">--}}
        {{--                        <td class="table-cell">{{$mail->subject}}</td>--}}
        {{--                        <td class="table-cell">{{$mail->summary}}</td>--}}
        {{--                        <td class="table-cell"><a href="/usermail/edit/{{$mail->id}}"><i class="fa-solid fa-pencil"></i></a>--}}
        {{--                        </td>--}}
        {{--                        <td class="table-cell"><a href="/usermail/preview/{{$mail->id}}"><i class="fa-solid fa-eye"></i></a>--}}
        {{--                        </td>--}}
        {{--                        <td class="table-cell"><a href="/usermail/sendMail/{{$mail->id}}"><i--}}
        {{--                                        class="fa-solid fa-envelope"></i></a></td>--}}
        {{--                        <td class="table-cell">--}}
        {{--                                <a href="/usermail/unpublish/{{$mail->id}}"><i--}}
        {{--                                            class="fa-solid fa-trash text-red-600"></i></a>--}}

        {{--                        </td>--}}
        {{--                    </tr>--}}

        {{--                @endforeach--}}
        {{--            </table>--}}
    </div>


    <div class="mt-4" id="buttons">
        <a href="/usermail/add"
           class="rounded-md  ml-4 pt-2 pb-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
            Add a new mail
        </a>
    </div>
    <script>
        document.getElementById('{{$defaultOpen}}').click();
    </script>
</x-club>