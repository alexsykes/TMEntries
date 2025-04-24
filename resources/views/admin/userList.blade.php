<x-admin>
    <x-slot:heading>User List</x-slot:heading>
    <div class="mx-auto max-w-7xl px-4  sm:px-6 lg:px-8">
    @php
//        dd($users);
    @endphp
    <div class=" mt-0 mb-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">&nbsp;</div>
    <table class="w-full">
        <tr>
            <th class="table-cell pl-4  pt-1  pb-1   hidden  md:table-cell">ID</th>
            <th class="table-cell pl-4">Name</th>
            <th class="table-cell pl-4">Email</th>
            <th class="table-cell pl-4">Club User</th>
            <th class="table-cell pl-4">Admin User</th>
            <th class="table-cell pl-4">Super User</th>
            <th class="table-cell pl-4">&nbsp;</th>
            <th class="table-cell pl-4">&nbsp;</th>

        </tr>
    @foreach($users as $user)
        @php
            $isAdminUser = $user->isAdminUser == 1 ? "Y" : "";
            $isClubUser = $user->isClubUser == 1 ? "Y" : "";
            $isSuperUser = $user->isSuperUser == 1 ? "Y" : "";

        @endphp
            <tr class="pr-4 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b ">
        <td class="text-right table-cell pl-4  pt-1  pb-1   hidden  md:table-cell">{{$user->id}}</td>
        <td class="table-cell pl-4">{{$user->name}}</td>
        <td class="table-cell pl-4">{{$user->email}}</td>
        <td class="text-center table-cell  pl-4">{{$isClubUser}}</td>
        <td class="text-center table-cell pl-4">{{$isAdminUser}}</td>
        <td class="text-center table-cell pl-4">{{$isSuperUser}}</td>
                <td class="font-semibold table-cell pl-4"><a class="underline" href="admin/editUser/{{$user->id}}"><i class="text-blue-800 fa-solid fa-gear"></i></a></td>
                <td class="font-semibold table-cell pl-4 pr-4"><a class="underline" href="admin/user/remove/{{$user->id}}">
                        @if(!$isSuperUser)
                            <i class="text-red-500 fa-solid fa-trash"></i>
                        @endif

                    </a></td>
        </tr>
    @endforeach
    </table>
    </div>
    </div>

    <div>
        {{ $users->links() }}

    </div>
</x-admin>