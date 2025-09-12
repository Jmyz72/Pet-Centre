{{-- Price & Payment --}}
@php
    $currency = 'RM';
    $displayPrice = null;
    $priceNote = null;

    if ($bookingType === 'service' && !empty($context['service'])) {
        $displayPrice = $context['service']->price ?? 0;
    } elseif ($bookingType === 'package' && !empty($context['package'])) {
        $displayPrice = $context['package']->price ?? 0;
        $priceNote = 'Final price may vary based on your pet (type/size/breed).';
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

            {{-- FPX extra (optional) --}}
            <div id="fpxFields" class="mt-4">
                <label class="block text-sm text-gray-700 mb-1">Select Bank</label>
                <select name="bank" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-pink-400 focus:ring-pink-400">
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

            {{-- Card fields (mock) --}}
            <div id="cardFields" class="mt-4 hidden">
                <label class="block text-sm text-gray-700 mb-1">Cardholder Name</label>
                <input type="text" name="card_name" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-pink-400 focus:ring-pink-400 placeholder-gray-400" placeholder="John Doe">

                <label class="block text-sm text-gray-700 mt-3 mb-1">Card Number</label>
                <input type="text" name="card_number" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-pink-400 focus:ring-pink-400 placeholder-gray-400" placeholder="4111 1111 1111 1111">

                <div class="grid grid-cols-2 gap-4 mt-3">
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Expiry Date</label>
                        <input type="text" name="card_expiry" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-pink-400 focus:ring-pink-400 placeholder-gray-400" placeholder="MM/YY">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">CCV</label>
                        <input type="text" name="card_ccv" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-pink-400 focus:ring-pink-400 placeholder-gray-400" placeholder="123">
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
    const expiryInput = document.querySelector('input[name="card_expiry"]');
    if (expiryInput) {
        expiryInput.addEventListener('input', function (e) {
            let val = e.target.value.replace(/[^0-9]/g, '');
            if (val.length >= 3) {
                val = val.substring(0,2) + '/' + val.substring(2,4);
            }
            e.target.value = val;
        });
    }
});
</script>