<?php

namespace App\Filament\Resources\PackageTypeResource\Pages;

use App\Filament\Resources\PackageTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPackageType extends EditRecord
{
    protected static string $resource = PackageTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
