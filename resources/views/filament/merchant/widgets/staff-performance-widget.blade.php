<x-filament-widgets::widget class="theme-transition">
    <x-filament::section>
        <x-slot name="heading">
            Staff Performance
        </x-slot>

        <div class="space-y-4 max-h-96 overflow-y-auto">
            @forelse($this->getStaffData() as $staff)
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-100 dark:border-gray-700/50 theme-transition hover:bg-gray-100 dark:hover:bg-gray-800">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-blue-600 dark:text-blue-400 font-semibold text-sm">
                                    {{ substr($staff['name'], 0, 1) }}{{ substr(explode(' ', $staff['name'])[1] ?? '', 0, 1) }}
                                </span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="font-medium text-gray-900 dark:text-gray-100 truncate">{{ $staff['name'] }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $staff['role'] ?? 'Staff' }}</p>
                                @if($staff['email'])
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $staff['email'] }}</p>
                                @endif
                                @if(isset($staff['bookings_this_month']))
                                    <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">
                                        <span class="inline-flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $staff['bookings_this_month'] }} bookings this month
                                        </span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0 ml-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $staff['status'] === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300' }}">
                            <svg class="w-2 h-2 mr-1 fill-current" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            {{ ucfirst($staff['status'] ?? 'active') }}
                        </span>
                        @if($staff['phone'])
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $staff['phone'] }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">No staff data available</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Add staff members to see their performance</p>
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>