<x-main>
    <x-slot:heading>
        TM Entries
    </x-slot:heading>
@foreach($trials as $trial)
    <div id="triallist" class="flex-auto">

        <div class="inline">{{$trial->date}}</div>
        <div>{{$trial->club}}</div>
        <div>{{$trial->name}}</div>
        <div><a href="entries/user_details/{{$trial->id}}"><span>+</span></a></div>
    </div>
@endforeach
</x-main>