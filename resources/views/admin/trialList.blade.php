<x-admin>
    <x-slot:heading>Trial list</x-slot:heading>
    <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="flex justify-between  w-full pt-2 pb-2 pl-4 pr-2 rounded-t-xl  text-white bg-red-600">
            <div class="font-bold">Trial List</div>
        </div>
        <div class="table  w-full text-sm">
            @foreach($trials as $trial)
                @php
                    $rawDate = new DateTime($trial->date);
                    $date  = date_format($rawDate, "jS F, Y");
                @endphp

                <div class="border-b table-row pr-2">
                    <a href="/admin/trial/edit/{{$trial->id}}"
                       class=" border-b pt-1 pb-1 pl-2 md:table-cell  ">{{$date}}</a>
                    <a href="/admin/trial/edit/{{$trial->id}}"
                       class="hidden border-b pt-1 pb-1 pl-2 md:table-cell  ">{{$trial->club}}</a>
                    <a href="/admin/trial/edit/{{$trial->id}}"
                       class=" border-b pt-1 pb-1 pl-2 md:table-cell  ">{{$trial->name}}</a>
                </div>
            @endforeach
        </div>
    </div>
</x-admin>
