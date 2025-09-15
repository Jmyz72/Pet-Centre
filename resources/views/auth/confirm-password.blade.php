<x-guest-layout>
    <x-slot name="title">Confirm Password</x-slot>
    <x-slot name="headerIcon">fa-shield-alt</x-slot>
    <x-slot name="headerTitle">Secure Area Access</x-slot>
    <x-slot name="headerSubtitle">Please confirm your password to continue</x-slot>

    <!-- Information Message -->
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
            <p class="text-sm text-blue-700">
                This is a secure area of the application. Please confirm your password before continuing.
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock text-purple-500 mr-2"></i>Password
            </label>
            <div class="relative">
                <input id="password" 
                       name="password" 
                       type="password" 
                       required 
                       autocomplete="current-password"
                       class="appearance-none block w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 @error('password') border-red-300 @enderror"
                       placeholder="Enter your password">
                <button type="button" 
                        onclick="togglePassword('password')"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition duration-200">
                    <i id="password-toggle-icon" class="fas fa-eye"></i>
                </button>
            </div>
            @error('password')
                <div class="mt-2 flex items-center text-red-600 text-sm">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <i class="fas fa-check text-purple-200 group-hover:text-purple-100"></i>
                </span>
                Confirm Password
            </button>
        </div>
    </form>
</x-guest-layout>
