<?php

namespace App\Filament\Resources\MerchantProfileResource\Pages;

use App\Filament\Resources\MerchantProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMerchantProfiles extends ListRecords
{
    protected static string $resource = MerchantProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
