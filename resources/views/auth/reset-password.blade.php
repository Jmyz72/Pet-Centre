<x-guest-layout>
    <x-slot name="title">Reset Password</x-slot>
    <x-slot name="headerIcon">fa-shield-alt</x-slot>
    <x-slot name="headerTitle">Reset Your Password</x-slot>
    <x-slot name="headerSubtitle">Create a new secure password</x-slot>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-envelope text-purple-500 mr-2"></i>Email Address
            </label>
            <input id="email" 
                   name="email" 
                   type="email" 
                   value="{{ old('email', $request->email) }}"
                   required 
                   autofocus 
                   autocomplete="username"
                   readonly
                   class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 bg-gray-50 text-gray-500 cursor-not-allowed"
                   placeholder="Email address">
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
                <i class="fas fa-lock text-purple-500 mr-2"></i>New Password
            </label>
            <div class="relative">
                <input id="password" 
                       name="password" 
                       type="password" 
                       required 
                       autocomplete="new-password"
                       class="appearance-none block w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 @error('password') border-red-300 @enderror"
                       placeholder="Enter your new password">
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

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock text-purple-500 mr-2"></i>Confirm New Password
            </label>
            <div class="relative">
                <input id="password_confirmation" 
                       name="password_confirmation" 
                       type="password" 
                       required 
                       autocomplete="new-password"
                       class="appearance-none block w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 @error('password_confirmation') border-red-300 @enderror"
                       placeholder="Confirm your new password">
                <button type="button" 
                        onclick="togglePassword('password_confirmation')"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition duration-200">
                    <i id="password_confirmation-toggle-icon" class="fas fa-eye"></i>
                </button>
            </div>
            @error('password_confirmation')
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
                    <i class="fas fa-shield-alt text-purple-200 group-hover:text-purple-100"></i>
                </span>
                Reset Password
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
