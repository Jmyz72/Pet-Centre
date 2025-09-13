@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">My Bookings</h1>
    </div>


    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-semibold text-gray-600">
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Merchant</th>
                    <th class="px-4 py-3">Service / Package</th>
                    <th class="px-4 py-3">Pet</th>
                    <th class="px-4 py-3">Start</th>
                    <th class="px-4 py-3">End</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Price</th>
                    <th class="px-4 py-3">Payment Ref</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse ($bookings as $b)
                    <tr onclick="window.location='{{ route('bookings.show', $b->id) }}'" class="cursor-pointer hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $b->id }}</td>
                        <td class="px-4 py-3">
                            {{ optional($b->merchant)->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            @php($type = (string)($b->booking_type ?? ''))
                            <div class="font-medium">
                                @if($type === 'service')
                                    {{ optional($b->service)->title ?? 'Service' }}
                                @elseif($type === 'package')
                                    {{ optional($b->package)->name ?? 'Package' }}
                                @else
                                    {{ $type !== '' ? ucfirst($type) : '—' }}
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @php($custPet = $b->customerPet ?? null)
                            @php($shelterPet = $b->merchantPet ?? null)

                            @if($custPet && ($custPet->name ?? null))
                                <div class="font-medium">{{ $custPet->name }}</div>
                                @if(optional($custPet->type)->name)
                                    <div class="text-xs text-gray-500">{{ $custPet->type->name }}</div>
                                @endif
                            @elseif($shelterPet && ($shelterPet->name ?? null))
                                <div class="font-medium">{{ $shelterPet->name }}</div>
                                @if(optional($shelterPet->type)->name)
                                    <div class="text-xs text-gray-500">{{ $shelterPet->type->name }}</div>
                                @endif
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($b->start_at)->format('d M Y, h:i A') }}
                        </td>
                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($b->end_at)->format('d M Y, h:i A') }}
                        </td>
                        <td class="px-4 py-3">
                            @php($s = (string) ($b->status ?? ''))
                            <span @class([
                                'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium ring-1',
                                'bg-green-100 text-green-800 ring-green-200' => $s === 'confirmed',
                                'bg-yellow-100 text-yellow-800 ring-yellow-200' => $s === 'pending',
                                'bg-red-100 text-red-800 ring-red-200' => $s === 'cancelled',
                                'bg-gray-100 text-gray-800 ring-gray-200' => ! in_array($s, ['confirmed','pending','cancelled']),
                            ])>
                                {{ ucfirst($s ?: 'unknown') }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            RM {{ number_format((float)($b->price_amount ?? 0), 2) }}
                        </td>
                        <td class="px-4 py-3">{{ $b->payment_ref ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            {{-- Optional: details/cancel buttons --}}
                            {{-- <a href="{{ route('bookings.show', $b) }}" class="text-indigo-600 hover:underline">View</a> --}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-4 py-6 text-center text-gray-500">
                            No bookings yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $bookings->links() }}
    </div>
</div>
@endsection