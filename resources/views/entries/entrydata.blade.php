<x-main>
    <script>
        function toggle(checked) {
            var x = document.getElementById("dateInput");
            if (checked) {
                x.style.display = "inline-block";
            } else {
                x.style.display = "none";
            }
        }
    </script>
    @php
    $email = session('email');
    $phone = session('phone');
    $trial_id = session('trial_id');

        $classes = explode(',',$trial->classlist);
        $courses = explode(',',$trial->courselist);
        $authority = $trial->authority;
        $types = array("2 stroke", "4 stroke", "e-bike");
$entryIDs = array();

    @endphp
    <x-slot:heading>
        Registrations
    </x-slot:heading>
    <div>Contact email: {{$email}}</div>
    <div>Contact phone: {{$phone}}</div>
    @if(sizeof($entries) > 0)
        <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Registration for {{$trial->name}} </div>
            <table class="w-full ml-4 mr-4">
                <tr>
                    <th class="">Ref</th>
                    <th class="pl-2">Name</th>
                    <th class="pl-2 hidden sm:table-cell">Course</th>
                    <th class="pl-2 hidden sm:table-cell">Class</th>

                    <th class="pl-2 hidden md:table-cell">Bike</th>
                    <th class="pl-2"></th>
                    <th class="pl-2"></th>
                </tr>
                @foreach($entries as $entry)
                    @php
                        $entryID = $entry->id ;
                        array_push($entryIDs, $entryID);
                    @endphp
                    <tr>
                        <td class="">{{$entryID}}</td>
                        <td class="pl-2">{{$entry->name}}</td>
                        <td class="pl-2 hidden sm:table-cell">{{$entry->course}}</td>
                        <td class="pl-2 hidden sm:table-cell">{{$entry->class}}</td>
                        <td class="pl-2 hidden md:table-cell">{{$entry->make}} {{$entry->size}}</td>
                        <td class="pl-2"><a href="edit/{{$entryID}}"><i class="fa-solid fa-pen-to-square"></i></a></td>
                        <td class="pl-2"><a href="delete/{{$entryID}}"><i class="fa-solid fa-ban text-orange-700"></i></a></td>
                    </tr>
                @endforeach
                @php
                    $entryIDstring = implode(',', $entryIDs)
                @endphp
            </table>
        </div>

        <form action="checkout" method="post">
            @csrf
            <button type="submit" class="mt-4 rounded-md  bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Checkout</button>
            <input type="hidden" id="entryID[]" name = "entryID[]" value="{{$entryIDstring}}">

        </form>
    @endif



    <form action="/entries/store" method="POST">
        @csrf
        <input type="hidden" id="trial_id" name="trial_id" value="{{$trial_id}}">
        <input type="hidden" id="email" name="email" value="{{$email}}">
        <input type="hidden" id="phone" name="phone" value="{{$phone}}">
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <div class=" mt-6 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                    <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Add an Entry </div>

                    <div class="mt-2 px-2 py-2 pb-4 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                        <x-form-field>
                            <x-form-label for="name">Name</x-form-label>
                            <div class="mt-2 ">
                                <x-form-input class="" name="name" type="text" id="name" :value="old('name')" placeholder="Rider's name" required />
                                <x-form-error name="name" />
                            </div>
                            @error('name')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </x-form-field>

                        <x-form-field>
                            <x-form-label for="licence">{{$authority}} Licence</x-form-label>
                            <div class="mt-2 col-span-2">
                                <x-form-input name="licence" type="text" id="licence" :value="old('licence')" placeholder="Licence number - leave blank if no licence"   />
                                <x-form-error name="licence" />
                            </div>
                        </x-form-field>

                        <x-form-field>
                            <x-form-label for="isYouth">Under-18</x-form-label>
                            <div class="ml-2 mt-2 col-span-full">
                                <input type="checkbox" name="isYouth" id="isYouth" :value="1" class="isYouth" onchange="toggle(checked)" />
                                <x-form-error name="isYouth" />
                            </div>
                        </x-form-field>

                        <div id="dateInput" class="hidden col-span-full">
                            <x-form-field >
                                <x-form-label for="dob">Date of Birth</x-form-label>
                                <div class="mt-2  max-w-40 col-span-full">
                                    <x-form-input type="date" name="dob" id="dob" :value="old('dob')"   />
                                </div>
                            </x-form-field>
                        </div>

                        <x-form-field>
                            <x-form-label for="make">Make</x-form-label>
                            <div class="mt-2 col-span-2">
                                <x-form-input name="make" type="text" id="make" :value="old('make')" placeholder="Bike make/model" required />
                                <x-form-error name="make" />
                            </div>
                            @error('make')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </x-form-field>

                        <x-form-field>
                            <x-form-label for="size">Capacity</x-form-label>
                            <div class="mt-2">
                                <x-form-input name="size" type="text" id="size" :value="old('size')" placeholder="Bike engine size - leave empty for e-Bike"  />
                                <x-form-error name="size" />
                            </div>
                        </x-form-field>

                        <x-form-field>
                            <x-form-label class="pb-2" for="course" >Type</x-form-label>

                            <div class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 drop-shadow-lg outline-blue-700 ">
                                <div class="pb-2 pt-2    sm:col-span-2">
                                    <select class="ml-2 bg-white  space-x-4 border-none" name="type" id="type" required>
                                        <option value="">Select your engine type</option>
                                        @foreach($types as $type)
                                            <option value="{{$type}}">{{$type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </x-form-field>

                        <x-form-field>

                            <x-form-label class="pb-2" for="course" >Course</x-form-label>
                            <div class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 drop-shadow-lg outline-blue-700 ">
                                <div class="pb-2 pt-2    sm:col-span-2">
                                    <select class="ml-2 bg-white  space-x-4 border-none" name="course" id="course" required>
                                        <option value="">Select your course</option>
                                        @foreach($courses as $course)
                                            <option value="{{$course}}" >{{$course}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </x-form-field>

                        <x-form-field>
                            <x-form-label class="pb-2" for="class" >Class</x-form-label>

                            <div class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 drop-shadow-lg outline-blue-700 ">
                                <div class="pb-2 pt-2 bg-white sm:col-span-2">
                                    <select class="ml-2  bg-white  space-x-4 border-none" name="class" id="class" required>
                                        <option value="">Select your class</option>
                                        @foreach($classes as $class)
                                            <option value="{{$class}}">{{$class}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </x-form-field>
                    </div>
                </div>


                <div class="mt-4" id="buttons">
                    <a href="/"  class="rounded-md bg-white px-3 py-2 text-sm  text-blue-600 shadow-sm hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>
                    <button type="submit" class="rounded-md ml-2 bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Register</button>
                </div>
            </div>
        </div>
    </form>
</x-main>