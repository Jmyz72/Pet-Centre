<?php

namespace App\Filament\Merchant\Resources\MerchantWalletResource\Pages;

use App\Filament\Merchant\Resources\MerchantWalletResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMerchantWallet extends EditRecord
{
    protected static string $resource = MerchantWalletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
