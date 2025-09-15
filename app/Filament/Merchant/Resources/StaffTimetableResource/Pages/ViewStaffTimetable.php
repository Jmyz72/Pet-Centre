<?php

namespace App\Filament\Merchant\Resources\StaffTimetableResource\Pages;

use App\Filament\Merchant\Resources\StaffTimetableResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Carbon\Carbon;

class ViewStaffTimetable extends ViewRecord
{
    protected static string $resource = StaffTimetableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back to List')
                ->icon('heroicon-o-arrow-left')
                ->url(route('filament.merchant.resources.staff-timetables.index')),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Staff Information')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Name'),
                                TextEntry::make('role')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'groomer' => 'success',
                                        'clinic' => 'info',
                                        'shelter' => 'warning',
                                        default => 'gray',
                                    }),
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'active' => 'success',
                                        'inactive' => 'danger',
                                        default => 'gray',
                                    }),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('email'),
                                TextEntry::make('phone'),
                            ]),
                    ]),

                Section::make('Weekly Operating Hours')
                    ->schema([
                        TextEntry::make('weekly_schedule')
                            ->label('')
                            ->html()
                            ->getStateUsing(function ($record) {
                                return $this->getWeeklyScheduleDisplay($record);
                            }),
                    ]),

                Section::make('Current Week Schedule')
                    ->schema([
                        TextEntry::make('current_week_detailed')
                            ->label('')
                            ->html()
                            ->getStateUsing(function ($record) {
                                return $this->getCurrentWeekDetailedSchedule($record);
                            }),
                    ]),
            ]);
    }

    protected function getWeeklyScheduleDisplay($staff): string
    {
        $days = [
            1 => 'Monday',
            2 => 'Tuesday', 
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            0 => 'Sunday'
        ];

        $html = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">';

        foreach ($days as $dayNum => $dayName) {
            $operatingHours = $staff->operatingHours()
                ->where('day_of_week', $dayNum)
                ->orderBy('start_time')
                ->get();

            $html .= '<div class="border rounded-lg p-3">';
            $html .= '<h4 class="font-medium text-gray-900 mb-2">' . $dayName . '</h4>';

            if ($operatingHours->isEmpty()) {
                $html .= '<p class="text-gray-500 text-sm">Closed</p>';
            } else {
                foreach ($operatingHours as $hour) {
                    $startTime = Carbon::parse($hour->start_time)->format('H:i');
                    $endTime = Carbon::parse($hour->end_time)->format('H:i');
                    
                    $blockType = $hour->block_type ?? 'available';
                    $label = $hour->label ?? '';
                    
                    $color = match($blockType) {
                        'available' => 'text-green-600',
                        'break' => 'text-yellow-600',
                        'unavailable' => 'text-red-600',
                        default => 'text-gray-600'
                    };

                    $html .= "<div class='text-sm {$color} mb-1'>";
                    $html .= "{$startTime} - {$endTime}";
                    if ($label) {
                        $html .= " <span class='text-xs italic'>({$label})</span>";
                    }
                    $html .= "</div>";
                }
            }

            $html .= '</div>';
        }

        $html .= '</div>';
        return $html;
    }

    protected function getCurrentWeekDetailedSchedule($staff): string
    {
        $weekStart = now()->startOfWeek();
        $html = '<div class="space-y-4">';

        for ($i = 0; $i < 7; $i++) {
            $date = $weekStart->copy()->addDays($i);
            $dayOfWeek = $date->dayOfWeek === 0 ? 0 : $date->dayOfWeek;
            $isToday = $date->isToday();

            $html .= '<div class="border rounded-lg p-4 ' . ($isToday ? 'bg-blue-50 border-blue-200' : '') . '">';
            $html .= '<h4 class="font-medium text-gray-900 mb-2">';
            $html .= $date->format('l, M j, Y');
            if ($isToday) {
                $html .= ' <span class="text-blue-600 text-sm">(Today)</span>';
            }
            $html .= '</h4>';

            // Operating Hours
            $operatingHours = $staff->operatingHours()
                ->where('day_of_week', $dayOfWeek)
                ->orderBy('start_time')
                ->get();

            if ($operatingHours->isEmpty()) {
                $html .= '<p class="text-gray-500 text-sm">Closed</p>';
            } else {
                $html .= '<div class="mb-3">';
                $html .= '<h5 class="text-sm font-medium text-gray-700 mb-1">Operating Hours:</h5>';
                foreach ($operatingHours as $hour) {
                    $startTime = Carbon::parse($hour->start_time)->format('H:i');
                    $endTime = Carbon::parse($hour->end_time)->format('H:i');
                    
                    $blockType = $hour->block_type ?? 'available';
                    $color = match($blockType) {
                        'available' => 'text-green-600',
                        'break' => 'text-yellow-600',
                        'unavailable' => 'text-red-600',
                        default => 'text-gray-600'
                    };

                    $html .= "<div class='text-sm {$color}'>{$startTime} - {$endTime}</div>";
                }
                $html .= '</div>';

                // Bookings/Schedules
                $schedules = $staff->schedules()
                    ->whereDate('start_at', $date->format('Y-m-d'))
                    ->orderBy('start_at')
                    ->get();

                if ($schedules->isNotEmpty()) {
                    $html .= '<div>';
                    $html .= '<h5 class="text-sm font-medium text-gray-700 mb-1">Bookings:</h5>';
                    foreach ($schedules as $schedule) {
                        $startTime = $schedule->start_at->format('H:i');
                        $endTime = $schedule->end_at->format('H:i');
                        $html .= "<div class='text-sm text-blue-600 font-medium'>🔒 {$startTime} - {$endTime}";
                        if ($schedule->booking_id) {
                            $html .= " (Booking #" . $schedule->booking_id . ")";
                        }
                        $html .= "</div>";
                    }
                    $html .= '</div>';
                } else {
                    $html .= '<p class="text-sm text-gray-500">No bookings scheduled</p>';
                }
            }

            $html .= '</div>';
        }

        $html .= '</div>';
        return $html;
    }
}
