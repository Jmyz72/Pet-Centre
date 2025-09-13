@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center px-4">
    <div class="max-w-md w-full">
        {{-- Bank Card --}}
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
            {{-- Bank Logo --}}
            <div class="mb-6">
                <div class="w-16 h-16 bg-blue-600 rounded-xl mx-auto flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mt-3">SecureBank</h2>
                <p class="text-sm text-gray-500">Secure Payment Gateway</p>
            </div>

            {{-- Transaction Details --}}
            <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
                <h3 class="font-medium text-gray-900 mb-3">Transaction Details</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Amount:</span>
                        <span class="font-medium">RM {{ number_format($hold->calculateAmount(), 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Type:</span>
                        <span class="font-medium">{{ ucfirst($hold->booking_type) }} Booking</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Reference:</span>
                        <span class="font-medium text-xs">{{ $idempotencyKey }}</span>
                    </div>
                </div>
            </div>

            {{-- Countdown and Instructions --}}
            <div class="mb-6">
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4">
                    <p class="text-sm text-amber-800">
                        <strong>📱 Check your phone</strong><br>
                        Please accept the payment notification on your mobile banking app.
                    </p>
                </div>
                
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600" id="countdown">30</div>
                    <p class="text-sm text-gray-500">seconds remaining</p>
                </div>
            </div>

            {{-- Action Button --}}
            <form method="POST" action="{{ route('bookings.complete') }}" id="completeForm">
                @csrf
                <input type="hidden" name="hold_id" value="{{ $hold->id }}">
                <input type="hidden" name="idempotency_key" value="{{ $idempotencyKey }}">
                
                <button type="submit" 
                        id="acceptButton"
                        disabled
                        class="w-full px-6 py-3 bg-gray-300 text-gray-500 rounded-lg font-medium transition disabled:cursor-not-allowed">
                    <span id="buttonText">Waiting for mobile confirmation...</span>
                </button>
            </form>

            {{-- Cancel Link --}}
            <div class="mt-4">
                <a href="{{ route('bookings.index') }}" 
                   class="text-sm text-gray-500 hover:text-gray-700">
                    Cancel Payment
                </a>
            </div>
        </div>

        {{-- Security Notice --}}
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500">
                🔒 Your payment is secured with 256-bit SSL encryption
            </p>
        </div>
    </div>
</div>

<script>
(function() {
    let countdown = 30;
    let buttonClickable = false;
    let userClicked = false;
    
    const countdownEl = document.getElementById('countdown');
    const buttonEl = document.getElementById('acceptButton');
    const buttonTextEl = document.getElementById('buttonText');

    // Enable button after 5 seconds
    setTimeout(() => {
        buttonClickable = true;
        buttonEl.disabled = false;
        buttonEl.className = 'w-full px-6 py-3 bg-green-600 hover:bg-green-500 text-white rounded-lg font-medium transition';
        buttonTextEl.textContent = 'I have accepted on my phone';
    }, 5000);

    // Track if user clicks the button
    buttonEl.addEventListener('click', () => {
        userClicked = true;
    });

    const timer = setInterval(() => {
        countdown--;
        countdownEl.textContent = countdown;

        if (countdown <= 0) {
            clearInterval(timer);
            
            // If user hasn't clicked by now, redirect back to create
            if (!userClicked) {
                window.location.href = '{{ route("bookings.create", request()->query()) }}';
            }
        }
    }, 1000);
})();
</script>
@endsection