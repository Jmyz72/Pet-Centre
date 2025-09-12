<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Licensing - PetCentre</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .license-hero {
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)), 
                        url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="%23f8fafc"/><path d="M0 0L100 100" stroke="%23e2e8f0" stroke-width="2"/><path d="M100 0L0 100" stroke="%23e2e8f0" stroke-width="2"/></svg>');
            background-size: cover;
        }
        
        .license-section {
            margin-bottom: 2.5rem;
        }
        
        .license-card {
            background-color: #f8fafc;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #3b82f6;
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
        
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #3b82f6;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
        }
        
        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            background: #2563eb;
            transform: translateY(-3px);
        }
        
        .toc-item {
            margin-bottom: 0.75rem;
            position: relative;
            padding-left: 1.5rem;
        }
        
        .toc-item:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #3b82f6;
        }
        
        .toc-link {
            color: #4b5563;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        
        .toc-link:hover {
            color: #3b82f6;
        }
    </style>
</head>
<body class="bg-white text-gray-800">

@extends('layouts.app')

@section('content')

    <!-- Hero Section -->
    <section class="license-hero py-12 md:py-16 px-4">
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-800 mb-4">Licensing <span class="text-blue-600">Information</span></h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Last updated: October 28, 2023
            </p>
        </div>
    </section>

    <!-- Table of Contents -->
    <section class="py-8 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white p-6 rounded-2xl shadow-md">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Table of Contents</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="toc-item"><a href="#business-license" class="toc-link">1. Business Licensing</a></div>
                        <div class="toc-item"><a href="#animal-welfare" class="toc-link">2. Animal Welfare Compliance</a></div>
                        <div class="toc-item"><a href="#veterinary-license" class="toc-link">3. Veterinary Services Licensing</a></div>
                        <div class="toc-item"><a href="#data-protection" class="toc-link">4. Data Protection</a></div>
                    </div>
                    <div>
                        <div class="toc-item"><a href="#intellectual-property" class="toc-link">5. Intellectual Property</a></div>
                        <div class="toc-item"><a href="#software-license" class="toc-link">6. Software Licensing</a></div>
                        <div class="toc-item"><a href="#content-license" class="toc-link">7. Content Licensing</a></div>
                        <div class="toc-item"><a href="#compliance" class="toc-link">8. Compliance Information</a></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Licensing Content -->
    <section class="py-12 md:py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            <div class="license-section">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 section-title">Licensing Overview</h2>
                <p class="text-gray-600 mb-4">
                    PetCentre operates in compliance with all relevant local, state, and federal regulations. This page outlines the various licenses, permits, and compliance measures that govern our operations.
                </p>
                <p class="text-gray-600">
                    We are committed to transparency in our business practices and maintaining the highest standards of legal and ethical compliance in all aspects of our services.
                </p>
            </div>

            <div id="business-license" class="license-section">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">1. Business Licensing</h3>
                <div class="license-card">
                    <p class="text-gray-600">PetCentre maintains all necessary business licenses and permits to operate our pet adoption, veterinary, and grooming services.</p>
                    <p class="text-gray-600 mt-2">Our primary business licenses include:</p>
                    <ul class="list-disc pl-5 mt-2 text-gray-600">
                        <li>Business Operation License: #B-2023-PC-789456</li>
                        <li>Animal Shelter Permit: #AS-2023-12345</li>
                        <li>Sales Tax License: #ST-987654321</li>
                        <li>Zoning Permit: #ZP-2023-PC-456</li>
                    </ul>
                    <p class="text-gray-600 mt-3">All licenses are renewed annually and are available for inspection at our main office during business hours.</p>
                </div>
            </div>

            <div id="animal-welfare" class="license-section">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">2. Animal Welfare Compliance</h3>
                <div class="license-card">
                    <p class="text-gray-600">PetCentre adheres to all animal welfare regulations and maintains certifications from recognized animal welfare organizations.</p>
                    <p class="text-gray-600 mt-2">Our certifications include:</p>
                    <ul class="list-disc pl-5 mt-2 text-gray-600">
                        <li>Animal Welfare Certification from the International Animal Welfare Board</li>
                        <li>Shelter Standards Compliance from the National Animal Care Association</li>
                        <li>Ethical Adoption Practices certification from Global Pet Alliance</li>
                    </ul>
                    <p class="text-gray-600 mt-3">We undergo regular inspections to ensure compliance with all animal housing, care, and handling regulations.</p>
                </div>
            </div>

            <div id="veterinary-license" class="license-section">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">3. Veterinary Services Licensing</h3>
                <div class="license-card">
                    <p class="text-gray-600">All veterinary services provided by PetCentre are performed by licensed veterinarians in compliance with state veterinary practice regulations.</p>
                    <p class="text-gray-600 mt-2">Our veterinary licenses include:</p>
                    <ul class="list-disc pl-5 mt-2 text-gray-600">
                        <li>Veterinary Clinic License: #VC-2023-789</li>
                        <li>Pharmacy License: #PH-2023-456123</li>
                        <li>Controlled Substances License: #CS-2023-987</li>
                        <li>X-Ray Equipment Registration: #XR-2023-654</li>
                    </ul>
                    <p class="text-gray-600 mt-3">All our veterinarians maintain current individual licenses and participate in continuing education as required by state regulations.</p>
                </div>
            </div>

            <div id="data-protection" class="license-section">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">4. Data Protection</h3>
                <div class="license-card">
                    <p class="text-gray-600">PetCentre complies with data protection regulations including GDPR, CCPA, and other applicable privacy laws.</p>
                    <p class="text-gray-600 mt-2">Our data protection measures include:</p>
                    <ul class="list-disc pl-5 mt-2 text-gray-600">
                        <li>Data Protection Registration: #DPR-0123456</li>
                        <li>SSL Encryption for all data transfers</li>
                        <li>Regular security audits and vulnerability assessments</li>
                        <li>Compliance with Payment Card Industry Data Security Standard (PCI DSS)</li>
                    </ul>
                    <p class="text-gray-600 mt-3">We have appointed a Data Protection Officer who can be contacted at dpo@petcentre.com for any data protection inquiries.</p>
                </div>
            </div>

            <div id="intellectual-property" class="license-section">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">5. Intellectual Property</h3>
                <div class="license-card">
                    <p class="text-gray-600">All intellectual property associated with PetCentre, including trademarks, logos, and proprietary content, is protected under applicable laws.</p>
                    <p class="text-gray-600 mt-2">Our registered intellectual property includes:</p>
                    <ul class="list-disc pl-5 mt-2 text-gray-600">
                        <li>PetCentre® - Registered Trademark: #TM-789456123</li>
                        <li>Logo and Brand Identity: Copyright Reg. #CR-2023-456789</li>
                        <li>Proprietary Software Systems: Patent Pending #PP-2023-123</li>
                    </ul>
                    <p class="text-gray-600 mt-3">Unauthorized use of any PetCentre intellectual property is strictly prohibited. For licensing inquiries, please contact legal@petcentre.com.</p>
                </div>
            </div>

            <div id="software-license" class="license-section">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">6. Software Licensing</h3>
                <div class="license-card">
                    <p class="text-gray-600">PetCentre utilizes both proprietary and third-party software, all properly licensed for commercial use.</p>
                    <p class="text-gray-600 mt-2">Our software licenses include:</p>
                    <ul class="list-disc pl-5 mt-2 text-gray-600">
                        <li>PetCentre Management System: Proprietary License</li>
                        <li>Veterinary Records Database: Commercial License #VRD-LIC-789456</li>
                        <li>Payment Processing System: Commercial License #PPS-LIC-2023-456</li>
                        <li>Customer Relationship Management: Commercial License #CRM-LIC-789123</li>
                    </ul>
                    <p class="text-gray-600 mt-3">We regularly audit our software licenses to ensure compliance with all terms and conditions.</p>
                </div>
            </div>

            <div id="content-license" class="license-section">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">7. Content Licensing</h3>
                <div class="license-card">
                    <p class="text-gray-600">All content on the PetCentre website and platforms is either original, properly licensed, or used with permission.</p>
                    <p class="text-gray-600 mt-2">Our content licensing includes:</p>
                    <ul class="list-disc pl-5 mt-2 text-gray-600">
                        <li>Stock Photography License: #SPL-2023-789</li>
                        <li>Educational Content License: #ECL-2023-456</li>
                        <li>Veterinary Content License: #VCL-2023-123</li>
                        <li>Media Distribution License: #MDL-2023-987</li>
                    </ul>
                    <p class="text-gray-600 mt-3">If you believe any content on our site infringes on your copyright, please contact us immediately at copyright@petcentre.com.</p>
                </div>
            </div>

            <div id="compliance" class="license-section">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">8. Compliance Information</h3>
                <div class="license-card">
                    <p class="text-gray-600">PetCentre is committed to full compliance with all applicable laws and regulations. We maintain detailed records of all licenses and permits, which are available for regulatory review upon request.</p>
                    <p class="text-gray-600 mt-2">Our compliance program includes:</p>
                    <ul class="list-disc pl-5 mt-2 text-gray-600">
                        <li>Regular internal compliance audits</li>
                        <li>Employee training on regulatory requirements</li>
                        <li>Documented compliance procedures</li>
                        <li>Designated Compliance Officer</li>
                    </ul>
                    <p class="text-gray-600 mt-3">For any questions about our licensing or compliance status, please contact our Compliance Officer at compliance@petcentre.com.</p>
                </div>
            </div>
        </div>
    </section>

    <a href="#" class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </a>

@endsection

@push('scripts')
    <script>
        // Back to top button functionality
        document.addEventListener('DOMContentLoaded', function() {
            const backToTopButton = document.getElementById('backToTop');
            
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTopButton.classList.add('visible');
                } else {
                    backToTopButton.classList.remove('visible');
                }
            });
            
            backToTopButton.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
            
            // Smooth scrolling for table of contents links
            document.querySelectorAll('.toc-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
@endpush

</body>
</html>