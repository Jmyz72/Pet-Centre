@extends('layouts.app')

@section('content')
<section class="py-1 bg-white">
    <div class="w-full max-w-xl mx-auto mt-10 mb-8">
        <div class="flex justify-between text-sm text-gray-600 mb-1">
            <span class="text-blue-600 font-semibold">Step 1: Select Type</span>
            <span>Step 2: Fill Details</span>
            <span>Step 3: Submit</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-blue-600 h-2 rounded-full w-1/3 transition-all duration-300"></div>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h1 class="text-3xl font-bold text-blue-600 mb-8">Become a Merchant</h1>
        <p class="text-gray-600 mb-12">Please choose your merchant type to begin your application:</p>

        <form action="{{ route('merchant.apply.form') }}" method="POST" id="merchantForm">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Clinic Card -->
                <label class="border rounded-lg p-6 cursor-pointer hover:shadow-lg transition block" id="clinicCard">
                    <input type="radio" name="merchant_type" value="clinic" class="hidden" required>
                    <div class="text-center">
                        <img src="/images/clinic.png" alt="Clinic" class="mx-auto h-16 mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Clinic</h2>
                        <p class="text-sm text-gray-500">Offer veterinary services and health care for pets.</p>
                    </div>
                </label>

                <!-- Shelter Card -->
                <label class="border rounded-lg p-6 cursor-pointer hover:shadow-lg transition block" id="shelterCard">
                    <input type="radio" name="merchant_type" value="shelter" class="hidden" required>
                    <div class="text-center">
                        <img src="/images/shelter.png" alt="Shelter" class="mx-auto h-16 mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Shelter</h2>
                        <p class="text-sm text-gray-500">Find homes for animals in need of adoption.</p>
                    </div>
                </label>

                <!-- Groomer Card -->
                <label class="border rounded-lg p-6 cursor-pointer hover:shadow-lg transition block" id="groomerCard">
                    <input type="radio" name="merchant_type" value="groomer" class="hidden" required>
                    <div class="text-center">
                        <img src="/images/groomer.png" alt="Groomer" class="mx-auto h-16 mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Groomer</h2>
                        <p class="text-sm text-gray-500">Provide grooming and hygiene services for pets.</p>
                    </div>
                </label>
            </div>

            <!-- NEXT BUTTON -->
            <div id="nextButtonWrapper" class="mt-10 hidden">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Next
                </button>
            </div>
        </form>
    </div>
</section>

<script>
    const cards = document.querySelectorAll('label');
    const nextBtn = document.getElementById('nextButtonWrapper');

    cards.forEach(card => {
        card.addEventListener('click', () => {
            cards.forEach(c => c.classList.remove('border-blue-500', 'ring', 'ring-blue-200'));
            card.classList.add('border-blue-500', 'ring', 'ring-blue-200');
            card.querySelector('input').checked = true;
            nextBtn.classList.remove('hidden');
        });
    });

    // Auto-select card for reapply users (prefill from server)
    const selectedType = @json($selectedType ?? null);
    if (selectedType) {
        const preselectedInput = document.querySelector(`input[name="merchant_type"][value="${selectedType}"]`);
        if (preselectedInput) {
            const parentLabel = preselectedInput.closest('label');
            cards.forEach(c => c.classList.remove('border-blue-500', 'ring', 'ring-blue-200'));
            parentLabel.classList.add('border-blue-500', 'ring', 'ring-blue-200');
            preselectedInput.checked = true;
            nextBtn.classList.remove('hidden');
        }
    }
</script>
@endsection
