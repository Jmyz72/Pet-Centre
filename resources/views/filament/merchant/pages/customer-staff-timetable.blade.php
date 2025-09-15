<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Week Navigation --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">
                    Week of {{ $this->weekDisplay }}
                </h2>
                
                <div class="flex items-center space-x-2">
                    <button 
                        wire:click="previousWeek"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Previous
                    </button>
                    
                    <button 
                        wire:click="currentWeek"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        This Week
                    </button>
                    
                    <button 
                        wire:click="nextWeek"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Next
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            {{-- Week dates display --}}
            <div class="mt-4 grid grid-cols-7 gap-2 text-center">
                @php
                    $weekStart = \Carbon\Carbon::parse($this->weekStart);
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                @endphp
                
                @foreach($days as $index => $day)
                    @php
                        $date = $weekStart->copy()->addDays($index);
                        $isToday = $date->isToday();
                    @endphp
                    <div class="p-2 rounded {{ $isToday ? 'bg-blue-100 text-blue-800' : 'text-gray-600' }}">
                        <div class="font-medium text-xs">{{ $day }}</div>
                        <div class="text-sm {{ $isToday ? 'font-bold' : '' }}">{{ $date->format('j') }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Legend --}}
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-sm font-medium text-gray-900 mb-3">Legend</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded mr-2"></div>
                    <span class="text-green-600">Available</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-yellow-500 rounded mr-2"></div>
                    <span class="text-yellow-600">Break</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-500 rounded mr-2"></div>
                    <span class="text-blue-600">🔒 Booked</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-red-500 rounded mr-2"></div>
                    <span class="text-red-600">Unavailable</span>
                </div>
            </div>
        </div>

        {{-- Staff Timetable Table --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            {{ $this->table }}
        </div>

        {{-- Help Text --}}
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Staff Availability Information</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>This timetable shows your staff's availability for the selected week. Times shown are based on each staff member's configured operating hours and existing bookings.</p>
                        <ul class="mt-2 list-disc list-inside space-y-1">
                            <li>Green times indicate when staff are available for bookings</li>
                            <li>Yellow times show scheduled breaks</li>
                            <li>Blue 🔒 times are already booked by customers</li>
                            <li>Red times indicate unavailable periods</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
