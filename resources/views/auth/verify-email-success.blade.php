<x-guest-layout>
    <div class="text-center">
        <!-- Success Icon -->
        <div class="mb-6">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-900">
                <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <!-- Success Message -->
        <h2 class="mb-4 text-2xl font-bold text-gray-900 dark:text-white">Email Verified Successfully!</h2>

        <div class="mb-6 p-4 text-sm text-green-800 bg-green-100 border border-green-300 rounded-lg dark:bg-green-900 dark:text-green-300 dark:border-green-800">
            {{ $message }}
        </div>

        @if(isset($justVerified) && $justVerified)
            <div class="mb-4 p-3 text-sm text-blue-800 bg-blue-100 border border-blue-300 rounded-lg dark:bg-blue-900 dark:text-blue-300 dark:border-blue-800">
                <strong>✓ Customer Role Assigned:</strong> You now have access to all customer features.
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="space-y-4">
            <div>
                <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Go to Login
                </a>
            </div>

            <div>
                <a href="{{ route('register') }}" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    Need to register another account?
                </a>
            </div>
        </div>

        <!-- User Info (for debugging in development) -->
        @if(config('app.debug') && isset($user))
            <div class="mt-8 p-3 text-xs text-gray-500 bg-gray-100 border border-gray-200 rounded dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700">
                <strong>Debug Info:</strong> User ID: {{ $user->id }}, Email: {{ $user->email }}, Verified: {{ $user->hasVerifiedEmail() ? 'Yes' : 'No' }}
            </div>
        @endif
    </div>
</x-guest-layout>