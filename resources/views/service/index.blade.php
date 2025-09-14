@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">

  {{-- Page header --}}
  <div class="mb-10 text-center">
    <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900">
      Our Services
    </h1>
    <p class="mt-3 text-lg text-slate-600 max-w-2xl mx-auto">
      Trusted care for your pets—grooming, clinical care, boarding and more from verified partners.
    </p>
  </div>

  {{-- Back button --}}
  <div class="mb-6">
    <a href="{{ url('/') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition-colors group">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
      Back to Home
    </a>
  </div>

  {{-- Empty state --}}
  @if(!$services->count())
    <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 p-12 text-center">
      <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-slate-100 mb-4">
        <svg class="h-8 w-8 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6" />
        </svg>
      </div>
      <h2 class="text-xl font-semibold text-slate-800">Coming Soon</h2>
      <p class="mt-1 text-slate-600">We're setting things up. Please check back shortly.</p>
    </div>
  @else

    {{-- Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      @foreach($services as $svc)
        <a href="{{ route('services.show', $svc) }}"
           class="group block rounded-2xl overflow-hidden border border-slate-200 bg-white shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

          {{-- Accent bar --}}
          <div class="h-2 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>

          <div class="p-6 flex flex-col min-h-[250px]">
            {{-- Icon / thumb --}}
            <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 ring-1 ring-indigo-100 group-hover:bg-indigo-100 group-hover:scale-110 transition-all">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4M7 12a5 5 0 1010 0 5 5 0 00-10 0z"/>
              </svg>
            </div>

            <h3 class="text-lg font-semibold text-slate-900 group-hover:text-indigo-700 transition-colors">
              {{ $svc->name }}
            </h3>

            <p class="mt-2 text-sm text-slate-600 line-clamp-3 flex-grow">
              {{ $svc->short_description ?? $svc->description ?? 'Professional and caring service for your pet.' }}
            </p>

            <div class="mt-4 flex items-center justify-between pt-4 border-t border-slate-100">
              <div class="space-x-2">
                @if(!empty($svc->category))
                  <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-medium">{{ $svc->category }}</span>
                @endif
              </div>

              @if(isset($svc->price))
                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-sm font-semibold text-blue-700 ring-1 ring-inset ring-blue-200">
                  {{ is_numeric($svc->price) ? 'RM ' . number_format($svc->price, 2) : $svc->price }}
                </span>
              @endif
            </div>
          </div>
        </a>
      @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-10">
      {{ $services->links() }}
    </div>
  @endif

</div>
@endsection