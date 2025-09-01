<?php

namespace App\Filament\Merchant\Resources\PackageResource\Pages;

use App\Filament\Merchant\Resources\PackageResource;
use Filament\Resources\Pages\ViewRecord;

class ViewPackage extends ViewRecord
{
    protected static string $resource = PackageResource::class;

    // Optional: customize the page title
    public function getTitle(): string
    {
        return 'View Package';
    }
}