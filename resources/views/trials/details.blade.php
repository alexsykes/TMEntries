<x-main>
    <x-slot:heading>
        {{$trial->name}}
    </x-slot:heading>
    @php
        //        dump($trial);
            session(['trial_id' => $trial->id]);
    @endphp
    <a href="/entries/userdata/{{$trial_id}}">Enter</a>

</x-main>