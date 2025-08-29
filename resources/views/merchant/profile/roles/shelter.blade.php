@php
    // Prefer a paginator injected by controller; fallback to relation collection
    $pets = $pets ?? ($profile->relationLoaded('pets') ? $profile->pets : collect());
@endphp

@php
    $hasPetShowRoute = \Illuminate\Support\Facades\Route::has('pets.show');
    $hasAdoptRoute   = \Illuminate\Support\Facades\Route::has('adoptions.create');
@endphp

<div class="mt-8">
    <h2 class="text-2xl font-semibold mb-4">Pets Available for Adoption</h2>

    @if($pets && $pets->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($pets as $pet)
                @php($url = $hasPetShowRoute ? route('pets.show', $pet) : ($hasAdoptRoute ? route('adoptions.create', $pet) : null))
                @if($url)
                    <a href="{{ $url }}" class="block">
                @else
                    <div class="block">
                @endif
                    @if($pet->image)
                        <img loading="lazy" src="{{ asset('storage/' . $pet->image) }}" alt="{{ $pet->name }}" class="w-full h-40 object-cover rounded-md mb-3">
                    @else
                        <div class="w-full h-40 flex items-center justify-center bg-gray-200 rounded-md mb-3">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m0 0l3-3m-3 3l-3-3" />
                            </svg>
                        </div>
                    @endif
                    <div>
                        <div class="text-lg font-semibold">{{ $pet->name }}</div>
                        <div class="mt-1 text-sm text-gray-600 flex items-center gap-2">
                            <span class="inline-block px-2 py-0.5 rounded bg-gray-100">
                                {{ optional($pet->type)->name ?? ucfirst($pet->type) }}
                            </span>
                            @if(!is_null($pet->age))
                                <span class="inline-block px-2 py-0.5 rounded bg-gray-100">{{ $pet->age }} yrs</span>
                            @endif
                        </div>
                    </div>
                @if($url)
                    </a>
                @else
                    </div>
                @endif
                <div class="mt-3">
                    @if($url)
                        <a href="{{ $url }}" class="inline-block px-3 py-1 rounded border hover:bg-gray-50 text-sm">View details</a>
                    @elseif($hasAdoptRoute)
                        <a href="{{ route('adoptions.create', $pet) }}" class="inline-block px-3 py-1 rounded border hover:bg-gray-50 text-sm">Adopt</a>
                    @else
                        <span class="inline-block px-3 py-1 rounded border text-sm text-gray-500 cursor-default">Details</span>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Pagination if a paginator was provided --}}
        @if($pets instanceof \Illuminate\Pagination\AbstractPaginator)
            <div class="mt-6">{{ $pets->links() }}</div>
        @endif
    @else
        <div class="text-gray-500 italic">No pets available for adoption.</div>
    @endif
</div>