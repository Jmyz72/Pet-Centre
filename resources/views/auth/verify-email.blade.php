<x-guest-layout>
    <x-slot name="title">Verify Email</x-slot>
    <x-slot name="headerIcon">fa-envelope</x-slot>
    <x-slot name="headerTitle">Verify Your Email</x-slot>
    <x-slot name="headerSubtitle">Check your inbox for our verification email</x-slot>

    <!-- Custom Message -->
    @if (session('message'))
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                <p class="text-sm text-blue-700">{{ session('message') }}</p>
            </div>
        </div>
    @else
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
            <div class="flex items-start">
                <i class="fas fa-envelope-open text-blue-500 mt-0.5 mr-3"></i>
                <p class="text-sm text-blue-700">
                    Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                </p>
            </div>
        </div>
    @endif

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <p class="text-sm text-green-700">
                    A new verification link has been sent to the email address you provided during registration.
                </p>
            </div>
        </div>
    @endif

    <div class="space-y-4">
        <!-- Resend Email Button -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <i class="fas fa-redo text-purple-200 group-hover:text-purple-100"></i>
                </span>
                Resend Verification Email
            </button>
        </form>

        <!-- Navigation Links -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-4">
            <a href="{{ route('login') }}" 
               class="font-medium text-purple-600 hover:text-purple-500 transition duration-200 text-sm">
                <i class="fas fa-arrow-left mr-1"></i>
                Back to Login
            </a>

            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" 
                        class="font-medium text-gray-600 hover:text-gray-800 transition duration-200 text-sm">
                    <i class="fas fa-sign-out-alt mr-1"></i>
                    Log Out
                </button>
            </form>
        </div>
    </div>

    <!-- Help Text -->
    <div class="mt-6 p-3 bg-gray-50 rounded-lg">
        <p class="text-xs text-gray-600 text-center">
            <i class="fas fa-lightbulb text-yellow-500 mr-1"></i>
            Didn't receive the email? Check your spam folder or try requesting a new verification email.
        </p>
    </div>
</x-guest-layout>
