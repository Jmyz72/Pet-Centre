@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Back button -->
    <div class="mb-6">
        <a href="{{ url('/') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Home
        </a>
    </div>

    <!-- Page header -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Adopt a Pet</h1>
        <p class="mt-2 text-gray-600">Browse all adorable friends currently looking for a home.</p>
    </div>

    <!-- Empty state -->
    @if(!$pets->count())
        <div class="rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 p-12 text-center">
            <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
                <svg class="h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-800">No pets available right now</h2>
            <p class="mt-1 text-gray-600">Check back soon—rescues are updated regularly.</p>
        </div>
    @else
        <!-- Grid of pet cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($pets as $pet)
                <a href="{{ route('pets.show', $pet) }}" class="group block rounded-xl overflow-hidden bg-white shadow-md border border-gray-100 hover:shadow-lg transition">
                    <div class="h-48 bg-gray-200 overflow-hidden">
                        <img src="{{ $pet->photo_url ?? asset('images/placeholder/pet.png') }}" 
                             alt="{{ $pet->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <div class="p-4">
                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                            @if(isset($pet->sex)) 
                                <span class="px-2 py-1 rounded-full bg-gray-100">{{ ucfirst($pet->sex) }}</span> 
                            @endif
                            @if(isset($pet->age_human)) 
                                <span>{{ $pet->age_human }}</span> 
                            @endif
                        </div>
                        <h3 class="font-semibold text-gray-900">{{ $pet->name }}</h3>
                        <p class="text-sm text-gray-600">
                            {{ $pet->type->name ?? '—' }} • {{ $pet->breed->name ?? 'Mixed' }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $pets->links() }}
        </div>
    @endif
</div>
@endsection