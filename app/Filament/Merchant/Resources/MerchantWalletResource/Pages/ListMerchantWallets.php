<?php

namespace App\Filament\Merchant\Resources\MerchantWalletResource\Pages;

use App\Filament\Merchant\Resources\MerchantWalletResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMerchantWallets extends ListRecords
{
    protected static string $resource = MerchantWalletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action - wallets are automatically created, not manually added
        ];
    }
}
