<?php

namespace App\Filament\Merchant\Resources\PetResource\Pages;

use App\Filament\Merchant\Resources\PetResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePet extends CreateRecord
{
    protected static string $resource = PetResource::class;
}
