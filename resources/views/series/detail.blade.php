<x-club>

    <x-slot:heading>{{$series->club      }}</x-slot:heading>

    <div class="  text-sm mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="flex justify-between  w-full pt-2 pb-2 pl-2 pr-4 rounded-t-xl  text-white bg-violet-600">
            <div class="font-bold">{{$series->name}} Series</div>
            <div class="text-end">{{$series->description}}</div>
        </div>
        <div class="p-2">
            <div class="text-sm">@php echo $series->notes; @endphp</div>
            <div class="flex justify-between">
                <div>
                    <div class="font-bold">Courses</div><div>{{$series->courses}}</div>
                </div>
                <div>
                <div class="font-bold text-end">Classes</div><div class="text-end">{{$series->classes}}</div>
                </div>
            </div>
        </div>
    </div>
</x-club>