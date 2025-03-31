<x-club>
    <x-slot:heading>
        {{$trial->name}}
    </x-slot:heading>
    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-violet-600">Entry list</div>
        <div class="pl-4">
        <table class="w-full">
            @foreach($entries as $entry)
                <tr>
                    <td>{{$entry->id}}</td>
                    <td>{{$entry->name}}</td>
                    <td>{{$entry->class}}</td>
                    <td>{{$entry->course}}</td>
                    <td>{{$entry->status}}</td>
                    <td><a href="/admin/entry/edit/{{$entry->id}}"><span><i class="fa-solid fa-gear"></i></span></a></td>
                    <td><a href="/admin/entry/cancel/{{$entry->id}}"><span><i class="fa-solid fa-ban"></i></span></a></td>
                </tr>
            @endforeach
        </table>
    </div>
        {{--    <a href="{{ route('stripe.index') }}" class="btn mt-5 bg">Continue Shopping</a>--}}
    </div>
</x-club>