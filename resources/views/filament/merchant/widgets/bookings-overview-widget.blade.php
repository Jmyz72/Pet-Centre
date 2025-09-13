<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Bookings Overview
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold">📅</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Today's Bookings</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $this->getTodayBookingsCount() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold">⏳</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Pending Bookings</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $this->getPendingBookingsCount() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h4 class="font-medium text-gray-900 mb-3">Upcoming Bookings</h4>
            <div class="space-y-3">
                @forelse($this->getBookingsData() as $booking)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <h5 class="font-medium text-gray-900">{{ $booking['customer_name'] }}</h5>
                            <p class="text-sm text-gray-600">
                                {{ $booking['service_name'] ?? $booking['package_name'] ?? 'Adoption' }}
                            </p>
                            @if($booking['customer_pet_name'] ?? $booking['merchant_pet_name'])
                                <p class="text-xs text-gray-500">Pet: {{ $booking['customer_pet_name'] ?? $booking['merchant_pet_name'] }}</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900">RM {{ number_format($booking['price_amount'], 2) }}</p>
                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($booking['start_at'])->format('M d, H:i') }}</p>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $booking['status'] === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($booking['status']) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6">
                        <p class="text-gray-500">No upcoming bookings</p>
                    </div>
                @endforelse
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>