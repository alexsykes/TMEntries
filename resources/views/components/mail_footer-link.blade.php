@props(['active' => false, 'type'=>'a'])

<footer class="flex flex-wrap flex-col items-center px-4 pt-6 pb-2 text-sm ">
    <div class="pt-2 {{ $active ? ' text-violet-400': ' text-violet-800 hover:bg-violet-2 hover:text-violet-300'}} rounded-md  text-sm font-light"
         aria-current="{{ $active ? 'page': 'false' }}"
            {{ $attributes }} >{{ $slot }}
    </div>
</footer>