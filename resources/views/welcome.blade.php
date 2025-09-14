<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCentre - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .hero-bg {
            background: linear-gradient(to right, rgba(255, 255, 255, 0.98), rgba(255, 255, 255, 0.95)), 
                        url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="%23fafafa"/><path d="M0 0L100 100" stroke="%23f0f0f0" stroke-width="1.5"/><path d="M100 0L0 100" stroke="%23f0f0f0" stroke-width="1.5"/></svg>');
            background-size: cover;
        }
        
        .pet-card {
            transition: all 0.3s ease;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }
        
        .pet-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }
        
        .feature-icon {
            background-color: #f8fafc;
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.03);
            border: 1px solid #f1f5f9;
        }
        
        .btn-primary {
            background: linear-gradient(to right, #3b82f6, #2563eb);
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.15);
        }
        
        .btn-primary:hover {
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }
        
        .btn-secondary {
            background: white;
            transition: all 0.3s ease;
            border: 2px solid #e5e7eb;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.03);
        }
        
        .btn-secondary:hover {
            background: #f8fafc;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
            border-color: #3b82f6;
        }
        
        .modal-content {
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }
        
        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 2rem;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 70px;
            height: 4px;
            background: linear-gradient(to right, #3b82f6, #2563eb);
            border-radius: 2px;
        }
        
        .service-card {
            transition: all 0.3s ease;
            border-radius: 20px;
            overflow: hidden;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            border: 1px solid #f8fafc;
        }
        
        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 30px -10px rgba(0, 0, 0, 0.08);
            border-color: #e0e7ff;
        }
        
        .service-icon {
            width: 90px;
            height: 90px;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.05);
        }
        
        .shelter-icon {
            background: linear-gradient(135deg, #e0f2fe, #bae6fd);
            color: #0369a1;
        }
        
        .clinic-icon {
            background: linear-gradient(135deg, #fce7f3, #fbcfe8);
            color: #9d174d;
        }
        
        .groomer-icon {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
        }
        
        .service-link {
            transition: all 0.3s ease;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .service-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .shelter-link {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .shelter-link:hover {
            background-color: #bfdbfe;
        }
        
        .clinic-link {
            background-color: #fce7f3;
            color: #9d174d;
        }
        
        .clinic-link:hover {
            background-color: #fbcfe8;
        }
        
        .groomer-link {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .groomer-link:hover {
            background-color: #bbf7d0;
        }
        
        .pet-tag {
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.35rem 0.75rem;
        }
    </style>
</head>
<body class="bg-white text-gray-800">

@extends('layouts.app')

@section('content')

    <!-- Hero Section -->
    <section class="hero-bg py-16 md:py-24 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-32">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6">Complete Care for Your <span class="text-blue-600">Pet Companion</span></h1>
                <p class="text-lg md:text-xl text-gray-600 mb-12 max-w-3xl mx-auto">
                    A centralized platform for pet adoption, clinical care, and grooming services.
                </p>
            </div>
            
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 section-title">Everything Your Pet Needs</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">We provide comprehensive care for your furry friends with our range of specialized services</p>
            </div>

            <!-- Service Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-4">
                <!-- Shelter Card -->
                <div class="service-card p-8 text-center">
                    <div class="service-icon shelter-icon">
                        <i class="fas fa-home text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-4">Shelter</h3>
                    <p class="text-gray-600 mb-6">
                        Find loving pets from local shelters waiting for their forever homes.
                    </p>
                    <a href="{{ route('merchants.index', ['role' => 'shelter']) }}" class="service-link shelter-link inline-block px-6 py-3 font-medium rounded-lg transition">
                        Explore Shelters <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>
                
                <!-- Clinic Card -->
                <div class="service-card p-8 text-center">
                    <div class="service-icon clinic-icon">
                        <i class="fas fa-stethoscope text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-4">Clinic</h3>
                    <p class="text-gray-600 mb-6">
                        Professional veterinary care to keep your pets healthy and happy.
                    </p>
                    <a href="{{ route('merchants.index', ['role' => 'clinic']) }}" class="service-link clinic-link inline-block px-6 py-3 font-medium rounded-lg transition">
                        Clinic Services <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>
                
                <!-- Groomer Card -->
                <div class="service-card p-8 text-center">
                    <div class="service-icon groomer-icon">
                        <i class="fas fa-spa text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-4">Groomer</h3>
                    <p class="text-gray-600 mb-6">
                        Professional grooming services to keep your pets looking their best.
                    </p>
                    <a href="{{ route('merchants.index', ['role' => 'groomer']) }}" class="service-link groomer-link inline-block px-6 py-3 font-medium rounded-lg transition">
                        Book Grooming <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    
 <!-- Pet Adoption Showcase -->
<section class="max-w-7xl mx-auto px-4 py-16">
  <div class="text-center mb-12">
    <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900">Adopt a Friend</h2>
    <p class="mt-3 text-lg text-slate-600 max-w-2xl mx-auto">
      Give a loving home to one of our furry friends waiting for adoption.
    </p>
  </div>

  @php
    $displayPets = ($pets ?? collect())->take(4);
    $slots = 4;
    $placeholders = max(0, $slots - $displayPets->count());
  @endphp

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

    {{-- Real pet cards --}}
    @foreach($displayPets as $pet)
      <a href="{{ route('pets.show', $pet ?? null, false) ?? url('/pets/' . ($pet->id ?? '')) }}"
         class="group block rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-xl hover:-translate-y-1 transition overflow-hidden">

        {{-- Pet photo --}}
        @if(!empty($pet->photo_path))
          <img src="{{ asset('storage/' . $pet->photo_path) }}"
               alt="{{ $pet->name }}"
               class="h-48 w-full object-cover">
        @else
          <div class="h-48 w-full bg-slate-100 flex items-center justify-center text-slate-400">
            <span class="text-sm">No Photo</span>
          </div>
        @endif

        <div class="p-6 flex flex-col min-h-[200px]">
          <h3 class="text-lg font-semibold text-slate-900 group-hover:text-blue-700 transition">
            {{ $pet->name ?? 'Unnamed Pet' }}
          </h3>
          <p class="mt-1 text-sm text-slate-600">
            {{ optional($pet->type)->name ?? 'Unknown Type' }} • {{ optional($pet->breed)->name ?? 'Unknown Breed' }}
          </p>
          <div class="mt-auto pt-4">
            <span class="inline-flex items-center rounded-full bg-green-50 px-3 py-1 text-sm font-semibold text-green-700 ring-1 ring-inset ring-green-200">
              Ready for Adoption
            </span>
          </div>
        </div>
      </a>
    @endforeach

    {{-- Empty placeholders (same as Services) --}}
    @for ($i = 0; $i < $placeholders; $i++)
      <div class="rounded-2xl border border-slate-200 bg-slate-50 flex items-center justify-center min-h-[200px] shadow-sm">
        <div class="text-center text-slate-400 font-semibold">
          <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-2 h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6" />
          </svg>
          <p>Coming Soon</p>
        </div>
      </div>
    @endfor
  </div>

  <!-- Browse all -->
  <div class="mt-14 text-center">
    <a href="{{ route('pets.index') }}" class="inline-flex items-center px-6 py-3 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium shadow hover:shadow-lg hover:scale-105 transition">
      Browse all pets
      <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
      </svg>
    </a>
  </div>
</section>
</section>


<!-- Services -->
<section class="max-w-7xl mx-auto px-4 py-16">
  <div class="text-center mb-12">
    <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900">Our Services</h2>
    <p class="mt-3 text-lg text-slate-600 max-w-2xl mx-auto">
      Trusted care from verified partners—book treatments, grooming, and more.
    </p>
  </div>

  @php
    $displayServices = ($services ?? collect())->take(4);
    $slots = 4;
    $placeholders = max(0, $slots - $displayServices->count());
  @endphp

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

    {{-- Real service cards --}}
    @foreach($displayServices as $svc)
      <a href="{{ route('services.show', $svc ?? null, false) ?? url('/services/' . ($svc->id ?? '')) }}"
         class="group block rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-xl hover:-translate-y-1 transition overflow-hidden">

        {{-- top accent --}}
        <div class="h-2 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>

        <div class="p-6 flex flex-col min-h-[220px]">
          <h3 class="text-lg font-semibold text-slate-900 group-hover:text-blue-700 transition">
            {{ $svc->name ?? 'Service' }}
          </h3>

          <p class="mt-1 text-sm text-slate-600 line-clamp-3">
            {{ $svc->short_description ?? $svc->description ?? 'Professional and caring service for your pet.' }}
          </p>

          <div class="mt-auto flex items-center justify-between pt-4">
            <div class="text-sm text-slate-700">
              @if(!empty($svc->category))
                <span class="px-2 py-1 rounded-full bg-slate-100">{{ $svc->category }}</span>
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

    {{-- Empty placeholders (Pet-style design) --}}
    @for ($i = 0; $i < $placeholders; $i++)
      <div class="rounded-2xl border border-slate-200 bg-slate-50 flex items-center justify-center min-h-[220px] shadow-sm">
        <div class="text-center text-slate-400 font-semibold">
          <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-2 h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6" />
          </svg>
          <p>Coming Soon</p>
        </div>
      </div>
    @endfor
  </div>

  <!-- Browse all -->
  <div class="mt-14 text-center">
    <a href="{{ route('services.index', [], false) ?? url('/services') }}" class="inline-flex items-center px-6 py-3 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium shadow hover:shadow-lg hover:scale-105 transition">
      Browse all services
      <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
      </svg>
    </a>
  </div>
</section>



@endsection

@push('scripts')
    <script>
        function openQuickView(name, desc, imgUrl) {
            document.getElementById('quickViewModal').classList.remove('hidden');
            document.getElementById('quickViewContent').innerHTML = `
                <div class="text-center">
                    <div class="h-56 overflow-hidden bg-gray-100 rounded-lg mb-5 flex items-center justify-center">
                        <img src="${imgUrl}" alt="${name}" class="h-full object-cover">
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">${name}</h3>
                    <p class="text-gray-600 mb-5">${desc}</p>
                    <div class="flex justify-center gap-4">
                        <button class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">
                            <i class="fas fa-heart mr-2"></i> Adopt Me
                        </button>
                        <button onclick="closeQuickView()" class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg font-medium hover:bg-gray-300 transition">
                            Close
                        </button>
                    </div>
                </div>
            `;
        }

        function closeQuickView() {
            document.getElementById('quickViewModal').classList.add('hidden');
        }
    </script>
@endpush
</body>
</html>