<x-guest-layout>
    <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-8">
        <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-6">
            {{ __('Log in to Your Account') }}
        </h2>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" 
                              type="email" name="email" :value="old('email')" 
                              required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full"
                              type="password" name="password" 
                              required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" 
                           class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 
                                  text-indigo-600 shadow-sm focus:ring-indigo-500 
                                  dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" 
                           name="remember">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Remember me') }}
                    </span>
                </label>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between mt-6">
                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline" 
                       href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-primary-button class="ml-3 px-6 py-2 rounded-xl">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>

        <!-- Register Redirect -->
        <div class="text-center mt-6">
            <a href="{{ route('register') }}" 
               class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                {{ __("Don't have an account? Register here") }}
            </a>
        </div>
    </div>
</x-guest-layout>
