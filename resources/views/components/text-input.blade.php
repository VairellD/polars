@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => 'border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-lg shadow-sm w-full px-4 py-3 transition duration-200'
    ]) }}
>
