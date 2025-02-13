<x-main>
    @php
        //    $trial from request
                $classes = explode(',',$trial->classlist);
                $courses = explode(',',$trial->courselist);
                $auth = array("ACU", "AMCA");
//    dd($trial);
    @endphp
    <form action="/entries/store" method="POST">
        @csrf
        <input type="hidden" id="trial_id" name="trial_id" value="{{$trial->id}}"/>
        <div class="font-bold  font-size-sm text-violet-600">Entry form for {{$trial->name}}</div>
        <div class="space-y-12">
            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                <x-form-field>
                    <x-form-label for="email">Email</x-form-label>
                    <div class="mt-2">
                        <x-form-input name="email" type="email" id="email" :value="old('email')" placeholder="Contact email" required />
                        <x-form-error name="email" />
                    </div>
                </x-form-field>

                <x-form-field>
                    <x-form-label for="phone">Phone</x-form-label>
                    <div class="mt-2">
                        <x-form-input name="phone" type="text" id="phone" :value="old('phone')" placeholder="Contact phone" required />
                        <x-form-error name="phone" />
                    </div>
                </x-form-field>

                <x-form-field>
                    <x-form-label for="name">Name</x-form-label>
                    <div class="mt-2">
                        <x-form-input name="name" type="text" id="name" :value="old('name')" placeholder="Rider's name" required />
                        <x-form-error name="name" />
                    </div>
                </x-form-field>


                <x-form-field>
                    <x-form-label for="licence">{{$auth[$trial->authority]}} Licence</x-form-label>
                    <div class="mt-2">
                        <x-form-input name="licence" type="text" id="licence" :value="old('licence')" placeholder="Licence number - leave blank if no licence"   />
                        <x-form-error name="licence" />
                    </div>
                </x-form-field>

                <x-form-field>
                    <x-form-label for="isYouth">Under-18</x-form-label>
                    <div class="mt-2">
                        <input type="checkbox" name="isYouth" id="isYouth" :value="1"   />
                        <x-form-error name="isYouth" />
                    </div>
                </x-form-field>

                <x-form-field>
                    <x-form-label for="isYouth">Date of Birth</x-form-label>
                    <div class="mt-2">
                        <x-form-input type="date" name="dob" id="dob" :value="old('dob')"   />
                        <x-form-error name="dob" />
                    </div>
                </x-form-field>

                <x-form-field>
                    <x-form-label for="make">Make</x-form-label>
                    <div class="mt-2">
                        <x-form-input name="make" type="text" id="make" :value="old('make')" placeholder="Bike make/model" required />
                        <x-form-error name="make" />
                    </div>
                </x-form-field>

                <x-form-field>
                    <x-form-label for="size">Capacity</x-form-label>
                    <div class="mt-2">
                        <x-form-input name="size" type="text" id="size" :value="old('size')" placeholder="Bike engine size - leave empty for e-Bike"  />
                        <x-form-error name="size" />
                    </div>
                </x-form-field>

                <x-form-field>
                    <fieldset>
                        <legend class="text-m font-semibold text-gray-900">Engine Type</legend>
                        <div class="mt-2 space-y-6">
                            <div class="flex ">
                                <input id="type" name="type" type="radio"  class="block text-sm/6  ml-4  checked:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                <x-form-label for="type" class="ml-2 mr-2 text-sm/6">2 Stroke</x-form-label>

                                <input id="type" name="type" type="radio"  class="block text-sm/6  ml-4 checked:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                <x-form-label for="type" class="ml-2 mr-2 text-sm/6">4 Stroke</x-form-label>

                                <input id="type" name="type" type="radio"  class="block text-sm/6  ml-4 checked:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                <x-form-label for="type" class=" block text-sm/6  ml-2 mr-2 checked:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">e-Bike</x-form-label>
                            </div>
                        </div>
                    </fieldset>
                </x-form-field>



                <x-form-field>
                    <fieldset>
                        <legend class="text-m font-semibold text-gray-900">Course</legend>
                        <div>
                            <div class="mt-2 space-y-6">
                                <div class="flex ">
                                    @foreach($courses as $course)
                                        <input id="2T" name="course" type="radio"  class="ml-4 mr-2 checked:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                        <label for="2T" class=" block text-sm/6  font-medium text-gray-900">{{$course}}</label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </fieldset></x-form-field>


                <x-form-field>
                    <fieldset>
                        <legend class="text-m font-semibold text-gray-900">Class</legend>
                        <div>
                            <div class="mt-2 space-y-6">
                                <div class="flex ">
                                    @foreach($classes as $class)
                                        <input id="class" name="class" type="radio"   class="ml-4 mr-2 checked:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                        <label for="class" class=" block text-sm/6 font-medium text-gray-900">{{$class}}</label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </fieldset></x-form-field>

        </div>

        <div class="mt-4" id="buttons">
            <a href="/"  class="rounded-md  bg-violet-100 px-3 py-1 text-sm font-light border border-violet-800 text-violet-800 drop-shadow-lg hover:bg-violet-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">Cancel</a>
            <button type="submit" class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">Register</button>
        </div>
        </div>
    </form>
</x-main>