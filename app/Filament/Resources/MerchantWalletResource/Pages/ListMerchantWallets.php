<?php

namespace App\Filament\Resources\MerchantWalletResource\Pages;

use App\Filament\Resources\MerchantWalletResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMerchantWallets extends ListRecords
{
    protected static string $resource = MerchantWalletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
