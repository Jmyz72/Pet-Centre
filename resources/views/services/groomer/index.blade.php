@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-semibold mb-6">All Groomer Packages</h1>

    {{-- Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        @forelse($packages as $package)
            <div class="block rounded-2xl border hover:shadow-lg p-4 transition">
                <div class="text-sm text-gray-500 mb-1">
                    Merchant: {{ $package->merchantProfile->name ?? 'Unknown' }}
                </div>
                <div class="font-semibold text-lg mb-2">{{ $package->name }}</div>
                <p class="text-gray-600 text-sm mb-2">{{ Str::limit($package->description, 100) }}</p>
                
                <ul class="text-gray-700 text-sm mb-2 space-y-1">
                    <li><strong>Price:</strong> RM {{ number_format($package->price, 2) }}</li>
                    <li><strong>Duration:</strong> {{ $package->duration_minutes }} mins</li>
                    <li><strong>Types:</strong> {{ $package->packageTypes->pluck('name')->join(', ') }}</li>
                    <li><strong>Pet Types:</strong> {{ $package->petTypes->pluck('name')->join(', ') }}</li>
                    <li><strong>Breeds:</strong> {{ $package->petBreeds->pluck('name')->join(', ') }}</li>
                    <li><strong>Sizes:</strong> {{ $package->packageSizes->pluck('label')->join(', ') }}</li>
                </ul>

                @if($package->variations->isNotEmpty())
                    <p class="font-semibold text-sm mb-1">Variations:</p>
                    <ul class="text-gray-700 text-sm list-disc list-inside">
                        @foreach($package->variations as $variation)
                            <li>{{ $variation->name }} RM {{ number_format($variation->price, 2) }}</li>
                        @endforeach
                    </ul>
                @endif

                <a href="#"
                   class="mt-3 inline-block rounded bg-gray-900 text-white px-4 py-2 text-sm hover:bg-gray-700 transition">
                    Book Now
                </a>
            </div>
        @empty
            <div class="col-span-full text-gray-600">No active packages available.</div>
        @endforelse
    </div>
</div>
@endsection
