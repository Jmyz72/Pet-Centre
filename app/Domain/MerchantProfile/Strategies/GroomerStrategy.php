<?php

namespace App\Domain\MerchantProfile\Strategies;

use App\Domain\MerchantProfile\Contracts\MerchantRoleStrategy;
use App\Models\MerchantProfile;
use Illuminate\Support\Facades\Schema;

class GroomerStrategy implements MerchantRoleStrategy
{
    public function handle(MerchantProfile $p): array
    {
        // Load counts for badges
        $p->loadCount(['packages']);

        // Eager load packages with all related data
        $packages = $p->packages()
            ->when(Schema::hasColumn('packages','is_active'), fn($q) => $q->where('is_active', 1))
            ->with([
                'packageTypes',
                'petTypes',
                'packageSizes',
                'petBreeds',
                'variations.petTypePivot.petType',
                'variations.sizePivot.size',
                'variations.breedPivot.breed',
            ])
            ->orderBy('name')
            ->get([
                'id',
                'merchant_id',
                'name',
                'description',
                'price',
                'duration_minutes',
                'is_active'
            ]);

        return [
            'profile' => $p,
            'packages' => $packages,
        ];
    }
}