@extends('layouts.app')

@php
    // Helpers to safely read arrays/objects returned from API consumers
    $dg = fn($v, $k, $d=null) => data_get($v, $k, $d);
    $bookingType = $prefill['booking_type'] ?? (request('package_id') ? 'package' : (request('pet_id') ? 'adoption' : 'service'));
@endphp

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Booking</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ ucfirst($bookingType) }} with
                {{ ($context['merchant']->display_name ?? $context['merchant']->name ?? null) ?? 'Merchant #'.($prefill['merchant_id'] ?? request('merchant_id')) }}
            </p>
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

    <form method="POST" action="{{ route('bookings.success') }}" class="space-y-8">
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
        <input type="hidden" name="pet_type_id" id="pet_type_id">
        <input type="hidden" name="size_id" id="size_id">
        <input type="hidden" name="breed_id" id="breed_id">

        {{-- Context Summary --}}
        @include('bookings.partials._summary')

        {{-- Pet picker --}}
        @include('bookings.partials._pet_picker')

        {{-- Schedule --}}
        @include('bookings.partials._schedule')

        {{-- Price & Payment --}}
        @include('bookings.partials._payment')

        <script>
        (function () {
            const radios = document.querySelectorAll('input[name="payment_method"]');
            const fpx = document.getElementById('fpxFields');
            const card = document.getElementById('cardFields');
            function toggle() {
                const m = document.querySelector('input[name="payment_method"]:checked')?.value || 'fpx';
                if (m === 'card') { card.classList.remove('hidden'); fpx.classList.add('hidden'); }
                else { fpx.classList.remove('hidden'); card.classList.add('hidden'); }
            }
            radios.forEach(r => r.addEventListener('change', toggle));
            toggle();
        })();
        </script>

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
                console.log('[quoteLivePrice]', {
                    type: bookingType,
                    service_id: ids.serviceId,
                    package_id: ids.packageId,
                    adoption_pet_id: ids.adoptionPetId,
                    customer_pet_id: document.getElementById('customer_pet_id')?.value,
                    pet_type_id: document.getElementById('pet_type_id')?.value,
                    size_id: document.getElementById('size_id')?.value,
                    breed_id: document.getElementById('breed_id')?.value
                });
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
                    const amountEl = document.querySelector('[data-amount-text]');
                    if (json?.ok && amountEl) {
                        amountEl.textContent = 'RM ' + json.amount_formatted;
                    }
                } catch (e) {
                    console.error('quote price failed', e);
                }
            }

            // expose for highlightPet() to call
            window.quoteLivePrice = quoteLivePrice;

            // If page already has a selected pet (back button), refresh price once.
            document.addEventListener('DOMContentLoaded', () => {
                quoteLivePrice();
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

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('bookings.index') }}"
               class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit"
                    class="px-5 py-2.5 rounded-md bg-indigo-600 text-white hover:bg-indigo-500 focus:ring-2 focus:ring-indigo-300">
                Book &amp; Pay
            </button>
        </div>
    </form>
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
</script>
@endsection
