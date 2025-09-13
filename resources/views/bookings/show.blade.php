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
            <div class="flex items-center gap-3">
                <button id="btn-generate-code" class="rounded-md ring-1 ring-gray-300 px-4 py-2 text-sm hover:bg-gray-50">Generate Code</button>
                <div id="code-box" class="hidden text-lg font-mono tracking-widest ring-1 ring-gray-300 rounded px-3 py-1">— — — — — —</div>
                <div id="code-exp" class="hidden text-xs text-gray-500"></div>
            </div>
        </div>
        @endif

        <!-- Release with Code (Merchant action) -->
        @php($isMerchantOwner = optional(auth()->user()->merchantProfile)->id === (int) $booking->merchant_id)
        @if($isMerchantOwner)
        <div class="bg-white rounded-lg shadow p-5 md:col-span-2">
            <h2 class="text-sm font-semibold text-gray-600 mb-3">Complete Booking with Code</h2>
            <form method="POST" action="{{ route('api.bookings.release', $booking) }}" class="flex flex-wrap items-center gap-3">
                @csrf
                <input name="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6"
                       class="ring-1 ring-gray-300 rounded px-3 py-2 text-sm"
                       placeholder="Enter 6-digit code" required>
                <button class="rounded-md ring-1 ring-gray-300 px-4 py-2 text-sm hover:bg-gray-50">Release</button>
            </form>
            <p class="text-xs text-gray-500 mt-2">Ask the customer for the one-time code. Codes expire in 30 minutes.</p>
        </div>
        @endif
    </div>
</div>
<script>
(function() {
    const btn = document.getElementById('btn-generate-code');
    if (!btn) return; // not the customer owner
    const codeBox = document.getElementById('code-box');
    const codeExp = document.getElementById('code-exp');

    btn.addEventListener('click', async function() {
        btn.disabled = true; btn.textContent = 'Generating…';
        try {
            const res = await fetch(`{{ route('api.bookings.generateReleaseCode', $booking->id) }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            if (!res.ok) {
                const text = await res.text();
                throw new Error(`Failed (${res.status}): ${text || 'Unknown error'}`);
            }
            const data = await res.json();
            codeBox.textContent = data.code;
            codeBox.classList.remove('hidden');
            if (data.expires_at) {
                codeExp.textContent = 'Expires at: ' + new Date(data.expires_at).toLocaleString();
                codeExp.classList.remove('hidden');
            }
        } catch (e) {
            alert(e.message || 'Something went wrong');
        } finally {
            btn.disabled = false; btn.textContent = 'Generate Code';
        }
    });
})();
</script>
@endsection