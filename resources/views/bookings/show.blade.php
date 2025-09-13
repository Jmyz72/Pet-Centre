@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Booking #{{ $booking->id }}</h1>
        <a href="{{ route('bookings.index') }}" class="text-sm text-indigo-600 hover:underline">← Back to My Bookings</a>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <!-- Merchant -->
        <div class="bg-white rounded-lg shadow p-5">
            <h2 class="text-sm font-semibold text-gray-600 mb-2">Merchant</h2>
            <div class="text-gray-900">{{ optional($booking->merchant)->name ?? '—' }}</div>
        </div>

        <!-- Status -->
        <div class="bg-white rounded-lg shadow p-5">
            <h2 class="text-sm font-semibold text-gray-600 mb-2">Status</h2>
            @php($s = (string) ($booking->status ?? ''))
            <span @class([
                'inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ring-1',
                'bg-green-100 text-green-800 ring-green-200' => $s === 'confirmed',
                'bg-yellow-100 text-yellow-800 ring-yellow-200' => $s === 'pending',
                'bg-red-100 text-red-800 ring-red-200' => $s === 'cancelled',
                'bg-gray-100 text-gray-800 ring-gray-200' => ! in_array($s, ['confirmed','pending','cancelled']),
            ])>
                {{ ucfirst($s ?: 'unknown') }}
            </span>
        </div>

        <!-- Service / Package -->
        <div class="bg-white rounded-lg shadow p-5">
            <h2 class="text-sm font-semibold text-gray-600 mb-2">Service / Package</h2>
            @php($type = (string) ($booking->booking_type ?? ''))
            <div class="text-gray-900">
                @if($type === 'service')
                    {{ optional($booking->service)->title ?? 'Service' }}
                @elseif($type === 'package')
                    {{ optional($booking->package)->name ?? 'Package' }}
                @else
                    {{ $type !== '' ? ucfirst($type) : '—' }}
                @endif
            </div>
        </div>

        <!-- Pet -->
        <div class="bg-white rounded-lg shadow p-5">
            <h2 class="text-sm font-semibold text-gray-600 mb-2">Pet</h2>
            @php($custPet = $booking->customerPet ?? null)
            @php($shelterPet = $booking->merchantPet ?? null)
            @if($custPet && ($custPet->name ?? null))
                <div class="text-gray-900">{{ $custPet->name }}</div>
                @if(optional($custPet->type)->name)
                    <div class="text-gray-500 text-sm">{{ $custPet->type->name }}</div>
                @endif
            @elseif($shelterPet && ($shelterPet->name ?? null))
                <div class="text-gray-900">{{ $shelterPet->name }}</div>
                @if(optional($shelterPet->type)->name)
                    <div class="text-gray-500 text-sm">{{ $shelterPet->type->name }} <span class="ml-1 text-xs text-gray-400">(Shelter)</span></div>
                @endif
            @else
                <div class="text-gray-900">—</div>
            @endif
        </div>

        <!-- Schedule -->
        <div class="bg-white rounded-lg shadow p-5">
            <h2 class="text-sm font-semibold text-gray-600 mb-2">Schedule</h2>
            <div class="text-gray-900">
                {{ \Carbon\Carbon::parse($booking->start_at)->format('d M Y, h:i A') }}
                –
                {{ \Carbon\Carbon::parse($booking->end_at)->format('d M Y, h:i A') }}
            </div>
        </div>

        <!-- Staff (if any) -->
        <div class="bg-white rounded-lg shadow p-5">
            <h2 class="text-sm font-semibold text-gray-600 mb-2">Assigned Staff</h2>
            <div class="text-gray-900">{{ optional($booking->staff)->name ?? '—' }}</div>
        </div>

        <!-- Payment -->
        <div class="bg-white rounded-lg shadow p-5 md:col-span-2">
            <h2 class="text-sm font-semibold text-gray-600 mb-2">Payment</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <div class="text-xs text-gray-500">Amount</div>
                    <div class="text-gray-900">RM {{ number_format((float)($booking->price_amount ?? 0), 2) }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500">Reference</div>
                    <div class="text-gray-900">{{ $booking->payment_ref ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500">Type</div>
                    <div class="text-gray-900">{{ $booking->payment_method ?? '—' }}</div>
                </div>
            </div>
        </div>

        <!-- Release Code (Customer action) -->
        @php($isCustomerOwner = (int) auth()->id() === (int) $booking->customer_id)
        @if($isCustomerOwner && isset($booking->meta['release_code']))
        <div class="bg-white rounded-lg shadow p-5 md:col-span-2">
            <h2 class="text-sm font-semibold text-gray-600 mb-3">Release Code</h2>
            <p class="text-sm text-gray-600 mb-3">Share this code with the merchant when your service is completed to release the payment.</p>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-lg font-mono tracking-widest text-yellow-900 font-bold">{{ $booking->meta['release_code'] }}</div>
                        <div class="text-xs text-yellow-700 mt-1">Give this code to the merchant</div>
                    </div>
                    <button onclick="copyToClipboard('{{ $booking->meta['release_code'] }}')" 
                            class="px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm rounded-lg transition-colors">
                        Copy
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
        const originalText = button.innerHTML;
        button.innerHTML = 'Copied!';
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