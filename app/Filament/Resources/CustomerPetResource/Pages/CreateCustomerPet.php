<?php

namespace App\Filament\Resources\CustomerPetResource\Pages;

use App\Filament\Resources\CustomerPetResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerPet extends CreateRecord
{
    protected static string $resource = CustomerPetResource::class;
}
