<?php

namespace App\Domain\MerchantProfile\Contracts;

use App\Models\MerchantProfile;

interface MerchantRoleStrategy
{
    /**
     * Handle role-specific data preparation for merchant profile.
     *
     * @param  MerchantProfile  $profile
     * @return array
     */
    public function handle(MerchantProfile $profile): array;
}