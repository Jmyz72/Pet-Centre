

{{-- Time + Staff (cute, date → time → staff) --}}
<section class="bg-white rounded-2xl border border-pink-100 shadow-sm p-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-800">
            <span class="inline-flex items-center gap-2">
                <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-pink-100">🐾</span>
                Schedule
            </span>
        </h2>
        <p class="text-xs text-gray-500">Pick a date, then a time slot. We’ll only show staff who can take that slot.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Date --}}
        <div>
            <label for="booking_date" class="block text-sm font-medium text-gray-700 mb-1">Choose Date</label>
            <input
                type="date"
                id="booking_date"
                min="{{ now()->toDateString() }}"
                class="mt-1 block w-full rounded-xl border-gray-300 focus:border-pink-400 focus:ring-pink-400"
            />
            <p class="text-xs text-gray-500 mt-2">Slots are generated from merchant operating hours (breaks removed).</p>

            {{-- Staff selector only for service/package (adoption has no staff) --}}
            @if(in_array($bookingType, ['service','package'], true))
                <div class="mt-6">
                    <label for="staff_id" class="block text-sm font-medium text-gray-700 mb-1">Assign Staff</label>
                    <select name="staff_id" id="staff_id"
                            class="mt-1 block w-full rounded-xl border-gray-300 pr-8 focus:border-pink-400 focus:ring-pink-400 disabled:bg-gray-50"
                            disabled required>
                        <option value="">— Select available staff —</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">List updates after you pick a time.</p>
                </div>
            @endif
        </div>

        {{-- Time grid --}}
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Choose Time</label>
            <div class="rounded-2xl bg-gray-50 p-4">
                <div id="timeGrid" class="grid grid-cols-3 gap-3 sm:grid-cols-4 md:grid-cols-6"></div>
                <p class="mt-3 text-xs text-gray-500">
                    Showing {{ ($duration ?? 60) }}‑minute slots between <span id="hourStart">09:00</span> and <span id="hourEnd">18:00</span>.
                </p>
            </div>
        </div>
    </div>

    {{-- Hidden fields to submit (ISO local) --}}
    <input type="hidden" name="start_at" id="start_at" value="{{ old('start_at') }}">
</section>