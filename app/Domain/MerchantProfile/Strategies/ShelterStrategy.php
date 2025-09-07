<?php

namespace App\Domain\MerchantProfile\Strategies;

use App\Domain\MerchantProfile\Contracts\MerchantRoleStrategy;
use App\Models\MerchantProfile;

class ShelterStrategy implements MerchantRoleStrategy
{
    public function handle(MerchantProfile $p): array
    {
        // 1) Header badges
        $p->loadCount(['pets']);

        // 2) Main dataset (unified style: build directly from relation)
        $pets = $p->pets()
            ->select([
                'id', 'merchant_id', 'name', 'status', 'created_at',
                'image', 'sex', 'date_of_birth', 'size_id', 'pet_breed_id',
                'weight_kg', 'vaccinated', 'description', 'adoption_fee', 'adopted_at',
            ])
            ->with(['petType', 'petBreed', 'size'])
            ->latest('created_at')
            ->paginate(12)
            ->withQueryString();

        // 3) Return payload
        return [
            'profile' => $p,
            'pets'    => $pets,
        ];
    }
}