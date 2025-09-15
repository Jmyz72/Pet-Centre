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
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" 
                          type="email" name="email" 
                          :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Actions Row -->
        <div class="flex items-center justify-between mt-6">
            <!-- Back to Login -->
            <a href="{{ route('login') }}" 
               class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline">
                ← Back to Login
            </a>

            <!-- Submit Button -->
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
