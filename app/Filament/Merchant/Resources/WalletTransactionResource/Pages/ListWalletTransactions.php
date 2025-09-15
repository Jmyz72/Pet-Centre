<?php

namespace App\Filament\Merchant\Resources\WalletTransactionResource\Pages;

use App\Filament\Merchant\Resources\WalletTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWalletTransactions extends ListRecords
{
    protected static string $resource = WalletTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action - transactions are generated automatically, not manually created
        ];
    }
}
