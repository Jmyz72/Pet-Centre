@php
    // Prefer a paginator injected by controller; fallback to relation collection
    $pets = $pets ?? ($profile->relationLoaded('pets') ? $profile->pets : collect());
@endphp


<div class="mt-8">
    <div class="flex items-end justify-between gap-4 mb-4">
        <h2 class="text-2xl font-semibold">Pets Available for Adoption</h2>
        @if($pets instanceof \Illuminate\Pagination\AbstractPaginator)
            <div class="text-sm text-gray-500">{{ $pets->total() }} result{{ $pets->total() === 1 ? '' : 's' }}</div>
        @elseif($pets)
            <div class="text-sm text-gray-500">{{ $pets->count() }} result{{ $pets->count() === 1 ? '' : 's' }}</div>
        @endif
    </div>

    @if($pets && $pets->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($pets as $pet)
                <div class="group block rounded-xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
                        {{-- Image --}}
                        <div class="relative">
                            @if($pet->image)
                                <img loading="lazy" src="{{ asset('storage/' . $pet->image) }}" alt="{{ $pet->name }} photo" class="w-full h-44 object-cover rounded-t-xl">
                            @else
                                <div class="w-full h-44 flex items-center justify-center bg-gray-100 rounded-t-xl">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m0 0l3-3m-3 3l-3-3" />
                                    </svg>
                                    <span class="sr-only">No image available</span>
                                </div>
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="p-4">
                            <div class="flex items-start justify-between gap-2">
                                <h3 class="text-base font-semibold text-gray-900 leading-snug">{{ $pet->name }}</h3>
                                @php($status = strtolower($pet->status ?? ''))
                                @php($statusStyles = [
                                    'available' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                                    'reserved'  => 'bg-amber-50 text-amber-700 ring-amber-200',
                                    'adopted'   => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
                                    'draft'     => 'bg-gray-50 text-gray-600 ring-gray-200',
                                ])
                                @if(!empty($status))
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-medium ring-1 {{ $statusStyles[$status] ?? 'bg-gray-50 text-gray-600 ring-gray-200' }}">{{ ucfirst($status) }}</span>
                                @endif
                            </div>

                            {{-- Chips row --}}
                            <div class="mt-2 text-[11px] text-gray-700 flex flex-wrap items-center gap-1.5">
                                {{-- Pet Type --}}
                                <span class="inline-flex items-center rounded-full bg-gray-50 ring-1 ring-gray-200 px-2 py-0.5">{{ optional($pet->petType)->name ?? 'Pet' }}</span>

                                {{-- Breed (optional) --}}
                                @if(!empty(optional($pet->petBreed)->name))
                                    <span class="inline-flex items-center rounded-full bg-gray-50 ring-1 ring-gray-200 px-2 py-0.5">{{ optional($pet->petBreed)->name }}</span>
                                @endif

                                {{-- Size (optional) --}}
                                @if(!empty(optional($pet->size)->label))
                                    <span class="inline-flex items-center rounded-full bg-gray-50 ring-1 ring-gray-200 px-2 py-0.5">{{ optional($pet->size)->label }}</span>
                                @endif

                                {{-- Sex (optional) --}}
                                @if(!empty($pet->sex) && strtolower($pet->sex) !== 'unknown')
                                    <span class="inline-flex items-center rounded-full bg-gray-50 ring-1 ring-gray-200 px-2 py-0.5">{{ ucfirst($pet->sex) }}</span>
                                @endif

                                {{-- Age (DoB preferred) --}}
                                @if(!empty($pet->date_of_birth))
                                    <span class="inline-flex items-center rounded-full bg-gray-50 ring-1 ring-gray-200 px-2 py-0.5">{{ \Carbon\Carbon::parse($pet->date_of_birth)->age }} yrs</span>
                                @elseif(!is_null($pet->age))
                                    <span class="inline-flex items-center rounded-full bg-gray-50 ring-1 ring-gray-200 px-2 py-0.5">{{ $pet->age }} yrs</span>
                                @endif

                                {{-- Weight (optional) --}}
                                @if(!empty($pet->weight_kg))
                                    <span class="inline-flex items-center rounded-full bg-gray-50 ring-1 ring-gray-200 px-2 py-0.5">{{ rtrim(rtrim(number_format((float)$pet->weight_kg, 2, '.', ''), '0'), '.') }} kg</span>
                                @endif

                                {{-- Vaccinated --}}
                                @if(isset($pet->vaccinated))
                                    @if((int)$pet->vaccinated === 1)
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 ring-1 ring-emerald-200 px-2 py-0.5 text-emerald-700">Vaccinated</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-50 ring-1 ring-gray-200 px-2 py-0.5 text-gray-600">Not vaccinated</span>
                                    @endif
                                @endif

                                {{-- Adoption fee --}}
                                @if(!is_null($pet->adoption_fee))
                                    <span class="inline-flex items-center rounded-full bg-indigo-50 ring-1 ring-indigo-200 px-2 py-0.5 text-indigo-700">RM {{ number_format((float)$pet->adoption_fee, 2) }}</span>
                                @endif
                            </div>

                            @if(!empty($pet->description))
                                <p class="mt-3 text-sm text-gray-600 leading-relaxed">{{ \Illuminate\Support\Str::limit($pet->description, 140) }}</p>
                            @endif

                            {{-- Footer button --}}
                            <div class="mt-4">
                                @if(strtolower($pet->status ?? '') === 'available')
                                    <a href="{{ route('bookings.create', ['merchant_id' => $profile->id, 'pet_id' => $pet->id]) }}" class="w-full inline-flex justify-center items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">Adopt</a>
                                @else
                                    <span class="w-full inline-flex justify-center items-center rounded-md bg-gray-200 px-3 py-2 text-sm font-semibold text-gray-500 cursor-not-allowed">Adopt</span>
                                @endif
                            </div>
                        </div>
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