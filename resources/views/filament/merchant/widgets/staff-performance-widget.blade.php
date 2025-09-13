<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Staff Performance
        </x-slot>

        <div class="space-y-4">
            @forelse($this->getStaffData() as $staff)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <h4 class="font-medium text-gray-900">{{ $staff['name'] }}</h4>
                        <p class="text-sm text-gray-600">{{ $staff['specialization'] ?? 'General' }}</p>
                        @if(isset($staff['bookings_this_month']))
                            <p class="text-xs text-gray-500">{{ $staff['bookings_this_month'] }} bookings this month</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-6">
                    <p class="text-gray-500">No staff data available</p>
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>