<x-layout>
    <x-slot:heading>
        Trials
    </x-slot:heading>
    <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">Trial list</div>
        <table class="w-full">
@foreach($trials as $trial)
    @php
        if ($trial->published) {
            $publishIMG = "fa-solid fa-eye text-black";
        } else {
             $publishIMG = "fa-solid fa-eye-slash text-orange-700";
        }
    @endphp
    <div id="triallist" class="flex-auto">
    <tr class="pr-4 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b ">
        <td class="pl-2 hidden md:table-cell">{{$trial->date}}</td>
        <td class="hidden md:table-cell">{{$trial->club}}</td>
        <td class="pl-2 table-cell">{{$trial->name}}</td>
        <td class="table-cell"><a href="/trials/toggleVisibility/{{$trial->id}}"><span><i class="{{$publishIMG}}"></i></span></a></td>
        <td class="table-cell"><a href="/trials/edit/{{$trial->id}}"><span><i class="fa-solid fa-pencil"></i></span></a></td>
    </tr>
    </div>
@endforeach
    </table>
    </div>
    <div class="mt-4" id="buttons">

        <a href="trials/add"
                class="rounded-md  bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
            Add a new trial
        </a>
    </div>
</x-layout>