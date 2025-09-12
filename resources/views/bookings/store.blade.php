

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Booking Submitted</h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('bookings.create') }}" class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">New Booking</a>
            <a href="{{ route('bookings.index') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm text-white hover:bg-indigo-500">My Bookings</a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 p-4 text-red-800">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @isset($booking)
        {{-- Detailed confirmation when a Booking model is provided --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Confirmation Details</h2>
            </div>
            <dl class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-8 text-sm">
                <div>
                    <dt class="text-gray-500">Booking ID</dt>
                    <dd class="text-gray-900">#{{ $booking->id }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Type</dt>
                    <dd class="text-gray-900">{{ ucfirst($booking->booking_type) }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Merchant</dt>
                    <dd class="text-gray-900">{{ $booking->merchant->display_name ?? $booking->merchant->name ?? ('Merchant #'.$booking->merchant_id) }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Customer</dt>
                    <dd class="text-gray-900">#{{ $booking->customer_id }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Start</dt>
                    <dd class="text-gray-900">{{ $booking->start_at }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">End</dt>
                    <dd class="text-gray-900">{{ $booking->end_at }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Status</dt>
                    <dd>
                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium 
                            @if($booking->status === 'confirmed') bg-green-100 text-green-800
                            @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $booking->status }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-500">Price (MYR)</dt>
                    <dd class="text-gray-900">{{ number_format((float) ($booking->price_amount ?? 0), 2) }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Payment Ref</dt>
                    <dd class="text-gray-900">{{ $booking->payment_ref ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Staff</dt>
                    <dd class="text-gray-900">{{ $booking->staff_id ? ('#'.$booking->staff_id) : 'Not assigned' }}</dd>
                </div>
            </dl>
        </div>
    @else
        {{-- Fallback summary when we don't have a Booking model (e.g., redirectless preview) --}}
        @php
            $data = session('booking_payload') ?? request()->all();
        @endphp
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Submission Summary</h2>
                <p class="mt-1 text-sm text-gray-500">We received your booking request. Use the buttons above to view all bookings or create another one.</p>
            </div>
            <div class="px-6 py-4">
                <pre class="text-xs bg-gray-50 border border-gray-200 rounded p-3 overflow-x-auto">{{ json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
    @endisset
</div>
@endsection