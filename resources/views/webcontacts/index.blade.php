<x-admin>
    <x-slot:heading>
        Contacts
    </x-slot:heading>


    <div class="px-4 py-4 mt-0 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
        @if(sizeof($webcontacts) > 0)
            <table class="table-auto w-full">
                <tr>
                <th>Date</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>IP</th>
                <th>&nbsp;</th>
                </tr>
                @foreach($webcontacts as $message)
                    @if($message->closed)
                        <tr class="">
                    @else
                        <tr class="text-red-500">
                            @endif
                            <td>{{ $message->created_at }}</td>
                            <td>{{ $message->name }}</td>
                            <td>{{ $message->email }}</td>
                            <td>{{ $message->message }}</td>
                            <td>{{ $message->ip_address }}</td>
                            <td><a href="/webcontact/show/{{$message->id}}">View</a></td>
                        </tr>
                        @endforeach
            </table>
        @endif

    </div>
</x-admin>