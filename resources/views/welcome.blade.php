@extends('layouts.app')

@section('content')

    {{-- Hero Section --}}
    <section class="bg-white py-24 px-4 text-center">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-5xl font-extrabold text-blue-600 mb-4">Welcome to PetCentre</h1>
            <p class="text-lg text-gray-600 mb-8">
                A centralized platform for pet adoption, clinical care, and grooming services.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="/pets" class="inline-block px-6 py-3 bg-blue-600 text-white text-lg rounded-lg hover:bg-blue-700 transition">Browse Pets</a>
                <a href="/services" class="inline-block px-6 py-3 bg-gray-200 text-gray-800 text-lg rounded-lg hover:bg-gray-300 transition">Our Services</a>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-16 bg-gray-100">
        <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">

            <div class="p-6 bg-white rounded-lg shadow hover:shadow-md transition">
                <h3 class="text-xl font-semibold text-blue-600 mb-2">Adoption</h3>
                <p class="text-gray-600">
                    Browse and adopt pets from local shelters looking for forever homes.
                </p>
            </div>

            <div class="p-6 bg-white rounded-lg shadow hover:shadow-md transition">
                <h3 class="text-xl font-semibold text-blue-600 mb-2">Pet Clinic</h3>
                <p class="text-gray-600">
                    Schedule vet visits, vaccinations, and keep medical records organized.
                </p>
            </div>

            <div class="p-6 bg-white rounded-lg shadow hover:shadow-md transition">
                <h3 class="text-xl font-semibold text-blue-600 mb-2">Grooming Services</h3>
                <p class="text-gray-600">
                    Book grooming sessions to keep your pets clean, healthy, and happy.
                </p>
            </div>

        </div>
    </section>
    
    {{-- Pet Adoption Showcase --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Pets Available for Adoption</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                <!-- Pet Card 1 -->
                <div onclick="openQuickView('Buddy', 'Golden Retriever • 2 years old', '/images/dog.png')" class="bg-gray-50 rounded-lg shadow p-4 hover:shadow-lg transition cursor-pointer">
                    <img src="/images/dog.png" alt="Buddy" class="w-full h-48 object-cover rounded mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Buddy</h3>
                    <p class="text-sm text-gray-500">Golden Retriever • 2 years old</p>
                </div>

                <!-- Pet Card 2 -->
                <div onclick="openQuickView('Mittens', 'Tabby Cat • 1.5 years old', '/images/cat.png')" class="bg-gray-50 rounded-lg shadow p-4 hover:shadow-lg transition cursor-pointer">
                    <img src="/images/cat.png" alt="Mittens" class="w-full h-48 object-cover rounded mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Mittens</h3>
                    <p class="text-sm text-gray-500">Tabby Cat • 1.5 years old</p>
                </div>

                <!-- Pet Card 3 -->
                <div onclick="openQuickView('Snowy', 'White Rabbit • 8 months old', '/images/paws.png')" class="bg-gray-50 rounded-lg shadow p-4 hover:shadow-lg transition cursor-pointer">
                    <img src="/images/paws.png" alt="Snowy" class="w-full h-48 object-cover rounded mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Snowy</h3>
                    <p class="text-sm text-gray-500">White Rabbit • 8 months old</p>
                </div>

                <!-- Pet Card 4 -->
                <div onclick="openQuickView('Rocky', 'Mixed Breed • 3 years old', '/images/dog.png')" class="bg-gray-50 rounded-lg shadow p-4 hover:shadow-lg transition cursor-pointer">
                    <img src="/images/dog.png" alt="Rocky" class="w-full h-48 object-cover rounded mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Rocky</h3>
                    <p class="text-sm text-gray-500">Mixed Breed • 3 years old</p>
                </div>
            </div>

            <div class="mt-10 text-center">
                <a href="/pets" class="text-blue-600 hover:underline font-medium">View all pets →</a>
            </div>
        </div>
    </section>
    
    {{-- Quick View Modal (Flowbite) --}}
    <div id="quickViewModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800 max-w-md w-full p-6">
            <button type="button" onclick="closeModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-900 text-sm">
                ✕
            </button>
            <div id="quickViewContent">
                <!-- JS will populate this -->
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function openQuickView(name, desc, imgUrl) {
        document.getElementById('quickViewModal').classList.remove('hidden');
        document.getElementById('quickViewContent').innerHTML = `
            <img src="${imgUrl}" alt="${name}" class="w-full h-48 object-cover rounded mb-4">
            <h3 class="text-xl font-bold text-gray-800">${name}</h3>
            <p class="text-sm text-gray-600 mb-4">${desc}</p>
            <a href="/pets" class="text-blue-600 hover:underline">See more pets →</a>
        `;
    }

    function closeModal() {
        document.getElementById('quickViewModal').classList.add('hidden');
    }
</script>
@endpush
