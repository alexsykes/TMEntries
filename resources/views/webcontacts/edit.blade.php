<x-admin>
    <x-slot:heading>
        Contact from {{$webcontact->name}}
    </x-slot:heading>

    <div class="px-4 py-4 mt-0 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
        <div>Sent: {{$webcontact->created_at}}</div>
        <div>From: {{$webcontact->name}}</div>
        <div class="mt-2">{{$webcontact->message}}</div>
    </div>

    <div class="px-4 py-4 mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
        <form method="POST" action="/webcontact/update">
            @csrf
            @method('PATCH')
            <input type="hidden" name="id" value="{{$webcontact->id}}">

            <div id="responseDiv" class="mt-2 col-span-full">
                <x-form-field>
                    <x-form-label class="text-red-700" for="response">Response</x-form-label>
                    <div class="mt-2 ">
                                    <textarea class="w-full" name="response" type="text"
                                              id="response">{{$webcontact->response}}</textarea>
                    </div>
                    @error('response')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>
            </div>

            <div id="actionDiv" class="mt-2 col-span-full">
                <x-form-field>
                    <x-form-label class="text-red-700" for="action">Action</x-form-label>
                    <div class="mt-2 ">
                                    <textarea class="w-full" name="action" type="text"
                                              id="action">{{$webcontact->action}}</textarea>
                    </div>
                    @error('action')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>
            </div>
            <x-form-field>
                <x-form-label class="text-red-700" for="category">Category</x-form-label>
                <div class="flex mt-2 rounded-md shadow-sm ring-1 ring-inset outline outline-1 -outline-offset-1 drop-shadow-lg outline-red-700 focus-within:ring-2  focus-within:ring-inset focus-within:ring-red-600 sm:max-w-md">
                    <select class="border-0  pl-2 pt-2  bg-transparent pb-1 space-x-4 :focus border-0"
                            name="category" id="category">
                        @foreach($categories as $category)
                            <option value="{{$category}} "

                                @if($webcontact->category == $category)
                                    selected
                                @endif
                                >
                                {{$category}}
                            </option>


                        @endforeach

                    </select>
                </div>
                @error('category')
                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                @enderror
            </x-form-field>


            <div class="mt-2" id="sendResponseDiv">
                <x-form-field>
                    <div class="flex justify-normal col-span-3">
                        <div class="font-semibold text text-red-700">Send response</div>
                        <div class="pl-2">
                            <input name="sendResponse" type="checkbox" value="1"/>
                        </div>
                    </div>
                </x-form-field>
            </div>


            <div class="mt-2" id="markClosedDiv">
                <x-form-field>
                    <div class="flex justify-normal col-span-3">
                        <div class="font-semibold text text-red-700">Mark as closed</div>
                        <div class="pl-2">
                            <input name="closed" type="checkbox" value="1"
                                   @if($webcontact->closed)
                                       checked
                                    @endif
                            />
                        </div>
                    </div>
                </x-form-field>
            </div>

            <div class="mt-4" id="buttons">
                <a href="/webcontacts"
                   class="rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-red-900 shadow-sm hover:bg-red-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-900">Cancel</a>
                <button type="submit"
                        class="rounded-md ml-2 bg-red-600 px-3 py-1 text-sm font-light  border border-red-800 text-white drop-shadow-lg hover:bg-red-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-admin>