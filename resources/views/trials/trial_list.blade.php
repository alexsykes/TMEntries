<x-main>
    <x-slot:heading>
        TrialMonster Entries
    </x-slot:heading>
    <?php $message = "Hello World"; ?>
    <x-alert type="error" message="$message" class="mt-4"/>
    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">

        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Coming up…</div>
        <table class="w-full ml-4 pr-6">
            @foreach($trials as $trial)
                    <tr class="flex-auto">
                        <td class="hidden md:table-cell">{{$trial->date}}</td>
                        <td class="hidden md:table-cell">{{$trial->club}}</td>
                        <td class="table-cell">{{$trial->name}}</td>
                        <td title="Entry list" class="table-cell" ><a href="/trial/{{$trial->id}}/entrylist"><span><i class="text-xl  fa-solid fa-list-ul"></i></span></a></td>
                        <td title="Register" class="table-cell "><a href="/trial/details/{{$trial->id}}"><span><i class="text-xl  fa-solid fa-circle-info"></i></span></a></td>
                    </tr>
            @endforeach
        </table>
    </div>
</x-main>