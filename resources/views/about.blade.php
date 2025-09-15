<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - PetCentre</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .about-hero {
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)), 
                        url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="%23f8fafc"/><path d="M0 0L100 100" stroke="%23e2e8f0" stroke-width="2"/><path d="M100 0L0 100" stroke="%23e2e8f0" stroke-width="2"/></svg>');
            background-size: cover;
        }
        
        .team-card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }
        
        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .value-icon {
            background-color: #eff6ff;
            width: 70px;
            height: 70px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
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
        
        .timeline-item {
            position: relative;
            padding-left: 2rem;
            margin-bottom: 3rem;
        }
        
        .timeline-item:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: #3b82f6;
            border-radius: 2px;
        }
        
        .timeline-dot {
            position: absolute;
            left: -8px;
            top: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #3b82f6;
            border: 4px solid #fff;
            box-shadow: 0 0 0 2px #3b82f6;
        }
        
        .stat-card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-white text-gray-800">

@extends('layouts.app')

@section('content')

    <!-- Hero Section -->
    <section class="about-hero py-12 md:py-16 px-4">
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-800 mb-4">About <span class="text-blue-600">PetCentre</span></h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Dedicated to connecting pets with loving homes and providing exceptional care for your furry family members.
            </p>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 section-title">Our Story</h2>
                    <p class="text-gray-600 mb-4">
                        Founded in 2010, PetCentre began as a small local initiative to help homeless pets find their forever families. What started as a passion project has grown into a comprehensive pet care platform serving thousands of pet owners across the country.
                    </p>
                    <p class="text-gray-600 mb-4">
                        Our founder, Dr. Sarah Johnson, a veterinarian with over 20 years of experience, saw the need for a centralized platform that could address all aspects of pet care - from adoption to veterinary services and grooming.
                    </p>
                    <p class="text-gray-600">
                        Today, PetCentre continues to evolve while staying true to our original mission: to make pet ownership accessible, enjoyable, and rewarding for everyone while ensuring the highest standard of care for our furry friends.
                    </p>
                </div>
                <div class="rounded-2xl overflow-hidden shadow-lg">
                    <img src="https://images.unsplash.com/photo-1450778869180-41d0601e046e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1152&q=80" 
                         alt="Happy pets at PetCentre" 
                         class="w-full h-64 md:h-96 object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="py-12 md:py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 text-center section-title">Our Mission & Vision</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="bg-blue-50 p-8 rounded-2xl shadow-md">
                    <div class="value-icon">
                        <i class="fas fa-bullseye text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Our Mission</h3>
                    <p class="text-gray-600 text-center">
                        To provide a comprehensive, compassionate, and convenient platform that connects pets with loving homes and ensures their lifelong health and happiness through exceptional veterinary care, grooming services, and ongoing support for pet owners.
                    </p>
                </div>
                
                <div class="bg-blue-50 p-8 rounded-2xl shadow-md">
                    <div class="value-icon">
                        <i class="fas fa-eye text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Our Vision</h3>
                    <p class="text-gray-600 text-center">
                        To create a world where every pet has a loving home and receives the care they deserve, while building a community of informed and responsible pet owners who champion animal welfare and the human-animal bond.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 text-center section-title">Our Values</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-md text-center">
                    <div class="value-icon">
                        <i class="fas fa-heart text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Compassion</h3>
                    <p class="text-gray-600 text-sm">We treat every animal with the kindness and respect they deserve.</p>
                </div>
                
                <div class="bg-white p-6 rounded-2xl shadow-md text-center">
                    <div class="value-icon">
                        <i class="fas fa-shield-alt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Integrity</h3>
                    <p class="text-gray-600 text-sm">We're honest, transparent, and always do what's best for the animals.</p>
                </div>
                
                <div class="bg-white p-6 rounded-2xl shadow-md text-center">
                    <div class="value-icon">
                        <i class="fas fa-users text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Community</h3>
                    <p class="text-gray-600 text-sm">We believe in building a supportive network of pet lovers and advocates.</p>
                </div>
                
                <div class="bg-white p-6 rounded-2xl shadow-md text-center">
                    <div class="value-icon">
                        <i class="fas fa-graduation-cap text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Excellence</h3>
                    <p class="text-gray-600 text-sm">We're committed to the highest standards in all our services and care.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="py-12 md:py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 text-center section-title">Our Journey</h2>
            
            <div class="relative">
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="bg-gray-50 p-6 rounded-2xl shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">2010</h3>
                        <p class="text-gray-600">PetCentre was founded by Dr. Sarah Johnson with a small adoption center and veterinary clinic.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="bg-gray-50 p-6 rounded-2xl shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">2013</h3>
                        <p class="text-gray-600">Expanded services to include professional grooming and launched our first community education program.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="bg-gray-50 p-6 rounded-2xl shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">2016</h3>
                        <p class="text-gray-600">Opened two additional locations and reached the milestone of 1,000 successful adoptions.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="bg-gray-50 p-6 rounded-2xl shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">2019</h3>
                        <p class="text-gray-600">Launched our online platform, making our services accessible to pet owners across the country.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="bg-gray-50 p-6 rounded-2xl shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">2023</h3>
                        <p class="text-gray-600">Celebrated over 5,000 adoptions and expanded our team to include 25 veterinary professionals.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 text-center section-title">Meet Our Team</h2>
            <p class="text-gray-600 text-center max-w-2xl mx-auto mb-10">
                Our dedicated team of animal lovers, veterinarians, and pet care specialists work tirelessly to ensure the best possible experience for you and your pets.
            </p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="team-card bg-white rounded-2xl overflow-hidden shadow-md text-center">
                    <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=640&q=80" 
                         alt="Dr. Sarah Johnson" 
                         class="w-full h-56 object-cover">
                    <div class="p-5">
                        <h3 class="text-xl font-semibold text-gray-800">Dr. Sarah Johnson</h3>
                        <p class="text-blue-600 mb-2">Founder & Head Veterinarian</p>
                        <p class="text-gray-600 text-sm">With over 20 years of experience, Dr. Johnson leads our team with expertise and compassion.</p>
                    </div>
                </div>
                
                <div class="team-card bg-white rounded-2xl overflow-hidden shadow-md text-center">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=640&q=80" 
                         alt="Michael Chen" 
                         class="w-full h-56 object-cover">
                    <div class="p-5">
                        <h3 class="text-xl font-semibold text-gray-800">Michael Chen</h3>
                        <p class="text-blue-600 mb-2">Adoption Coordinator</p>
                        <p class="text-gray-600 text-sm">Michael has helped over 1,000 pets find their forever homes through our adoption program.</p>
                    </div>
                </div>
                
                <div class="team-card bg-white rounded-2xl overflow-hidden shadow-md text-center">
                    <img src="https://images.unsplash.com/photo-1567532939604-b6b5b0db1604?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=640&q=80" 
                         alt="Emily Rodriguez" 
                         class="w-full h-56 object-cover">
                    <div class="p-5">
                        <h3 class="text-xl font-semibold text-gray-800">Emily Rodriguez</h3>
                        <p class="text-blue-600 mb-2">Head Groomer</p>
                        <p class="text-gray-600 text-sm">Emily is a certified master groomer with a special talent for making pets look and feel their best.</p>
                    </div>
                </div>
                
                <div class="team-card bg-white rounded-2xl overflow-hidden shadow-md text-center">
                    <img src="https://images.unsplash.com/photo-1552058544-f2b08422138a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=640&q=80" 
                         alt="David Kim" 
                         class="w-full h-56 object-cover">
                    <div class="p-5">
                        <h3 class="text-xl font-semibold text-gray-800">David Kim</h3>
                        <p class="text-blue-600 mb-2">Veterinary Technician</p>
                        <p class="text-gray-600 text-sm">David specializes in surgical assistance and postoperative care for our veterinary patients.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 md:py-16 bg-blue-600 text-white">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-bold mb-6 text-center section-title">By The Numbers</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="stat-card bg-blue-700 p-6 rounded-2xl shadow-md text-center">
                    <div class="text-3xl md:text-4xl font-bold mb-2">5,000+</div>
                    <p class="text-blue-100">Pets Adopted</p>
                </div>
                
                <div class="stat-card bg-blue-700 p-6 rounded-2xl shadow-md text-center">
                    <div class="text-3xl md:text-4xl font-bold mb-2">25+</div>
                    <p class="text-blue-100">Team Members</p>
                </div>
                
                <div class="stat-card bg-blue-700 p-6 rounded-2xl shadow-md text-center">
                    <div class="text-3xl md:text-4xl font-bold mb-2">13</div>
                    <p class="text-blue-100">Years of Service</p>
                </div>
                
                <div class="stat-card bg-blue-700 p-6 rounded-2xl shadow-md text-center">
                    <div class="text-3xl md:text-4xl font-bold mb-2">98%</div>
                    <p class="text-blue-100">Satisfaction Rate</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 md:py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6">Join Our Pet Care Community</h2>
            <p class="text-gray-600 mb-8 max-w-2xl mx-auto">
                Whether you're looking to adopt, need veterinary services, or want to keep your pet looking their best, we're here to help every step of the way.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="../#pets" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition">
                    Browse Pets
                </a>
                <a href="/contact" class="px-6 py-3 bg-gray-100 text-gray-800 font-semibold rounded-lg hover:bg-gray-200 transition">
                    Contact Us
                </a>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        // Simple animation for stats counting
        document.addEventListener('DOMContentLoaded', function() {
            const statElements = document.querySelectorAll('.stat-card .text-3xl');
            
            statElements.forEach(stat => {
                const target = parseInt(stat.textContent);
                let current = 0;
                const duration = 2000; // 2 seconds
                const steps = 50;
                const increment = target / steps;
                const stepTime = duration / steps;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        stat.textContent = target + (stat.textContent.includes('+') ? '+' : '');
                        clearInterval(timer);
                    } else {
                        stat.textContent = Math.floor(current) + (stat.textContent.includes('+') ? '+' : '');
                    }
                }, stepTime);
            });
        });
    </script>
@endpush

</body>
</html>