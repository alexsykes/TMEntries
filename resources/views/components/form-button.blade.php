<button {{ $attributes->merge(['class' => 'rounded-md bg-violet-600 border border-white  px-3 py-1 text-sm font-light text-white shadow-sm hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600', 'type' => 'submit']) }}>
    {{ $slot }}
</button>
