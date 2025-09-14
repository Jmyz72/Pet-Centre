@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Bookings</h1>
    </div>

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 mb-8">
        <nav class="-mb-px flex space-x-8">
            <button onclick="showTab('adoption')" id="adoption-tab" class="tab-button active-tab py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors">
                <i class="fas fa-heart mr-2"></i>
                Adoptions
                <span class="adoption-count ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">0</span>
            </button>
            <button onclick="showTab('service')" id="service-tab" class="tab-button py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors">
                <i class="fas fa-stethoscope mr-2"></i>
                Services
                <span class="service-count ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">0</span>
            </button>
            <button onclick="showTab('package')" id="package-tab" class="tab-button py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors">
                <i class="fas fa-gift mr-2"></i>
                Packages
                <span class="package-count ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">0</span>
            </button>
        </nav>
    </div>

    <!-- Adoption Tab Content -->
    <div id="adoption-content" class="tab-content">
        <div class="bg-gradient-to-r from-pink-50 to-purple-50 rounded-xl p-6 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-heart text-3xl text-pink-600"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Pet Adoptions</h3>
                    <p class="text-gray-600">Your journey to welcoming new family members</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Shelter</th>
                        <th class="px-6 py-4">Pet</th>
                        <th class="px-6 py-4">Adoption Date</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Adoption Fee</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="adoption-bookings divide-y divide-gray-100 text-sm">
                    <!-- Adoption bookings will be populated here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Service Tab Content -->
    <div id="service-content" class="tab-content hidden">
        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-6 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-stethoscope text-3xl text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Veterinary Services</h3>
                    <p class="text-gray-600">Healthcare appointments for your beloved pets</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Clinic</th>
                        <th class="px-6 py-4">Service</th>
                        <th class="px-6 py-4">Pet</th>
                        <th class="px-6 py-4">Appointment</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="service-bookings divide-y divide-gray-100 text-sm">
                    <!-- Service bookings will be populated here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Package Tab Content -->
    <div id="package-content" class="tab-content hidden">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-gift text-3xl text-green-600"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Care Packages</h3>
                    <p class="text-gray-600">Comprehensive grooming and wellness packages</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Provider</th>
                        <th class="px-6 py-4">Package</th>
                        <th class="px-6 py-4">Pet</th>
                        <th class="px-6 py-4">Scheduled</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="package-bookings divide-y divide-gray-100 text-sm">
                    <!-- Package bookings will be populated here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Empty State (will be shown if no bookings in current tab) -->
    <div id="empty-state" class="hidden text-center py-12">
        <div class="max-w-md mx-auto">
            <div class="empty-icon text-8xl mb-6">🐾</div>
            <h3 class="empty-title text-xl font-semibold text-gray-900 mb-2">No Bookings Yet</h3>
            <p class="empty-description text-gray-600 mb-6">You haven't made any bookings in this category yet.</p>
            <a href="/" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-lg font-semibold hover:from-purple-600 hover:to-pink-700 transition duration-300">
                <i class="fas fa-plus mr-2"></i> Browse Services
            </a>
        </div>
    </div>
</div>

<style>
.tab-button {
    @apply text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300;
}

.active-tab {
    @apply text-purple-600 border-purple-500;
}

.tab-content {
    @apply transition-all duration-300;
}
</style>

<script>
// Booking data from Laravel
const bookings = @json($bookings->items());

// Separate bookings by type
const adoptionBookings = bookings.filter(b => b.booking_type === 'adoption');
const serviceBookings = bookings.filter(b => b.booking_type === 'service');
const packageBookings = bookings.filter(b => b.booking_type === 'package');

// Update counts
document.querySelector('.adoption-count').textContent = adoptionBookings.length;
document.querySelector('.service-count').textContent = serviceBookings.length;
document.querySelector('.package-count').textContent = packageBookings.length;

// Function to show specific tab
function showTab(tabType) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active-tab');
        button.classList.add('text-gray-500', 'border-transparent');
        button.classList.remove('text-purple-600', 'border-purple-500');
    });
    
    // Show selected tab content
    document.getElementById(tabType + '-content').classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById(tabType + '-tab');
    activeTab.classList.add('active-tab');
    activeTab.classList.remove('text-gray-500', 'border-transparent');
    activeTab.classList.add('text-purple-600', 'border-purple-500');
    
    // Populate the table based on tab type
    populateTable(tabType);
}

// Function to populate table based on booking type
function populateTable(type) {
    let bookingsData, tbody;
    
    switch(type) {
        case 'adoption':
            bookingsData = adoptionBookings;
            tbody = document.querySelector('.adoption-bookings');
            break;
        case 'service':
            bookingsData = serviceBookings;
            tbody = document.querySelector('.service-bookings');
            break;
        case 'package':
            bookingsData = packageBookings;
            tbody = document.querySelector('.package-bookings');
            break;
    }
    
    // Clear existing content
    tbody.innerHTML = '';
    
    if (bookingsData.length === 0) {
        // Show empty state
        showEmptyState(type);
        return;
    }
    
    // Hide empty state
    document.getElementById('empty-state').classList.add('hidden');
    
    // Populate table
    bookingsData.forEach(booking => {
        const row = createBookingRow(booking, type);
        tbody.appendChild(row);
    });
}

// Function to create booking row
function createBookingRow(booking, type) {
    const row = document.createElement('tr');
    row.className = 'cursor-pointer hover:bg-gray-50 transition-colors';
    row.onclick = () => window.location = `/bookings/${booking.id}`;
    
    const statusClass = getStatusClass(booking.status);
    const startDate = new Date(booking.start_at).toLocaleString('en-US', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    });
    
    if (type === 'adoption') {
        row.innerHTML = `
            <td class="px-6 py-4 font-medium">${booking.id}</td>
            <td class="px-6 py-4">${booking.merchant?.name || '—'}</td>
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center mr-3">
                        <i class="fas fa-heart text-pink-600"></i>
                    </div>
                    <div>
                        <div class="font-medium">${booking.merchant_pet?.name || '—'}</div>
                        <div class="text-xs text-gray-500">${booking.merchant_pet?.type?.name || ''}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">${startDate}</td>
            <td class="px-6 py-4">
                <span class="${statusClass}">${booking.status ? booking.status.charAt(0).toUpperCase() + booking.status.slice(1) : 'Unknown'}</span>
            </td>
            <td class="px-6 py-4 font-semibold">RM ${parseFloat(booking.price_amount || 0).toFixed(2)}</td>
            <td class="px-6 py-4">
                <button onclick="event.stopPropagation(); window.location='/bookings/${booking.id}'" class="text-pink-600 hover:text-pink-800 font-medium">
                    View Details
                </button>
            </td>
        `;
    } else if (type === 'service') {
        row.innerHTML = `
            <td class="px-6 py-4 font-medium">${booking.id}</td>
            <td class="px-6 py-4">${booking.merchant?.name || '—'}</td>
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                        <i class="fas fa-stethoscope text-blue-600"></i>
                    </div>
                    <div>
                        <div class="font-medium">${booking.service?.title || 'Service'}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <div>
                    <div class="font-medium">${booking.customer_pet?.name || '—'}</div>
                    <div class="text-xs text-gray-500">${booking.customer_pet?.type?.name || ''}</div>
                </div>
            </td>
            <td class="px-6 py-4">${startDate}</td>
            <td class="px-6 py-4">
                <span class="${statusClass}">${booking.status ? booking.status.charAt(0).toUpperCase() + booking.status.slice(1) : 'Unknown'}</span>
            </td>
            <td class="px-6 py-4 font-semibold">RM ${parseFloat(booking.price_amount || 0).toFixed(2)}</td>
            <td class="px-6 py-4">
                <button onclick="event.stopPropagation(); window.location='/bookings/${booking.id}'" class="text-blue-600 hover:text-blue-800 font-medium">
                    View Details
                </button>
            </td>
        `;
    } else if (type === 'package') {
        row.innerHTML = `
            <td class="px-6 py-4 font-medium">${booking.id}</td>
            <td class="px-6 py-4">${booking.merchant?.name || '—'}</td>
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                        <i class="fas fa-gift text-green-600"></i>
                    </div>
                    <div>
                        <div class="font-medium">${booking.package?.name || 'Package'}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <div>
                    <div class="font-medium">${booking.customer_pet?.name || '—'}</div>
                    <div class="text-xs text-gray-500">${booking.customer_pet?.type?.name || ''}</div>
                </div>
            </td>
            <td class="px-6 py-4">${startDate}</td>
            <td class="px-6 py-4">
                <span class="${statusClass}">${booking.status ? booking.status.charAt(0).toUpperCase() + booking.status.slice(1) : 'Unknown'}</span>
            </td>
            <td class="px-6 py-4 font-semibold">RM ${parseFloat(booking.price_amount || 0).toFixed(2)}</td>
            <td class="px-6 py-4">
                <button onclick="event.stopPropagation(); window.location='/bookings/${booking.id}'" class="text-green-600 hover:text-green-800 font-medium">
                    View Details
                </button>
            </td>
        `;
    }
    
    return row;
}

// Function to get status styling classes
function getStatusClass(status) {
    const baseClasses = 'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium ring-1';
    
    switch(status) {
        case 'confirmed':
            return `${baseClasses} bg-green-100 text-green-800 ring-green-200`;
        case 'pending':
            return `${baseClasses} bg-yellow-100 text-yellow-800 ring-yellow-200`;
        case 'cancelled':
            return `${baseClasses} bg-red-100 text-red-800 ring-red-200`;
        default:
            return `${baseClasses} bg-gray-100 text-gray-800 ring-gray-200`;
    }
}

// Function to show empty state
function showEmptyState(type) {
    const emptyState = document.getElementById('empty-state');
    const emptyIcon = document.querySelector('.empty-icon');
    const emptyTitle = document.querySelector('.empty-title');
    const emptyDescription = document.querySelector('.empty-description');
    
    switch(type) {
        case 'adoption':
            emptyIcon.textContent = '🏠';
            emptyTitle.textContent = 'No Adoptions Yet';
            emptyDescription.textContent = 'You haven\'t adopted any pets yet. Find your perfect companion!';
            break;
        case 'service':
            emptyIcon.textContent = '🏥';
            emptyTitle.textContent = 'No Service Bookings';
            emptyDescription.textContent = 'You haven\'t booked any veterinary services yet. Keep your pets healthy!';
            break;
        case 'package':
            emptyIcon.textContent = '📦';
            emptyTitle.textContent = 'No Package Bookings';
            emptyDescription.textContent = 'You haven\'t booked any care packages yet. Treat your pets to premium services!';
            break;
    }
    
    emptyState.classList.remove('hidden');
}

// Initialize with adoption tab
document.addEventListener('DOMContentLoaded', function() {
    showTab('adoption');
});
</script>
@endsection