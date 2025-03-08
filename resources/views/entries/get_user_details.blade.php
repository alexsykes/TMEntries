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
    <x-slot:heading>Contact details
    </x-slot:heading>
    @php
//    dd($trial);
    @endphp
    <form action="/entries/userdata" method="POST">
        @csrf
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                    <x-form-field>
                        <x-form-label for="email">Email</x-form-label>
                        <div class="mt-2">
                            <x-form-input name="email" type="email" id="email" value="{{$sessionEmail}}" placeholder="Contact email" required />
                            <x-form-error name="email" />
                        </div>
                        @error('email')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror

                    </x-form-field>

                    <x-form-field>
                        <x-form-label for="email">Confirm Email</x-form-label>
                        <div class="mt-2">
                            <x-form-input name="email_confirmation" type="email" id="email_confirmation" value="{{$sessionEmail}}" placeholder="Contact email check" required />
                            <x-form-error name="email_confirmation" />
                        </div>

                    </x-form-field>

                    <x-form-field>
                        <x-form-label for="phone">Phone</x-form-label>
                        <div class="mt-2">
                            <x-form-input name="phone" type="text" id="phone" value="{{$sessionPhone}}" placeholder="Contact phone" required />
                            <x-form-error name="phone" />
                        </div>
                        @error('phone')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </x-form-field>


                    <x-form-field>
                        <x-form-label for="phone">Motorsport can be Dangerous</x-form-label>
                        <div class="mt-2">
                            <div class="text-sm">
                                By checking the box I confirm that I agree to the Terms and Conditions listed on this website and will comply with the Rules and Regulations of the organising body.
                            </div>
                            <input name="accept" type="checkbox" id="accept" value="1" placeholder="Contact accept" required />
                            <x-form-error name="accept" />
                        </div>
                        @error('')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </x-form-field>
                </div>



                <div class="mt-4" id="buttons">
                    <a href="/"  class="rounded-md  bg-white px-3 py-1 text-sm font-light border border-blue-800 text-blue-800 drop-shadow-lg hover:bg-blue-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Cancel</a>
                    <button type="submit" class="rounded-md ml-2 bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Next…</button>
                </div>
            </div>
        </div>
    </form>
</x-main>