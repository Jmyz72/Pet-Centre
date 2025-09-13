@php
    /**
     * Operating Hours partial
     * Top: compact weekly summary (unchanged)
     * Details: timetable grid (replaces old "detailed blocks" table)
     */

    $dayNames = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
    $hours = optional($profile->operatingHours) ?? collect();

    // ---------- COMPACT WEEKLY SUMMARY (same behavior as before) ----------
    $byDay = collect(range(0,6))->mapWithKeys(function ($d) use ($hours) {
        $rows = $hours->where('day_of_week', $d)->sortBy('block_index') ?? collect();

        $hasOpen  = $rows->contains(fn($r) => $r->block_type === 'open' && $r->start_time && $r->end_time);
        $hasClosedOnly = !$hasOpen && $rows->isNotEmpty();

        if ($rows->isEmpty() || $hasClosedOnly) {
            return [$d => ['display' => 'Closed', 'segments' => [], 'isClosed' => true]];
        }

        $segments = [];
        foreach ($rows as $r) {
            if ($r->block_type !== 'open' || !$r->start_time || !$r->end_time) continue;
            $segments[] = [substr($r->start_time,0,5), substr($r->end_time,0,5)];
        }

        // merge contiguous segments
        $merged = [];
        foreach ($segments as $seg) {
            if (empty($merged)) { $merged[] = $seg; continue; }
            $last = &$merged[count($merged)-1];
            if ($last[1] === $seg[0]) {
                $last[1] = $seg[1];
            } else {
                $merged[] = $seg;
            }
        }

        $display = collect($merged)->map(fn($s) => "{$s[0]}–{$s[1]}")->implode(', ');
        return [$d => ['display' => $display ?: 'Closed', 'segments' => $merged, 'isClosed' => empty($merged)]];
    });

    // ---------- TIMETABLE (replaces old details section) ----------
    // Colors
    $color = [
        'open'   => 'bg-emerald-200 text-emerald-900',
        'break'  => 'bg-amber-200 text-amber-900',
        'closed' => 'bg-rose-200 text-rose-900',
    ];

    // derive min/max from data (consider open+break); default 09:00–18:00
    $spanRows = $hours->filter(fn($r) => in_array($r->block_type, ['open','break']) && $r->start_time && $r->end_time);
    $minStart = substr($spanRows->min('start_time') ?? '09:00:00', 0, 5);
    $maxEnd   = substr($spanRows->max('end_time')   ?? '18:00:00', 0, 5);

    $step = 30; // minutes
    $slotsPerHour = intdiv(60, $step); // used for bold hour grid lines
    function buildSlots($start, $end, $step) {
        [$sh, $sm] = array_map('intval', explode(':', $start));
        [$eh, $em] = array_map('intval', explode(':', $end));
        $slots = [];
        $cur = $sh * 60 + $sm;
        $endm = $eh * 60 + $em;
        while ($cur < $endm) {
            $h = floor($cur / 60);
            $m = $cur % 60;
            $slots[] = sprintf('%02d:%02d', $h, $m);
            $cur += $step;
        }
        return $slots;
    }
    $slotLabels = buildSlots($minStart, $maxEnd, $step);
    // For header ticks, also show the final end time (e.g., 18:00) for clarity
    $headerLabels = $slotLabels;
    $headerLabels[] = $maxEnd;

    // Prepend a 30-min block before the first slot to align the first tick on a grid border
    // Compute label for (minStart - 30 mins)
    [$h0, $m0] = array_map('intval', explode(':', $minStart));
    $mins = $h0 * 60 + $m0 - $step;
    if ($mins < 0) { $mins += 24 * 60; } // wrap just in case
    $leadingLabel = sprintf('%02d:%02d', intdiv($mins,60), $mins % 60);
    array_unshift($headerLabels, $leadingLabel);

    // Build per-day slot types
    $perDay = [];
    foreach (range(0,6) as $d) {
        $perDay[$d] = array_fill(0, count($slotLabels), 'closed'); // default closed
        $rows = $hours->where('day_of_week', $d)->sortBy('block_index')->values();
        foreach ($rows as $r) {
            if (!in_array($r->block_type, ['open','break']) || empty($r->start_time) || empty($r->end_time)) {
                continue;
            }
            $start = substr($r->start_time, 0, 5);
            $end   = substr($r->end_time,   0, 5);

            // find first slot >= start
            $startIdx = 0;
            foreach ($slotLabels as $i => $lbl) { if ($lbl >= $start) { $startIdx = $i; break; } }
            // first slot >= end (exclusive upper bound)
            $endIdx = count($slotLabels);
            foreach ($slotLabels as $i => $lbl) { if ($lbl >= $end) { $endIdx = $i; break; } }

            for ($i = $startIdx; $i < $endIdx; $i++) {
                $perDay[$d][$i] = $r->block_type;
            }
        }
    }
@endphp

<div class="mt-6 rounded-2xl border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-200 px-6 py-4">
        <h3 class="text-base font-semibold text-gray-900">Operating Hours</h3>
    </div>

    {{-- Compact weekly table (kept) --}}
    <div class="px-6 py-5">
        <div class="hidden md:block">
            <div class="grid grid-cols-7 gap-3">
                @foreach($byDay as $i => $info)
                    <div class="rounded-lg px-3 py-3 {{ $info['isClosed'] ? 'bg-gray-50 text-gray-400' : 'bg-emerald-50 text-emerald-900' }}">
                        <div class="text-xs font-semibold uppercase tracking-wide">{{ $dayNames[$i] }}</div>
                        <div class="mt-1 text-sm">{{ $info['display'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Mobile list (kept) --}}
        <div class="md:hidden space-y-2">
            @foreach($byDay as $i => $info)
                <div class="flex items-center justify-between rounded-lg border px-3 py-2 {{ $info['isClosed'] ? 'border-gray-200 bg-gray-50' : 'border-emerald-200 bg-emerald-50' }}">
                    <div class="text-sm font-medium">{{ $dayNames[$i] }}</div>
                    <div class="text-sm {{ $info['isClosed'] ? 'text-gray-500' : 'text-emerald-900' }}">{{ $info['display'] }}</div>
                </div>
            @endforeach
        </div>

        {{-- NEW: "Show detailed timetable" replaces old details table --}}
        @php $hasAny = $hours->isNotEmpty(); @endphp
        @if($hasAny)
        <details class="mt-5 group">
            <summary id="hours-summary" class="cursor-pointer select-none rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 flex items-center gap-2" aria-controls="hours-timetable">
                <svg class="h-4 w-4 text-gray-500 transition-transform group-open:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
                Show detailed timetable
            </summary>
            <div id="hours-timetable" class="mt-4 overflow-x-auto rounded-lg border">
                <table class="min-w-full border-separate border-spacing-0 text-xs md:text-sm">
                    <thead>
                        <tr>
                            <th class="sticky left-0 z-10 bg-white px-3 py-2 text-left font-semibold text-gray-700 border-b">Day</th>
                            @foreach($headerLabels as $lbl)
                                @php $isHour = substr($lbl, -2) === '00'; @endphp
                                <th class="px-0 py-2 text-left text-[11px] {{ $isHour ? 'font-semibold text-gray-700' : 'font-medium text-gray-600' }} border-b">{{ $lbl }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($perDay as $d => $types)
                            <tr>
                                <th class="sticky left-0 z-10 px-3 py-2 text-left font-medium border-b
                                    {{ in_array('open',$types) || in_array('break',$types) ? 'bg-white text-gray-700' : 'bg-rose-200 text-rose-900' }}">
                                    {{ $dayNames[$d] }}
                                </th>
                                {{-- leading 30-min block to align first tick --}}
                                <td class="border-b border-l border-gray-300 bg-white px-0 py-3"></td>
                                @foreach($types as $i => $type)
                                    <td class="border-b border-l {{ ($i % $slotsPerHour) === 0 ? 'border-l-2' : '' }} border-gray-300 text-center px-1 py-3
                                        {{ $type === 'open' ? $color['open'] : ($type === 'break' ? $color['break'] : 'bg-white') }}">
                                        <span class="sr-only">{{ ucfirst($type) }}</span>
                                    </td>
                                @endforeach
                                {{-- filler cell to align with final end-time tick in header --}}
                                <td class="border-b border-l border-gray-300 bg-white px-0 py-3"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-3 text-xs text-gray-600">
                    <span class="inline-flex items-center gap-2 mr-4"><span class="h-3 w-3 rounded {{ $color['open'] }}"></span> Open</span>
                    <span class="inline-flex items-center gap-2 mr-4"><span class="h-3 w-3 rounded {{ $color['break'] }}"></span> Break</span>
                    <span class="inline-flex items-center gap-2 mr-4"><span class="h-3 w-3 rounded bg-white border border-gray-300"></span> Closed time (empty)</span>
                    <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded {{ $color['closed'] }}"></span> Closed day label</span>
                </div>
            </div>
        </details>
        @endif
    </div>
</div>