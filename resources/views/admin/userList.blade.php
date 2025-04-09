<x-admin>
    <x-slot:heading>User List</x-slot:heading>

    @php
//        dd($users);
    @endphp
    <div class=" mt-0 mb-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
    <table class=w-full">
    @foreach($users as $user)
            <tr class="pr-4 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b ">
        <td class="pl-4  pt-1  pb-1   hidden  md:table-cell">{{$user->id}}</td>
        <td>{{$user->name}}</td>
        <td>{{$user->email}}</td>
        <td>{{$user->isClubUser}}</td>
        <td>{{$user->isAdminUser}}</td>
        <td>{{$user->isSuperUser}}</td>
        <td>Remove</td>
        </tr>
    @endforeach
    </table>
    </div>
    <div>
        {{ $users->links() }}

    </div>
</x-admin>