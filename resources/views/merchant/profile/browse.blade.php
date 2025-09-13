<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Find Pet Services') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('merchants.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                       placeholder="Search by name or location..."
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700">Service Type</label>
                                <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Services</option>
                                    <option value="clinic" {{ request('role') === 'clinic' ? 'selected' : '' }}>Veterinary Clinic</option>
                                    <option value="groomer" {{ request('role') === 'groomer' ? 'selected' : '' }}>Pet Grooming</option>
                                    <option value="shelter" {{ request('role') === 'shelter' ? 'selected' : '' }}>Pet Adoption</option>
                                </select>
                            </div>
                            
                            <div class="flex items-end">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($profiles->count() > 0)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600">
                                Showing {{ $profiles->firstItem() }} to {{ $profiles->lastItem() }} of {{ $profiles->total() }} results
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($profiles as $profile)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                                {{ $profile->name }}
                                            </h3>
                                            
                                            <div class="mb-3">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ $profile->role === 'clinic' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $profile->role === 'groomer' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $profile->role === 'shelter' ? 'bg-purple-100 text-purple-800' : '' }}">
                                                    {{ ucfirst($profile->role) }}
                                                </span>
                                            </div>
                                            
                                            @if($profile->address)
                                                <p class="text-sm text-gray-600 mb-2">
                                                    📍 {{ Str::limit($profile->address, 40) }}
                                                </p>
                                            @endif
                                            
                                            @if($profile->phone)
                                                <p class="text-sm text-gray-600 mb-3">
                                                    📞 {{ $profile->phone }}
                                                </p>
                                            @endif
                                            
                                            <!-- Stats (if available) -->
                                            <div class="flex space-x-4 text-xs text-gray-500 mb-4">
                                                @if(isset($profile->services_count))
                                                    <span>{{ $profile->services_count }} Services</span>
                                                @endif
                                                @if(isset($profile->packages_count))
                                                    <span>{{ $profile->packages_count }} Packages</span>
                                                @endif
                                                @if(isset($profile->pets_count))
                                                    <span>{{ $profile->pets_count }} Pets</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <a href="{{ route('merchants.show', $profile) }}" 
                                           class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out inline-flex items-center justify-center">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $profiles->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mx-auto h-12 w-12 text-gray-400">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.87 0-5.43 1.5-6.84 3.891L3 21l18-18-3.159-3.159z" />
                                </svg>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No merchants found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>