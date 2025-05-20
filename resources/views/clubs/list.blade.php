<x-main>
    <x-slot:heading>Club list</x-slot:heading>

    @php
        //    dump($results);
    @endphp
    <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-red-600">Results</div>
        <table class="w-full">
            @foreach($results as $trial)

                <tr class="pr-4 odd:bg-white  even:bg-gray-50  border-b ">
                    <td class="text-sm hidden md:table-cell  pl-4 pt-1 pb-1"><a href="/results/display/{{$trial->id}}">{{$trial->date}}</a></td>
                    <td class="table-cell text-sm  pl-4"><a href="/results/display/{{$trial->id}}">{{$trial->club}}</a></td>
                    <td class="table-cell text-sm  pl-4"><a href="/results/display/{{$trial->id}}">{{$trial->name}}</a></td>
                    <td class="table-cell text-sm  pl-4">
                        <a href="/results/display/{{$trial->id}}"><span><i class="ml-1 mr-3 fa-solid fa-eye "></i></span></a>
                    </td>
                    <td class="table-cell text-sm  pl-4">
                        <a href="/results/edit/{{$trial->id}}"><span><i class="ml-1 mr-3 fa-solid fa-pencil"></i></span></a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</x-main>
