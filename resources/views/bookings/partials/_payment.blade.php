{{-- Price & Payment --}}
@php
    use Illuminate\Support\Facades\DB;
    
    $currency = 'RM';
    $displayPrice = null;
    $priceNote = null;

    if ($bookingType === 'service' && !empty($context['service'])) {
        $displayPrice = $context['service']->price ?? 0;
    } elseif ($bookingType === 'package' && !empty($context['package'])) {
        $basePrice = $context['package']->price ?? 0;
        $displayPrice = $basePrice;
        
        // If we have a selected pet, calculate the variation price
        if (!empty($selectedPet)) {
            $petTypeId = $selectedPet['pet_type_id'] ?? null;
            $sizeId = $selectedPet['size_id'] ?? null;
            $breedId = $selectedPet['pet_breed_id'] ?? $selectedPet['breed_id'] ?? null;
            
            if ($petTypeId) {
                // Find pivot records for this package + pet type
                $pivotIds = DB::table('package_pet_types')
                    ->where('package_id', $context['package']->id)
                    ->where('pet_type_id', $petTypeId)
                    ->pluck('id');
                
                if ($pivotIds->isNotEmpty()) {
                    // Get active variations for this package + pet type
                    $variations = \App\Models\PackageVariation::query()
                        ->where('package_id', $context['package']->id)
                        ->whereIn('package_pet_type_id', $pivotIds)
                        ->where('is_active', 1)
                        ->get();
                    
                    if ($variations->isNotEmpty()) {
                        // Choose the most specific match: breed > size > fallback
                        $chosen = null;
                        if ($breedId) {
                            $chosen = $variations->firstWhere('package_breed_id', $breedId);
                        }
                        if (!$chosen && $sizeId) {
                            $chosen = $variations->firstWhere('package_size_id', $sizeId);
                        }
                        if (!$chosen) {
                            $chosen = $variations->first();
                        }
                        
                        if ($chosen) {
                            $displayPrice = (float) $chosen->price;
                        }
                    }
                }
            }
        }
        
        $priceNote = 'Price calculated based on your selected pet.';
    } elseif ($bookingType === 'adoption' && !empty($context['pet'])) {
        $displayPrice = $context['pet']->adoption_fee ?? 0;
    }
@endphp

<section class="bg-white rounded-2xl border border-gray-200 p-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-800">
            <span class="inline-flex items-center gap-2">
                <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-green-100">💳</span>
                Price &amp; Payment
            </span>
        </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start md:items-stretch">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
            <div class="flex items-center gap-6 text-sm">
                <label class="inline-flex items-center gap-2">
                    <input type="radio" name="payment_method" value="fpx" {{ old('payment_method','fpx') === 'fpx' ? 'checked' : '' }} required>
                    <span>FPX (Online Banking)</span>
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="radio" name="payment_method" value="card" {{ old('payment_method') === 'card' ? 'checked' : '' }}>
                    <span>Credit / Debit Card</span>
                </label>
            </div>

            {{-- FPX extra (required) --}}
            <div id="fpxFields" class="mt-4">
                <label class="block text-sm text-gray-700 mb-1">Select Bank <span class="text-red-500">*</span></label>
                <select name="bank" id="bank" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-pink-400 focus:ring-pink-400" required>
                    <option value="">— Choose a bank —</option>
                    <option value="maybank">Maybank</option>
                    <option value="cimb">CIMB Bank</option>
                    <option value="rhb">RHB Bank</option>
                    <option value="public">Public Bank</option>
                    <option value="hongleong">Hong Leong Bank</option>
                    <option value="ambank">AmBank</option>
                    <option value="bankislam">Bank Islam</option>
                    <option value="bsn">Bank Simpanan Nasional (BSN)</option>
                </select>
            </div>

            {{-- Card fields (all required) --}}
            <div id="cardFields" class="mt-4 hidden">
                <label class="block text-sm text-gray-700 mb-1">Cardholder Name <span class="text-red-500">*</span></label>
                <input type="text" name="card_name" id="card_name" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-pink-400 focus:ring-pink-400 placeholder-gray-400" placeholder="John Doe" required>

                <label class="block text-sm text-gray-700 mt-3 mb-1">Card Number <span class="text-red-500">*</span></label>
                <input type="text" name="card_number" id="card_number" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-pink-400 focus:ring-pink-400 placeholder-gray-400" placeholder="4111 1111 1111 1111" required maxlength="19">

                <div class="grid grid-cols-2 gap-4 mt-3">
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Expiry Date <span class="text-red-500">*</span></label>
                        <input type="text" name="card_expiry" id="card_expiry" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-pink-400 focus:ring-pink-400 placeholder-gray-400" placeholder="MM/YY" required maxlength="5">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">CVV <span class="text-red-500">*</span></label>
                        <input type="text" name="card_ccv" id="card_ccv" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-pink-400 focus:ring-pink-400 placeholder-gray-400" placeholder="123" required maxlength="4">
                    </div>
                </div>
            </div>
        </div>

        <div class="md:pl-6 flex">
            <div class="ml-auto flex flex-col justify-end text-right w-full">
                <div>
                    <div class="text-xs text-gray-500">Amount due</div>
                    <div class="text-2xl font-semibold text-gray-900" data-price-box>
                        <span data-amount-text>{{ $currency }} {{ number_format((float) ($displayPrice ?? 0), 2) }}</span>
                    </div>
                    @if($priceNote)
                        <div class="text-xs text-gray-500 mt-1">{{ $priceNote }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fpxFields = document.getElementById('fpxFields');
    const cardFields = document.getElementById('cardFields');
    const bankSelect = document.getElementById('bank');
    const cardName = document.getElementById('card_name');
    const cardNumber = document.getElementById('card_number');
    const cardExpiry = document.getElementById('card_expiry');
    const cardCcv = document.getElementById('card_ccv');

    // Handle payment method switching
    function togglePaymentFields() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked')?.value || 'fpx';
        
        if (selectedMethod === 'card') {
            // Show card fields and make them required
            cardFields.classList.remove('hidden');
            fpxFields.classList.add('hidden');
            
            // Make card fields required
            if (cardName) cardName.required = true;
            if (cardNumber) cardNumber.required = true;
            if (cardExpiry) cardExpiry.required = true;
            if (cardCcv) cardCcv.required = true;
            
            // Make bank field optional
            if (bankSelect) bankSelect.required = false;
        } else {
            // Show FPX fields and make bank required
            fpxFields.classList.remove('hidden');
            cardFields.classList.add('hidden');
            
            // Make card fields optional
            if (cardName) cardName.required = false;
            if (cardNumber) cardNumber.required = false;
            if (cardExpiry) cardExpiry.required = false;
            if (cardCcv) cardCcv.required = false;
            
            // Make bank field required
            if (bankSelect) bankSelect.required = true;
        }
    }

    // Listen to payment method changes
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', togglePaymentFields);
    });

    // Initialize on page load
    togglePaymentFields();

    // Card number formatting
    if (cardNumber) {
        cardNumber.addEventListener('input', function (e) {
            let val = e.target.value.replace(/[^0-9]/g, '');
            val = val.replace(/(.{4})/g, '$1 ').trim();
            if (val.length > 19) val = val.substring(0, 19);
            e.target.value = val;
        });
    }

    // Expiry date formatting
    if (cardExpiry) {
        cardExpiry.addEventListener('input', function (e) {
            let val = e.target.value.replace(/[^0-9]/g, '');
            if (val.length >= 3) {
                val = val.substring(0,2) + '/' + val.substring(2,4);
            }
            e.target.value = val;
        });
    }

    // CVV numeric only
    if (cardCcv) {
        cardCcv.addEventListener('input', function (e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });
    }

    // Form validation before submit
    const form = document.querySelector('form[action*="bookings.store"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            const startAt = document.getElementById('start_at');
            const staffId = document.getElementById('staff_id');
            const selectedMethod = document.querySelector('input[name="payment_method"]:checked')?.value;

            // Check if schedule is selected
            if (!startAt || !startAt.value) {
                e.preventDefault();
                alert('Please select a date and time for your booking.');
                return false;
            }

            // Check if staff is selected for service/package bookings
            if (staffId && staffId.hasAttribute('required') && !staffId.value) {
                e.preventDefault();
                alert('Please select an available staff member.');
                return false;
            }

            // Validate payment fields based on selected method
            if (selectedMethod === 'card') {
                if (!cardName?.value || !cardNumber?.value || !cardExpiry?.value || !cardCcv?.value) {
                    e.preventDefault();
                    alert('Please fill in all credit card details.');
                    return false;
                }

                // Basic card validation
                const cardNum = cardNumber.value.replace(/\s/g, '');
                if (cardNum.length < 13 || cardNum.length > 19) {
                    e.preventDefault();
                    alert('Please enter a valid card number.');
                    return false;
                }

                // Expiry validation
                const expiry = cardExpiry.value;
                if (!/^\d{2}\/\d{2}$/.test(expiry)) {
                    e.preventDefault();
                    alert('Please enter a valid expiry date (MM/YY).');
                    return false;
                }

                // CVV validation
                if (cardCcv.value.length < 3 || cardCcv.value.length > 4) {
                    e.preventDefault();
                    alert('Please enter a valid CVV (3-4 digits).');
                    return false;
                }
            } else {
                // FPX validation
                if (!bankSelect?.value) {
                    e.preventDefault();
                    alert('Please select a bank for online banking payment.');
                    return false;
                }
            }
        });
    }
});
</script>