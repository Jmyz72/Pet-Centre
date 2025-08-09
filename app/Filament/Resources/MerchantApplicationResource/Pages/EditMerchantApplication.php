<?php

namespace App\Filament\Resources\MerchantApplicationResource\Pages;

use App\Filament\Resources\MerchantApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMerchantApplication extends EditRecord
{
    protected static string $resource = MerchantApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
