

{{-- Context Summary (partial) --}}
<section class="bg-white rounded-xl border border-gray-200 p-5">
    <h2 class="text-lg font-semibold text-gray-800 mb-3">Summary</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
        <div>
            <div class="text-gray-500">Booking Type</div>
            <div class="mt-1 font-medium">{{ ucfirst($bookingType) }}</div>
        </div>
        <div>
            <div class="text-gray-500">Merchant</div>
            <div class="mt-1 font-medium">
                {{ ($context['merchant']->display_name ?? $context['merchant']->name ?? null) ?? 'Merchant #'.($prefill['merchant_id'] ?? request('merchant_id')) }}
            </div>
        </div>

        @if(!empty($context['service']) || request('service_id'))
            <div>
                <div class="text-gray-500">Service</div>
                <div class="mt-1 font-medium">
                    {{ ($context['service']->name ?? null) ?? ('Service #'.($prefill['service_id'] ?? request('service_id'))) }}
                </div>
            </div>
        @endif

        @if(!empty($context['package']) || request('package_id'))
            <div>
                <div class="text-gray-500">Package</div>
                <div class="mt-1 font-medium">
                    {{ ($context['package']->name ?? null) ?? ('Package #'.($prefill['package_id'] ?? request('package_id'))) }}
                </div>
            </div>
        @endif

        @if(!empty($context['pet']) || request('pet_id'))
            <div>
                <div class="text-gray-500">Shelter Pet</div>
                <div class="mt-1 font-medium">
                    {{ ($context['pet']->name ?? null) ?? ('Pet #'.($prefill['pet_id'] ?? request('pet_id'))) }}
                </div>
            </div>
        @endif
    </div>
</section>