<?php

namespace App\Filament\Resources\PetTypeResource\Pages;

use App\Filament\Resources\PetTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPetType extends EditRecord
{
    protected static string $resource = PetTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
