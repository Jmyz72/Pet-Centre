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
        @if($isCustomerOwner)
        <div class="bg-white rounded-lg shadow p-5 md:col-span-2">
            <h2 class="text-sm font-semibold text-gray-600 mb-3">Release Code</h2>
            <p class="text-sm text-gray-600 mb-3">Generate a one-time code and share it with the merchant to complete your booking and release the payment.</p>
            <div id="alert-box" class="hidden mb-3 p-3 rounded-md"></div>
            <div class="flex items-center gap-3">
                <button id="btn-generate-code" class="rounded-md bg-indigo-600 text-white px-4 py-2 text-sm hover:bg-indigo-700 disabled:opacity-50">Generate Code</button>
                <div id="code-box" class="hidden text-lg font-mono tracking-widest bg-green-50 text-green-800 ring-1 ring-green-300 rounded px-3 py-1">— — — — — —</div>
                <div id="code-exp" class="hidden text-xs text-gray-500"></div>
            </div>
        </div>
        @endif

    </div>
</div>
<script>
// Generate Release Code (Customer)
(function() {
    const btn = document.getElementById('btn-generate-code');
    if (!btn) return;
    
    const codeBox = document.getElementById('code-box');
    const codeExp = document.getElementById('code-exp');
    const alertBox = document.getElementById('alert-box');

    function showAlert(message, type = 'info') {
        alertBox.textContent = message;
        alertBox.className = `mb-3 p-3 rounded-md ${type === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'}`;
        alertBox.classList.remove('hidden');
    }

    btn.addEventListener('click', async function() {
        btn.disabled = true; 
        btn.textContent = 'Generating...';
        
        try {
            const res = await fetch('/api/bookings/{{ $booking->id }}/release-code', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            
            const data = await res.json();
            
            if (data.success) {
                codeBox.textContent = data.code;
                codeBox.classList.remove('hidden');
                
                if (data.expires_at) {
                    codeExp.textContent = 'Expires: ' + new Date(data.expires_at).toLocaleString();
                    codeExp.classList.remove('hidden');
                }
                
                showAlert(data.message, 'success');
            } else {
                showAlert(data.message || 'Failed to generate code');
            }
        } catch (e) {
            showAlert('Error: ' + e.message);
        } finally {
            btn.disabled = false; 
            btn.textContent = 'Generate Code';
        }
    });
})();

</script>
@endsection