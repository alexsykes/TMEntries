<x-main>
    <x-slot:heading>
        New Entry for {{$trial->name}}
    </x-slot:heading>
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
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                <x-form-field>
                    <x-form-label for="email">Email</x-form-label>
                    <div class="mt-2">
                        <x-form-input name="email" type="email" id="email" :value="old('email')" placeholder="Contact email" required />
                        <x-form-error name="email" />
                    </div>
                    @error('email')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror

                </x-form-field>

                <x-form-field>
                    <x-form-label for="phone">Phone</x-form-label>
                    <div class="mt-2">
                        <x-form-input name="phone" type="text" id="phone" :value="old('phone')" placeholder="Contact phone" required />
                        <x-form-error name="phone" />
                    </div>
                    @error('phone')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="name">Name</x-form-label>
                    <div class="mt-2">
                        <x-form-input name="name" type="text" id="name" :value="old('name')" placeholder="Rider's name" required />
                        <x-form-error name="name" />
                    </div>
                    @error('name')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
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

                    <div class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 outline-gray-300 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-violet-600">
                        <div class="pb-2 pt-2    sm:col-span-2">
                            <select class="ml-2 bg-white  space-x-4 border-none" name="type" id="type" required>
                                <option value="">Select your engine type</option>
                                <option value="two-stroke" selected>Two stroke</option>
                                <option value="four-stroke">Four stroke</option>
                                <option value="e-bike">e-Bike</option>
                            </select>
                        </div>
                    </div>
                </x-form-field>


                <x-form-field>
                    <x-form-label class="pb-2" for="course" >Course</x-form-label>

                    <div class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 outline-gray-300 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-violet-600">
                        <div class="pb-2 pt-2    sm:col-span-2">
                            <select class="ml-2 bg-white  space-x-4 border-none" name="course" id="course" required>
                                <option value="">Select your course</option>
                                @foreach($courses as $course)
                                    <option value="{{$course}}">{{$course}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </x-form-field>

                <x-form-field>
                    <x-form-label class="pb-2" for="class" >Class</x-form-label>

                    <div class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 outline-gray-300 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-violet-600">
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



        <div class="mt-4" id="buttons">
            <a href="/"  class="rounded-md  bg-violet-100 px-3 py-1 text-sm font-light border border-violet-800 text-violet-800 drop-shadow-lg hover:bg-violet-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">Cancel</a>
            <button type="submit" class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">Register</button>
        </div>
        </div>
        </div>
    </form>
</x-main>