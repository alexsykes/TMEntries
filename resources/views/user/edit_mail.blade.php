<x-club>
    {{--    @dump($mail)--}}
    @php
        $mimeTypes = explode(',', $mail->mimeType);
        $fileNames = explode(',', $mail->fileName);
        $originalNames = explode(',', $mail->originalName);
//
//        dump($fileNames, $originalNames, $mimeTypes);

    @endphp
    <script>
        function removeAttachment() {
            document.getElementById('attachDiv').style.display = "none";
            document.getElementById('hasAttachment').value = false;
        }
    </script>
    <x-slot:heading>Edit email</x-slot:heading>
    @php
        //        $categoryArray = array("Trial Announcement", "Result Published", "General Announcement", 'Other');
                $categoryArray = array('AGM','Committee Meetings','Trials','Social Events ','Other');
                $addressToArray = array("Test", "Entry List", "Unconfirmed Entries", "Previous Entrants", "Other");
                if($mail->originalName == "") { $hasAttachment = false; } else { $hasAttachment = true; }
    @endphp
    <form action="/usermail/update" method="POST" enctype="multipart/form-data">
        {{--        @method('PATCH')--}}
        @csrf
        <input type="hidden" id="mail_id" name="mail_id" value="{{$mail->id}}">
        <input type="hidden" id="hasAttachment" name="hasAttachment" value="{{$hasAttachment}}">


        <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">

            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">{{$mail->summary}}</div>

            <div class="space-y-4 px-4">

                <x-form-field>
                    <x-form-label for="category">Category</x-form-label>
                    <div class="mt-2">
                        @foreach($categoryArray as $option)
                            <input name="category" type="radio" id="category" required
                                   value="{{$option}}"
                                    {{ ($mail->category == $option) ? ' checked' : '' }}
                            >
                            <label class="pl-1 pr-4" for="category">{{$option}}</label>
                        @endforeach
                    </div>
                    @error('category')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                @if($hasAttachment)
                    <div id="attachmentsDiv" class=" mt-2">
                        <div class="font-semibold text-blue-700 mt-2">Attachments</div>
                        @for($i = 0; $i < sizeof($fileNames); $i++)
                            <div class="flex">
                                <div class="pl-2">{{$originalNames[$i]}}</div>
                                <div class="pl-4"><input
                                            type="checkbox" name="fileToRemove[]"
                                            id="fileToRemove$i"
                                            value="{{$i}}">
                                    <label
                                            class="font-semibold text-red-600"
                                            for="fileToRemove$i">Remove</label>
                                </div>
                            </div>
                        @endfor
                    </div>
                @endif

                <div id="attachDiv" class="  mt-2">
                    <x-form-field>
                        <x-form-label for="fileToAdd">Add Attachment - PDF only</x-form-label>
                        <div class="mt-2 ">
                            <input name="fileToAdd" accept="application/pdf" type="file" id="fileToAdd" value=""/>
                        </div>
                        @error('fileToAdd')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </x-form-field>
                    {{--                @endif--}}
                </div>

                <x-form-field>
                    <x-form-label for="summary">Summary - brief description of email message</x-form-label>
                    <div class="mt-2 ">
                        <x-form-input name="summary" type="text" id="summary" value="{{$mail->summary}}"
                                      placeholder="eg. Membership reminder" required/>
                        <x-form-error name="summary"/>
                    </div>
                    @error('summary')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="subject">Subject</x-form-label>
                    <div class="mt-2 ">
                        <x-form-input name="subject" type="text" id="subject" value="{{$mail->subject}}"
                                      placeholder="eg. Final Instructions" required/>
                        <x-form-error name="subject"/>
                    </div>
                    @error('subject')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="reply_to_name">Reply to (name)</x-form-label>
                    <div class="mt-2 ">
                        <x-form-input name="reply_to_name" type="text" id="reply_to_name"
                                      value="{{$mail->reply_to_name}}"
                                      placeholder="Optional"/>
                        <x-form-error name="reply_to_name"/>
                    </div>
                    @error('reply_to_name')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="reply_to_address">Reply to (address)</x-form-label>
                    <div class="mt-2 ">
                        <x-form-input name="reply_to_address" type="email" id="reply_to_address"
                                      value="{{$mail->reply_to_address}}"
                                      placeholder="Optional"/>
                        <x-form-error name="reply_to_address"/>
                    </div>
                    @error('reply_to_address')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="bodyText">Email body</x-form-label>
                    <div class="mt-2 ">
                        <textarea class="withEditor" name="bodyText" type="text" id="bodyText">
                            {{$mail->bodyText}}
                        </textarea>
                    </div>
                    @error('description')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>
            </div>
        </div>
        <div id="buttons" class="py-2">
            <a href="/club/mails"
               class=" rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-violet-900 shadow-sm hover:bg-violet-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-900">Cancel</a>

            <button type="submit" value="update" name="action"
                    class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                Update
            </button>

        </div>
    </form>

</x-club>