<?php

namespace App\Filament\Traits;

use App\Models\MerchantProfile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait MerchantScopedResource
{
    protected static function merchantForeignKey(): string
    {
        return 'merchant_id';
    }

    /**
     * Helper to get the current user's merchant_profile id.
     */
    protected static function resolveMerchantProfileId(): ?int
    {
        $userId = Auth::id();
        if (! $userId) {
            return null;
        }

        return optional(Auth::user()->merchantProfile)->id
            ?? MerchantProfile::where('user_id', $userId)->value('id');
    }

    /**
     * Scope the resource list to only the logged-in merchant's records.
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if ($merchantProfileId = static::resolveMerchantProfileId()) {
            $query->where(static::merchantForeignKey(), $merchantProfileId);
        }

        return $query;
    }

    /**
     * Stamp the correct merchant FK on create (write-level safety).
     */
    public static function mutateFormDataBeforeCreate(array $data): array
    {
        if ($merchantProfileId = static::resolveMerchantProfileId()) {
            $data[static::merchantForeignKey()] = $merchantProfileId;
        }

        return $data;
    }
}