<?php

namespace App\Domain\MerchantProfile;

use App\Domain\MerchantProfile\Contracts\MerchantRoleStrategy;
use App\Domain\MerchantProfile\Strategies\{ShelterStrategy, GroomerStrategy, ClinicStrategy};
use App\Models\MerchantProfile;
use InvalidArgumentException;

class RoleStrategyResolver
{
    /**
     * Resolve the correct strategy implementation based on merchant role.
     */
    public function for(MerchantProfile $profile): MerchantRoleStrategy
    {
        return match ($profile->role) {
            'shelter' => new ShelterStrategy(),
            'groomer' => new GroomerStrategy(),
            'clinic'  => new ClinicStrategy(),
            default   => throw new InvalidArgumentException('Unknown role: ' . $profile->role),
        };
    }
}
