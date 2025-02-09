<x-guest-layout>
@foreach($trials as $trial)
    <div id="triallist" class="flex-auto">

        <div class="inline">{{$trial->date}}</div>
        <div>{{$trial->club}}</div>
        <div>{{$trial->name}}</div>
        <div><a href="entries/create/{{$trial->id}}"><span>+</span></a></div>
    </div>
@endforeach
</x-guest-layout>