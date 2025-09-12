@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-50">
    <div class="bg-white rounded-xl border shadow p-8 max-w-md text-center">
        <div class="text-green-500 text-6xl mb-4">✅</div>
        <h1 class="text-3xl font-semibold mb-2">Booking Successful</h1>
        <p class="text-gray-600 mb-6">Your booking and payment have been confirmed. You can view it in My Bookings.</p>
        <a href="{{ route('bookings.index') }}" class="inline-block px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
            Go to My Bookings
        </a>
    </div>
</div>
@endsection
