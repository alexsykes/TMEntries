<x-main>
    <x-slot:heading>TrialMonster UK</x-slot:heading>
        <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Results</div>
            <table class="w-full">
            @foreach($pastTrials as $trial)

                <tr class="pr-4 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b ">
                        <td class="text-sm hidden md:table-cell  pl-4 pt-1 pb-1">{{$trial->date}}</td>
                        <td class="table-cell text-sm  pl-4">{{$trial->club}}</td>
                        <td class="table-cell text-sm  pl-4">{{$trial->name}}</td>
                    <td class="pl-2 pr-2 table-cell"><a href="/results/display/{{$trial->id}}"><span><i class="fa-solid fa-eye"></i></span></a></td>
                    </tr>
                @endforeach
        </table>
    </div>
</x-main>