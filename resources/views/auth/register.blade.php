<x-guest-layout>
    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Getting Started</h2>
            <p class="text-gray-600 text-sm">Create an account to get started</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Username -->
            <div>
                <x-input-label for="username" :value="__('Username')" class="text-gray-700 font-medium mb-2" />
                <x-text-input
                    id="username"
                    class="block w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                    type="text"
                    name="username"
                    :value="old('username')"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Enter your username"
                />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Full Name')" class="text-gray-700 font-medium mb-2" />
                <x-text-input
                    id="name"
                    class="block w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                    type="text"
                    name="name"
                    :value="old('name')"
                    required
                    autocomplete="name"
                    placeholder="Enter your full name"
                />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- ANGKATAN -->
            <div>
                <x-input-label for="angkatan" :value="__('Angkatan')" class="text-gray-700 font-medium mb-2" />
                <x-text-input
                    id="angkatan"
                    class="block w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                    type="text"
                    name="angkatan"
                    :value="old('angkatan')"
                    required
                    autocomplete="angaktan"
                    placeholder="Enter your angakatan"
                />
                <x-input-error :messages="$errors->get('angkatan')" class="mt-2" />
            </div>

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
                    autocomplete="new-password"
                    placeholder="Enter your password"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-700 font-medium mb-2" />
                <x-text-input
                    id="password_confirmation"
                    class="block w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Confirm your password"
                />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Register Button -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 ease-in-out transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                    {{ __('Register') }}
                </button>
            </div>

            <!-- Login Link -->
            <div class="text-center pt-4 border-t border-gray-100">
                <p class="text-sm text-gray-600">
                    {{ __('Already registered?') }}
                    <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-800 font-medium ml-1">
                        Log in
                    </a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
