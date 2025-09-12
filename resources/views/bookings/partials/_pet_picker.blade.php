

{{-- Pet picker (Card style) for service/package) --}}
@if(in_array($bookingType, ['service','package'], true))
<section class="bg-white rounded-xl border border-gray-200 p-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-800"><span class="inline-flex items-center gap-2"><span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-pink-100">🐶</span>Select Your Pet</span></h2>
        <p class="text-xs text-gray-500">Required for clinic services and groomer packages.</p>
    </div>

    @php $selectedPetId = old('customer_pet_id'); @endphp

    @if(($pets ?? collect())->isEmpty())
        <div class="text-sm text-gray-500">
            You don’t have any pets yet. Please add a pet in your profile first.
        </div>
    @else
        <input type="hidden" name="customer_pet_id" id="customer_pet_id" value="{{ $selectedPetId }}">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="petGrid">
            @foreach($pets as $p)
                @php
                    $pid   = $dg($p,'id');
                    $isSel = (string)$selectedPetId === (string)$pid;

                    // Use the normalized absolute URL from controller; else placeholder
                    $photo = $dg($p,'photo_url') ?: 'https://placehold.co/600x400?text=Pet';

                    // Names now come from API (normalized in controller). Provide sensible fallbacks.
                    $typeName  = $dg($p,'type_name');
                    $sizeName  = $dg($p,'size_name') ?: $dg($p,'size_label');
                    $breedName = $dg($p,'breed_name');
                    $breedId   = $dg($p,'pet_breed_id') ?? $dg($p,'breed_id');
                @endphp
                <button type="button"
                        data-pet="{{ $pid }}"
                        data-pet-type="{{ $dg($p,'pet_type_id') }}"
                        data-size-id="{{ $dg($p,'size_id') }}"
                        data-breed-id="{{ $breedId }}"
                        class="group text-left rounded-xl border {{ $isSel ? 'border-indigo-500 ring-2 ring-indigo-200' : 'border-gray-200' }} bg-white overflow-hidden hover:shadow-sm transition"
                        onclick="document.getElementById('customer_pet_id').value='{{ $pid }}'; highlightPet('{{ $pid }}')">
                    <div class="aspect-video bg-gray-100 overflow-hidden">
                        <img src="{{ $photo }}" alt="{{ $dg($p,'name','Pet') }} photo"
                             class="w-full h-full object-cover group-hover:scale-[1.02] transition">
                    </div>
                    <div class="p-3">
                        <div class="flex items-center justify-between">
                            <h3 class="font-medium text-gray-900">
                                {{ $dg($p,'name','My Pet') }}
                            </h3>
                            @if($isSel)
                                <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">Selected</span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500 mt-1 space-x-2">
                            <span>Type: {{ $typeName ?? ('#'.$dg($p,'pet_type_id','?')) }}</span>
                            @if($dg($p,'size_id'))
                                <span>• Size: {{ $sizeName ?? ('#'.$dg($p,'size_id')) }}</span>
                            @endif
                            @if($breedId)
                                <span>• Breed: {{ $breedName ?? ('#'.$breedId) }}</span>
                            @endif
                        </div>
                    </div>
                </button>
            @endforeach
        </div>
    @endif
</section>
@endif