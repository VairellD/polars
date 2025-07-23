<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">

    <div class="min-h-screen flex flex-col lg:flex-row">

        {{--
        - On mobile, this is a header with a fixed height ('h-48').
        - On large screens ('lg:'), it takes up half the width ('lg:flex-1').
        --}}
        <div class="relative flex h-48 items-center justify-center lg:flex-1 lg:h-auto">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-400 via-blue-400 to-purple-300"></div>

            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-20 left-20 w-32 h-32 rounded-full bg-white/10 blur-xl"></div>
                <div class="absolute bottom-10 right-10 w-24 h-24 rounded-full bg-white/15 blur-lg"></div>
                <div
                    class="absolute top-1/2 left-1/2 w-40 h-40 rounded-full bg-white/10 blur-2xl transform -translate-x-1/2 -translate-y-1/2">
                </div>
            </div>

            <div class="relative z-10">
                <h1 class="text-4xl font-bold text-white tracking-wide">TeRMinal</h1>
            </div>
        </div>

        {{--
        - 'flex-1' makes this container fill the remaining vertical space on mobile.
        - Padding is now responsive for better spacing on all screen sizes.
        --}}
        <div class="flex flex-1 items-center justify-center bg-gray-50 p-6 sm:p-8">
            <div class="w-full max-w-md">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>