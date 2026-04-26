<x-main>
    <x-slot:heading>
        Your message has been received.
    </x-slot:heading>
    <div class="text-sm space-y-2 p-1 w-full rounded-md bg-white border border-blue-700">
        <div>From: {{$webContact->name}}</div>
        <div>Email: {{$webContact->email}}</div>
        <div>You wrote: {{$webContact->message}}</div>
        <div>IP address: {{$webContact->ip_address}}</div>
        <div>A copy of this message has also been sent to your email address</div>
    </div>
</x-main>