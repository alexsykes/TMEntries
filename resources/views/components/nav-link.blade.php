@props(['active' => false, 'type'=>'a'])

@if($type = 'a')

	<a class="{{ $active ? 'bg-blue-100 border border-white font-light  text-blue-900': 'bg-blue-500 border border-white  text-white hover:bg-blue-200 hover:text-blue-800'}} rounded-md px-3 py-1 text-sm font-light"
	   aria-current="{{ $active ? 'page': 'false' }}"
			{{ $attributes }}

	>{{ $slot }}</a>
@else
	<button class="{{ $active ? '  bg-blue-100 border border-white font-light text-blue-900': 'text-blue-500 border-white  text-white hover:bg-blue-200 hover:text-blue-800'}} rounded-md px-3 py-1 text-sm font-light"
			aria-current="{{ $active ? 'page': 'false' }}"
			{{ $attributes }}

	>{{ $slot }}</button>

@endif