<?php

namespace App\Filament\Resources\PlatformWalletResource\Pages;

use App\Filament\Resources\PlatformWalletResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePlatformWallet extends CreateRecord
{
    protected static string $resource = PlatformWalletResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['balance'] = 0.00;
        $data['currency'] = 'MYR';
        
        return $data;
    }
}