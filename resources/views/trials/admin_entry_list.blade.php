<x-club>
    <x-slot:heading>
        {{$trial->name}}
    </x-slot:heading>

    <div class="mx-auto max-w-7xl px-4  sm:px-6 lg:px-8">
    @php
        $statusOptions = array(    'Unconfirmed', 'Confirmed', 'Withdrawn - paid awaiting refund', 'Refunded', 'Accepted - awaiting payment', 'Reserve', 'Removed', 'Manual entry - to pay', 'Manual entry - paid', 'Manual entry - FoC');
        $manualStatusOptions = array(  'Manual entry - to pay', 'Manual entry - paid', 'Manual entry - FoC');
        $classes = explode(',',$trial->classlist);
        $courses = explode(',',$trial->courselist);
        $authority = $trial->authority;
        $types = array("2 stroke", "4 stroke", "e-bike");
    @endphp
    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="flex justify-between font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">
            <div>Entry list</div>
            <div class="flex space-x-4">
                <div><a href="/admin/entries/editRidingNumbers/{{$trial->id}}">Edit Riding Numbers</a></div>
                {{--            <div><a href="/admin/entries/addManualEntries">Add Entries</a></div>--}}
                <div><a href="/admin/entries/printSignOnSheets/{{$trial->id}}">Signing-on Sheets</a></div>
            </div>
        </div>
        <div class="pl-4">
            <table class="w-full">
                @foreach($entries as $entry)
                    <tr>
                        <td class="text-right pr-2 w-12">{{$entry->ridingNumber}}</td>
                        <td>{{$entry->name}}</td>
                        <td>{{$entry->class}}</td>
                        <td>{{$entry->course}}</td>
                        <td>{{$statusOptions[$entry->status]}}</td>
                        <td><a href="/admin/entry/edit/{{$entry->id}}"><span><i class="fa-solid fa-gear"></i></span></a>
                        </td>
                        <td><a href="/admin/entry/cancel/{{$entry->id}}"><span><i
                                            class="fa-solid fa-ban"></i></span></a></td>
                    </tr>
                @endforeach
            </table>
        </div>
        {{--    <a href="{{ route('stripe.index') }}" class="btn mt-5 bg">Continue Shopping</a>--}}
    </div>

    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class=" font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">New Entry</div>

        <form action="/admin/entries/store" method="POST">
            <input type="hidden" id="trialID" name="trialID" value="{{$trial->id}}">
            @csrf

                <div class="mt-2 px-4 py-2 pb-4 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                    <x-form-field>
                        <x-form-label class="pb-2" for="class">Status</x-form-label>

                        <div class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 drop-shadow-lg outline-blue-700 ">
                            <div class="pb-2 pt-2 bg-white sm:col-span-2">
                                <select class="ml-2  bg-white  space-x-4 border-none" name="status" id="status"
                                        required>
                                    @foreach($manualStatusOptions as $key => $status)
                                        <option value="{{$key}}" >{{$status}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </x-form-field>

                    <x-form-field>
                        <x-form-label for="name">Name</x-form-label>
                        <div class="mt-2 ">
                            <x-form-input class="" name="name" type="text" id="name" :value="old('name')"
                                          placeholder="Rider's name" required/>
                            <x-form-error name="name"/>
                        </div>
                        @error('name')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </x-form-field>

                    <x-form-field>
                        <x-form-label for="licence">{{$authority}} Licence</x-form-label>
                        <div class="mt-2 col-span-2">
                            <x-form-input name="licence" type="text" id="licence" :value="old('licence')"
                                          placeholder="Licence number - leave blank if no licence"/>
                            <x-form-error name="licence"/>
                        </div>
                    </x-form-field>

                    <x-form-field>
                        <x-form-label for="isYouth">Under-18</x-form-label>
                        <div class="ml-2 mt-2 col-span-full">
                            <input type="checkbox" name="isYouth" id="isYouth" :value="1" class="isYouth"/>
                            <x-form-error name="isYouth"/>
                        </div>
                    </x-form-field>

                    <div id="dateInput" class=" col-span-full">
                        <x-form-field>
                            <x-form-label for="dob">Date of Birth</x-form-label>
                            <div class="mt-2  max-w-40 col-span-full">
                                <x-form-input type="date" name="dob" id="dob" :value="old('dob')"/>
                            </div>
                        </x-form-field>
                    </div>

                    <x-form-field>
                        <x-form-label for="make">Make</x-form-label>
                        <div class="mt-2 col-span-2">
                            <x-form-input name="make" type="text" id="make" :value="old('make')"
                                          placeholder="Bike make/model" required/>
                            <x-form-error name="make"/>
                        </div>
                        @error('make')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </x-form-field>

                    <x-form-field>
                        <x-form-label for="size">Capacity</x-form-label>
                        <div class="mt-2">
                            <x-form-input name="size" type="text" id="size" :value="old('size')"
                                          placeholder="Bike engine size - leave empty for e-Bike"/>
                            <x-form-error name="size"/>
                        </div>
                    </x-form-field>

                    <x-form-field>
                        <x-form-label class="pb-2" for="course">Type</x-form-label>

                        <div class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 drop-shadow-lg outline-violet-700 ">
                            <div class="pb-2 pt-2    sm:col-span-2">
                                <select class="ml-2 bg-white  space-x-4 border-none" name="type" id="type" required>
                                    @foreach($types as $type)
                                        <option value="{{$type}}">{{$type}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </x-form-field>

                    <x-form-field>

                        <x-form-label class="pb-2" for="course">Course</x-form-label>
                        <div class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 drop-shadow-lg outline-violet-700 ">
                            <div class="pb-2 pt-2    sm:col-span-2">
                                <select class="ml-2 bg-white  space-x-4 border-none" name="course" id="course" required>
                                    @foreach($courses as $course)
                                        <option value="{{$course}}">{{$course}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </x-form-field>

                    <x-form-field>
                        <x-form-label class="pb-2" for="class">Class</x-form-label>

                        <div class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 drop-shadow-lg outline-violet-700 ">
                            <div class="pb-2 pt-2 bg-white sm:col-span-2">
                                <select class="ml-2  bg-white  space-x-4 border-none" name="class" id="class" required>
                                    @foreach($classes as $class)
                                        <option value="{{$class}}">{{$class}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </x-form-field>
                </div>

                <div class="mt-2 pl-2 mb-2" id="buttons">

                    <button type="submit"
                            class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">
                        Register
                    </button>
                </div>

        </form>
    </div>
    </div>
</x-club>