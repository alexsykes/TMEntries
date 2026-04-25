<x-main>
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
    <x-slot:heading>
        Contact Us
    </x-slot:heading>
{{--<div class="text-blue-800 text-7xl">Contact Us</div>--}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="w-full  lg:max-w-4xl max-w-[335px]">
    <form method="POST" name="contactForm" id="contactForm" action="{{ route('contact.submit') }}">
        @csrf
        <div>
            <label class="mt-2 text-blue-700" for="name">Name:</label><br>
            <input class="p-1 rounded-md    border-blue-700 border bg-white" type="text" id="name" name="name" required><br>
        </div>
        <div class="mt-2">
            <label class=" text-blue-700" for="email">Email:</label><br>
            <input class="p-1 rounded-md border-blue-700 border bg-white" type="email" id="email" name="email" required><br>
        </div>
        <div class="mt-2">
            <label  class="text-blue-700" for="message">Message:</label><br>
            <textarea class="p-1 w-full rounded-md bg-white border border-blue-700" rows="5"  id="message" name="message"
                      placeholder="30 characters minimum"></textarea><br>
        </div>
        {{--    <button type="submit" class="mt-2 rounded-md border border-blue-950 bg-blue-800 px-3 py-1 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Send</button>--}}
        <button class="g-recaptcha btn btn-primary btn-lg mt-2 rounded-md border border-blue-950 bg-blue-800 px-3 py-1 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                data-sitekey="{{ config('services.recaptcha_v3.siteKey') }}"
                data-callback="onSubmit"
                data-action="submitContact">Send</button>
    </form>
</div>
</x-main>