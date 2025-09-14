<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCentre - Your Pet's Complete Care Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Nunito', sans-serif;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .hero-pattern {
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(255,255,255,0.05) 0%, transparent 50%);
        }
        
        .card-hover {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .card-hover:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        
        .pet-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }
        
        /* Service icon styles */
        .service-icon {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(79, 172, 254, 0.3);
        }
        
        /* Package icon styles */
        .package-icon {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(67, 233, 123, 0.3);
        }
        
        .adoption-icon {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            box-shadow: 0 10px 30px rgba(240, 147, 251, 0.3);
        }
        
        .clinic-icon {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            box-shadow: 0 10px 30px rgba(79, 172, 254, 0.3);
        }
        
        .groomer-icon {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            box-shadow: 0 10px 30px rgba(67, 233, 123, 0.3);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: white;
            border: 2px solid #e2e8f0;
            color: #4a5568;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            border-color: #667eea;
            color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
        }
        
        .section-title {
            position: relative;
            display: inline-block;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }
        
        .price-badge {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.9rem;
        }
        
        .adoption-badge {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        
        .floating-element {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .stats-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .paw-pattern {
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(102, 126, 234, 0.1) 2px, transparent 2px),
                radial-gradient(circle at 75% 75%, rgba(118, 75, 162, 0.1) 2px, transparent 2px);
            background-size: 50px 50px;
        }
        
        /* Additional floating animations */
        .floating-paw {
            animation: float 6s ease-in-out infinite;
        }

        .floating-heart {
            animation: float 4s ease-in-out infinite reverse;
        }
        
        /* Card specific hover effects */
        .service-card {
            border: 1px solid rgba(79, 172, 254, 0.2);
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        }
        
        .service-card:hover {
            border-color: rgba(79, 172, 254, 0.4);
            box-shadow: 0 20px 40px rgba(79, 172, 254, 0.2);
        }
        
        .package-card {
            border: 1px solid rgba(67, 233, 123, 0.2);
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        }
        
        .package-card:hover {
            border-color: rgba(67, 233, 123, 0.4);
            box-shadow: 0 20px 40px rgba(67, 233, 123, 0.2);
        }
        
        /* Updated pet card hover */
        .pet-card:hover {
            border-color: rgba(240, 147, 251, 0.4);
            box-shadow: 0 20px 40px rgba(240, 147, 251, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

@extends('layouts.app')

@section('content')

    <!-- Hero Section -->
    <section class="hero-gradient hero-pattern relative overflow-hidden">
        <div class="absolute inset-0 paw-pattern"></div>
        <div class="relative z-10 py-20 md:py-32 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="text-center">
                    <!-- Floating elements -->
                    <div class="floating-element absolute top-10 left-10 text-white opacity-20">
                        <i class="fas fa-paw text-4xl"></i>
                    </div>
                    <div class="floating-element absolute top-20 right-20 text-white opacity-20" style="animation-delay: -2s;">
                        <i class="fas fa-heart text-3xl"></i>
                    </div>
                    <div class="floating-element absolute bottom-20 left-20 text-white opacity-20" style="animation-delay: -4s;">
                        <i class="fas fa-bone text-3xl"></i>
                    </div>
                    
                    <div class="max-w-4xl mx-auto">
                        <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                            <span class="text-gray-800 drop-shadow-lg">Where Pets Find</span>
                            <span class="bg-gradient-to-r from-pink-500 to-purple-600 bg-clip-text text-transparent block">
                                Love & Care
                            </span>
                        </h1>
                        <p class="text-xl md:text-2xl text-gray-700 mb-12 leading-relaxed">
                            Your one-stop destination for pet adoption, veterinary care, and grooming services. 
                            Because every pet deserves the best! 🐾
                        </p>
                        
                        <div class="flex flex-col md:flex-row gap-6 justify-center items-center mb-16">
                            <a href="#pets" class="btn-primary inline-flex items-center text-lg">
                                <i class="fas fa-heart mr-3"></i>
                                Find Your Pet
                            </a>
                            <a href="#services" class="btn-secondary inline-flex items-center text-lg">
                                <i class="fas fa-stethoscope mr-3"></i>
                                Book Care Services
                            </a>
                        </div>
                        
                        <!-- Stats -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
                            <div class="stats-card rounded-2xl p-6 text-center">
                                <div class="text-3xl font-bold text-purple-600 mb-2">{{ $stats['adoptions'] > 0 ? $stats['adoptions'] . '+' : '0' }}</div>
                                <div class="text-sm text-gray-600">Happy Adoptions</div>
                            </div>
                            <div class="stats-card rounded-2xl p-6 text-center">
                                <div class="text-3xl font-bold text-blue-600 mb-2">{{ $stats['clinics'] > 0 ? $stats['clinics'] . '+' : '0' }}</div>
                                <div class="text-sm text-gray-600">Trusted Clinics</div>
                            </div>
                            <div class="stats-card rounded-2xl p-6 text-center">
                                <div class="text-3xl font-bold text-green-600 mb-2">{{ $stats['services'] > 0 ? $stats['services'] . '+' : '0' }}</div>
                                <div class="text-sm text-gray-600">Available Services</div>
                            </div>
                            <div class="stats-card rounded-2xl p-6 text-center">
                                <div class="text-3xl font-bold text-pink-600 mb-2">{{ $stats['happy_families'] > 0 ? $stats['happy_families'] . '+' : '0' }}</div>
                                <div class="text-sm text-gray-600">Happy Families</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Overview -->
    <section class="py-20 bg-white relative">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 section-title">
                    Everything Your Pet Needs
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    From finding your perfect companion to keeping them healthy and beautiful, 
                    we've got all your pet care needs covered.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Adoption Card -->
                <div class="card-hover bg-white rounded-3xl p-8 text-center border border-gray-100">
                    <div class="service-icon adoption-icon mx-auto mb-6">
                        <i class="fas fa-home text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Pet Adoption</h3>
                    <p class="text-gray-600 mb-8 leading-relaxed">
                        Discover loving pets from verified shelters waiting for their forever homes. 
                        Start your adoption journey today!
                    </p>
                    <a href="#pets" class="inline-flex items-center text-pink-600 font-semibold hover:text-pink-700">
                        Browse Pets <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                
                <!-- Clinic Card -->
                <div class="card-hover bg-white rounded-3xl p-8 text-center border border-gray-100">
                    <div class="service-icon clinic-icon mx-auto mb-6">
                        <i class="fas fa-stethoscope text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Veterinary Care</h3>
                    <p class="text-gray-600 mb-8 leading-relaxed">
                        Professional healthcare from certified veterinarians. 
                        Keep your pets healthy with expert medical care.
                    </p>
                    <a href="#services" class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-700">
                        Book Appointment <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                
                <!-- Grooming Card -->
                <div class="card-hover bg-white rounded-3xl p-8 text-center border border-gray-100">
                    <div class="service-icon groomer-icon mx-auto mb-6">
                        <i class="fas fa-spa text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Pet Grooming</h3>
                    <p class="text-gray-600 mb-8 leading-relaxed">
                        Pamper your pets with professional grooming services. 
                        Keep them looking and feeling their absolute best.
                    </p>
                    <a href="#packages" class="inline-flex items-center text-green-600 font-semibold hover:text-green-700">
                        View Packages <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    
    <!-- Pet Adoption Showcase -->
    <section id="pets" class="py-20 bg-gradient-to-br from-purple-50 to-pink-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <div class="inline-flex items-center bg-pink-100 text-pink-800 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                    <i class="fas fa-heart mr-2"></i>
                    Featured Pets
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 section-title">
                    Pets Looking for Love
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    These adorable companions are ready to fill your home with joy and unconditional love. 
                    Could one of them be your perfect match? 💝
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($featuredPets as $pet)
                <!-- Pet Card -->
                <div class="pet-card rounded-3xl overflow-hidden card-hover">
                    <!-- Pet Image -->
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ $pet['image'] }}" alt="{{ $pet['name'] }}" 
                             class="w-full h-full object-cover">
                        <!-- Price Badge -->
                        <div class="absolute top-4 right-4">
                            @if($pet['adoption_fee'])
                                <span class="price-badge">RM {{ number_format($pet['adoption_fee'], 2) }}</span>
                            @else
                                <span class="adoption-badge">Free Adoption</span>
                            @endif
                        </div>
                        <!-- Vaccination Badge -->
                        @if($pet['vaccinated'])
                            <div class="absolute top-4 left-4">
                                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                    <i class="fas fa-shield-alt mr-1"></i> Vaccinated
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Pet Info -->
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $pet['name'] }}</h3>
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-birthday-cake mr-1"></i>{{ $pet['age'] }}
                            </div>
                        </div>
                        
                        <p class="text-gray-600 mb-3">
                            <i class="fas fa-tag mr-2 text-purple-500"></i>
                            {{ $pet['type'] }}{{ $pet['breed'] ? ' • ' . $pet['breed'] : '' }}
                        </p>
                        
                        <!-- Shelter Info -->
                        <div class="flex items-center text-sm text-gray-500 mb-6">
                            <i class="fas fa-map-marker-alt mr-2 text-pink-500"></i>
                            <span>{{ $pet['merchant_name'] }}</span>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <a href="/bookings/create?pet_id={{ $pet['id'] }}&booking_type=adoption" 
                               class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl font-semibold hover:from-pink-600 hover:to-purple-700 transition duration-300">
                                <i class="fas fa-heart mr-2"></i> Adopt {{ $pet['name'] }}
                            </a>
                            <a href="/merchants/{{ $pet['merchant_id'] }}" 
                               class="w-full inline-flex items-center justify-center px-6 py-3 bg-white text-gray-700 border-2 border-gray-200 rounded-xl font-semibold hover:border-purple-300 hover:text-purple-600 transition duration-300">
                                <i class="fas fa-home mr-2"></i> Visit Shelter
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Fallback if no pets -->
                <div class="col-span-full text-center py-20">
                    <div class="max-w-md mx-auto">
                        <div class="text-8xl mb-6">🐾</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">No Pets Available Right Now</h3>
                        <p class="text-gray-600 mb-8">
                            Check back soon! New adorable pets are looking for their forever homes every day.
                        </p>
                        <a href="/merchants?role=shelter" class="btn-primary inline-flex items-center">
                            <i class="fas fa-search mr-2"></i> Browse All Shelters
                        </a>
                    </div>
                </div>
                @endforelse
            </div>
            
            <!-- View More Button -->
            @if(count($featuredPets) > 0)
            <div class="text-center mt-12">
                <a href="/merchants?role=shelter" class="btn-primary inline-flex items-center text-lg">
                    <i class="fas fa-paw mr-3"></i>
                    View All Available Pets
                </a>
            </div>
            @endif
        </div>
    </section>

    <!-- Featured Services Section -->
        <!-- Services Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <div class="inline-flex items-center bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                    <i class="fas fa-stethoscope mr-2"></i>
                    Professional Services
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 section-title">
                    Complete Pet Care Services
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    From routine checkups to specialized treatments, our trusted veterinary partners provide 
                    comprehensive care to keep your pets healthy and happy.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($featuredServices as $service)
                <!-- Service Card -->
                <div class="service-card bg-white rounded-3xl overflow-hidden border border-gray-100 card-hover p-8">
                    <!-- Service Icon -->
                    <div class="service-icon mb-6">
                        @php
                            $serviceIcons = [
                                'Vaccination' => 'fa-syringe',
                                'General Check-up' => 'fa-stethoscope',
                                'Surgery' => 'fa-scalpel',
                                'X-ray' => 'fa-x-ray',
                                'Blood Test' => 'fa-vial',
                                'Ultrasound' => 'fa-heartbeat',
                                'Dental Care' => 'fa-tooth',
                                'Deworming' => 'fa-pills',
                                'Microchipping' => 'fa-microchip',
                                'Emergency Care' => 'fa-ambulance',
                                'default' => 'fa-stethoscope'
                            ];
                            $icon = $serviceIcons[$service['category']] ?? $serviceIcons['default'];
                        @endphp
                        <i class="fas {{ $icon }} text-3xl"></i>
                    </div>
                    
                    <!-- Service Info -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-xl font-bold text-gray-900">{{ $service['name'] }}</h3>
                            <span class="text-2xl font-bold text-blue-600">RM {{ number_format($service['price'], 2) }}</span>
                        </div>
                        
                        <!-- Category Badge -->
                        <div class="mb-4">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $service['category'] }}
                            </span>
                        </div>
                        
                        <p class="text-gray-600 mb-4 text-sm leading-relaxed">{{ $service['description'] }}</p>
                        
                        <!-- Duration Info -->
                        @if($service['duration'])
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <i class="fas fa-clock mr-2 text-blue-500"></i>
                                <span>{{ $service['duration'] }} minutes</span>
                            </div>
                        @endif
                        
                        <!-- Clinic Info -->
                        <div class="flex items-center text-sm text-gray-500 mb-6">
                            <i class="fas fa-clinic-medical mr-2 text-blue-500"></i>
                            <span>{{ $service['merchant_name'] }}</span>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <a href="/bookings/create?service_id={{ $service['id'] }}&booking_type=service" 
                           class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-cyan-600 text-white rounded-xl font-semibold hover:from-blue-600 hover:to-cyan-700 transition duration-300">
                            <i class="fas fa-calendar-plus mr-2"></i> Book Service
                        </a>
                        <a href="/merchants/{{ $service['merchant_id'] }}" 
                           class="w-full inline-flex items-center justify-center px-6 py-3 bg-white text-gray-700 border-2 border-gray-200 rounded-xl font-semibold hover:border-blue-300 hover:text-blue-600 transition duration-300">
                            <i class="fas fa-clinic-medical mr-2"></i> Visit Clinic
                        </a>
                    </div>
                </div>
                @empty
                <!-- Fallback if no services -->
                <div class="col-span-full text-center py-20">
                    <div class="max-w-md mx-auto">
                        <div class="text-8xl mb-6">🏥</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">No Services Available</h3>
                        <p class="text-gray-600 mb-8">
                            Our veterinary partners are constantly adding new services. Check back soon!
                        </p>
                        <a href="/merchants?role=clinic" class="btn-primary inline-flex items-center">
                            <i class="fas fa-search mr-2"></i> Browse All Clinics
                        </a>
                    </div>
                </div>
                @endforelse
            </div>
            
            <!-- View More Button -->
            @if(count($featuredServices) > 0)
            <div class="text-center mt-12">
                <a href="/merchants?role=clinic" class="btn-primary inline-flex items-center text-lg">
                    <i class="fas fa-stethoscope mr-3"></i>
                    View All Available Services
                </a>
            </div>
            @endif
        </div>
    </section>

    <!-- Featured Packages Section -->
        <!-- Packages Section -->
    <section class="py-20 bg-gradient-to-br from-green-50 to-emerald-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <div class="inline-flex items-center bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                    <i class="fas fa-gift mr-2"></i>
                    Special Packages
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 section-title">
                    Complete Care Packages
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Save money while giving your pets the best care with our specially curated packages. 
                    Everything your furry friend needs, bundled with love! 🎁
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($featuredPackages as $package)
                <!-- Package Card -->
                <div class="package-card bg-white rounded-3xl overflow-hidden border border-gray-100 card-hover p-8">
                    <!-- Package Icon -->
                    <div class="package-icon mb-6">
                        @php
                            $packageIcons = [
                                'Grooming' => 'fa-cut',
                                'Health Package' => 'fa-heart-pulse',
                                'Wellness Package' => 'fa-spa',
                                'Puppy Package' => 'fa-dog',
                                'Senior Care' => 'fa-heart',
                                'Complete Care' => 'fa-star',
                                'default' => 'fa-gift'
                            ];
                            $firstType = is_array($package['types']) && count($package['types']) > 0 ? $package['types'][0] : 'default';
                            $icon = $packageIcons[$firstType] ?? $packageIcons['default'];
                        @endphp
                        <i class="fas {{ $icon }} text-3xl"></i>
                    </div>
                    
                    <!-- Package Info -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-xl font-bold text-gray-900">{{ $package['name'] }}</h3>
                            <span class="text-2xl font-bold text-green-600">RM {{ number_format($package['price'], 2) }}</span>
                        </div>
                        
                        <!-- Package Types -->
                        @if(is_array($package['types']) && count($package['types']) > 0)
                            <div class="mb-4">
                                @foreach($package['types'] as $type)
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold mr-2 mb-2 inline-block">
                                        {{ $type }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        
                        <p class="text-gray-600 mb-4 text-sm leading-relaxed">{{ $package['description'] }}</p>
                        
                        <!-- Duration Info -->
                        @if($package['duration'])
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <i class="fas fa-clock mr-2 text-green-500"></i>
                                <span>{{ $package['duration'] }} minutes</span>
                            </div>
                        @endif
                        
                        <!-- Clinic Info -->
                        <div class="flex items-center text-sm text-gray-500 mb-6">
                            <i class="fas fa-clinic-medical mr-2 text-green-500"></i>
                            <span>{{ $package['merchant_name'] }}</span>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <a href="/bookings/create?package_id={{ $package['id'] }}&booking_type=package" 
                           class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-semibold hover:from-green-600 hover:to-emerald-700 transition duration-300">
                            <i class="fas fa-shopping-cart mr-2"></i> Book Package
                        </a>
                        <a href="/merchants/{{ $package['merchant_id'] }}" 
                           class="w-full inline-flex items-center justify-center px-6 py-3 bg-white text-gray-700 border-2 border-gray-200 rounded-xl font-semibold hover:border-green-300 hover:text-green-600 transition duration-300">
                            <i class="fas fa-clinic-medical mr-2"></i> Visit Clinic
                        </a>
                    </div>
                </div>
                @empty
                <!-- Fallback if no packages -->
                <div class="col-span-full text-center py-20">
                    <div class="max-w-md mx-auto">
                        <div class="text-8xl mb-6">📦</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">No Packages Available</h3>
                        <p class="text-gray-600 mb-8">
                            Our partners are working on amazing care packages. Stay tuned for great deals!
                        </p>
                        <a href="/merchants?role=groomer" class="btn-primary inline-flex items-center">
                            <i class="fas fa-search mr-2"></i> Browse All Groomers
                        </a>
                    </div>
                </div>
                @endforelse
            </div>
            
            <!-- View More Button -->
            @if(count($featuredPackages) > 0)
            <div class="text-center mt-12">
                <a href="/merchants?role=groomer" class="btn-primary inline-flex items-center text-lg">
                    <i class="fas fa-gift mr-3"></i>
                    View All Available Packages
                </a>
            </div>
            @endif
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        // No scripts needed for pet cards anymore
    </script>
@endpush
</body>
</html>