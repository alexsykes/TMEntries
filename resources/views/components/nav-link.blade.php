@props(['active' => false, 'type'=>'a'])

@if($type = 'a')
	<a class="{{ $active ? 'bg-violet-300 border border-white font-light  text-violet-500': 'bg-violet-500 border border-white  text-white hover:bg-violet-2 hover:text-violet-300'}} rounded-md px-3 py-1 text-sm font-light"
	aria-current="{{ $active ? 'page': 'false' }}"
	{{ $attributes }}

>{{ $slot }}</a>
@else
	<button class="{{ $active ? '  bg-violet-300 border border-white font-light text-white': 'text-violet-500 border-white  text-white hover:bg-violet-200 hover:text-violet-300'}} rounded-md px-3 py-1 text-sm font-light"
	aria-current="{{ $active ? 'page': 'false' }}"
	{{ $attributes }}

>{{ $slot }}</button>

@endif