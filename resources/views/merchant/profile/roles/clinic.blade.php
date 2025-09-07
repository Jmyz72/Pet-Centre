<section class="max-w-6xl mx-auto py-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Clinic Services</h2>
        <div class="text-sm text-gray-500">
            {{ $services->count() }} result{{ $services->count() === 1 ? '' : 's' }}
        </div>
    </div>

    @php
        $services = $services ?? ($profile->services ?? collect());
    @endphp

    @if($services && $services->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6">
            @foreach($services as $svc)
                <div class="group rounded-xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition">
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="text-base font-semibold text-gray-900 leading-snug">{{ $svc->name }}</h3>
                            @if(!$svc->is_active)
                                <span class="inline-flex items-center rounded-full bg-gray-50 text-gray-600 ring-1 ring-gray-200 px-2 py-0.5 text-[11px]">Inactive</span>
                            @endif
                        </div>

                        <div class="mt-2 text-[11px] text-gray-700 flex flex-wrap items-center gap-1.5">
                            {{-- Service Type --}}
                            @if(optional($svc->serviceType)->name)
                                <span class="inline-flex items-center rounded-full bg-gray-50 ring-1 ring-gray-200 px-2 py-0.5">{{ $svc->serviceType->name }}</span>
                            @endif

                            {{-- Duration --}}
                            @if(!is_null($svc->duration_minutes))
                                <span class="inline-flex items-center rounded-full bg-gray-50 ring-1 ring-gray-200 px-2 py-0.5">{{ $svc->duration_minutes }} min</span>
                            @endif

                            {{-- Price --}}
                            @if(!is_null($svc->price))
                                <span class="inline-flex items-center rounded-full bg-indigo-50 ring-1 ring-indigo-200 px-2 py-0.5 text-indigo-700">RM {{ number_format((float)$svc->price, 2) }}</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-gray-50 ring-1 ring-gray-200 px-2 py-0.5 text-gray-600">Contact for pricing</span>
                            @endif
                        </div>

                        {{-- Description --}}
                        @if(!empty($svc->description))
                            <p class="mt-3 text-sm text-gray-600 leading-relaxed">{{ \Illuminate\Support\Str::limit($svc->description, 140) }}</p>
                        @endif

                        {{-- Footer button --}}
                        <div class="mt-4">
                            @if($svc->is_active)
                                <a href="{{ route('booking.create', ['merchantProfile' => $profile->id, 'service' => $svc->id]) }}" class="w-full inline-flex justify-center items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                                    Book
                                </a>
                            @else
                                <span class="w-full inline-flex justify-center items-center rounded-md bg-gray-200 px-3 py-2 text-sm font-semibold text-gray-500 cursor-not-allowed">Book</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 p-5 rounded shadow text-center">
            <span class="font-medium">No services available at this clinic.</span>
        </div>
    @endif
</section>