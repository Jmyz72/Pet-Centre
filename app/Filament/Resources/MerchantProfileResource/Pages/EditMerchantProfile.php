<?php

namespace App\Filament\Resources\MerchantProfileResource\Pages;

use App\Filament\Resources\MerchantProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMerchantProfile extends EditRecord
{
    protected static string $resource = MerchantProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
