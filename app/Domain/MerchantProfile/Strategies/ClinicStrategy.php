<?php

namespace App\Domain\MerchantProfile\Strategies;

use App\Domain\MerchantProfile\Contracts\MerchantRoleStrategy;
use App\Models\MerchantProfile;
use Illuminate\Support\Facades\Schema;

class ClinicStrategy implements MerchantRoleStrategy
{
    public function handle(MerchantProfile $p): array
    {
        // Load counts for badges
        $p->loadCount(['services']);

        $services = $p->services()
            ->when(Schema::hasColumn('services','is_active'), fn($q) => $q->where('is_active', 1))
            ->with(['serviceType'])
            ->orderBy('name')
            ->get([
                'id',
                'merchant_id',
                'service_type_id',
                'name',
                'description',
                'price',
                'duration_minutes',
                'is_active',
            ]);

        return [
            'profile' => $p,
            'services' => $services,
        ];
    }
}