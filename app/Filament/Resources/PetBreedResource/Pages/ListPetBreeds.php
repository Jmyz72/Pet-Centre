<?php

namespace App\Filament\Resources\PetBreedResource\Pages;

use App\Filament\Resources\PetBreedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPetBreeds extends ListRecords
{
    protected static string $resource = PetBreedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
