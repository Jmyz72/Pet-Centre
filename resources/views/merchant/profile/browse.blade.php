@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-semibold mb-4">Find Merchants</h1>

    {{-- Filters --}}
    <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-3">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search by name or address"
            class="border rounded p-2"
        />
        <select name="role" class="border rounded p-2">
            <option value="">All roles</option>
            @foreach(['clinic','shelter','groomer'] as $r)
                <option value="{{ $r }}" @selected(request('role') === $r)>{{ ucfirst($r) }}</option>
            @endforeach
        </select>
        <button class="rounded bg-gray-900 text-white px-4">Filter</button>
    </form>

    {{-- Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($profiles as $p)
            <a href="{{ route('merchants.show', $p) }}" class="block rounded-2xl border hover:shadow p-4 transition">
                <img
                    src="{{ $p->photo ? asset('storage/'.$p->photo) : 'https://placehold.co/600x400?text=Merchant' }}"
                    alt="{{ $p->name }}"
                    class="w-full h-40 object-cover rounded-xl mb-3"
                    loading="lazy"
                >
                <div class="text-sm text-gray-500 mb-1">{{ ucfirst($p->role) }}</div>
                <div class="font-semibold text-lg">{{ $p->name }}</div>
                <div class="text-gray-600 text-sm">{{ Str::limit($p->address, 80) }}</div>
            </a>
        @empty
            <div class="col-span-full text-gray-600">No merchants found.</div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">{{ $profiles->links() }}</div>
</div>
@endsection