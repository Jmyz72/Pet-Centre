@extends('layouts.app')

@section('content')
@php
    // Simple role → color map for badges/chips
    $roleColors = [
        'clinic' => 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200',
        'shelter' => 'bg-sky-100 text-sky-700 ring-1 ring-sky-200',
        'groomer' => 'bg-amber-100 text-amber-700 ring-1 ring-amber-200',
    ];
    $currentRole = request('role');
    $search     = request('search');
@endphp

<div class="relative isolate bg-gray-50">
    {{-- Hero / Heading --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-8">
        <div class="rounded-3xl bg-gradient-to-r from-emerald-50 via-sky-50 to-amber-50 p-6 sm:p-8 border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-gray-900">Find Merchants</h1>
                    <p class="mt-1 text-sm text-gray-600">Browse groomers, clinics, and shelters in the PetCentre network.</p>
                </div>
                {{-- Quick role chips --}}
                <div class="flex flex-wrap items-center gap-2">
                    @foreach (['' => 'All', 'clinic' => 'Clinic', 'shelter' => 'Shelter', 'groomer' => 'Groomer'] as $value => $label)
                        @php
                            $isActive = ($currentRole === $value) || ($value === '' && $currentRole === null);
                        @endphp
                        <a
                            href="{{ request()->fullUrlWithQuery(['role' => $value ?: null]) }}"
                            class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-sm transition
                                {{ $isActive ? 'bg-gray-900 text-white shadow-sm' : 'bg-white/80 text-gray-700 hover:bg-white border border-gray-200' }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Sticky Filters Bar --}}
    <div class="sticky top-0 z-10 bg-white/80 backdrop-blur supports-[backdrop-filter]:bg-white/60 border-b border-gray-100 mt-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <form method="GET" class="py-3 grid grid-cols-1 md:grid-cols-[1fr_220px_auto] gap-3">
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        {{-- magnifier icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.5 3.5a5 5 0 013.98 8.082l3.219 3.219a.75.75 0 11-1.06 1.06l-3.22-3.218A5 5 0 118.5 3.5zm0 1.5a3.5 3.5 0 100 7 3.5 3.5 0 000-7z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search by name or address…"
                        class="w-full rounded-xl border border-gray-300 pl-10 pr-4 py-2.5 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400"
                    />
                </div>

                <div class="relative">
                    <select
                        name="role"
                        class="w-full appearance-none rounded-xl border border-gray-300 bg-white pr-10 pl-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400"
                    >
                        <option value="">All roles</option>
                        @foreach(['clinic','shelter','groomer'] as $r)
                            <option value="{{ $r }}" @selected($currentRole === $r)>{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                        {{-- chevron-down --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.22 7.47a.75.75 0 011.06 0L10 11.19l3.72-3.72a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.22 8.53a.75.75 0 010-1.06z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                </div>

                <button class="inline-flex items-center justify-center rounded-xl bg-gray-900 text-white px-5 text-sm font-medium hover:bg-black/90 transition">
                    {{-- funnel icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M3 4.5A1.5 1.5 0 014.5 3h11A1.5 1.5 0 0117 4.5a1.5 1.5 0 01-.44 1.06L12 10.12v4.63a.75.75 0 01-1.2.6l-2.5-1.88a.75.75 0 01-.3-.6v-2.75L3.44 5.56A1.5 1.5 0 013 4.5z"/>
                    </svg>
                    Filter
                </button>
            </form>
        </div>
    </div>

    {{-- Results --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        @if ($profiles->count())
            <div class="space-y-4">
                @foreach ($profiles as $p)
                    <a href="{{ route('merchants.show', $p) }}"
                       class="group block rounded-2xl bg-white ring-1 ring-gray-200 hover:ring-gray-300 hover:shadow-sm transition">
                        <div class="grid grid-cols-[96px_1fr_auto] items-center gap-4 p-4">
                            {{-- Left: Photo --}}
                            <img
                                src="{{ $p->photo ? asset('storage/'.$p->photo) : 'https://placehold.co/120x120?text=Merchant' }}"
                                alt="{{ $p->name }}"
                                class="h-24 w-24 rounded-xl object-cover ring-1 ring-gray-200"
                                loading="lazy"
                            >

                            {{-- Middle: Details --}}
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-lg text-gray-900 truncate">{{ $p->name }}</h3>
                                    @php $badge = $roleColors[$p->role] ?? 'bg-gray-100 text-gray-700 ring-1 ring-gray-200'; @endphp
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badge }}">
                                        {{ ucfirst($p->role) }}
                                    </span>
                                </div>

                                @if (!empty($p->address))
                                    <p class="mt-1 text-sm text-gray-600 line-clamp-1">
                                        {{ \Illuminate\Support\Str::limit($p->address, 80) }}
                                    </p>
                                @endif

                                <div class="mt-2 flex items-center gap-3">
                                    @if (!empty($p->phone))
                                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 px-2 py-1 text-sm text-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M2.88 3.37A2 2 0 014.67 2h1.66c.5 0 .94.33 1.06.81l.62 2.48a1.1 1.1 0 01-.28 1.05L6.7 7.4a11.8 11.8 0 005.9 5.9l1.05-1.03a1.1 1.1 0 011.05-.28l2.48.62c.48.12.81.56.81 1.06v1.66a2 2 0 01-1.37 1.79c-.9.3-1.86.3-2.79.03a18.5 18.5 0 01-9.61-9.61c-.27-.93-.27-1.89.03-2.79z"/>
                                            </svg>
                                            {{ $p->phone }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Right: CTA --}}
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-500 hidden sm:inline">View</span>
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-600 group-hover:bg-gray-200 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.22 14.78a.75.75 0 010-1.06L10.94 10 7.22 6.28a.75.75 0 111.06-1.06l4.25 4.25a.75.75 0 010 1.06l-4.25 4.25a.75.75 0 01-1.06 0z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $profiles->onEachSide(1)->links() }}
            </div>
        @else
            {{-- Empty state --}}
            <div class="rounded-3xl border border-dashed border-gray-300 bg-white p-10 text-center">
                <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M3 4.5A1.5 1.5 0 014.5 3h11A1.5 1.5 0 0117 4.5V15a2 2 0 01-2 2H5a2 2 0 01-2-2V4.5zM5 6v9h10V6H5z"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900">No merchants found</h3>
                <p class="mt-1 text-sm text-gray-600">Try adjusting your filters or search keywords.</p>
            </div>
        @endif
    </div>
</div>
@endsection