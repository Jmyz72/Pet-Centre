@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-10">
<div class="mx-auto max-w-6xl px-4 py-8">
  {{-- Header / Toolbar --}}
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white shadow">
    <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
      <div>
        <h1 class="text-2xl font-semibold tracking-tight">My Pets</h1>
        <p class="mt-1 text-sm text-blue-100">Manage your pets' profiles and details.</p>
      </div>
      <a href="{{ route('customer.pets.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-white/95 px-4 py-2 text-sm font-medium text-blue-700 shadow hover:bg-white">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4"><path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/></svg>
        Add Pet
      </a>
    </div>
  </div>

  @if(session('success'))
    <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
      {{ session('success') }}
    </div>
  @endif

  {{-- Cards Grid --}}
  <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
    @forelse($pets as $pet)
      <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md">
        <div class="p-5">
          <div class="flex items-center gap-4">
            @if($pet->photo_path)
              <img src="{{ Storage::url($pet->photo_path) }}" alt="{{ $pet->name }}" class="h-16 w-16 rounded-full object-cover ring-2 ring-blue-50"/>
            @else
              <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-gray-100 to-gray-200 text-lg font-semibold text-gray-600 ring-2 ring-gray-100">
                {{ strtoupper(mb_substr($pet->name,0,1)) }}
              </div>
            @endif
            <div class="min-w-0">
              <div class="flex items-center gap-2">
                <h3 class="truncate text-lg font-semibold text-gray-900">{{ $pet->name }}</h3>
                @if($pet->type?->name)
                  <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-200">{{ $pet->type->name }}</span>
                @endif
                @if($pet->breed?->name)
                  <span class="inline-flex items-center rounded-full bg-violet-50 px-2 py-0.5 text-xs font-medium text-violet-700 ring-1 ring-inset ring-violet-200">{{ $pet->breed->name }}</span>
                @endif
              </div>
              <p class="mt-1 line-clamp-2 text-xs text-gray-500">{{ $pet->description ?? ' ' }}</p>
            </div>
          </div>

          <dl class="mt-4 grid grid-cols-3 gap-3 text-sm">
            <div class="rounded-lg border bg-gray-50 p-3">
              <dt class="text-xs text-gray-500">Sex</dt>
              <dd class="mt-0.5 font-medium capitalize text-gray-800">{{ $pet->sex }}</dd>
            </div>
            <div class="rounded-lg border bg-gray-50 p-3">
              <dt class="text-xs text-gray-500">Weight</dt>
              <dd class="mt-0.5 font-medium text-gray-800">{{ $pet->weight_kg ? number_format($pet->weight_kg, 2) : '—' }} kg</dd>
            </div>
            <div class="rounded-lg border bg-gray-50 p-3">
              <dt class="text-xs text-gray-500">Age</dt>
              <dd class="mt-0.5 font-medium text-gray-800">
                @if($pet->birthdate)
                  @php
                    $dob = \Carbon\Carbon::parse($pet->birthdate);
                    $now = \Carbon\Carbon::now();
                    $years = $dob->diffInYears($now);
                    $months = $dob->copy()->addYears($years)->diffInMonths($now);
                  @endphp
                  {{ number_format($years) }} y {{ number_format($months) }} m
                @else
                  —
                @endif
              </dd>
            </div>
          </dl>

          <div class="mt-4 flex items-center justify-between">
            <a href="{{ route('customer.pets.edit', $pet) }}" class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712z"/><path d="M3.75 15.75v4.5h4.5l10.92-10.92-4.5-4.5L3.75 15.75z"/></svg>
              Edit
            </a>
            @if($pet->size?->label)
              <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-200">{{ $pet->size->label }}</span>
            @endif
          </div>
        </div>
      </div>
    @empty
      <div class="col-span-full">
        <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed bg-white p-10 text-center shadow-sm">
          <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6"><path d="M11.47 3.84a.75.75 0 011.06 0l7.65 7.65a.75.75 0 01-1.06 1.06L12.75 6.44V20a.75.75 0 01-1.5 0V6.44l-6.37 6.11a.75.75 0 11-1.06-1.06l7.65-7.65z"/></svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900">No pets yet</h3>
          <p class="mt-1 text-sm text-gray-500">Get started by creating your first pet profile.</p>
          <a href="{{ route('customer.pets.create') }}" class="mt-4 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-white shadow hover:bg-blue-700">Add your first pet</a>
        </div>
      </div>
    @endforelse
  </div>

  <div class="mt-8">{{ $pets->links() }}</div>
</div>
</div>
@endsection