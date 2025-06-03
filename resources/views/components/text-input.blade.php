@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => 'border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm w-full px-3 py-2'
    ]) }}
>
