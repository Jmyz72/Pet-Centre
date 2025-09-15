<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}{{ isset($title) ? ' - ' . $title : '' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .floating-element {
            animation: float 6s ease-in-out infinite;
        }
        .floating-element:nth-child(2) { animation-delay: -1s; }
        .floating-element:nth-child(3) { animation-delay: -2s; }
        .floating-element:nth-child(4) { animation-delay: -3s; }
        .floating-element:nth-child(5) { animation-delay: -4s; }
        .floating-element:nth-child(6) { animation-delay: -5s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-10px) rotate(5deg); }
            66% { transform: translateY(5px) rotate(-5deg); }
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .pet-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
    </style>
</head>
<body class="h-full font-sans antialiased">
    <!-- Floating Pet Elements -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="floating-element absolute top-20 left-10 text-purple-300 opacity-30">
            <i class="fas fa-paw text-3xl"></i>
        </div>
        <div class="floating-element absolute top-32 right-20 text-pink-300 opacity-30">
            <i class="fas fa-heart text-2xl"></i>
        </div>
        <div class="floating-element absolute bottom-40 left-16 text-purple-300 opacity-30">
            <i class="fas fa-bone text-2xl"></i>
        </div>
        <div class="floating-element absolute bottom-20 right-16 text-pink-300 opacity-30">
            <i class="fas fa-cat text-3xl"></i>
        </div>
        <div class="floating-element absolute top-1/2 left-1/4 text-purple-300 opacity-20">
            <i class="fas fa-paw text-xl"></i>
        </div>
        <div class="floating-element absolute top-3/4 right-1/3 text-pink-300 opacity-20">
            <i class="fas fa-dog text-2xl"></i>
        </div>
    </div>

    <div class="min-h-full gradient-bg flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative z-10">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <!-- Logo and Header -->
            <div class="text-center">
                <div class="mx-auto w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-lg mb-6">
                    <i class="fas {{ $headerIcon ?? 'fa-paw' }} text-purple-600 text-2xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">{{ $headerTitle ?? 'Pet Center' }}</h2>
                <p class="text-purple-100 text-sm">{{ $headerSubtitle ?? 'Welcome to our pet family' }}</p>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="pet-card py-8 px-6 shadow-2xl rounded-2xl sm:px-10">
                {{ $slot }}
            </div>
        </div>
    </div>

    @if(isset($additionalScripts))
        {{ $additionalScripts }}
    @endif

    <!-- Default Password Toggle Script -->
    <script>
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(fieldId + '-toggle-icon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
