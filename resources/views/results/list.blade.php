<x-main>
    <x-slot:heading>{{config('app.name')}}</x-slot:heading>
        <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Results</div>
            <table class="w-full">
            @foreach($pastTrials as $trial)

                    @php
                        $date = date_format(date_create($trial->date), "M jS, Y");
                    @endphp
                <tr class="pr-4 odd:bg-white  even:bg-gray-50  border-b ">
                    <td class="text-sm hidden sm:table-cell  pl-4 pt-1 pb-1"><a href="/results/display/{{$trial->id}}">{{$date}}</a></td>
                    <td class="table-cell text-sm  pl-4"><a href="/results/display/{{$trial->id}}">{{$trial->club}}</a></td>
                    <td class="table-cell text-sm  pl-4"><a href="/results/display/{{$trial->id}}">{{$trial->name}}</a></td>
                    <td class="table-cell text-sm  pl-4"><a href="/results/display/{{$trial->id}}">{{$trial->venue}}</a></td>
                    </tr>
                @endforeach
        </table>
    </div>
</x-main>