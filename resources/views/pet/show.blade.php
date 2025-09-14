@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Back Button -->
    <a href="{{ route('pets.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 mb-6 transition-colors">
        <i class="fas fa-arrow-left mr-2 transition-transform"></i>
        Back to All Pets
    </a>

    <!-- Pet Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="md:flex">
            <!-- Pet Image -->
            <div class="md:w-2/5">
                <img src="{{ $pet->photo_url ?? asset('images/placeholder/pet.png') }}" 
                     alt="{{ $pet->name }}" class="w-full h-64 md:h-full object-cover">
            </div>
            
            <!-- Pet Details -->
            <div class="p-6 md:w-3/5">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $pet->name }}</h1>
                        <p class="text-gray-600">{{ $pet->type->name ?? '—' }} • {{ $pet->breed->name ?? 'Mixed Breed' }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-medium">
                        Available
                    </span>
                </div>

                <div class="mt-4 flex items-center space-x-4">
                    @if(isset($pet->sex))
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-venus-mars mr-2"></i>
                        <span>{{ ucfirst($pet->sex) }}</span>
                    </div>
                    @endif
                    
                    @if(isset($pet->age_human))
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-birthday-cake mr-2"></i>
                        <span>{{ $pet->age_human }}</span>
                    </div>
                    @endif
                </div>

                <div class="mt-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">About {{ $pet->name }}</h2>
                    <p class="text-gray-700">
                        {{ $pet->description ?? 'No description available.' }}
                    </p>
                </div>

                <div class="mt-8 flex space-x-4">
                    <button class="flex-1 bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                        Adopt Me
                    </button>
                    <button class="flex-1 border border-indigo-600 text-indigo-600 py-3 rounded-lg hover:bg-indigo-50 transition-colors font-medium">
                        Ask About Me
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection