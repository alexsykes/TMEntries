<x-main>
    <script>
        function toggle(checked) {
            var x = document.getElementById("dateInput");
            if (checked) {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script>
    <x-slot:heading>Entry for {{$trial->name}}
    </x-slot:heading>
    @php
        //    $trial from request
                $classes = explode(',',$trial->classlist);
                $courses = explode(',',$trial->courselist);
                $auth = array("ACU", "AMCA");
//    dd($trial);
    @endphp
    <form action="/entries/userdata" method="POST">
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
                        <x-form-label for="email">Confirm Email</x-form-label>
                        <div class="mt-2">
                            <x-form-input name="email_confirmation" type="email" id="email_confirmation" :value="old('email_confirmation')" placeholder="Contact email check" required />
                            <x-form-error name="email_confirmation" />
                        </div>

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
                </div>



                <div class="mt-4" id="buttons">
                    <a href="/"  class="rounded-md  bg-violet-100 px-3 py-1 text-sm font-light border border-violet-800 text-violet-800 drop-shadow-lg hover:bg-violet-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">Cancel</a>
                    <button type="submit" class="rounded-md ml-2 bg-violet-600 px-3 py-1 text-sm font-light  border border-violet-800 text-white drop-shadow-lg hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">Register</button>
                </div>
            </div>
        </div>
    </form>
</x-main>