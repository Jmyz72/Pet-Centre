<x-guest-layout>
    <x-slot name="title">Email Verified</x-slot>
    <x-slot name="headerIcon">fa-check-circle</x-slot>
    <x-slot name="headerTitle">Email Verified Successfully!</x-slot>
    <x-slot name="headerSubtitle">Welcome to our pet family</x-slot>

    <!-- Success Message -->
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 mr-3 text-xl"></i>
            <p class="text-sm text-green-700">{{ $message }}</p>
        </div>
    </div>

    @if(isset($justVerified) && $justVerified)
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
            <div class="flex items-center">
                <i class="fas fa-user-check text-blue-500 mr-3"></i>
                <div class="text-sm text-blue-700">
                    <strong>✓ Customer Role Assigned:</strong> You now have access to all customer features.
                </div>
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="space-y-4">
        <div>
            <a href="{{ route('login') }}" 
               class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <i class="fas fa-sign-in-alt text-purple-200 group-hover:text-purple-100"></i>
                </span>
                Go to Login
            </a>
        </div>

        <div class="text-center">
            <a href="{{ route('register') }}" 
               class="font-medium text-purple-600 hover:text-purple-500 transition duration-200 text-sm">
                <i class="fas fa-user-plus mr-1"></i>
                Need to register another account?
            </a>
        </div>
    </div>

    <!-- User Info (for debugging in development) -->
    @if(config('app.debug') && isset($user))
        <div class="mt-6 p-3 bg-gray-50 rounded-lg">
            <p class="text-xs text-gray-600 text-center">
                <i class="fas fa-bug text-orange-500 mr-1"></i>
                <strong>Debug Info:</strong> User ID: {{ $user->id }}, Email: {{ $user->email }}, Verified: {{ $user->hasVerifiedEmail() ? 'Yes' : 'No' }}
            </p>
        </div>
    @endif
</x-guest-layout>