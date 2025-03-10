<x-main>
    <x-slot:heading>
        Payment confirmed
    </x-slot:heading>
<div class="container text-center mt-5 pt-5">
    <h1 class="text-center">Thank you for making payment</h1>
    <h3 class="text-center mt-3">{{ $successMessage }}</h3>

{{--    <a href="{{ route('stripe.index') }}" class="btn mt-5 bg">Continue Shopping</a>--}}
</div>
</x-main>