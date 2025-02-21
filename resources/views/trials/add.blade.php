<x-main>
    <x-slot:heading>
        Create a new trial
    </x-slot:heading>
    @php


    @endphp
    <form action="/trials/store" method="POST">

        @csrf
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <div class="px-4 py-4 mt-6 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                        <x-form-field>
                            <x-form-label for="name">Name</x-form-label>
                            <div class="mt-2 col-span-2">
                                <x-form-input name="name" type="text" id="name"
                                              placeholder="Name" required/>
                                <x-form-error name="name"/>
                            </div>
                            @error('name')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </x-form-field>

                        <x-form-field>
                            <x-form-label for="club">Club</x-form-label>
                            <div class="mt-2 col-span-2">
                                <x-form-input name="club" type="text" id="club"
                                              placeholder="Club name" required/>
                                <x-form-error name="club"/>
                            </div>
                            @error('club')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </x-form-field>

                        <x-form-field>
                            <x-form-label for="date">Date</x-form-label>
                            <div class="mt-2 col-span-2">
                                <x-form-input name="date" type="date" min="{{date('Y-m-d')}}" id="date"  required/>
                                <x-form-error name="date"/>
                            </div>
                            @error('date')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </x-form-field>

                        <x-form-field>
                            <x-form-label for="courselist">Courses</x-form-label>
                            <div class="mt-2 col-span-2">
                                <x-form-input name="courselist" type="text" id="courselist"
                                              placeholder="List of classes" required/>
                                <x-form-error name="courselist"/>
                            </div>
                            @error('courselist')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </x-form-field>

                        <x-form-field>
                            <x-form-label for="classlist">Classes</x-form-label>
                            <div class="mt-2 col-span-2">
                                <x-form-input name="classlist" type="text" id="classlist"
                                              placeholder="List of classes" required/>
                                <x-form-error name="classlist"/>
                            </div>
                            @error('classlist')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </x-form-field>


                    </div>
                </div>

                <div class="mt-4" id="buttons">
                    <a href="/adminTrials"
                       class="rounded-md bg-white px-3 py-2 text-sm  text-blue-600 shadow-sm hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>

                    <button type="submit"
                            class="rounded-md ml-2 bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                        Save
                    </button>

{{--                    <button type="submit"--}}
{{--                            class="rounded-md ml-2 bg-green-600 px-3 py-1 text-sm font-light  border border-green-800 text-white drop-shadow-lg hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">--}}
{{--                        Save as template--}}
{{--                    </button>--}}
                </div>
{{--                <div class="text-sm  font-semibold  mt-2 text-green-500">Save as template creates a Trial template </div>--}}
            </div>
        </div>
    </form>
</x-main>