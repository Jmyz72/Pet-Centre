<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - PetCentre</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .contact-bg {
            background: linear-gradient(to right, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.98)), 
                        url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="%23f8fafc"/><path d="M0 0L100 100" stroke="%23e2e8f0" stroke-width="2"/><path d="M100 0L0 100" stroke="%23e2e8f0" stroke-width="2"/></svg>');
            background-size: cover;
        }
        
        .contact-card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }
        
        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .contact-icon {
            background-color: #eff6ff;
            width: 60px;
            height: 60px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
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
        
        .form-input {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            outline: none;
        }
        
        .map-container {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            height: 400px;
        }
        
        #map {
            height: 100%;
            width: 100%;
        }
    </style>
</head>
<body class="bg-white text-gray-800">

@extends('layouts.app')

@section('content')

    <!-- Hero Section -->
    <section class="contact-bg py-12 md:py-16 px-4">
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-800 mb-4">Get in <span class="text-blue-600">Touch</span></h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Have questions about our services or want to schedule an appointment? We'd love to hear from you.
            </p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <!-- Contact Information -->
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 section-title">Contact Information</h2>
                    <p class="text-gray-600 mb-8">
                        Feel free to reach out to us for any inquiries about pet adoption, veterinary services, or grooming appointments. Our team is here to help you and your furry friends.
                    </p>
                    
                    <div class="space-y-6">
                        <div class="contact-card bg-white p-6 rounded-xl shadow-md flex items-start">
                            <div class="contact-icon mr-5 text-blue-600">
                                <i class="fas fa-map-marker-alt text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-1">Our Location</h3>
                                <p class="text-gray-600">123 Pet Street, Animal City, AC 12345</p>
                            </div>
                        </div>
                        
                        <div class="contact-card bg-white p-6 rounded-xl shadow-md flex items-start">
                            <div class="contact-icon mr-5 text-blue-600">
                                <i class="fas fa-phone text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-1">Phone Number</h3>
                                <p class="text-gray-600">+1 (555) 123-PETS</p>
                                <p class="text-sm text-gray-500">Mon-Fri: 8:00 AM - 6:00 PM</p>
                            </div>
                        </div>
                        
                        <div class="contact-card bg-white p-6 rounded-xl shadow-md flex items-start">
                            <div class="contact-icon mr-5 text-blue-600">
                                <i class="fas fa-envelope text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-1">Email Address</h3>
                                <p class="text-gray-600">info@petcentre.com</p>
                                <p class="text-gray-600">support@petcentre.com</p>
                            </div>
                        </div>
                        
                        <div class="contact-card bg-white p-6 rounded-xl shadow-md flex items-start">
                            <div class="contact-icon mr-5 text-blue-600">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-1">Business Hours</h3>
                                <p class="text-gray-600">Monday - Friday: 8:00 AM - 6:00 PM</p>
                                <p class="text-gray-600">Saturday: 9:00 AM - 4:00 PM</p>
                                <p class="text-gray-600">Sunday: 10:00 AM - 2:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Form -->
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 section-title">Send Us a Message</h2>
                    
                    <form class="bg-white p-6 md:p-8 rounded-xl shadow-md">
                        <div class="mb-5">
                            <label for="name" class="block text-gray-700 font-medium mb-2">Your Name</label>
                            <input type="text" id="name" class="form-input w-full" placeholder="John Doe">
                        </div>
                        
                        <div class="mb-5">
                            <label for="email" class="block text-gray-700 font-medium mb-2">Email Address</label>
                            <input type="email" id="email" class="form-input w-full" placeholder="john@example.com">
                        </div>
                        
                        <div class="mb-5">
                            <label for="phone" class="block text-gray-700 font-medium mb-2">Phone Number</label>
                            <input type="tel" id="phone" class="form-input w-full" placeholder="(555) 123-4567">
                        </div>
                        
                        <div class="mb-5">
                            <label for="subject" class="block text-gray-700 font-medium mb-2">Subject</label>
                            <select id="subject" class="form-input w-full">
                                <option value="">Select a subject</option>
                                <option value="adoption">Pet Adoption</option>
                                <option value="veterinary">Veterinary Services</option>
                                <option value="grooming">Grooming Services</option>
                                <option value="general">General Inquiry</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-5">
                            <label for="message" class="block text-gray-700 font-medium mb-2">Your Message</label>
                            <textarea id="message" rows="5" class="form-input w-full" placeholder="How can we help you?"></textarea>
                        </div>
                        
                        <button type="submit" class="btn-primary w-full py-3 rounded-lg text-white font-semibold">
                            Send Message <i class="fas fa-paper-plane ml-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Map Section -->
    <section class="py-12 md:py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 text-center section-title">Our Location</h2>
            
            <div class="map-container">
                <div id="map"></div>
            </div>
            
            <div class="mt-6 text-center">
                <p class="text-gray-600">123 Pet Street, Animal City, AC 12345</p>
                <a href="https://www.google.com/maps/dir//123+Pet+Street,+Animal+City,+AC+12345" 
                   target="_blank" 
                   class="inline-block mt-3 text-blue-600 hover:text-blue-800 font-medium">
                    Get Directions <i class="fas fa-arrow-right ml-1 text-sm"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 text-center section-title">Frequently Asked Questions</h2>
            
            <div class="space-y-4">
                <div class="bg-white p-5 rounded-xl shadow-md">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center">
                        <i class="fas fa-paw text-blue-600 mr-3"></i>
                        How long does the adoption process take?
                    </h3>
                    <p class="text-gray-600">The adoption process typically takes 3-5 business days. This includes application review, meeting the pet, and completing the necessary paperwork.</p>
                </div>
                
                <div class="bg-white p-5 rounded-xl shadow-md">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center">
                        <i class="fas fa-stethoscope text-blue-600 mr-3"></i>
                        Do you offer emergency veterinary services?
                    </h3>
                    <p class="text-gray-600">Yes, we have an on-call veterinarian for emergencies during business hours. After hours, we recommend contacting Animal Emergency Hospital at (555) 123-HELP.</p>
                </div>
                
                <div class="bg-white p-5 rounded-xl shadow-md">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center">
                        <i class="fas fa-spa text-blue-600 mr-3"></i>
                        How often should I groom my pet?
                    </h3>
                    <p class="text-gray-600">It depends on the breed and coat type. Generally, dogs should be groomed every 4-6 weeks, while cats may require less frequent grooming. Our experts can provide personalized recommendations.</p>
                </div>
            </div>
            
            <div class="text-center mt-8">
                <a href="#" class="text-blue-600 font-medium hover:text-blue-800">
                    View all FAQs <i class="fas fa-arrow-right ml-1 text-sm"></i>
                </a>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
    
    <script>
        // Initialize and display the map
        function initMap() {
            // Replace with your actual address coordinates
            const petcentreLocation = { lat: 3.2160, lng: 101.7256 }; // Example: New York City
            
            // Create a map centered at the location
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: petcentreLocation,
                styles: [
                    {
                        "featureType": "administrative",
                        "elementType": "labels.text.fill",
                        "stylers": [{"color": "#444444"}]
                    },
                    {
                        "featureType": "landscape",
                        "elementType": "all",
                        "stylers": [{"color": "#f2f2f2"}]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "all",
                        "stylers": [{"visibility": "off"}]
                    },
                    {
                        "featureType": "road",
                        "elementType": "all",
                        "stylers": [{"saturation": -100}, {"lightness": 45}]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "all",
                        "stylers": [{"visibility": "simplified"}]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "labels.icon",
                        "stylers": [{"visibility": "off"}]
                    },
                    {
                        "featureType": "transit",
                        "elementType": "all",
                        "stylers": [{"visibility": "off"}]
                    },
                    {
                        "featureType": "water",
                        "elementType": "all",
                        "stylers": [{"color": "#d4e6ff"}, {"visibility": "on"}]
                    }
                ]
            });
            
            // Create a marker at the location
            const marker = new google.maps.Marker({
                position: petcentreLocation,
                map: map,
                title: 'PetCentre',
                icon: {
                    url: 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzMCA0MCI+PHBhdGggZD0iTTE1IDNDOC4zNzMgMyAzIDguMzczIDMgMTVjMCA1LjI2MyA0LjIxMSA5LjU3NyAxMCAxNC42MzIgNS43ODktNS4wNTUgMTAtOS4zNjkgMTAtMTQuNjMyIDAtNi42MjctNS4zNzMtMTItMTItMTJ6IiBmaWxsPSIjM2I4MmY2IiBzdHJva2U9IiMyNTYzZWIiIHN0cm9rZS13aWR0aD0iMS41Ii8+PGNpcmNsZSBjeD0iMTUiIGN5PSIxNSIgcj0iNSIgZmlsbD0id2hpdGUiLz48L3N2Zz4=',
                    scaledSize: new google.maps.Size(30, 40),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(15, 40)
                }
            });
            
            // Create an info window
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="padding: 10px;">
                        <h3 style="margin: 0 0 8px; color: #3b82f6; font-weight: 600;">PetCentre</h3>
                        <p style="margin: 0; color: #4b5563;">123 Pet Street, Animal City, AC 12345</p>
                        <p style="margin: 8px 0 0;">
                            <a href="https://www.google.com/maps/dir//123+Pet+Street,+Animal+City,+AC+12345" 
                               target="_blank" 
                               style="color: #3b82f6; text-decoration: none; font-weight: 500;">
                                Get Directions
                            </a>
                        </p>
                    </div>
                `
            });
            
            // Add click event to open info window
            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });
            
            // Open info window by default
            infoWindow.open(map, marker);
        }
        
        // Form validation script
        document.addEventListener('DOMContentLoaded', function() {
            const contactForm = document.querySelector('form');
            
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Simple validation
                    const nameInput = document.getElementById('name');
                    const emailInput = document.getElementById('email');
                    const messageInput = document.getElementById('message');
                    
                    let isValid = true;
                    
                    if (!nameInput.value.trim()) {
                        isValid = false;
                        highlightError(nameInput);
                    } else {
                        removeHighlight(nameInput);
                    }
                    
                    if (!emailInput.value.trim() || !isValidEmail(emailInput.value)) {
                        isValid = false;
                        highlightError(emailInput);
                    } else {
                        removeHighlight(emailInput);
                    }
                    
                    if (!messageInput.value.trim()) {
                        isValid = false;
                        highlightError(messageInput);
                    } else {
                        removeHighlight(messageInput);
                    }
                    
                    if (isValid) {
                        // Simulate form submission success
                        alert('Thank you for your message! We will get back to you soon.');
                        contactForm.reset();
                    }
                });
            }
            
            function highlightError(input) {
                input.classList.add('border-red-500');
            }
            
            function removeHighlight(input) {
                input.classList.remove('border-red-500');
            }
            
            function isValidEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }
        });
    </script>
@endpush

</body>
</html>