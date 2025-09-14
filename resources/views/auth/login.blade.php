<x-guest-layout>
    <x-slot name="title">Login</x-slot>
    <x-slot name="headerIcon">fa-paw</x-slot>
    <x-slot name="headerTitle">Welcome Back to Our Pet Family</x-slot>
    <x-slot name="headerSubtitle">Sign in to your account</x-slot>

    <!-- Status Messages -->
    @if (session('status'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <p class="text-sm text-green-700">{{ session('status') }}</p>
            </div>
        </div>
    @endif

    @if (session('message'))
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                <p class="text-sm text-blue-700">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-envelope text-purple-500 mr-2"></i>Email Address
            </label>
            <input id="email" 
                   name="email" 
                   type="email" 
                   value="{{ old('email') }}"
                   required 
                   autofocus 
                   autocomplete="username"
                   class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 @error('email') border-red-300 @enderror"
                   placeholder="Enter your email address">
            @error('email')
                <div class="mt-2 flex items-center text-red-600 text-sm">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>

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

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" 
                   name="remember" 
                   type="checkbox"
                   class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
            <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                Remember me for 30 days
            </label>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <i class="fas fa-paw text-purple-200 group-hover:text-purple-100"></i>
                </span>
                Sign In to Pet Center
            </button>
        </div>

        <!-- Links -->
        <div class="flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0 text-sm">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" 
                   class="font-medium text-purple-600 hover:text-purple-500 transition duration-200">
                    <i class="fas fa-key mr-1"></i>
                    Forgot Password?
                </a>
            @endif
            <a href="{{ route('register') }}" 
               class="font-medium text-purple-600 hover:text-purple-500 transition duration-200">
                <i class="fas fa-user-plus mr-1"></i>
                Create Account
            </a>
        </div>

        <!-- Back to Home -->
        <div class="text-center pt-4">
            <a href="{{ route('home') }}" 
               class="font-medium text-gray-600 hover:text-gray-800 transition duration-200 text-sm">
                <i class="fas fa-arrow-left mr-1"></i>
                Back to Pet Center
            </a>
        </div>
    </form>
</x-guest-layout>
