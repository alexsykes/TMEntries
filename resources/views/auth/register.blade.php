<x-guest-layout>    <script src="https://www.google.com/recaptcha/api.js"></script>

    <script>
        function onSubmit(token) {
            document.getElementById("registerForm").submit();
        }
    </script>
    <form id="registerForm" method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="font-semibold">Motor sport can be dangerous. Please remember to check the box below to confirm your acceptance of our Terms of Use. </div>
        <div class="pt-2">You should receive an email - check your Junk mail - wth a link to verify your email address. Once your email has been verified, you will be able to register your entry for trials.</div>
        <div class="pt-2 pb-2 font-semibold">Difficulties registering? Please let us know - <a href="mailto:monster@trialmonster.uk?Subject=Registration">click here</a> </div>
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex mt-4">
            <x-input-label class="font-semibold" for="agree" :value="__('I have read and understood the Terms and Conditions detailed on this website - see link below.')" />
            <x-text-input id="agree" class="block mt-1"
                          type="checkbox"
                          name="agree" required /></div>
<div>
            <x-input-error :messages="$errors->get('agree')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="g-recaptcha btn btn-primary btn-lg ms-4"
                    data-sitekey="{{ config('services.recaptcha_v3.siteKey') }}"
                    data-callback="onSubmit"
                    data-action="registerUser">Submit</x-primary-button>
{{--            <x-primary-button class="ms-4">--}}
{{--                {{ __('Register') }}--}}
{{--            </x-primary-button>--}}
        </div>
        <div>The Small Print - click <a href="{{$disclaimerUrl}}">here</a></div>
    </form>
</x-guest-layout>
