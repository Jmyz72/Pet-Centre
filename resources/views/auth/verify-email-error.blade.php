<x-guest-layout>
    <x-slot name="title">Verification Failed</x-slot>
    <x-slot name="headerIcon">fa-exclamation-triangle</x-slot>
    <x-slot name="headerTitle">Email Verification Failed</x-slot>
    <x-slot name="headerSubtitle">Something went wrong with the verification</x-slot>

    <!-- Error Message -->
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-red-500 mr-3 text-xl"></i>
            <p class="text-sm text-red-700">{{ $error }}</p>
        </div>
    </div>

    <!-- Help Text -->
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
            <div class="text-sm text-blue-700">
                <p>The verification link may have expired or been used already.</p>
                <p class="mt-2">Please request a new verification email or contact support if the problem persists.</p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="space-y-4">
        <div>
            <a href="{{ route('register') }}" 
               class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <i class="fas fa-user-plus text-purple-200 group-hover:text-purple-100"></i>
                </span>
                Register Again
            </a>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}" 
               class="font-medium text-purple-600 hover:text-purple-500 transition duration-200 text-sm">
                <i class="fas fa-arrow-left mr-1"></i>
                Back to Login
            </a>
        </div>
    </div>
</x-guest-layout>
