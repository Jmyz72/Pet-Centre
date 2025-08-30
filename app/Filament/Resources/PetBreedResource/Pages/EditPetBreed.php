<?php

namespace App\Filament\Resources\PetBreedResource\Pages;

use App\Filament\Resources\PetBreedResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPetBreed extends EditRecord
{
    protected static string $resource = PetBreedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
