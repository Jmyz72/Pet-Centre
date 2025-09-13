<?php

namespace App\Filament\Resources\CustomerPetResource\Pages;

use App\Filament\Resources\CustomerPetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomerPets extends ListRecords
{
    protected static string $resource = CustomerPetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
