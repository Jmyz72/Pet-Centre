<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - PetCentre</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .policy-hero {
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)), 
                        url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="%23f8fafc"/><path d="M0 0L100 100" stroke="%23e2e8f0" stroke-width="2"/><path d="M100 0L0 100" stroke="%23e2e8f0" stroke-width="2"/></svg>');
            background-size: cover;
        }
        
        .policy-section {
            margin-bottom: 2.5rem;
        }
        
        .policy-card {
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
    <section class="policy-hero py-12 md:py-16 px-4">
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-800 mb-4">Privacy <span class="text-blue-600">Policy</span></h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Last updated: August 28, 2024
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
                        <div class="toc-item"><a href="#information-we-collect" class="toc-link">1. Information We Collect</a></div>
                        <div class="toc-item"><a href="#how-we-use-info" class="toc-link">2. How We Use Your Information</a></div>
                        <div class="toc-item"><a href="#cookies" class="toc-link">3. Cookies and Tracking Technologies</a></div>
                        <div class="toc-item"><a href="#data-sharing" class="toc-link">4. Data Sharing and Disclosure</a></div>
                    </div>
                    <div>
                        <div class="toc-item"><a href="#data-security" class="toc-link">5. Data Security</a></div>
                        <div class="toc-item"><a href="#your-rights" class="toc-link">6. Your Privacy Rights</a></div>
                        <div class="toc-item"><a href="#children-privacy" class="toc-link">7. Children's Privacy</a></div>
                        <div class="toc-item"><a href="#policy-changes" class="toc-link">8. Policy Changes</a></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Privacy Policy Content -->
    <section class="py-12 md:py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            <div class="policy-section">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 section-title">Our Privacy Commitment</h2>
                <p class="text-gray-600 mb-4">
                    At PetCentre, we are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our website and services.
                </p>
                <p class="text-gray-600">
                    By accessing or using PetCentre, you agree to the terms of this Privacy Policy. If you do not agree with the terms, please do not access the site.
                </p>
            </div>

            <div id="information-we-collect" class="policy-section">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">1. Information We Collect</h3>
                <div class="policy-card">
                    <h4 class="font-semibold text-gray-800 mb-2">Personal Information</h4>
                    <p class="text-gray-600">When you register for an account, use our services, or contact us, we may collect:</p>
                    <ul class="list-disc pl-5 mt-2 text-gray-600">
                        <li>Name, email address, and phone number</li>
                        <li>Billing and payment information</li>
                        <li>Pet information and veterinary records</li>
                        <li>Communication preferences</li>
                    </ul>
                </div>
                
                <div class="policy-card">
                    <h4 class="font-semibold text-gray-800 mb-2">Usage Information</h4>
                    <p class="text-gray-600">We automatically collect certain information when you visit our website:</p>
                    <ul class="list-disc pl-5 mt-2 text-gray-600">
                        <li>IP address and device information</li>
                        <li>Browser type and operating system</li>
                        <li>Pages visited and time spent on site</li>
                        <li>Referring websites and search terms</li>
                    </ul>
                </div>
            </div>

            <div id="how-we-use-info" class="policy-section">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">2. How We Use Your Information</h3>
                <div class="policy-card">
                    <p class="text-gray-600">We use the information we collect for various purposes, including:</p>
                    <ul class="list-disc pl-5 mt-2 text-gray-600">
                        <li>Providing, maintaining, and improving our services</li>
                        <li>Processing transactions and sending related information</li>
                        <li>Personalizing your experience and delivering content relevant to your interests</li>
                        <li>Sending administrative information, such as updates to our terms and policies</li>
                        <li>Responding to your comments, questions, and requests</li>
                        <li>Monitoring and analyzing trends, usage, and activities</li>
                        <li>Detecting, preventing, and addressing technical issues or fraudulent activities</li>
                    </ul>
                </div>
            </div>

            <div id="cookies" class="policy-section">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">3. Cookies and Tracking Technologies</h3>
                <div class="policy-card">
                    <p class="text-gray-600">We use cookies and similar tracking technologies to track activity on our website and store certain information.</p>
                    <p class="text-gray-600 mt-2">Types of cookies we use:</p>
                    <ul class="list-disc pl-5 mt-2 text-gray-600">
                        <li><strong>Essential cookies:</strong> Required for the operation of our website</li>
                        <li><strong>Analytical/performance cookies:</strong> Allow us to recognize and count visitors</li>
                        <li><strong>Functionality cookies:</strong> Enable us to personalize content</li>
                        <li><strong>Targeting cookies:</strong> Record your visit and pages you have visited</li>
                    </ul>
                    <p class="text-gray-600 mt-3">You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent.</p>
                </div>
            </div>

            <div id="data-sharing" class="policy-section">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">4. Data Sharing and Disclosure</h3>
                <div class="policy-card">
                    <p class="text-gray-600">We may share your information in the following situations:</p>
                    <ul class="list-disc pl-5 mt-2 text-gray-600">
                        <li><strong>Service providers:</strong> We may share information with third-party vendors who perform services on our behalf</li>
                        <li><strong>Business transfers:</strong> In connection with any merger or sale of company assets</li>
                        <li><strong>Legal requirements:</strong> When required by law or to protect our rights</li>
                        <li><strong>Veterinary partners:</strong> To facilitate care for your pets with your consent</li>
                    </ul>
                    <p class="text-gray-600 mt-3">We do not sell your personal information to third parties.</p>
                </div>
            </div>

            <div id="data-security" class="policy-section">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">5. Data Security</h3>
                <div class="policy-card">
                    <p class="text-gray-600">We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>
                    <p class="text-gray-600 mt-2">While we strive to use commercially acceptable means to protect your information, no method of transmission over the Internet or electronic storage is 100% secure.</p>
                </div>
            </div>

            <div id="your-rights" class="policy-section">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">6. Your Privacy Rights</h3>
                <div class="policy-card">
                    <p class="text-gray-600">Depending on your location, you may have the following rights regarding your personal information:</p>
                    <ul class="list-disc pl-5 mt-2 text-gray-600">
                        <li>Access and receive a copy of your personal data</li>
                        <li>Rectify inaccurate or incomplete information</li>
                        <li>Request deletion of your personal data</li>
                        <li>Restrict or object to our processing of your data</li>
                        <li>Data portability (receiving your data in a structured format)</li>
                        <li>Withdraw consent at any time where we rely on consent to process your information</li>
                    </ul>
                    <p class="text-gray-600 mt-3">To exercise these rights, please contact us using the information provided in the "Contact Us" section.</p>
                </div>
            </div>

            <div id="children-privacy" class="policy-section">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">7. Children's Privacy</h3>
                <div class="policy-card">
                    <p class="text-gray-600">Our website is not intended for children under the age of 13. We do not knowingly collect personal information from children under 13.</p>
                    <p class="text-gray-600 mt-2">If you are a parent or guardian and believe that your child has provided us with personal information, please contact us, and we will take steps to delete such information from our systems.</p>
                </div>
            </div>

            <div id="policy-changes" class="policy-section">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">8. Policy Changes</h3>
                <div class="policy-card">
                    <p class="text-gray-600">We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date.</p>
                    <p class="text-gray-600 mt-2">You are advised to review this Privacy Policy periodically for any changes. Changes to this Privacy Policy are effective when they are posted on this page.</p>
                </div>
            </div>

            <div class="policy-section">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Contact Us</h3>
                <div class="policy-card">
                    <p class="text-gray-600">If you have any questions about this Privacy Policy, please contact us:</p>
                    <ul class="list-none pl-0 mt-2 text-gray-600">
                        <li class="mb-2"><i class="fas fa-envelope text-blue-600 mr-2"></i>privacy@petcentre.com</li>
                        <li class="mb-2"><i class="fas fa-phone text-blue-600 mr-2"></i> +60 12 345 6789</li>
                    </ul>
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