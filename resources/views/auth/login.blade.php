<x-guest-layout>
    {{-- We adjust padding for different screen sizes: p-6 for mobile, p-8 for small screens and up --}}
    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-xl border border-gray-100">
        {{-- Adjusted margin and font size for mobile --}}
        <div class="mb-6 sm:mb-8 text-center sm:text-left">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">Log In</h2>
            <p class="text-gray-600 text-sm">Welcome Back!</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium mb-2" />
                <x-text-input id="email"
                    class="block w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                    type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                    placeholder="Enter your email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- This structure is more robust for aligning the icon inside the input field --}}
            <div>
                <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium mb-2" />
                <div x-data="{ show: false }" class="relative">
                    <x-text-input id="password"
                        class="block w-full px-4 py-3 pr-12 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                        x-bind:type="show ? 'text' : 'password'" name="password" required
                        autocomplete="current-password" placeholder="Enter your password" />

                    <button type="button"
                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-500 hover:text-gray-700"
                        @click="show = !show" :aria-label="show ? 'Hide password' : 'Show password'" tabindex="-1">

                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>

                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 012.338-3.592m3.16-2.175A9.99 9.99 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.978 9.978 0 01-1.246 2.527M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- Stacks vertically on mobile, and horizontally on larger screens --}}
            <div class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                <label for="remember_me" class="flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 focus:ring-2"
                        name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-purple-600 hover:text-purple-800 font-medium"
                        href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 ease-in-out transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                    {{ __('Log In') }}
                </button>
            </div>

            <div class="text-center pt-4 border-t border-gray-100">
                <p class="text-sm text-gray-600">
                    Don't have an account yet?
                    <a href="{{ route('register') }}" class="font-medium text-purple-600 hover:text-purple-800 ml-1">
                        Sign up
                    </a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>