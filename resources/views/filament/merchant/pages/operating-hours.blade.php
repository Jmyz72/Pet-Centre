<x-filament-panels::page>
    {{-- Weekly Timetable (read-only summary) --}}
    <div class="mb-6">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            @php
                $days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                $byDay = $profile->operatingHours()
                    ->orderBy('day_of_week')
                    ->orderByRaw('CASE WHEN start_time IS NULL THEN 1 ELSE 0 END')
                    ->orderBy('start_time')
                    ->get()
                    ->groupBy('day_of_week');
            @endphp

            <div class="text-base font-semibold mb-4">Weekly Timetable</div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm border border-gray-200 rounded-md">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="p-2 text-left w-24">Day</th>
                        <th class="p-2 text-left">Blocks</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white">
                    @foreach(range(0,6) as $d)
                        @php $rows = $byDay->get($d, collect()); @endphp
                        <tr class="border-t">
                            <td class="p-2 font-medium">{{ $days[$d] }}</td>
                            <td class="p-2">
                                @if($rows->count() === 0)
                                    <span class="text-gray-400">Not set</span>
                                @elseif($rows->first()->block_type === 'closed' && $rows->count() === 1 && $rows->first()->start_time === null)
                                    <span class="inline-block px-2 py-1 border rounded">Closed</span>
                                @else
                                    @foreach($rows as $r)
                                        <span class="inline-block px-2 py-1 mr-1 mb-1 border rounded">
                                            {{ $r->block_type === 'break' ? 'Break' : 'Open' }}
                                            {{ substr($r->start_time,0,5) }}–{{ substr($r->end_time,0,5) }}
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
