@props(['active' => false, 'type'=>'a'])

@if($type = 'a')
	<a class="{{ $active ? 'bg-blue-900 border border-white font-light  text-white': 'bg-blue-500 border border-white  text-white hover:bg-blue-2 hover:text-blue-300'}} rounded-lg px-3 py-1 text-sm font-light"
	aria-current="{{ $active ? 'page': 'false' }}"
	{{ $attributes }}

>{{ $slot }}</a>
@else
	<button class="{{ $active ? '  bg-blue-900 border border-white font-light text-white': 'text-blue-800 border-white  text-white hover:bg-blue-200 hover:text-blue-300'}} rounded-lg px-3 py-1 text-sm font-light"
	aria-current="{{ $active ? 'page': 'false' }}"
	{{ $attributes }}

>{{ $slot }}</button>

@endif