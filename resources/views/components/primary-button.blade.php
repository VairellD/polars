<button {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-6 py-3 bg-purple-600 border border-transparent rounded-lg font-semibold text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition duration-200 ease-in-out transform hover:scale-[1.02]']) }}>
    {{ $slot }}
</button>
