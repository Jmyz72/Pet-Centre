<x-filament-widgets::widget class="theme-transition">
    <x-filament::section>
        <x-slot name="heading">
            Bookings Overview
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-100 dark:border-blue-800/30 theme-transition">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-500 dark:bg-blue-600 rounded-full flex items-center justify-center shadow-sm">
                            <span class="text-white font-bold text-lg">📅</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Today's Bookings</p>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $this->getTodayBookingsCount() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border border-yellow-100 dark:border-yellow-800/30 theme-transition">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-yellow-500 dark:bg-yellow-600 rounded-full flex items-center justify-center shadow-sm">
                            <span class="text-white font-bold text-lg">⏳</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Pending Bookings</p>
                        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $this->getPendingBookingsCount() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-medium text-gray-900 dark:text-gray-100">Upcoming Bookings</h4>
                <span class="text-xs text-gray-500 dark:text-gray-400">Latest first</span>
            </div>
            <div class="space-y-3 max-h-80 overflow-y-auto">
                @forelse($this->getBookingsData() as $booking)
                    <div class="flex items-start justify-between p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-100 dark:border-gray-700/50 theme-transition hover:bg-gray-100 dark:hover:bg-gray-800">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900/30 dark:to-purple-800/30 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <span class="text-purple-600 dark:text-purple-400 font-semibold text-xs">
                                        {{ substr($booking['customer_name'], 0, 1) }}
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h5 class="font-medium text-gray-900 dark:text-gray-100 truncate">{{ $booking['customer_name'] }}</h5>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 truncate">
                                        {{ $booking['service_name'] ?? $booking['package_name'] ?? 'Adoption' }}
                                    </p>
                                    @if($booking['customer_pet_name'] ?? $booking['merchant_pet_name'])
                                        <div class="flex items-center mt-1">
                                            <svg class="w-3 h-3 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-6H8v6a1 1 0 01-1 1H4a1 1 0 110-2V4z"/>
                                            </svg>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $booking['customer_pet_name'] ?? $booking['merchant_pet_name'] }}</p>
                                        </div>
                                    @endif
                                    @if(isset($booking['staff_name']) && $booking['staff_name'])
                                        <div class="flex items-center mt-1">
                                            <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $booking['staff_name'] }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 ml-4">
                            <p class="font-medium text-gray-900 dark:text-gray-100">RM {{ number_format($booking['price_amount'], 2) }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($booking['start_at'])->format('M d, H:i') }}</p>
                            <span class="inline-flex items-center px-2 py-1 mt-1 text-xs font-semibold rounded-full
                                {{ $booking['status'] === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' :
                                   ($booking['status'] === 'completed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' :
                                   'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300') }}">
                                <svg class="w-2 h-2 mr-1 fill-current" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                {{ ucfirst($booking['status']) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10m6-10v10m-9-7h.01M19 10h.01M12 19h.01" />
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">No upcoming bookings</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">New bookings will appear here</p>
                    </div>
                @endforelse
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>