<x-main>
    <x-slot:heading>Club list</x-slot:heading>

    @php
        //    dump($results);
    @endphp

    @foreach($clubs as $club)
    <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="flex justify-between font-bold w-full mt-4 pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">
            <div>{{$club->name}}</div>
            <div>{{$club->area}}</div>
        </div>
       <div class="p-2 pl-4 text-sm">@php echo $club->description;
 @endphp
       </div>
    </div>
    @endforeach
</x-main>
