@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Success Header -->
        <div class="bg-white rounded-2xl shadow-lg mb-8 p-8 text-center">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Booking Confirmed!</h1>
            <p class="text-gray-600 text-lg">Your booking has been successfully processed and payment confirmed.</p>
            <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-800">
                    <strong>Booking ID:</strong> #{{ $booking->id }}<br>
                    <strong>Reference:</strong> {{ $booking->idempotency_key }}
                </p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Booking Details -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-7 4v8a2 2 0 002 2h4a2 2 0 002-2v-8M8 11V7a1 1 0 011-1h6a1 1 0 011 1v4"></path>
                    </svg>
                    Booking Details
                </h2>
                
                <div class="space-y-4">
                    <!-- Merchant -->
                    <div class="flex justify-between items-start">
                        <span class="text-sm font-medium text-gray-500">Merchant</span>
                        <div class="text-right">
                            <div class="font-semibold text-gray-900">{{ $booking->merchant->name ?? 'N/A' }}</div>
                            @if($booking->merchant->phone)
                            <div class="text-sm text-gray-500">{{ $booking->merchant->phone }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Service/Package -->
                    <div class="flex justify-between items-start">
                        <span class="text-sm font-medium text-gray-500">Service</span>
                        <div class="text-right">
                            @if($booking->booking_type === 'service')
                                <div class="font-semibold text-gray-900">{{ $booking->service->title ?? 'Service' }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->service->duration_minutes ?? 60 }} minutes</div>
                            @elseif($booking->booking_type === 'package')
                                <div class="font-semibold text-gray-900">{{ $booking->package->name ?? 'Package' }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->package->duration_minutes ?? 60 }} minutes</div>
                            @else
                                <div class="font-semibold text-gray-900">Pet Adoption</div>
                            @endif
                        </div>
                    </div>

                    <!-- Pet -->
                    <div class="flex justify-between items-start">
                        <span class="text-sm font-medium text-gray-500">Pet</span>
                        <div class="text-right">
                            @if($booking->customerPet)
                                <div class="font-semibold text-gray-900">{{ $booking->customerPet->name }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ optional($booking->customerPet->type)->name }} • 
                                    {{ optional($booking->customerPet->breed)->name }} • 
                                    {{ optional($booking->customerPet->size)->label }}
                                </div>
                            @elseif($booking->merchantPet)
                                <div class="font-semibold text-gray-900">{{ $booking->merchantPet->name }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ optional($booking->merchantPet->type)->name }} • 
                                    <span class="text-orange-600">Shelter Pet</span>
                                </div>
                            @else
                                <div class="text-gray-500">No pet specified</div>
                            @endif
                        </div>
                    </div>

                    <!-- Staff -->
                    @if($booking->staff)
                    <div class="flex justify-between items-start">
                        <span class="text-sm font-medium text-gray-500">Assigned Staff</span>
                        <div class="text-right">
                            <div class="font-semibold text-gray-900">{{ $booking->staff->name }}</div>
                            <div class="text-sm text-gray-500">{{ ucfirst($booking->staff->role) }}</div>
                        </div>
                    </div>
                    @endif

                    <!-- Status -->
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">Status</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Schedule & Payment -->
            <div class="space-y-6">
                <!-- Schedule -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m4 4H8m5-4v8a2 2 0 01-2 2H8a2 2 0 01-2-2V7a3 3 0 013-3h3a3 3 0 013 3z"></path>
                        </svg>
                        Schedule
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-semibold text-blue-900">
                                        {{ $booking->start_at->format('l, F j, Y') }}
                                    </div>
                                    <div class="text-sm text-blue-700">
                                        {{ $booking->start_at->format('g:i A') }} - {{ $booking->end_at->format('g:i A') }}
                                    </div>
                                </div>
                                <div class="text-blue-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        @if($booking->start_at->isFuture())
                        <div class="text-center text-sm text-gray-600">
                            <strong>{{ $booking->start_at->diffForHumans() }}</strong>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Payment -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Payment Information
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">Total Amount</span>
                            <span class="text-2xl font-bold text-green-600">RM {{ number_format($booking->price_amount, 2) }}</span>
                        </div>
                        
                        @if($booking->payments->isNotEmpty())
                            @foreach($booking->payments as $payment)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-sm font-medium text-gray-900">Payment #{{ $payment->id }}</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $payment->status === 'succeeded' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    <div>Method: {{ ucfirst($payment->provider) }}</div>
                                    <div>Reference: {{ $payment->payment_ref }}</div>
                                    @if($payment->meta)
                                        @if(isset($payment->meta['bank']))
                                            <div>Bank: {{ $payment->meta['bank'] }}</div>
                                        @endif
                                        @if(isset($payment->meta['last4']))
                                            <div>Card: ****{{ $payment->meta['last4'] }}</div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Release Code -->
                @if(isset($booking->meta['release_code']))
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m0 0a2 2 0 012 2 12.5 12.5 0 01-.1 1.526A2 2 0 0118 14H8.5a2 2 0 01-1.8-1.126L5.5 11 4 9m11-2V5a2 2 0 00-2-2H6a2 2 0 00-2 2v2"></path>
                        </svg>
                        Service Release Code
                    </h2>
                    
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                        <div class="mb-4">
                            <p class="text-sm text-yellow-800 font-medium mb-2">Important: Save this code!</p>
                            <p class="text-xs text-yellow-700">Give this code to the merchant when your service is completed to release payment.</p>
                        </div>
                        
                        <div class="bg-white border-2 border-yellow-300 rounded-lg p-4 inline-block">
                            <span class="text-3xl font-bold text-yellow-900 tracking-wider font-mono">{{ $booking->meta['release_code'] }}</span>
                        </div>
                        
                        <div class="mt-4">
                            <button onclick="copyToClipboard('{{ $booking->meta['release_code'] }}')" 
                                    class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                Copy Code
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 text-center space-x-4">
            <a href="{{ route('bookings.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                View All My Bookings
            </a>
            <a href="{{ route('bookings.show', $booking) }}" class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                View Booking Details
            </a>
        </div>

        <!-- Next Steps -->
        <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">What happens next?</h2>
            <div class="space-y-3 text-sm text-gray-600">
                @if($booking->booking_type === 'service')
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-xs font-semibold text-blue-600">1</span>
                        </div>
                        <div>You'll receive a confirmation email with appointment details</div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-xs font-semibold text-blue-600">2</span>
                        </div>
                        <div>Arrive 15 minutes early for your appointment</div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-xs font-semibold text-blue-600">3</span>
                        </div>
                        <div>Bring your pet and any relevant medical records</div>
                    </div>
                @elseif($booking->booking_type === 'package')
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-xs font-semibold text-green-600">1</span>
                        </div>
                        <div>Drop off your pet at the scheduled time</div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-xs font-semibold text-green-600">2</span>
                        </div>
                        <div>You'll be notified when the grooming is complete</div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-xs font-semibold text-green-600">3</span>
                        </div>
                        <div>Pick up your freshly groomed pet!</div>
                    </div>
                @else
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-xs font-semibold text-orange-600">1</span>
                        </div>
                        <div>Complete the adoption paperwork</div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-xs font-semibold text-orange-600">2</span>
                        </div>
                        <div>Schedule a meet-and-greet with your new pet</div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-xs font-semibold text-orange-600">3</span>
                        </div>
                        <div>Take your new family member home!</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success feedback
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = `
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Copied!
        `;
        button.classList.remove('bg-yellow-600', 'hover:bg-yellow-700');
        button.classList.add('bg-green-600', 'hover:bg-green-700');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-600', 'hover:bg-green-700');
            button.classList.add('bg-yellow-600', 'hover:bg-yellow-700');
        }, 2000);
    }).catch(function(err) {
        console.error('Failed to copy text: ', err);
        alert('Failed to copy code. Please copy manually: ' + text);
    });
}
</script>
@endsection