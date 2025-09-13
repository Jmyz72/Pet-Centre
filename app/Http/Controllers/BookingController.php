<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Booking, BookingHold, MerchantProfile, CustomerPet, Staff, Service, Package, Pet, Schedule};
use App\Models\PackageVariation;
use App\Models\OperatingHour;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Bookings\BookingFactory;

class BookingController extends Controller
{
    /**
     * Base URL for internal API server (e.g., http://127.0.0.1:8001/api).
     */
    private function apiBase(): string
    {
        return rtrim((string) config('services.internal_api.base', env('API_BASE_URL', 'http://127.0.0.1:8001/api')), '/');
    }

    public function create(Request $request)
    {
        $merchants = MerchantProfile::all();
        $api = $this->apiBase();
        $petsResp = Http::acceptJson()
            ->timeout(5)
            ->connectTimeout(2)
            ->get("{$api}/customers/" . auth()->id() . "/pets", ['limit' => 100]);

        // Expect the API to already return human‑readable names/labels.
        // We only normalize the keys and build a photo_url if only photo_path is present.
        $pets = collect($petsResp->json('data') ?? [])->map(function ($p) {
            // Normalize name/label keys coming from the API
            $p['type_name']  = $p['type_name']  ?? $p['pet_type_name'] ?? null;
            $p['size_name']  = $p['size_label'] ?? $p['size_name']     ?? null; // API might send label or name
            $p['breed_name'] = $p['breed_name'] ?? null;

            if ($p['type_name'] === null && !empty($p['pet_type_id'])) {
                $p['type_name'] = DB::table('pet_types')->where('id', $p['pet_type_id'])->value('name');
            }
            if ($p['size_name'] === null && !empty($p['size_id'])) {
                $p['size_name'] = DB::table('sizes')->where('id', $p['size_id'])->value('label');
            }
            if ($p['breed_name'] === null && (!empty($p['pet_breed_id']) || !empty($p['breed_id']))) {
                $breedId = $p['pet_breed_id'] ?? $p['breed_id'];
                $p['breed_name'] = DB::table('pet_breeds')->where('id', $breedId)->value('name');
            }

            if (!empty($p['photo_url'])) {
                // keep as is
            } elseif (!empty($p['photo_path'])) {
                $p['photo_url'] = asset('storage/' . ltrim($p['photo_path'], '/'));
            }
            return $p;
        });

        // --- Resolve context based on query params ---
        $merchant = $request->filled('merchant_id') ? MerchantProfile::find($request->integer('merchant_id')) : null;
        $service  = $request->filled('service_id')  ? Service::find($request->integer('service_id'))   : null;
        $package  = $request->filled('package_id')  ? Package::find($request->integer('package_id'))   : null;
        $pet      = $request->filled('pet_id')      ? Pet::find($request->integer('pet_id'))           : null; // shelter pet

        // Determine booking type from context (user must not change it in the form)
        $bookingType = 'service';
        if ($package) { $bookingType = 'package'; }
        if ($pet)     { $bookingType = 'adoption'; }

        // Eligible staff limited by merchant + capability pivots
        $eligibleStaff = collect();
        if ($merchant && ($service || $package)) {
            $itemType = $service ? 'service' : 'package';
            $itemId   = $service ? $service->id : $package->id;

            // Optionally include time filter if the page pre-selects a start_at
            $startAt = $request->query('start_at');
            $minutes = null;
            if ($startAt) {
                $minutes = $service ? ($service->duration_minutes ?? 60)
                                    : ($package->duration_minutes ?? 60);
            }

            $eligibleStaff = collect(optional(
                Http::acceptJson()
                    ->timeout(5)
                    ->connectTimeout(2)
                    ->get("{$api}/merchants/{$merchant->id}/eligible-staff", array_filter([
                        'item'     => $itemType,
                        'item_id'  => (int) $itemId,
                        'start_at' => $startAt,
                        'minutes'  => $minutes,
                    ], fn($v) => !is_null($v)))
                    ->json()
            )['data'] ?? []);
        }

        // Prefill values from query params (merchant_id, service_id, package_id, pet_id)
        $prefill = [
            'merchant_id' => $request->query('merchant_id'),
            'service_id'  => $request->query('service_id'),
            'package_id'  => $request->query('package_id'),
            'pet_id'      => $request->query('pet_id'),
        ];

        $prefill['booking_type'] = $bookingType;

        $context = [
            'merchant' => $merchant,
            'service'  => $service,
            'package'  => $package,
            'pet'      => $pet,
        ];

        return view('bookings.create', compact('merchants', 'pets', 'prefill', 'context', 'eligibleStaff'));
    }

    public function store(Request $request)
    {
        // Determine booking type from context: user cannot switch types
        $type = $request->input('booking_type');
        if ($request->filled('package_id')) { $type = 'package'; }
        if ($request->filled('pet_id'))     { $type = 'adoption'; }

        // Validation rules by type
        $rulesCommon = [
            'merchant_id' => 'required|exists:merchant_profiles,id',
            'start_at'    => [
                'required','date',
                function($attr,$value,$fail){
                    $start = Carbon::parse($value);
                    if ($start->lt(now()->addMinutes(120))) {
                        $fail('Please book at least 2 hours in advance.');
                    }
                    if ($start->gt(now()->addDays(28)->endOfDay())) {
                        $fail('Bookings cannot be made more than 28 days in advance.');
                    }
                }
            ],
            'payment_method' => 'required|in:fpx,card',
            'bank'           => 'nullable|string',
            'card_name'      => 'nullable|string',
            'card_number'    => 'nullable|string',
            'card_expiry'    => 'nullable|string',
            'card_ccv'       => 'nullable|string',
        ];
        $rulesByType = match ($type) {
            'service' => [
                'service_id'      => 'required|exists:services,id',
                'customer_pet_id' => 'required|exists:customer_pets,id',
                'staff_id'        => 'required|exists:staff,id',
            ],
            'package' => [
                'package_id'      => 'required|exists:packages,id',
                'customer_pet_id' => 'required|exists:customer_pets,id',
                'staff_id'        => 'required|exists:staff,id',
                'pet_type_id'     => 'nullable|integer',
                'size_id'         => 'nullable|integer',
                'breed_id'        => 'nullable|integer',
            ],
            'adoption' => [
                'pet_id' => [
                    'required',
                    'exists:pets,id',
                    function ($attribute, $value, $fail) {
                        $fee = \App\Models\Pet::where('id', $value)->value('adoption_fee');
                        if (is_null($fee) || $fee <= 0) {
                            $fail('Selected pet does not have a valid adoption fee.');
                        }
                    },
                ],
                // no staff for adoption
            ],
            default => abort(422, 'Unknown booking type'),
        };

        // Extra conditional rules when the chosen method is card
        if ($request->input('payment_method') === 'card') {
            $request->validate([
                'card_name'   => 'required|string',
                'card_number' => 'required|string',
                'card_expiry' => 'required|string',
                'card_ccv'    => 'required|string',
            ]);
        }

        $data = $request->validate($rulesCommon + $rulesByType);
        $data['booking_type'] = $type;
        $data['customer_id']  = auth()->id();
        $data['idempotency_key'] = bin2hex(random_bytes(16));

        // ---------- Use Template Method via BookingFactory ----------
        $booking = \App\Bookings\BookingFactory::make($data['booking_type'])->process($data);

        return view('bookings.sucess', compact('booking'));
    }
    /**
     * AJAX: Quote live price for service/package/adoption without writing to DB.
    * Consumed by bookings/create.blade.js via GET /bookings/quote-price.
     */
    public function quotePrice(Request $request)
    {
        $data = $request->validate([
            'type'            => 'required|in:service,package,adoption',
            'service_id'      => 'nullable|exists:services,id',
            'package_id'      => 'nullable|exists:packages,id',
            'customer_pet_id' => 'nullable|exists:customer_pets,id',
            'pet_type_id'     => 'nullable|integer',
            'size_id'         => 'nullable|integer',
            'breed_id'        => 'nullable|integer',
            'pet_id'          => 'nullable|exists:pets,id', // adoption
        ]);
  
        // Derive traits from selected customer pet if present
        $petTypeId = $data['pet_type_id'] ?? null;
        $sizeId    = $data['size_id'] ?? null;
        $breedId   = $data['breed_id'] ?? null;
        if (!empty($data['customer_pet_id'])) {
            $cp = CustomerPet::find($data['customer_pet_id']);
            if ($cp) {
                $petTypeId = $petTypeId ?? $cp->pet_type_id;
                $sizeId    = $sizeId    ?? $cp->size_id;
                $breedId   = $breedId   ?? $cp->pet_breed_id;
            }
        }
  
        $amount = 0.0;
        switch ($data['type']) {
            case 'service':
                if (empty($data['service_id'])) {
                    throw \Illuminate\Validation\ValidationException::withMessages(['service_id' => 'service_id is required']);
                }
                $svc = Service::findOrFail($data['service_id']);
                $amount = (float) ($svc->price ?? 0.0);
                break;
  
            case 'package':
                if (empty($data['package_id'])) {
                    throw \Illuminate\Validation\ValidationException::withMessages(['package_id' => 'package_id is required']);
                }
                $pkg  = Package::findOrFail($data['package_id']);
                $base = (float) ($pkg->price ?? 0.0);

                if (!$petTypeId) {
                    $amount = $base;
                    break;
                }

                // IMPORTANT: match via pivot (package_pet_types.id), not pet_types.id
                $pivotIds = DB::table('package_pet_types')
                    ->where('package_id', $pkg->id)
                    ->where('pet_type_id', $petTypeId)
                    ->pluck('id');

                if ($pivotIds->isEmpty()) {
                    $amount = $base; // no mapping for this pet type → use package base price
                    break;
                }

                $vars = PackageVariation::query()
                    ->where('package_id', $pkg->id)
                    ->whereIn('package_pet_type_id', $pivotIds)
                    ->where('is_active', 1)
                    ->get();

                if ($vars->isEmpty()) {
                    $amount = $base; // no variations for this type → use base
                    break;
                }

                $chosen = null;
                if ($breedId) { $chosen = $vars->firstWhere('package_breed_id', $breedId); }
                if (!$chosen && $sizeId) { $chosen = $vars->firstWhere('package_size_id', $sizeId); }
                if (!$chosen) { $chosen = $vars->first(); }

                $amount = (float) (optional($chosen)->price ?? $base);
                break;
  
            case 'adoption':
                $petId = $data['pet_id'] ?? null;
                if (!$petId) {
                    throw \Illuminate\Validation\ValidationException::withMessages(['pet_id' => 'pet_id is required']);
                }
                $pet = Pet::findOrFail($petId);
                $amount = (float) ($pet->adoption_fee ?? 0.0);
                break;
        }
  
        return response()->json([
            'ok'               => true,
            'amount'           => $amount,
            'amount_formatted' => number_format($amount, 2),
        ]);
    }


    /**
     * Build available time slots from operating_hours (Eloquent) without external API.
     * Staff availability is checked after user clicks a slot (existing /bookings/available-staff).
     */
    public function availableSlots(Request $request)
    {
        $minLeadMinutes = 120; // 2 hours
        $maxAdvanceDays = 28;  // 28 days

        $data = $request->validate([
            'merchant_id' => 'required|exists:merchant_profiles,id',
            'type'        => 'required|in:service,package,adoption',
            'service_id'  => 'nullable|exists:services,id',
            'package_id'  => 'nullable|exists:packages,id',
            'date'        => 'required|date|after_or_equal:today|before_or_equal:' . now()->addDays($maxAdvanceDays)->toDateString(),
        ]);

        // duration based on item type
        $duration = 60;
        if ($data['type'] === 'service' && !empty($data['service_id'])) {
            $duration = (int) (Service::find($data['service_id'])->duration_minutes ?? 60);
        } elseif ($data['type'] === 'package' && !empty($data['package_id'])) {
            $duration = (int) (Package::find($data['package_id'])->duration_minutes ?? 60);
        }
        $step = 30;

        $date = CarbonImmutable::parse($data['date'])->startOfDay();
        $dow  = (int) $date->dayOfWeek; // 0=Sun…6=Sat

        // ---- Occupancy from schedules (store/staff bookings) to gray-out full slots ----
        $dayStart = $date->copy();                 // immutable start of day
        $dayEnd   = $date->copy()->endOfDay();     // immutable end of day

        // Pull all schedules overlapping this date for the merchant
        $schedRows = \App\Models\Schedule::query()
            ->where('merchant_id', $data['merchant_id'])
            ->where('start_at', '<', $dayEnd->toDateTimeString())
            ->where('end_at',   '>', $dayStart->toDateTimeString())
            ->get(['staff_id','start_at','end_at']);

        // Partition into store-level blocks (staff_id NULL) and per-staff blocks
        $storeBlocksMin = [];             // [[sMin,eMin], ...]
        $staffBlocksMin = [];             // [staff_id => [[sMin,eMin], ...]]
        foreach ($schedRows as $row) {
            $sCarbon = CarbonImmutable::parse($row->start_at);
            $eCarbon = CarbonImmutable::parse($row->end_at);

            // Clamp to this day’s [00:00, 24:00] window and convert to minutes since midnight
            $sMin = ($sCarbon->isSameDay($date)) ? ($sCarbon->hour * 60 + $sCarbon->minute) : 0;
            $eMin = ($eCarbon->isSameDay($date)) ? ($eCarbon->hour * 60 + $eCarbon->minute) : 24 * 60;
            if ($eMin <= $sMin) { continue; } // ignore invalid/zero-length

            if (is_null($row->staff_id)) {
                $storeBlocksMin[] = [$sMin, $eMin];
            } else {
                $staffBlocksMin[$row->staff_id][] = [$sMin, $eMin];
            }
        }

        // Merge overlapping blocks for simpler overlap checks
        $storeBlocksMin = $this->mergeRanges($storeBlocksMin);
        foreach ($staffBlocksMin as $sid => $ranges) {
            $staffBlocksMin[$sid] = $this->mergeRanges($ranges);
        }

        // Staff table links to merchant by `merchant_id`
        $totalStaffForMerchant = \App\Models\Staff::where('merchant_id', $data['merchant_id'])->count();

        // If the selected date is today, disable slots earlier than "now"
        $nowMinutes = null;
        $appNow = Carbon::now();
        if ($appNow->isSameDay($date)) {
            $nowMinutes = $appNow->hour * 60 + $appNow->minute;
        }

        // Latest allowable date/time for booking
        $maxAllowed = $appNow->copy()->addDays($maxAdvanceDays)->endOfDay();

        // Pull rows using the OperatingHour model
        $rows = OperatingHour::query()
            ->where('merchant_profile_id', $data['merchant_id'])
            ->where('day_of_week', $dow)
            ->orderBy('block_index')
            ->get(['start_time','end_time','block_type']);

        $openBlocks  = $rows->where('block_type','open')->filter(fn($r)=>$r->start_time && $r->end_time);
        $breakBlocks = $rows->where('block_type','break')->filter(fn($r)=>$r->start_time && $r->end_time);
        $closedOnly  = $rows->where('block_type','closed')->count() > 0 && $openBlocks->count() === 0;

        if ($closedOnly || $openBlocks->isEmpty()) {
            return response()->json([
                'step'          => $step,
                'duration'      => $duration,
                'is_closed_day' => true,
                'hours'         => ['start'=>null,'end'=>null],
                'slots'         => [],
            ]);
        }

        $open   = $openBlocks->map(fn($r)=>[$r->start_time, $r->end_time])->values()->all();
        $breaks = $breakBlocks->map(fn($r)=>[$r->start_time, $r->end_time])->values()->all();

        // to minutes
        $openMin   = $this->rangesToMinutes($open);
        $breakMin  = $this->rangesToMinutes($breaks);
        $windows   = $this->subtractRanges($this->mergeRanges($openMin), $this->mergeRanges($breakMin));

        $slots = [];
        $minStart = null; $maxEnd = null;
        foreach ($windows as [$s,$e]) {
            $minStart = is_null($minStart) ? $s : min($minStart, $s);
            $maxEnd   = is_null($maxEnd)   ? $e : max($maxEnd, $e);
            for ($t=$s; $t+$step <= $e; $t += $step) {
                $slotStart = $date->copy()->addMinutes($t);
                $slotEnd   = $slotStart->copy()->addMinutes($duration);

                $okWindow = ($t + $duration) <= $e; // fits inside open window
                $okNow    = is_null($nowMinutes) || $t >= $nowMinutes; // not in the past if today
                $okLead   = $slotStart->greaterThanOrEqualTo($appNow->copy()->addMinutes($minLeadMinutes));
                $okMax    = $slotStart->lessThanOrEqualTo($maxAllowed);

                // ---- New: gray-out if schedules make this slot unavailable ----
                $slotS = $t;
                $slotE = $t + $duration;

                // 1) If any store-level block overlaps, the slot is unavailable
                $blockedByStore = false;
                foreach ($storeBlocksMin as [$bs, $be]) {
                    if ($this->rangesOverlap($slotS, $slotE, $bs, $be)) { $blockedByStore = true; break; }
                }

                // 2) If all staff are occupied for this interval, the slot is unavailable
                $blockedByAllStaff = false;
                if (!$blockedByStore && $totalStaffForMerchant > 0) {
                    $busyCount = 0;
                    foreach ($staffBlocksMin as $sid => $ranges) {
                        // if any range for this staff overlaps, they are busy
                        $isBusy = false;
                        foreach ($ranges as [$bs,$be]) {
                            if ($this->rangesOverlap($slotS, $slotE, $bs, $be)) { $isBusy = true; break; }
                        }
                        if ($isBusy) { $busyCount++; }
                    }
                    $blockedByAllStaff = ($busyCount >= $totalStaffForMerchant);
                }

                $okSchedules = !$blockedByStore && !$blockedByAllStaff;

                $ok = $okWindow && $okNow && $okLead && $okMax && $okSchedules;

                $slots[] = [
                    'time' => sprintf('%02d:%02d', intdiv($t,60), $t%60),
                    'ok'   => $ok,
                ];
            }
        }

        return response()->json([
            'step'          => $step,
            'duration'      => $duration,
            'is_closed_day' => false,
            'hours'         => [
                'start' => is_null($minStart) ? null : sprintf('%02d:%02d', intdiv($minStart,60), $minStart%60),
                'end'   => is_null($maxEnd)   ? null : sprintf('%02d:%02d', intdiv($maxEnd,60),   $maxEnd%60),
            ],
            'slots'         => $slots,
        ]);
    }

    /** -------- Time-range utilities (minutes since midnight) -------- */
    private function toMinutes(string $hhmmss): int
    {
        [$h,$m] = array_map('intval', explode(':', substr($hhmmss,0,5)));
        return $h*60 + $m;
    }
    private function rangesToMinutes(array $pairs): array
    {
        return array_map(fn($p)=>[$this->toMinutes($p[0]), $this->toMinutes($p[1])], $pairs);
    }
    private function mergeRanges(array $ranges): array
    {
        if (empty($ranges)) return [];
        usort($ranges, fn($a,$b)=>$a[0] <=> $b[0]);
        $merged = [$ranges[0]];
        for ($i=1; $i<count($ranges); $i++) {
            [$s,$e] = $ranges[$i];
            [$ps,$pe] = $merged[count($merged)-1];
            if ($s <= $pe) {
                $merged[count($merged)-1][1] = max($pe,$e);
            } else {
                $merged[] = [$s,$e];
            }
        }
        return $merged;
    }
    private function subtractRanges(array $ranges, array $subtract): array
    {
        if (empty($subtract)) return $ranges;
        $result = [];
        foreach ($ranges as [$s,$e]) {
            $segments = [[$s,$e]];
            foreach ($subtract as [$bs,$be]) {
                $next = [];
                foreach ($segments as [$cs,$ce]) {
                    if ($be <= $cs || $bs >= $ce) { $next[] = [$cs,$ce]; continue; }
                    if ($bs > $cs) $next[] = [$cs, min($bs,$ce)];
                    if ($be < $ce) $next[] = [max($be,$cs), $ce];
                }
                $segments = array_values(array_filter($next, fn($seg)=>$seg[1] > $seg[0]));
            }
            foreach ($segments as $seg) $result[] = $seg;
        }
        return $this->mergeRanges($result);
    }

    private function rangesOverlap(int $s1, int $e1, int $s2, int $e2): bool
    {
        // true if [s1,e1) intersects [s2,e2)
        return ($s1 < $e2) && ($s2 < $e1);
    }

    /**
     * AJAX: Return available staff for a merchant + (service/package) at a given start time.
     */
    public function availableStaff(Request $request)
    {
        $data = $request->validate([
            'merchant_id' => 'required|exists:merchant_profiles,id',
            'type'        => 'required|in:service,package,adoption',
            'service_id'  => 'nullable|exists:services,id',
            'package_id'  => 'nullable|exists:packages,id',
            'start_at'    => 'nullable|date',
        ]);

        if ($data['type'] === 'adoption') {
            return response()->json(['ok' => true, 'data' => []]);
        }

        $api = $this->apiBase();

        $itemType = $data['type'];
        $itemId   = $itemType === 'service' ? (int) ($data['service_id'] ?? 0) : (int) ($data['package_id'] ?? 0);

        $minutes = null;
        if (!empty($data['start_at'])) {
            if ($itemType === 'service' && !empty($data['service_id'])) {
                $minutes = (int) (Service::find($data['service_id'])->duration_minutes ?? 60);
            } elseif ($itemType === 'package' && !empty($data['package_id'])) {
                $minutes = (int) (Package::find($data['package_id'])->duration_minutes ?? 60);
            }
        }

        $resp = Http::acceptJson()
            ->timeout(5)
            ->connectTimeout(2)
            ->get(
                "{$api}/merchants/{$data['merchant_id']}/eligible-staff",
                array_filter([
                    'item'     => $itemType,
                    'item_id'  => $itemId,
                    'start_at' => $data['start_at'] ?? null,
                    'minutes'  => $minutes,
                ], fn($v) => !is_null($v))
            );

        return response()->json([
            'ok'   => true,
            'data' => $resp->json('data') ?? [],
        ]);
    }
}
