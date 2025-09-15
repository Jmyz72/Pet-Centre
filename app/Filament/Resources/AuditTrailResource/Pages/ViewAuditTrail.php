<?php

// The namespace MUST match the folder path EXACTLY.
namespace App\Filament\Resources\AuditTrailResource\Pages;

use App\Filament\Resources\AuditTrailResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

// The class name MUST match the file name.
class ViewAuditTrail extends ViewRecord
{
    protected static string $resource = AuditTrailResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // 1. Get the full Activity model for this page.
        //    The 'causer' and 'subject' relationships are already loaded thanks to our getEloquentQuery() method.
        $activity = $this->getRecord();

        // 2. Manually add the data from our model's accessors to the form's data array.
        //    The form's TextInputs are looking for these exact keys.
        $data['causerName'] = $activity->causer_name;
        $data['causerEmail'] = $activity->causer_email;
        $data['subjectDescription'] = $activity->subject_description;

        // 3. Return the modified data array for the form to use.
        return $data;
    }
}