@extends('layouts.app')

@php
    // Helpers to safely read arrays/objects returned from API consumers
    $dg = fn($v, $k, $d=null) => data_get($v, $k, $d);
    $bookingType = $prefill['booking_type'] ?? (request('package_id') ? 'package' : (request('pet_id') ? 'adoption' : 'service'));
    $merchant = $context['merchant'] ?? null;
    $service = $context['service'] ?? null;
    $package = $context['package'] ?? null;
    $shelterPet = $context['pet'] ?? null;
@endphp

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-5-3v2m0 0V6m0-2a1 1 0 011-1h2a1 1 0 011 1v2M8 7V5a1 1 0 011-1h6a1 1 0 011 1v2m-7 4v8a2 2 0 002 2h4a2 2 0 002-2v-8M8 11V9a1 1 0 011-1h6a1 1 0 011 1v2"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create New Booking</h1>
                    <p class="text-gray-600 mt-1">Complete your {{ strtolower($bookingType) }} booking in a few simple steps</p>
                </div>
            </div>
            
            {{-- Progress Steps --}}
            <div class="flex items-center gap-4 mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center text-sm font-medium">1</div>
                    <span class="text-sm font-medium text-indigo-600">Service Details</span>
                </div>
                <div class="w-12 h-px bg-gray-300"></div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">2</div>
                    <span class="text-sm text-gray-500">Schedule</span>
                </div>
                <div class="w-12 h-px bg-gray-300"></div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">3</div>
                    <span class="text-sm text-gray-500">Payment</span>
                </div>
            </div>
        </div>

    {{-- Alerts --}}
    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-50 p-4 text-red-800 border border-red-200">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        <form method="POST" action="{{ route('bookings.store') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf

            {{-- Hidden enforced context (user cannot change) --}}
            <input type="hidden" name="booking_type" value="{{ $bookingType }}">
            <input type="hidden" name="merchant_id" value="{{ $prefill['merchant_id'] ?? request('merchant_id') }}">
            @if(!empty($prefill['service_id']) || request('service_id'))
                <input type="hidden" name="service_id" value="{{ $prefill['service_id'] ?? request('service_id') }}">
            @endif
            @if(!empty($prefill['package_id']) || request('package_id'))
                <input type="hidden" name="package_id" value="{{ $prefill['package_id'] ?? request('package_id') }}">
            @endif
            @if(!empty($prefill['pet_id']) || request('pet_id'))
                <input type="hidden" name="pet_id" value="{{ $prefill['pet_id'] ?? request('pet_id') }}">
            @endif
            @if($bookingType === 'adoption')
                <input type="hidden" name="pet_id" id="pet_id" value="{{ $prefill['pet_id'] ?? request('pet_id') }}">
            @endif
            <input type="hidden" name="customer_pet_id" value="{{ request('customer_pet_id') }}">
            <input type="hidden" name="pet_type_id" id="pet_type_id" value="{{ $dg($selectedPet, 'pet_type_id') }}">
            <input type="hidden" name="size_id" id="size_id" value="{{ $dg($selectedPet, 'size_id') }}">
            <input type="hidden" name="breed_id" id="breed_id" value="{{ $dg($selectedPet, 'pet_breed_id') ?? $dg($selectedPet, 'breed_id') }}">

            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Service Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            @if($bookingType === 'service')
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.586V5L8 4z"></path>
                                </svg>
                            @elseif($bookingType === 'package')
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">
                                @if($bookingType === 'service' && $service)
                                    {{ $service->title ?? $service->name }}
                                @elseif($bookingType === 'package' && $package)  
                                    {{ $package->name }}
                                @elseif($bookingType === 'adoption' && $shelterPet)
                                    Pet Adoption - {{ $shelterPet->name }}
                                @else
                                    {{ ucfirst($bookingType) }} Booking
                                @endif
                            </h2>
                            <p class="text-sm text-gray-600">{{ $merchant->name ?? 'Merchant' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <div class="text-gray-500 mb-1">Business Name</div>
                            <div class="font-medium text-gray-900">{{ $merchant->name ?? 'N/A' }}</div>
                        </div>
                        
                        @if($merchant && $merchant->address)
                            <div>
                                <div class="text-gray-500 mb-1">Address</div>
                                <div class="font-medium text-gray-900">{{ $merchant->address }}</div>
                            </div>
                        @endif

                        @if($merchant && $merchant->phone)
                            <div>
                                <div class="text-gray-500 mb-1">Contact</div>
                                <div class="font-medium text-gray-900">{{ $merchant->phone }}</div>
                            </div>
                        @endif

                        @if($service && $service->duration_minutes)
                            <div>
                                <div class="text-gray-500 mb-1">Duration</div>
                                <div class="font-medium text-gray-900">{{ $service->duration_minutes }} minutes</div>
                            </div>
                        @elseif($package && $package->duration_minutes)
                            <div>
                                <div class="text-gray-500 mb-1">Duration</div>
                                <div class="font-medium text-gray-900">{{ $package->duration_minutes }} minutes</div>
                            </div>
                        @endif

                        @if($service && $service->description)
                            <div class="md:col-span-2">
                                <div class="text-gray-500 mb-1">Description</div>
                                <div class="text-gray-900">{{ $service->description }}</div>
                            </div>
                        @elseif($package && $package->description)
                            <div class="md:col-span-2">
                                <div class="text-gray-500 mb-1">Description</div>
                                <div class="text-gray-900">{{ $package->description }}</div>
                            </div>
                        @elseif($shelterPet && ($shelterPet->description || $shelterPet->medical_notes))
                            <div class="md:col-span-2">
                                <div class="text-gray-500 mb-1">About This Pet</div>
                                <div class="text-gray-900">
                                    {{ $shelterPet->description ?? $shelterPet->medical_notes }}
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($shelterPet)
                        <div class="mt-4 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-orange-900">{{ $shelterPet->name }} is looking for a home!</h4>
                                    <div class="text-sm text-orange-800 mt-1">
                                        <div><strong>Age:</strong> {{ $shelterPet->age ?? 'Unknown' }}</div>
                                        @if($shelterPet->type)
                                            <div><strong>Type:</strong> {{ $shelterPet->type->name }}</div>
                                        @endif
                                        @if($shelterPet->breed)
                                            <div><strong>Breed:</strong> {{ $shelterPet->breed->name }}</div>
                                        @endif
                                        @if($shelterPet->size)
                                            <div><strong>Size:</strong> {{ $shelterPet->size->label }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Schedule Section --}}
                @include('bookings.partials._schedule')
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Price Summary --}}
                @include('bookings.partials._payment')

                {{-- Action Buttons --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="space-y-3">
                        <button type="submit" id="submitBtn" disabled class="w-full bg-gray-400 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2 cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span id="submitBtnText">Complete All Fields to Continue</span>
                        </button>
                        <a href="{{ route('bookings.index') }}" class="w-full border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-3 px-4 rounded-lg transition-colors duration-200 text-center block">
                            Cancel
                        </a>
                    </div>

                    {{-- Required Fields Checklist --}}
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Required Information:</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center gap-2" id="check-date">
                                <div class="w-4 h-4 rounded border border-gray-300 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-green-500 hidden" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-600">Select booking date</span>
                            </div>
                            <div class="flex items-center gap-2" id="check-time">
                                <div class="w-4 h-4 rounded border border-gray-300 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-green-500 hidden" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-600">Choose time slot</span>
                            </div>
                            @if(in_array($bookingType, ['service','package'], true))
                            <div class="flex items-center gap-2" id="check-staff">
                                <div class="w-4 h-4 rounded border border-gray-300 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-green-500 hidden" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-600">Select staff member</span>
                            </div>
                            @endif
                            <div class="flex items-center gap-2" id="check-payment">
                                <div class="w-4 h-4 rounded border border-gray-300 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-green-500 hidden" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-600">Complete payment details</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span>Secure payment processing</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600 mt-2">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span>Instant booking confirmation</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600 mt-2">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span>24/7 customer support</span>
                        </div>
                    </div>
                </div>
            </div>
        </form>


        <script>
        (function () {
            const bookingType = '{{ $bookingType }}';
            const quoteUrl = '{{ route('bookings.quote-price') }}';
            function getIds() {
                return {
                    serviceId: document.querySelector('input[name="service_id"]')?.value || '',
                    packageId: document.querySelector('input[name="package_id"]')?.value || '',
                    adoptionPetId: document.getElementById('pet_id')?.value || ''
                };
            }

            async function quoteLivePrice() {
                const ids = getIds();
                const petData = {
                    type: bookingType,
                    service_id: ids.serviceId,
                    package_id: ids.packageId,
                    adoption_pet_id: ids.adoptionPetId,
                    customer_pet_id: document.getElementById('customer_pet_id')?.value,
                    pet_type_id: document.getElementById('pet_type_id')?.value,
                    size_id: document.getElementById('size_id')?.value,
                    breed_id: document.getElementById('breed_id')?.value
                };
                console.log('[quoteLivePrice] Sending data:', petData);
                try {
                    const ids2 = getIds();
                    const params = new URLSearchParams({
                        type: bookingType,
                        service_id: ids2.serviceId,
                        package_id: ids2.packageId,
                        customer_pet_id: document.getElementById('customer_pet_id')?.value || '',
                        pet_type_id: document.getElementById('pet_type_id')?.value || '',
                        size_id: document.getElementById('size_id')?.value || '',
                        breed_id: document.getElementById('breed_id')?.value || '',
                        pet_id: ids2.adoptionPetId
                    });
                    const res = await fetch(quoteUrl + '?' + params.toString(), { headers: { 'Accept': 'application/json' }});
                    const json = await res.json();
                    console.log('[quoteLivePrice][response]', json);
                    console.log('[quoteLivePrice][current amount element]', document.querySelector('[data-amount-text]')?.textContent);
                    const amountEl = document.querySelector('[data-amount-text]');
                    if (json?.ok && amountEl) {
                        console.log('[quoteLivePrice][updating amount]', 'RM ' + json.amount_formatted);
                        amountEl.textContent = 'RM ' + json.amount_formatted;
                    } else {
                        console.error('[quoteLivePrice][failed]', { jsonOk: json?.ok, amountEl: !!amountEl, json });
                    }
                } catch (e) {
                    console.error('quote price failed', e);
                }
            }

            // expose for highlightPet() to call
            window.quoteLivePrice = quoteLivePrice;

            // If page already has a selected pet, refresh price once on load
            document.addEventListener('DOMContentLoaded', () => {
                console.log('[DOMContentLoaded] Hidden input values:', {
                    customer_pet_id: document.getElementById('customer_pet_id')?.value,
                    pet_type_id: document.getElementById('pet_type_id')?.value,
                    size_id: document.getElementById('size_id')?.value,
                    breed_id: document.getElementById('breed_id')?.value
                });
                
                // Always refresh price on load (especially for package variations)
                setTimeout(() => {
                    quoteLivePrice();
                }, 100);
                
                const cp = document.getElementById('customer_pet_id');
                if (cp) cp.addEventListener('change', quoteLivePrice);
            });
        })();
        </script>

        {{-- Scheduler scripts --}}
        <script>
        (function () {
            const DURATION_MIN = {{ (int)($duration ?? 60) }};
            const STEP_FALLBACK = 30;  // fallback if controller omits step

            const dateEl   = document.getElementById('booking_date');
            const gridEl   = document.getElementById('timeGrid');
            const startAtH = document.getElementById('start_at');
            const staffSel = document.getElementById('staff_id');

            const merchantId = '{{ $prefill['merchant_id'] ?? request('merchant_id') }}';
            const bookingType = '{{ $bookingType }}';
            const serviceId = '{{ $prefill['service_id'] ?? request('service_id') }}';
            const packageId = '{{ $prefill['package_id'] ?? request('package_id') }}';

            // today's yyyy-mm-dd string (client-side), used to clamp past dates
            const today = new Date();
            const todayPad = (n)=> n.toString().padStart(2,'0');
            const todayStr = `${today.getFullYear()}-${todayPad(today.getMonth()+1)}-${todayPad(today.getDate())}`;

            function pad(n){return n.toString().padStart(2,'0')}
            function localISO(dateObj){
                // YYYY-MM-DDTHH:MM (no timezone Z) to align with your controller's expectation
                const y = dateObj.getFullYear();
                const m = pad(dateObj.getMonth()+1);
                const d = pad(dateObj.getDate());
                const hh = pad(dateObj.getHours());
                const mm = pad(dateObj.getMinutes());
                return `${y}-${m}-${d}T${hh}:${mm}`;
            }

            function clearStaff(disabledMsg){
                if(!staffSel) return;
                staffSel.innerHTML = `<option value="">${disabledMsg || '— Select available staff —'}</option>`;
                staffSel.disabled = true;
            }

            async function buildGrid(){
                if(!gridEl) return;
                gridEl.innerHTML = '';
                const day = dateEl?.value;
                if(!day){ clearStaff('— Pick a date first —'); return; }
                try{
                    const params = new URLSearchParams({
                        merchant_id: merchantId,
                        type: bookingType,
                        service_id: serviceId,
                        package_id: packageId,
                        date: day
                    });
                    const res = await fetch('{{ route('bookings.available-slots') }}?' + params.toString(), { headers: { 'Accept': 'application/json' }});
                    const json = await res.json();
                    console.log('[available-slots][response]', json);
                    if (!res.ok) {
                        const msg = (json?.message || 'Failed to load slots.');
                        gridEl.innerHTML = '<div class="col-span-full text-sm text-red-500">' + msg + '</div>';
                        return;
                    }
                    if (json?.errors) {
                        const errText = Object.values(json.errors).flat().join('<br>');
                        gridEl.innerHTML = '<div class="col-span-full text-sm text-red-500">' + errText + '</div>';
                        return;
                    }
                    const step = json.step ?? STEP_FALLBACK;
                    const duration = json.duration ?? DURATION_MIN;
                    const hourStartEl = document.getElementById('hourStart');
                    const hourEndEl   = document.getElementById('hourEnd');
                    if (hourStartEl) hourStartEl.textContent = json?.hours?.start ?? '--:--';
                    if (hourEndEl)   hourEndEl.textContent   = json?.hours?.end   ?? '--:--';
                    const slotsArr = Array.isArray(json.slots) ? json.slots : [];
                    if (json.is_closed_day) {
                        gridEl.innerHTML = '<div class="col-span-full text-sm text-gray-500">Store is closed on this day.</div>';
                        return;
                    }
                    if (!slotsArr.length) {
                        gridEl.innerHTML = '<div class="col-span-full text-sm text-gray-500">No available times for this day.</div>';
                        return;
                    }
                    slotsArr.forEach(slot => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.textContent = slot.time;
                        btn.className = 'rounded-xl border px-3 py-2 text-sm ' +
                            (slot.ok ? 'border-white bg-white hover:border-pink-200' : 'cursor-not-allowed border-gray-200 bg-gray-100 text-gray-400');
                        btn.disabled = !slot.ok;
                        if (slot.ok) {
                            btn.addEventListener('click', async () => {
                                const dt = new Date(day + 'T' + slot.time);
                                startAtH.value = localISO(dt);
                                Array.from(gridEl.children).forEach(c => c.classList.remove('ring-2','ring-pink-200','border-pink-400'));
                                btn.classList.add('ring-2','ring-pink-200','border-pink-400');
                                await fetchStaff();
                            });
                        }
                        gridEl.appendChild(btn);
                    });
                }catch(e){
                    console.error(e);
                    gridEl.innerHTML = '<div class="col-span-full text-sm text-red-500">Failed to load slots.</div>';
                }
            }

            async function fetchStaff(){
                if(!staffSel) return;
                if(!startAtH.value){ clearStaff('— Pick a time first —'); return; }

                try{
                    clearStaff('Loading…');
                    const params = new URLSearchParams({
                        merchant_id: merchantId,
                        type: bookingType,
                        service_id: serviceId,
                        package_id: packageId,
                        start_at: startAtH.value,
                    });
                    const res = await fetch('{{ url('/bookings/available-staff') }}?' + params.toString(), { headers: { 'Accept': 'application/json' }});
                    const json = await res.json();
                    staffSel.innerHTML = '<option value="">' + '— Select available staff —' + '</option>';
                    if(json.ok && Array.isArray(json.data) && json.data.length){
                        json.data.forEach(s => {
                            const opt = document.createElement('option');
                            opt.value = s.id;
                            opt.textContent = s.name ?? s.email ?? ('Staff #' + s.id);
                            staffSel.appendChild(opt);
                        });
                        staffSel.disabled = false;
                    }else{
                        clearStaff('— No eligible staff for this time —');
                    }
                }catch(e){
                    console.error(e);
                    clearStaff('— Error loading staff —');
                }
            }

            // init: enforce min date (today), clamp past selections, build grid
            if(dateEl){
                // enforce min (covers browser back-cache cases)
                dateEl.setAttribute('min', todayStr);
                // default to today if empty or older than today
                if (!dateEl.value || dateEl.value < todayStr) {
                    dateEl.value = todayStr;
                }
                dateEl.addEventListener('change', () => {
                    if (dateEl.value < todayStr) {
                        dateEl.value = todayStr; // clamp to today
                    }
                    startAtH.value = '';
                    buildGrid();
                    clearStaff('— Pick a time first —');
                });
            }
            buildGrid();
            clearStaff('— Pick a time first —');
        })();
        </script>

    </div>
</div>

{{-- Javascript: helpers --}}
<script>
function highlightPet(id) {
    const grid = document.getElementById('petGrid');
    if (!grid) return;
    Array.from(grid.querySelectorAll('[data-pet]')).forEach(card => {
        const isSel = (card.getAttribute('data-pet') == id);
        if (isSel) {
            card.classList.remove('border-gray-200');
            card.classList.add('border-indigo-500','ring-2','ring-indigo-200');

            // copy traits to hidden inputs
            const pt = card.getAttribute('data-pet-type') || '';
            const sz = card.getAttribute('data-size-id') || '';
            const br = card.getAttribute('data-breed-id') || '';
            const ptEl = document.getElementById('pet_type_id');
            const szEl = document.getElementById('size_id');
            const brEl = document.getElementById('breed_id');
            if (ptEl) ptEl.value = pt;
            if (szEl) szEl.value = sz;
            if (brEl) brEl.value = br;

            // if adoption flow, also set hidden pet_id
            const adoptionPetInput = document.getElementById('pet_id');
            if (adoptionPetInput) {
                adoptionPetInput.value = id;
            }
            // also reflect selection into hidden customer_pet_id (for robustness) and dispatch change
            const cp = document.getElementById('customer_pet_id');
            if (cp) {
                cp.value = id;
                cp.dispatchEvent(new Event('change'));
            }
        } else {
            card.classList.remove('border-indigo-500','ring-2','ring-indigo-200');
            card.classList.add('border-gray-200');
        }
    });

    // Refresh live price after selection
    if (typeof window.quoteLivePrice === 'function') {
        window.quoteLivePrice();
    }
}

// Form validation and submit button management
(function() {
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const bookingDateInput = document.getElementById('booking_date');
    const startAtInput = document.getElementById('start_at');
    const staffSelect = document.getElementById('staff_id');
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const bankSelect = document.getElementById('bank');
    const cardName = document.getElementById('card_name');
    const cardNumber = document.getElementById('card_number');
    const cardExpiry = document.getElementById('card_expiry');
    const cardCcv = document.getElementById('card_ccv');
    
    const bookingType = '{{ $bookingType }}';
    const needsStaff = ['service', 'package'].includes(bookingType);
    
    function updateCheckmark(checkId, isValid) {
        const checkEl = document.getElementById(checkId);
        if (checkEl) {
            const svg = checkEl.querySelector('svg');
            const div = checkEl.querySelector('div');
            if (isValid) {
                svg.classList.remove('hidden');
                div.classList.add('bg-green-100', 'border-green-300');
                div.classList.remove('border-gray-300');
            } else {
                svg.classList.add('hidden');
                div.classList.remove('bg-green-100', 'border-green-300');
                div.classList.add('border-gray-300');
            }
        }
    }
    
    function validateForm() {
        let isValid = true;
        const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value;
        
        // Check date
        const dateValid = bookingDateInput && bookingDateInput.value;
        updateCheckmark('check-date', dateValid);
        if (!dateValid) isValid = false;
        
        // Check time
        const timeValid = startAtInput && startAtInput.value;
        updateCheckmark('check-time', timeValid);
        if (!timeValid) isValid = false;
        
        // Check staff (only for service/package)
        if (needsStaff) {
            const staffValid = staffSelect && staffSelect.value;
            updateCheckmark('check-staff', staffValid);
            if (!staffValid) isValid = false;
        }
        
        // Check payment details
        let paymentValid = false;
        if (selectedPaymentMethod === 'card') {
            paymentValid = cardName?.value && cardNumber?.value && cardExpiry?.value && cardCcv?.value;
        } else if (selectedPaymentMethod === 'fpx') {
            paymentValid = bankSelect?.value;
        }
        updateCheckmark('check-payment', paymentValid);
        if (!paymentValid) isValid = false;
        
        // Update submit button
        if (isValid) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
            submitBtn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
            submitBtnText.textContent = 'Book & Pay Now';
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
            submitBtn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
            submitBtnText.textContent = 'Complete All Fields to Continue';
        }
    }
    
    // Add event listeners
    if (bookingDateInput) bookingDateInput.addEventListener('change', validateForm);
    if (startAtInput) {
        // Use MutationObserver to detect when start_at is set by JavaScript
        const observer = new MutationObserver(validateForm);
        observer.observe(startAtInput, { attributes: true, attributeFilter: ['value'] });
        startAtInput.addEventListener('change', validateForm);
    }
    if (staffSelect) staffSelect.addEventListener('change', validateForm);
    if (bankSelect) bankSelect.addEventListener('change', validateForm);
    if (cardName) cardName.addEventListener('input', validateForm);
    if (cardNumber) cardNumber.addEventListener('input', validateForm);
    if (cardExpiry) cardExpiry.addEventListener('input', validateForm);
    if (cardCcv) cardCcv.addEventListener('input', validateForm);
    
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', validateForm);
    });
    
    // Initial validation
    validateForm();
    
    // Also validate when time slots are selected (hook into existing highlightPet function)
    const originalQuoteLivePrice = window.quoteLivePrice;
    if (originalQuoteLivePrice) {
        window.quoteLivePrice = function() {
            originalQuoteLivePrice();
            setTimeout(validateForm, 100); // Small delay to ensure DOM updates
        };
    }
})();
</script>
@endsection
