<x-guest-layout>
    <x-slot name="title">Forgot Password</x-slot>
    <x-slot name="headerIcon">fa-key</x-slot>
    <x-slot name="headerTitle">Forgot Password?</x-slot>
    <x-slot name="headerSubtitle">No worries! We'll send you reset instructions</x-slot>

    <!-- Information Message -->
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
            <p class="text-sm text-blue-700">
                Enter your email address and we'll send you a password reset link that will allow you to choose a new one.
            </p>
        </div>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <p class="text-sm text-green-700">{{ session('status') }}</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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
                   class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 @error('email') border-red-300 @enderror"
                   placeholder="Enter your email address">
            @error('email')
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
                    <i class="fas fa-paper-plane text-purple-200 group-hover:text-purple-100"></i>
                </span>
                Email Password Reset Link
            </button>
        </div>

        <!-- Back to Login -->
        <div class="text-center">
            <p class="text-sm text-gray-600">
                Remember your password? 
                <a href="{{ route('login') }}" class="font-medium text-purple-600 hover:text-purple-500 transition duration-200">
                    Back to Sign In
                    <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
