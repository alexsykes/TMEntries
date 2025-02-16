<x-main>
    <x-slot:heading>
        TM Entries
    </x-slot:heading>
    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Coming up…</div>
        <table class="w-full ml-4 mr-4">
@foreach($trials as $trial)
    <div id="triallist" class="flex-auto">
    <tr>
        <td class="inline">{{$trial->date}}</td>
        <td>{{$trial->club}}</td>
        <td>{{$trial->name}}</td>
        <td><a href="entries/user_details/{{$trial->id}}"><span>Enter</span></a></td>
    </tr>
    </div>
@endforeach
    </table>
    </div>
</x-main>