<x-filament-panels::page>
    {{-- Weekly Timetable (read-only summary) --}}
    <div class="mb-6">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:bg-gray-900 dark:border-gray-700">
            @php
                $days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                $byDay = collect();
                if (!empty($staff)) {
                    $byDay = $staff->operatingHours()
                        ->orderBy('day_of_week')
                        ->orderByRaw('CASE WHEN start_time IS NULL THEN 1 ELSE 0 END')
                        ->orderBy('start_time')
                        ->get()
                        ->groupBy('day_of_week');
                }
            @endphp

            <div class="text-base font-semibold mb-4">Weekly Timetable</div>
            @if (empty($staff))
                <div class="text-sm text-gray-500 dark:text-gray-400 mb-3">Please select a staff above to view their timetable.</div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-sm border border-gray-200 rounded-md dark:border-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="p-2 text-left w-24">Day</th>
                        <th class="p-2 text-left">Blocks</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900">
                    @foreach(range(0,6) as $d)
                        @php $rows = $byDay->get($d, collect()); @endphp
                        <tr class="border-t border-gray-200 dark:border-gray-700">
                            <td class="p-2 font-medium">{{ $days[$d] }}</td>
                            <td class="p-2">
                                @if($rows->count() === 0)
                                    <span class="text-gray-400 dark:text-gray-500">Not set</span>
                                @elseif($rows->first()->block_type === 'closed' && $rows->count() === 1 && $rows->first()->start_time === null)
                                    <span class="inline-block px-2 py-1 border rounded bg-white border-gray-300 dark:bg-gray-800 dark:border-gray-600">Closed</span>
                                @else
                                    @foreach($rows as $r)
                                        <span class="inline-block px-2 py-1 mr-1 mb-1 border rounded bg-white border-gray-300 dark:bg-gray-800 dark:border-gray-600">
                                            {{ $r->block_type === 'break' ? 'Break' : 'Open' }}
                                            @if($r->start_time && $r->end_time) {{ substr($r->start_time,0,5) }}–{{ substr($r->end_time,0,5) }} @endif
                                            @if($r->label) <em class="text-gray-500">({{ $r->label }})</em> @endif
                                        </span>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{ $this->form }}
</x-filament-panels::page>
