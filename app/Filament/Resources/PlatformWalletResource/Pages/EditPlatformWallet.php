<?php

namespace App\Filament\Resources\PlatformWalletResource\Pages;

use App\Filament\Resources\PlatformWalletResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlatformWallet extends EditRecord
{
    protected static string $resource = PlatformWalletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}