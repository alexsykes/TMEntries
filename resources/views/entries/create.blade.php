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
                        <x-form-input name="text" type="text" id="make" :value="old('make')" placeholder="Bike make/model" required />
                        <x-form-error name="make" />
                    </div>
                </x-form-field>


                <div class="flex items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 outline-gray-300 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-violet-600">
                    <div class="pl-4 sm:col-span-2">
                        <x-form-label for="type">Type</x-form-label>
                        <select class="ml-2 bg-white pb-2 space-x-4 border-none" name="type" id="type">
                            <option value="2 Stroke">2 Stroke</option>
                            <option value="4 Stroke">4 Stroke</option>
                            <option value="e-Bike">e-Bike</option>
                        </select>
                    </div>
                </div>


                <x-form-field>
                    <x-form-label for="size">Capacity</x-form-label>
                    <div class="mt-2">
                        <x-form-input name="size" type="text" id="size" :value="old('size')" placeholder="Bike engine size - leave empty for e-Bike"  />
                        <x-form-error name="size" />
                    </div>
                </x-form-field>


                <div class="flex items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 outline-gray-300 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-violet-600">
                    <div class="pl-4 sm:col-span-2">
                        <x-form-label for="course">Course</x-form-label>
                        <select class="ml-2 bg-white pb-2 space-x-4 border-none" name="course" id="course">
                            @foreach($courses as $course)
                                <option value="{{$course}}">{{$course}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 outline-gray-300 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-violet-600">
                    <div class="pl-4 sm:col-span-2">
                        <label for="class" >Class</label>
                        <select class="ml-2 bg-white pb-2 space-x-4 border-none" name="class" id="class">
                            @foreach($classes as $class)
                                <option value="{{$class}}">{{$class}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div id="buttons">
            <x-form-button>Cancel</x-form-button>
            <x-form-button>Save</x-form-button>
        </div>
    </form>
</x-main>