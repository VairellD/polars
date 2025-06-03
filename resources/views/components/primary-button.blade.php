<button {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-purple-700 border border-transparent rounded-md font-semibold text-white hover:bg-purple-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
