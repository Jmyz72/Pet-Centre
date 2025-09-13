<?php

namespace App\Filament\Merchant\Resources\WalletTransactionResource\Pages;

use App\Filament\Merchant\Resources\WalletTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWalletTransaction extends CreateRecord
{
    protected static string $resource = WalletTransactionResource::class;
}
