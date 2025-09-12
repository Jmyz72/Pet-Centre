@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">My Bookings</h1>
        <a href="{{ route('bookings.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-500 transition-colors">
            + New Booking
        </a>
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

    <div class="overflow-x-auto bg-white shadow-sm ring-1 ring-gray-200 rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">End</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price (MYR)</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment Ref</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($bookings as $b)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-900">#{{ $b->id }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ ucfirst($b->booking_type) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $b->start_at }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $b->end_at }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium 
                                @if($b->status === 'confirmed') bg-green-100 text-green-800
                                @elseif($b->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $b->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ number_format((float) $b->price_amount, 2) }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $b->payment_ref ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">No bookings yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
