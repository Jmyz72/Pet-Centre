<?php

namespace App\Filament\Resources\PlatformWalletTransactionResource\Pages;

use App\Filament\Resources\PlatformWalletTransactionResource;
use Filament\Resources\Pages\ListRecords;

class ListPlatformWalletTransactions extends ListRecords
{
    protected static string $resource = PlatformWalletTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action - transactions are created automatically
        ];
    }
}