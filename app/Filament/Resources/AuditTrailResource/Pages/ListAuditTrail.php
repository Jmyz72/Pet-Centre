<?php

// The namespace MUST match the folder path EXACTLY.
namespace App\Filament\Resources\AuditTrailResource\Pages;

use App\Filament\Resources\AuditTrailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

// The class name MUST match the file name.
class ListAuditTrails extends ListRecords
{
    protected static string $resource = AuditTrailResource::class;
}