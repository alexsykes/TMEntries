<button {{ $attributes->merge(['class' => 'rounded-md bg-blue-500 border border-white  px-3 py-1 text-sm font-light text-white shadow-sm hover:bg-blue-200  hover:text-blue-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600', 'type' => 'submit']) }}>
    {{ $slot }}
</button>
