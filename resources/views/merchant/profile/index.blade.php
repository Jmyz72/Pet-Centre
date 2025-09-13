<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $profile->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Profile Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-start md:space-x-6">
                        <div class="flex-shrink-0 mb-4 md:mb-0">
                            @if($profile->photo)
                                <img class="h-32 w-32 rounded-lg object-cover" src="{{ Storage::url($profile->photo) }}" alt="{{ $profile->name }}">
                            @else
                                <div class="h-32 w-32 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500 text-4xl">🏢</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $profile->name }}</h1>
                            
                            <div class="mb-4">
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                    {{ $profile->role === 'clinic' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $profile->role === 'groomer' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $profile->role === 'shelter' ? 'bg-purple-100 text-purple-800' : '' }}">
                                    {{ ucfirst($profile->role) }}
                                </span>
                            </div>
                            
                            @if($profile->description)
                                <p class="text-gray-600 mb-4">{{ $profile->description }}</p>
                            @endif
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($profile->phone)
                                    <div class="flex items-center">
                                        <span class="text-gray-500 mr-2">📞</span>
                                        <span>{{ $profile->phone }}</span>
                                    </div>
                                @endif
                                
                                @if($profile->address)
                                    <div class="flex items-start">
                                        <span class="text-gray-500 mr-2">📍</span>
                                        <span>{{ $profile->address }}</span>
                                    </div>
                                @endif
                                
                                @if($profile->registration_number)
                                    <div class="flex items-center">
                                        <span class="text-gray-500 mr-2">🏢</span>
                                        <span>Reg: {{ $profile->registration_number }}</span>
                                    </div>
                                @endif
                                
                                @if($profile->license_number)
                                    <div class="flex items-center">
                                        <span class="text-gray-500 mr-2">📜</span>
                                        <span>License: {{ $profile->license_number }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Operating Hours -->
            @if($profile->operatingHours && $profile->operatingHours->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Operating Hours</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($profile->operatingHours->groupBy('day_of_week') as $day => $hours)
                                <div class="flex justify-between">
                                    <span class="font-medium">{{ ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][$day] }}:</span>
                                    <span>
                                        @if($hours->count() > 0)
                                            @foreach($hours as $hour)
                                                {{ $hour->open_time }} - {{ $hour->close_time }}
                                                @if(!$loop->last), @endif
                                            @endforeach
                                        @else
                                            Closed
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Role-specific Content -->
            <div class="space-y-6">
                @if($profile->role === 'clinic' && isset($services))
                    <!-- Services for Clinic -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Our Services</h3>
                            @if($services->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($services as $service)
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <h4 class="font-medium text-gray-900 mb-2">{{ $service->title }}</h4>
                                            @if($service->description)
                                                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($service->description, 100) }}</p>
                                            @endif
                                            @if($service->price)
                                                <p class="text-sm font-semibold text-green-600">From RM {{ number_format($service->price, 2) }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No services available at the moment.</p>
                            @endif
                        </div>
                    </div>
                @endif

                @if($profile->role === 'groomer' && isset($packages))
                    <!-- Packages for Groomer -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Grooming Packages</h3>
                            @if($packages->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($packages as $package)
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <h4 class="font-medium text-gray-900 mb-2">{{ $package->name }}</h4>
                                            @if($package->description)
                                                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($package->description, 100) }}</p>
                                            @endif
                                            @if($package->base_price)
                                                <p class="text-sm font-semibold text-green-600">From RM {{ number_format($package->base_price, 2) }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No packages available at the moment.</p>
                            @endif
                        </div>
                    </div>
                @endif

                @if($profile->role === 'shelter' && isset($pets))
                    <!-- Pets for Shelter -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pets Available for Adoption</h3>
                            @if($pets->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($pets as $pet)
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            @if($pet->photo)
                                                <img class="w-full h-48 object-cover rounded-lg mb-3" src="{{ Storage::url($pet->photo) }}" alt="{{ $pet->name }}">
                                            @else
                                                <div class="w-full h-48 bg-gray-200 rounded-lg mb-3 flex items-center justify-center">
                                                    <span class="text-gray-500 text-4xl">🐾</span>
                                                </div>
                                            @endif
                                            <h4 class="font-medium text-gray-900 mb-2">{{ $pet->name }}</h4>
                                            <p class="text-sm text-gray-600">{{ $pet->breed ?? 'Mixed breed' }}</p>
                                            @if($pet->age)
                                                <p class="text-sm text-gray-600">Age: {{ $pet->age }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No pets available for adoption at the moment.</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Book Service Button -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 text-center">
                    <a href="{{ route('booking.create', $profile) }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-150 ease-in-out">
                        Book Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>