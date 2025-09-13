@php
    /** @var \App\Models\MerchantProfile $profile */
    // Accept either injected $packages or from $profile->packages
    $groomingPackages = isset($packages) ? $packages : ($profile->packages ?? collect());
    // Helper to display price nicely; fallback text if null
    $formatPrice = function ($p) {
        $value = $p->price ?? $p->base_price ?? null;
        if (is_numeric($value)) {
            return 'RM ' . number_format((float) $value, 2);
        }
        return 'Price on request';
    };
    // Helper to display duration if present (supports duration_minutes or duration)
    $formatDuration = function ($p) {
        $val = $p->duration_minutes ?? $p->duration ?? null;
        if ($val === null || $val === '') {
            return null;
        }
        return is_numeric($val) ? ($val . ' min') : $val;
    };

    // Helpers to render relation chips safely (no queries in Blade)
    $namesOrEmpty = function ($collection, $key = 'name') {
        try {
            if (is_iterable($collection) && method_exists($collection, 'pluck')) {
                return $collection->pluck($key)->filter()->take(6)->values()->all();
            }
        } catch (\Throwable $e) {}
        return [];
    };

    $chipList = function (array $items) {
        if (empty($items)) return '';
        $html = '<div class="mt-1.5 flex flex-wrap gap-1.5">';
        foreach ($items as $item) {
            $html .= '<span class="inline-flex items-center rounded-full bg-gray-50 text-gray-700 ring-1 ring-gray-200 px-2 py-0.5 text-[11px] font-medium">'.e($item).'</span>';
        }
        $html .= '</div>';
        return $html;
    };

    // Helpers for variations (package_variations)
    $formatVarLabel = function ($v) {
        $parts = [];

        // The variation points to PIVOT rows (package_* tables). From there we resolve the
        // real master entities to get human labels.
        // Expected relationship names on the PackageVariation model:
        //   petTypePivot -> belongsTo PackagePetType  (column: package_pet_type_id)
        //   sizePivot    -> belongsTo PackageSize     (column: package_size_id)
        //   breedPivot   -> belongsTo PackageBreed    (column: package_breed_id)
        // and each pivot has: petType(), size(), breed() relations to master tables.

        // Pet Type name
        try {
            $name = $v->petTypePivot->petType->name ?? null;
            if ($name) { $parts[] = $name; }
        } catch (Throwable $e) {}
        if (empty($name) && isset($v->package_pet_type_id)) {
            $parts[] = 'Type #'.$v->package_pet_type_id; // fallback
        }

        // Size label (sizes table uses `label`, not `name`)
        try {
            $label = $v->sizePivot->size->label ?? null;
            if ($label) { $parts[] = $label; }
        } catch (Throwable $e) {}
        if (empty($label) && isset($v->package_size_id)) {
            $parts[] = 'Size #'.$v->package_size_id; // fallback
        }

        // Breed name
        try {
            $bname = $v->breedPivot->breed->name ?? null;
            if ($bname) { $parts[] = $bname; }
        } catch (Throwable $e) {}
        if (empty($bname) && isset($v->package_breed_id)) {
            $parts[] = 'Breed #'.$v->package_breed_id; // fallback
        }

        if (empty($parts)) { $parts[] = 'Custom'; }
        return implode(' • ', $parts);
    };

    $hasVariations = function ($package) {
        try {
            if (isset($package->variations) && is_iterable($package->variations)) {
                return (method_exists($package->variations, 'count') ? $package->variations->count() : count($package->variations)) > 0;
            }
        } catch (\Throwable $e) {}
        return false;
    };
@endphp

<section class="my-8">
    <div class="flex items-end justify-between gap-4 mb-4">
        <div>
            <h2 class="text-2xl font-semibold tracking-tight text-gray-900">Grooming Packages</h2>
            <p class="text-sm text-gray-600">Choose a package and book your preferred time.</p>
        </div>
        @if(method_exists($groomingPackages, 'total'))
            <span class="text-sm text-gray-500">{{ $groomingPackages->total() }} result{{ $groomingPackages->total() == 1 ? '' : 's' }}</span>
        @elseif(is_iterable($groomingPackages))
            @php $count = is_array($groomingPackages) ? count($groomingPackages) : (method_exists($groomingPackages,'count') ? $groomingPackages->count() : 0); @endphp
            <span class="text-sm text-gray-500">{{ $count }} result{{ $count == 1 ? '' : 's' }}</span>
        @endif
    </div>

    @if($groomingPackages && (is_countable($groomingPackages) ? count($groomingPackages) : (method_exists($groomingPackages,'count') ? $groomingPackages->count() : 0)))
        <div class="space-y-4">
            @foreach($groomingPackages as $package)
                <div class="group rounded-2xl ring-1 ring-gray-200 bg-white hover:ring-gray-300 hover:shadow-sm transition">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 p-6">
                        {{-- Left: Title & description --}}
                        <div class="md:col-span-8 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-semibold text-2xl text-gray-900 truncate">{{ $package->name }}</h3>
                                @if($formatDuration($package))
                                    <span class="inline-flex items-center rounded-full bg-gray-100 text-gray-800 ring-1 ring-gray-200 px-3 py-0.5 text-xs font-medium">
                                        {{ $formatDuration($package) }}
                                    </span>
                                @endif
                            </div>
                            @if(!empty($package->short_description))
                                <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $package->short_description }}</p>
                            @elseif(!empty($package->description))
                                <p class="mt-2 text-base text-gray-700/90 line-clamp-2">{{ \Illuminate\Support\Str::limit($package->description, 200) }}</p>
                            @endif

                            {{-- Relation chips: Pet Types, Sizes, Breeds, Services --}}
                            @php
                                // Try common relation names; ignore if model doesn't define them
                                $petTypeNames   = isset($package->petTypes)       ? $namesOrEmpty($package->petTypes)                 : [];
                                $sizeNames      = isset($package->packageSizes)   ? $namesOrEmpty($package->packageSizes, 'label')    : [];
                                $breedNames     = isset($package->petBreeds)      ? $namesOrEmpty($package->petBreeds)                : [];
                                $serviceNames   = isset($package->packageTypes)   ? $namesOrEmpty($package->packageTypes)             : [];

                                // Fallback aliases some teams use
                                if (empty($serviceNames) && isset($package->types)) {
                                    $serviceNames = $namesOrEmpty($package->types);
                                }
                            @endphp

                            @if(!empty($serviceNames))
                                <div class="mt-3">
                                    <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Includes:</span>
                                    {!! $chipList($serviceNames) !!}
                                </div>
                            @endif
                            @if(!empty($petTypeNames))
                                <div class="mt-3">
                                    <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Supports Pet Types:</span>
                                    {!! $chipList($petTypeNames) !!}
                                </div>
                            @endif
                            @if(!empty($sizeNames))
                                <div class="mt-3">
                                    <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Supports Sizes:</span>
                                    {!! $chipList($sizeNames) !!}
                                </div>
                            @endif
                            @if(!empty($breedNames))
                                <div class="mt-3">
                                    <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Supports Breeds:</span>
                                    {!! $chipList($breedNames) !!}
                                </div>
                            @else
                                <div class="mt-3">
                                    <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Supports Breeds:</span>
                                    <div class="mt-1.5 flex flex-wrap gap-1.5">
                                        <span class="inline-flex items-center rounded-full bg-gray-50 text-gray-700 ring-1 ring-gray-200 px-2 py-0.5 text-[11px] font-medium">All breeds</span>
                                    </div>
                                </div>
                            @endif
                            {{-- Variations (specific prices per size/breed/type) --}}
                            @if($hasVariations($package))
                                <details class="mt-4">
                                    <summary class="cursor-pointer select-none text-sm font-medium text-gray-700 hover:text-gray-900 inline-flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                                        See specific prices
                                    </summary>
                                    <div class="mt-3 overflow-hidden rounded-xl ring-1 ring-gray-200">
                                        <div class="divide-y divide-gray-200 bg-white">
                                            @foreach($package->variations as $v)
                                                <div class="flex items-center justify-between gap-4 px-4 py-2">
                                                    <div class="text-sm text-gray-700">
                                                        {{ $formatVarLabel($v) }}
                                                        @if(isset($v->is_active) && (string)$v->is_active === '0')
                                                            <span class="ml-2 inline-flex items-center rounded-full bg-gray-100 text-gray-500 ring-1 ring-gray-200 px-2 py-0.5 text-[11px]">Unavailable</span>
                                                        @endif
                                                    </div>
                                                    <div class="text-sm font-semibold text-gray-900">
                                                        {{ is_numeric($v->price ?? null) ? ('RM ' . number_format((float)$v->price, 2)) : '—' }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </details>
                            @endif
                        </div>

                        {{-- Right: Price & CTA --}}
                        <div class="md:col-span-4">
                            <div class="h-full md:h-auto flex md:flex-col items-center md:items-end justify-between md:justify-start gap-3">
                                <div class="text-right md:mb-3">
                                    <div class="text-[11px] uppercase tracking-wide text-gray-500">From</div>
                                    <div class="text-3xl font-semibold text-gray-900">{{ $formatPrice($package) }}</div>
                                </div>
                                @if(isset($package->is_active) && (string)$package->is_active === '0')
                                    <span class="inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-500 ring-1 ring-gray-200 px-4 py-2 text-sm font-medium cursor-not-allowed">
                                        Unavailable
                                    </span>
                                @else
                                    <a href="{{ route('bookings.create', ['merchant_id' => $profile->id, 'package_id' => $package->id]) }}"
                                       class="inline-flex items-center justify-center rounded-lg bg-gray-900 text-white px-4 py-2 text-sm font-medium hover:bg-black/90 transition">
                                        Book Now
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination if a paginator is passed --}}
        @if(method_exists($groomingPackages, 'links'))
            <div class="mt-6">
                {{ $groomingPackages->onEachSide(1)->links() }}
            </div>
        @endif
    @else
        <div class="rounded-3xl border border-dashed border-gray-300 bg-white p-8 text-center">
            <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M3 4.5A1.5 1.5 0 014.5 3h11A1.5 1.5 0 0117 4.5V15a2 2 0 01-2 2H5a2 2 0 01-2-2V4.5zM5 6v9h10V6H5z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900">No grooming packages available</h3>
            <p class="mt-1 text-sm text-gray-600">Please check back later or contact the merchant for more information.</p>
        </div>
    @endif
</section>