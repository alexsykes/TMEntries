<x-mail::layout>

    <x-slot:header>
        <x-mail::header url="{{config('app.url')}}">
            #AppTeam
        </x-mail::header>
    </x-slot:header>

    #Greetings!

    Here is my email body for you to read

    <x-slot:footer>
        <x-mail::footer>
            <a href="{{route('user.unsubscribe')}}">Click here to unsubscribe from these emails</a>
        </x-mail::footer>
    </x-slot:foot>

</x-mail::layout>