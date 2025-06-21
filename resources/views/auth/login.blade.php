<x-guest-layout>
    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Log In</h2>
            <p class="text-gray-600 text-sm">Welcome Back !</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium mb-2" />
                <x-text-input
                    id="email"
                    class="block w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Enter your email"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium mb-2" />
                <x-text-input
                    id="password"
                    class="block w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Enter your password"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center">
                    <input
                        id="remember_me"
                        type="checkbox"
                        class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 focus:ring-2"
                        name="remember"
                    >
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-purple-600 hover:text-purple-800 font-medium" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <!-- Login Button -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 ease-in-out transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                    {{ __('Log In') }}
                </button>
            </div>

            <!-- Register Link -->
            <div class="text-center pt-4 border-t border-gray-100">
                <p class="text-sm text-gray-600">
                    Don't have an account yet?
                    <a href="{{ route('register') }}" class="text-purple-600 hover:text-purple-800 font-medium ml-1">
                        Sign up
                    </a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
