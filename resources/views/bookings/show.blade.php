@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Booking Details</h1>
            <p class="text-gray-600 mt-1">Booking #{{ $booking->id }}</p>
        </div>
        <a href="{{ route('bookings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Back to My Bookings
        </a>
    </div>

    @php($type = (string) ($booking->booking_type ?? ''))

    <!-- Booking Type Header -->
    <div class="mb-8">
        @if($type === 'adoption')
            <div class="bg-gradient-to-r from-pink-50 to-purple-50 rounded-xl p-6 border border-pink-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-purple-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-heart text-2xl text-white"></i>
                        </div>
                    </div>
                    <div class="ml-6">
                        <h2 class="text-2xl font-bold text-gray-900">Pet Adoption</h2>
                        <p class="text-gray-600">Welcoming a new family member</p>
                    </div>
                </div>
            </div>
        @elseif($type === 'service')
            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-6 border border-blue-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-stethoscope text-2xl text-white"></i>
                        </div>
                    </div>
                    <div class="ml-6">
                        <h2 class="text-2xl font-bold text-gray-900">Veterinary Service</h2>
                        <p class="text-gray-600">Professional healthcare for your pet</p>
                    </div>
                </div>
            </div>
        @elseif($type === 'package')
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-gift text-2xl text-white"></i>
                        </div>
                    </div>
                    <div class="ml-6">
                        <h2 class="text-2xl font-bold text-gray-900">Care Package</h2>
                        <p class="text-gray-600">Comprehensive grooming and wellness</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <!-- Service/Package Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                @if($type === 'adoption')
                    <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-home text-pink-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Adoption Details</h3>
                @elseif($type === 'service')
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-medical-bag text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Service Details</h3>
                @else
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-box text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Package Details</h3>
                @endif
            </div>
            
            <div class="space-y-3">
                <div>
                    <div class="text-sm text-gray-500">
                        @if($type === 'adoption')
                            Adoption Type
                        @elseif($type === 'service')
                            Service Name
                        @else
                            Package Name
                        @endif
                    </div>
                    <div class="text-lg font-medium text-gray-900">
                        @if($type === 'service')
                            {{ optional($booking->service)->title ?? 'Service' }}
                        @elseif($type === 'package')
                            {{ optional($booking->package)->name ?? 'Package' }}
                        @else
                            {{ ucfirst($type) }}
                        @endif
                    </div>
                </div>
                
                <div>
                    <div class="text-sm text-gray-500">Provider</div>
                    <div class="text-lg font-medium text-gray-900">{{ optional($booking->merchant)->name ?? '—' }}</div>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-info-circle text-gray-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Booking Status</h3>
            </div>
            
            @php($s = (string) ($booking->status ?? ''))
            <div class="flex items-center">
                <span @class([
                    'inline-flex items-center rounded-full px-4 py-2 text-sm font-medium ring-1',
                    'bg-green-100 text-green-800 ring-green-200' => $s === 'confirmed',
                    'bg-yellow-100 text-yellow-800 ring-yellow-200' => $s === 'pending',
                    'bg-red-100 text-red-800 ring-red-200' => $s === 'cancelled',
                    'bg-gray-100 text-gray-800 ring-gray-200' => ! in_array($s, ['confirmed','pending','cancelled']),
                ])>
                    @if($s === 'confirmed')
                        <i class="fas fa-check-circle mr-2"></i>
                    @elseif($s === 'pending')
                        <i class="fas fa-clock mr-2"></i>
                    @elseif($s === 'cancelled')
                        <i class="fas fa-times-circle mr-2"></i>
                    @else
                        <i class="fas fa-question-circle mr-2"></i>
                    @endif
                    {{ ucfirst($s ?: 'unknown') }}
                </span>
            </div>
        </div>

        <!-- Pet Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-paw text-purple-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Pet Information</h3>
            </div>
            
            @php($custPet = $booking->customerPet ?? null)
            @php($shelterPet = $booking->merchantPet ?? null)
            
            @if($custPet && ($custPet->name ?? null))
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-heart text-purple-600"></i>
                    </div>
                    <div>
                        <div class="text-lg font-medium text-gray-900">{{ $custPet->name }}</div>
                        @if(optional($custPet->petType)->name)
                            <div class="text-sm text-gray-500">{{ $custPet->petType->name }} <span class="ml-1 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Your Pet</span></div>
                        @endif
                    </div>
                </div>
            @elseif($shelterPet && ($shelterPet->name ?? null))
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-pink-100 to-red-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-home text-pink-600"></i>
                    </div>
                    <div>
                        <div class="text-lg font-medium text-gray-900">{{ $shelterPet->name }}</div>
                        @if(optional($shelterPet->petType)->name)
                            <div class="text-sm text-gray-500">{{ $shelterPet->petType->name }} <span class="ml-1 text-xs bg-pink-100 text-pink-800 px-2 py-1 rounded">Pet ID: {{ $shelterPet->id }}</span></div>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-gray-500 italic">No pet information available</div>
            @endif
        </div>

        <!-- Schedule -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-calendar-alt text-indigo-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Schedule</h3>
            </div>
            
            <div class="space-y-3">
                <div>
                    <div class="text-sm text-gray-500">Start Time</div>
                    <div class="text-lg font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($booking->start_at)->format('l, M j, Y') }}
                        <span class="text-base text-indigo-600 font-semibold">
                            {{ \Carbon\Carbon::parse($booking->start_at)->format('g:i A') }}
                        </span>
                    </div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">End Time</div>
                    <div class="text-lg font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($booking->end_at)->format('l, M j, Y') }}
                        <span class="text-base text-indigo-600 font-semibold">
                            {{ \Carbon\Carbon::parse($booking->end_at)->format('g:i A') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Assignment -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-user-md text-orange-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Assigned Staff</h3>
            </div>
            
            @if(optional($booking->staff)->name)
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-100 to-red-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-user text-orange-600"></i>
                    </div>
                    <div>
                        <div class="text-lg font-medium text-gray-900">{{ $booking->staff->name }}</div>
                        <div class="text-sm text-gray-500">Professional Staff</div>
                    </div>
                </div>
            @else
                <div class="text-gray-500 italic">Staff will be assigned closer to your appointment</div>
            @endif
        </div>

        <!-- Payment Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:col-span-2">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-credit-card text-green-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Payment Information</h3>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-500 mb-1">Total Amount</div>
                    <div class="text-2xl font-bold text-gray-900">RM {{ number_format((float)($booking->price_amount ?? 0), 2) }}</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-500 mb-1">Payment Reference</div>
                    <div class="text-lg font-mono text-gray-900">{{ $booking->payment_ref ?? 'Pending' }}</div>
                </div>
            </div>
        </div>

        <!-- Release Code (Customer action) -->
        @php($isCustomerOwner = (int) auth()->id() === (int) $booking->customer_id)
        @if($isCustomerOwner && isset($booking->meta['release_code']))
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl shadow-sm border border-yellow-200 p-6 md:col-span-2">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-key text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Release Code</h3>
                    <p class="text-sm text-gray-600">Share this code with the merchant when your service is completed</p>
                </div>
            </div>
            
            <div class="bg-white rounded-lg border-2 border-dashed border-yellow-300 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-yellow-100 to-orange-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-lock-open text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <div class="text-3xl font-mono tracking-widest text-yellow-900 font-bold">{{ $booking->meta['release_code'] }}</div>
                            <div class="text-sm text-yellow-700 mt-1">Present this code to complete your service</div>
                        </div>
                    </div>
                    <button onclick="copyToClipboard('{{ $booking->meta['release_code'] }}')" 
                            class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                        <i class="fas fa-copy mr-2"></i> Copy Code
                    </button>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success feedback
        const button = event.target.closest('button');
        const originalHtml = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check mr-2"></i> Copied!';
        button.classList.remove('from-yellow-500', 'to-orange-600', 'hover:from-yellow-600', 'hover:to-orange-700');
        button.classList.add('from-green-500', 'to-emerald-600', 'hover:from-green-600', 'hover:to-emerald-700');
        
        setTimeout(() => {
            button.innerHTML = originalHtml;
            button.classList.remove('from-green-500', 'to-emerald-600', 'hover:from-green-600', 'hover:to-emerald-700');
            button.classList.add('from-yellow-500', 'to-orange-600', 'hover:from-yellow-600', 'hover:to-orange-700');
        }, 2000);
    }).catch(function(err) {
        console.error('Failed to copy text: ', err);
        alert('Failed to copy code. Please copy manually: ' + text);
    });
}
</script>
@endsection