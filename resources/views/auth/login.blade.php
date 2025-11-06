<x-guest-layout>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script>
        function onSubmit(token) {
            document.getElementById("loginForm").submit();
        }
    </script>
    <style>
        .grecaptcha-badge {
            width: 70px !important;
            overflow: hidden !important;
            transition: all 0.3s ease !important;
            left: 4px !important;
        }

        .grecaptcha-badge:hover {
            width: 256px !important;
        }

    </style>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')"/>

    <div class="mt-4 font-semibold">Login</div>


    <form id="loginForm" method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label class="mt-4" for="email" :value="__('Email')"/>
            <x-text-input id="email" class="border block mt-1 w-full" type="email" name="email" :value="old('email')"
                          required autofocus autocomplete="username"/>
            <x-input-error :messages="$errors->get('email')" class="mt-2"/>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')"/>

            <x-text-input id="password" class="border block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="current-password"/>

            <x-input-error :messages="$errors->get('password')" class="mt-2"/>
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                   href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            {{--            <x-primary-button class="ms-3">--}}
            {{--                {{ __('Log in') }}--}}
            {{--            </x-primary-button>--}}
        </div>
        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="g-recaptcha btn btn-primary btn-lg ms-4"
                              data-sitekey="{{ config('services.recaptcha_v3.siteKey') }}"
                              data-callback="onSubmit"
                              data-action="login">Submit
            </x-primary-button>
        </div>
    </form>
    <div class="mt-4 font-semibold">New User?</div>
    <div>If you wish to enter a trial, you will need to create an account using the link below.</div>
    <div class="mt-4"><a
                class="underline  text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('register') }}">
            {{ __('No account? Register now') }}
        </a></div>


    <div class="mt-4 font-semibold">Login difficulties?</div>
    <div>If you need help logging in, please drop us an email. <a class="underline"
                                                                  href="mailto:monster@trialmonster.uk?subject=Login difficulties">Click
            here</a></div>
</x-guest-layout>
