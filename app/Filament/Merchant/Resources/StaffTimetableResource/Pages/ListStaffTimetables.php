<?php

namespace App\Filament\Merchant\Resources\StaffTimetableResource\Pages;

use App\Filament\Merchant\Resources\StaffTimetableResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStaffTimetables extends ListRecords
{
    protected static string $resource = StaffTimetableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('refresh')
                ->label('Refresh')
                ->icon('heroicon-o-arrow-path')
                ->action(fn () => $this->redirect(request()->header('Referer'))),
                
            Actions\Action::make('view_detailed_timetable')
                ->label('Detailed Timetable View')
                ->icon('heroicon-o-calendar-days')
                ->url(route('filament.merchant.pages.customer-staff-timetable'))
                ->color('primary'),
        ];
    }

    public function getTitle(): string
    {
        return 'Staff Timetable Overview';
    }

    public function getHeading(): string
    {
        return 'Staff Availability Overview';
    }

    public function getSubheading(): ?string
    {
        return 'Quick overview of your staff availability and schedules. For detailed weekly view, click "Detailed Timetable View".';
    }
}
