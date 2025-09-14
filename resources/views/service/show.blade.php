@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-indigo-50 to-white pb-10">
  <div class="max-w-6xl mx-auto px-4 pt-8">
    {{-- Consistent back button --}}
    <a href="{{ route('services.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition-colors group mb-6">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
      </svg>
      Back to Services
    </a>

    <header class="pb-8">
      <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900">
        {{ $service->name }}
      </h1>
      <p class="mt-3 text-lg text-slate-600 max-w-3xl">
        {{ $service->short_description ?? Str::limit($service->description, 160) }}
      </p>

      <div class="mt-5 flex flex-wrap items-center gap-2">
        @if(!empty($service->category))
          <span class="px-3 py-1.5 rounded-full bg-indigo-100 text-indigo-700 text-sm font-medium">
            {{ $service->category }}
          </span>
        @endif

        @if(isset($service->price))
          <span class="px-3 py-1.5 rounded-full bg-blue-100 text-blue-700 text-sm font-medium ring-1 ring-blue-200">
            {{ is_numeric($service->price) ? 'RM ' . number_format($service->price, 2) : $service->price }}
          </span>
        @endif

        @if(Schema::hasColumn('services','is_active'))
          <span class="px-3 py-1.5 rounded-full text-sm font-medium
              {{ $service->is_active ? 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200' : 'bg-slate-100 text-slate-600' }}">
            {{ $service->is_active ? 'Available' : 'Inactive' }}
          </span>
        @endif
      </div>
    </header>
  </div>
</div>

<main class="max-w-6xl mx-auto px-4 py-8 -mt-10">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Main content --}}
    <div class="lg:col-span-2">
      <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6 lg:p-8">
        <h2 class="text-2xl font-bold text-slate-900 mb-6 pb-3 border-b border-slate-100">About this service</h2>
        <div class="prose prose-slate max-w-none">
          {!! nl2br(e($service->description ?? 'No description provided.')) !!}
        </div>

        {{-- Additional details section --}}
        @if($service->duration || $service->inclusions)
        <div class="mt-8 pt-6 border-t border-slate-100">
          <h3 class="text-lg font-semibold text-slate-900 mb-4">Service Details</h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($service->duration)
            <div>
              <h4 class="text-sm font-medium text-slate-700 mb-2">Duration</h4>
              <p class="text-slate-900">{{ $service->duration }}</p>
            </div>
            @endif
            
            @if($service->inclusions)
            <div>
              <h4 class="text-sm font-medium text-slate-700 mb-2">What's Included</h4>
              <ul class="list-disc list-inside text-slate-900 space-y-1">
                @foreach(explode(',', $service->inclusions) as $inclusion)
                <li>{{ trim($inclusion) }}</li>
                @endforeach
              </ul>
            </div>
            @endif
          </div>
        </div>
        @endif
      </div>
    </div>

    {{-- Side panel --}}
    <aside class="space-y-6">
      <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Service Highlights</h3>
        <ul class="space-y-3 text-slate-700">
          <li class="flex items-start">
            <svg class="h-5 w-5 text-indigo-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>Friendly, certified staff</span>
          </li>
          <li class="flex items-start">
            <svg class="h-5 w-5 text-indigo-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>Clean & safe environment</span>
          </li>
          <li class="flex items-start">
            <svg class="h-5 w-5 text-indigo-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>Transparent pricing</span>
          </li>
          <li class="flex items-start">
            <svg class="h-5 w-5 text-indigo-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>Personalized care for your pet</span>
          </li>
        </ul>

        <div class="mt-6 flex gap-3">
          <a href="{{ route('services.index') }}"
             class="flex-1 inline-flex items-center justify-center px-4 py-3 rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition-colors text-center">
            Back to Services
          </a>
          <a href="{{ url('/merchants?role=clinic&service=' . $service->id) }}"
             class="flex-1 inline-flex items-center justify-center px-4 py-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 shadow-sm hover:shadow-md transition-all text-center font-medium">
            Book Now
          </a>
        </div>
      </div>

      {{-- Contact card --}}
      <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-3">Need help?</h3>
        <p class="text-slate-600 mb-4">Have questions about this service? Get in touch and we'll guide you.</p>
        <a href="{{ url('/contact') }}"
           class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl bg-slate-900 text-white hover:bg-slate-800 transition-colors font-medium">
          Contact Us
        </a>
      </div>
    </aside>
  </div>
</main>
@endsection