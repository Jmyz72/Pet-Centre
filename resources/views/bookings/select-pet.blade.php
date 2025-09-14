@extends('layouts.app')

@php
    $dg = fn($v, $k, $d=null) => data_get($v, $k, $d);
@endphp

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Select Your Pet</h1>
            <p class="text-sm text-gray-500 mt-1">
                Choose which pet you want to book for
            </p>
        </div>
    </div>

    {{-- Error Message --}}
    @if(session('error'))
        <div class="mb-6 rounded-lg bg-red-50 p-4 text-red-800 border border-red-200">
            <p class="text-sm">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Pet Selection Grid --}}
    @if(($pets ?? collect())->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
            <div class="text-gray-400 text-4xl mb-4">🐾</div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Pets Found</h3>
            <p class="text-sm text-gray-500 mb-4">You don't have any pets registered yet.</p>
            <a href="{{ route('customer.pets.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500">
                Add Pet to Profile
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($pets as $pet)
                @php
                    $pid = $dg($pet, 'id');
                    $photo = $dg($pet, 'photo_url') ?: 'https://placehold.co/600x400?text=Pet';
                    $typeName = $dg($pet, 'type_name');
                    $sizeName = $dg($pet, 'size_name') ?: $dg($pet, 'size_label');
                    $breedName = $dg($pet, 'breed_name');
                @endphp
                <a href="{{ $continueUrl }}&customer_pet_id={{ $pid }}" 
                   class="group block bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg hover:border-indigo-200 transition">
                    <div class="aspect-video bg-gray-100 overflow-hidden">
                        <img src="{{ $photo }}" alt="{{ $dg($pet, 'name', 'Pet') }} photo"
                             class="w-full h-full object-cover group-hover:scale-[1.02] transition">
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium text-gray-900 mb-2">
                            {{ $dg($pet, 'name', 'My Pet') }}
                        </h3>
                        <div class="text-sm text-gray-500 space-y-1">
                            <div>Type: {{ $typeName ?? '#'.$dg($pet, 'pet_type_id', '?') }}</div>
                            @if($dg($pet, 'size_id'))
                                <div>Size: {{ $sizeName ?? '#'.$dg($pet, 'size_id') }}</div>
                            @endif
                            @if($dg($pet, 'pet_breed_id') || $dg($pet, 'breed_id'))
                                <div>Breed: {{ $breedName ?? '#'.($dg($pet, 'pet_breed_id') ?? $dg($pet, 'breed_id')) }}</div>
                            @endif
                        </div>
                        <div class="mt-3 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-indigo-50 text-indigo-700 border border-indigo-200 group-hover:bg-indigo-100">
                                Select This Pet
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    {{-- Actions --}}
    <div class="flex items-center justify-between mt-8">
        <a href="{{ route('bookings.index') }}" 
           class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50">
            Cancel
        </a>
    </div>
</div>
@endsection