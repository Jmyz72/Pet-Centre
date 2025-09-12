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
            background: linear-gradient(to right, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.98)), 
                        url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="%23f8fafc"/><path d="M0 0L100 100" stroke="%23e2e8f0" stroke-width="2"/><path d="M100 0L0 100" stroke="%23e2e8f0" stroke-width="2"/></svg>');
            background-size: cover;
        }
        
        .pet-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .pet-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            background-color: #eff6ff;
            width: 70px;
            height: 70px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        .btn-primary {
            background: linear-gradient(to right, #3b82f6, #2563eb);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.4);
        }
        
        .btn-secondary {
            background: white;
            transition: all 0.3s ease;
            border: 2px solid #3b82f6;
        }
        
        .btn-secondary:hover {
            background: #f8fafc;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .modal-content {
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 2rem;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(to right, #3b82f6, #2563eb);
            border-radius: 2px;
        }
    </style>
</head>
<body class="bg-white text-gray-800">

@extends('layouts.app')

@section('content')

    <!-- Hero Section -->
    <section class="hero-bg py-16 md:py-24 px-4">
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 mb-6">Find Your Perfect <span class="text-blue-600">Pet Companion</span></h1>
            <p class="text-lg md:text-xl text-gray-600 mb-10 max-w-3xl mx-auto">
                A centralized platform for pet adoption, clinical care, and grooming services.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-5">
                <a href="/pets" class="btn-primary px-8 py-4 rounded-lg text-lg font-semibold text-white">
                    <i class="fas fa-paw mr-2"></i> Browse Pets
                </a>
                <a href="/services" class="btn-secondary px-8 py-4 rounded-lg text-lg font-semibold text-blue-600">
                    <i class="fas fa-concierge-bell mr-2"></i> Our Services
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 md:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4 section-title">Everything Your Pet Needs</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">We provide comprehensive care for your furry friends with our range of specialized services</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 text-center">
                    <div class="feature-icon">
                        <i class="fas fa-home text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Adoption</h3>
                    <p class="text-gray-600">
                        Browse and adopt pets from local shelters looking for forever homes.
                    </p>
                    <a href="/adoption" class="inline-block mt-6 text-blue-600 font-medium hover:text-blue-800">
                        Learn more <i class="fas fa-arrow-right ml-1 text-sm"></i>
                    </a>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 text-center">
                    <div class="feature-icon">
                        <i class="fas fa-stethoscope text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Pet Clinic</h3>
                    <p class="text-gray-600">
                        Schedule vet visits, vaccinations, and keep medical records organized.
                    </p>
                    <a href="/clinic" class="inline-block mt-6 text-blue-600 font-medium hover:text-blue-800">
                        Learn more <i class="fas fa-arrow-right ml-1 text-sm"></i>
                    </a>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 text-center">
                    <div class="feature-icon">
                        <i class="fas fa-spa text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Grooming Services</h3>
                    <p class="text-gray-600">
                        Book grooming sessions to keep your pets clean, healthy, and happy.
                    </p>
                    <a href="/grooming" class="inline-block mt-6 text-blue-600 font-medium hover:text-blue-800">
                        Learn more <i class="fas fa-arrow-right ml-1 text-sm"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Pet Adoption Showcase -->
    <section class="py-16 md:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4 section-title">Pets Looking for a Home</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">These adorable pets are waiting to meet their forever families. Could one of them be yours?</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                <!-- Pet Card 1 -->
                <div onclick="openQuickView('Buddy', 'Golden Retriever • 2 years old', '/images/dog.png')" 
                     class="pet-card bg-white rounded-xl overflow-hidden shadow-md cursor-pointer border border-gray-100">
                    <div class="h-56 overflow-hidden bg-gray-100 flex items-center justify-center">
                        <img src="/images/dog.png" alt="Buddy" class="w-full h-full object-cover">
                    </div>
                    <div class="p-5">
                        <h3 class="text-xl font-semibold text-gray-800">Buddy</h3>
                        <p class="text-gray-600 text-sm mt-1">Golden Retriever • 2 years</p>
                        <div class="flex items-center mt-4">
                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Friendly</span>
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full ml-2">Good with kids</span>
                        </div>
                    </div>
                </div>

                <!-- Pet Card 2 -->
                <div onclick="openQuickView('Mittens', 'Tabby Cat • 1.5 years old', '/images/cat.png')" 
                     class="pet-card bg-white rounded-xl overflow-hidden shadow-md cursor-pointer border border-gray-100">
                    <div class="h-56 overflow-hidden bg-gray-100 flex items-center justify-center">
                        <img src="/images/cat.png" alt="Mittens" class="w-full h-full object-cover">
                    </div>
                    <div class="p-5">
                        <h3 class="text-xl font-semibold text-gray-800">Mittens</h3>
                        <p class="text-gray-600 text-sm mt-1">Tabby Cat • 1.5 years</p>
                        <div class="flex items-center mt-4">
                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Playful</span>
                            <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full ml-2">Indoor</span>
                        </div>
                    </div>
                </div>

                <!-- Pet Card 3 -->
                <div onclick="openQuickView('Snowy', 'White Rabbit • 8 months old', '/images/paws.png')" 
                     class="pet-card bg-white rounded-xl overflow-hidden shadow-md cursor-pointer border border-gray-100">
                    <div class="h-56 overflow-hidden bg-gray-100 flex items-center justify-center">
                        <img src="/images/paws.png" alt="Snowy" class="w-full h-full object-cover">
                    </div>
                    <div class="p-5">
                        <h3 class="text-xl font-semibold text-gray-800">Snowy</h3>
                        <p class="text-gray-600 text-sm mt-1">White Rabbit • 8 months</p>
                        <div class="flex items-center mt-4">
                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Gentle</span>
                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full ml-2">Quiet</span>
                        </div>
                    </div>
                </div>

                <!-- Pet Card 4 -->
                <div onclick="openQuickView('Rocky', 'Mixed Breed • 3 years old', '/images/dog.png')" 
                     class="pet-card bg-white rounded-xl overflow-hidden shadow-md cursor-pointer border border-gray-100">
                    <div class="h-56 overflow-hidden bg-gray-100 flex items-center justify-center">
                        <img src="/images/dog.png" alt="Rocky" class="w-full h-full object-cover">
                    </div>
                    <div class="p-5">
                        <h3 class="text-xl font-semibold text-gray-800">Rocky</h3>
                        <p class="text-gray-600 text-sm mt-1">Mixed Breed • 3 years</p>
                        <div class="flex items-center mt-4">
                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Loyal</span>
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full ml-2">Active</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-16 text-center">
                <a href="/pets" class="inline-flex items-center px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg shadow-md hover:shadow-lg transition-all border border-gray-200">
                    View All Available Pets <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 md:py-24 bg-gray-50">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6">Ready to Find Your New Best Friend?</h2>
            <p class="text-lg text-gray-600 mb-10 max-w-2xl mx-auto">
                Join thousands of happy pet owners who found their perfect companion through PetCentre.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="/register" class="px-8 py-4 bg-blue-600 text-white font-semibold rounded-lg shadow-lg hover:bg-blue-700 transition">
                    Get Started
                </a>
                <a href="/about" class="px-8 py-4 bg-white border border-gray-300 text-gray-800 font-semibold rounded-lg hover:bg-gray-50 transition">
                    Learn More
                </a>
            </div>
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
                    <h3 class="text-2xl font-bold text-gray-800">${name}</h3>
                    <p class="text-gray-600 mb-5">${desc}</p>
                    <div class="bg-blue-50 p-4 rounded-lg mb-5">
                        <h4 class="font-semibold text-blue-800 mb-2">Interested in adopting ${name}?</h4>
                        <p class="text-sm text-blue-600">Fill out our adoption form to schedule a meeting!</p>
                    </div>
                    <a href="/adoption-form" class="inline-block w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">Start Adoption Process</a>
                    <a href="/pets" class="inline-block w-full py-3 mt-3 text-blue-600 hover:text-blue-800 font-medium">See more pets <i class="fas fa-arrow-right ml-1 text-sm"></i></a>
                </div>
            `;
        }

        function closeModal() {
            document.getElementById('quickViewModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('quickViewModal').addEventListener('click', function(e) {
            if (e.target.id === 'quickViewModal') {
                closeModal();
            }
        });
    </script>
@endpush

</body>
</html>