<?php

namespace App\Filament\Merchant\Pages;

use App\Models\Staff;
use App\Models\StaffOperatingHour;
use App\Models\Schedule;
use App\Models\MerchantProfile;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class CustomerStaffTimetable extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Staff Timetable';
    protected static ?string $navigationGroup = 'Staff Management';
    protected static ?int $navigationSort = 20;
    protected static ?string $title = 'Staff Availability Timetable';

    protected static string $view = 'filament.merchant.pages.customer-staff-timetable';

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        $merchantProfile = $user?->merchantProfile;
        
        // Debug logging
        \Log::info('Staff Timetable Navigation Check', [
            'user_id' => $user?->id,
            'has_merchant_profile' => $merchantProfile ? 'yes' : 'no',
            'merchant_role' => $merchantProfile?->role,
            'allowed_roles' => ['clinic', 'groomer'],
            'should_show' => $merchantProfile && in_array($merchantProfile->role, ['clinic', 'groomer'])
        ]);
        
        // Temporarily always show for debugging
        return true;
        
        // Show for clinic and groomer merchants only
        // return $merchantProfile && in_array($merchantProfile->role, ['clinic', 'groomer']);
    }

    public $selectedWeek;
    public $weekStart;
    public $weekEnd;

    public function mount(): void
    {
        // Initialize to current week
        $this->selectedWeek = now()->startOfWeek()->format('Y-m-d');
        $this->updateWeekRange();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getStaffQuery())
            ->columns([
                TextColumn::make('name')
                    ->label('Staff Member')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'groomer' => 'success',
                        'clinic' => 'info',
                        'shelter' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('monday_schedule')
                    ->label('Monday')
                    ->html()
                    ->getStateUsing(fn ($record) => $this->getStaffDaySchedule($record, 1)),

                TextColumn::make('tuesday_schedule')
                    ->label('Tuesday')
                    ->html()
                    ->getStateUsing(fn ($record) => $this->getStaffDaySchedule($record, 2)),

                TextColumn::make('wednesday_schedule')
                    ->label('Wednesday')
                    ->html()
                    ->getStateUsing(fn ($record) => $this->getStaffDaySchedule($record, 3)),

                TextColumn::make('thursday_schedule')
                    ->label('Thursday')
                    ->html()
                    ->getStateUsing(fn ($record) => $this->getStaffDaySchedule($record, 4)),

                TextColumn::make('friday_schedule')
                    ->label('Friday')
                    ->html()
                    ->getStateUsing(fn ($record) => $this->getStaffDaySchedule($record, 5)),

                TextColumn::make('saturday_schedule')
                    ->label('Saturday')
                    ->html()
                    ->getStateUsing(fn ($record) => $this->getStaffDaySchedule($record, 6)),

                TextColumn::make('sunday_schedule')
                    ->label('Sunday')
                    ->html()
                    ->getStateUsing(fn ($record) => $this->getStaffDaySchedule($record, 0)),
            ])
            ->filters([
                // You can add filters here if needed
            ])
            ->actions([
                // Actions can be added here
            ])
            ->bulkActions([
                // Bulk actions can be added here
            ]);
    }

    protected function getStaffQuery(): Builder
    {
        $user = auth()->user();
        $merchantProfile = $user->merchantProfile;

        if (!$merchantProfile) {
            return Staff::query()->whereRaw('1 = 0'); // Return empty query
        }

        return Staff::query()
            ->where('merchant_id', $merchantProfile->id)
            ->where('status', 'active')
            ->with(['operatingHours', 'schedules']);
    }

    protected function getStaffDaySchedule(Staff $staff, int $dayOfWeek): string
    {
        // Get the specific date for this day of the week
        $weekStart = Carbon::parse($this->weekStart);
        $targetDate = $weekStart->copy()->addDays($dayOfWeek === 0 ? 6 : $dayOfWeek - 1);

        // Get operating hours for this day
        $operatingHours = $staff->operatingHours()
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time')
            ->get();

        // Get schedules/bookings for this specific date
        $schedules = $staff->schedules()
            ->whereDate('start_at', $targetDate->format('Y-m-d'))
            ->orderBy('start_at')
            ->get();

        $html = '';

        if ($operatingHours->isEmpty()) {
            return '<span class="text-gray-400 text-xs">Closed</span>';
        }

        // Show operating hours
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

            $html .= "<div class='text-xs {$color}'>";
            $html .= "{$startTime}-{$endTime}";
            if ($label) {
                $html .= " <em>({$label})</em>";
            }
            $html .= "</div>";
        }

        // Show bookings/schedules
        if ($schedules->isNotEmpty()) {
            $html .= "<div class='mt-1 pt-1 border-t border-gray-200'>";
            foreach ($schedules as $schedule) {
                $startTime = $schedule->start_at->format('H:i');
                $endTime = $schedule->end_at->format('H:i');
                
                $blockType = $schedule->block_type ?? 'booking';
                $color = match($blockType) {
                    'booking' => 'text-blue-600 font-medium',
                    'blocked' => 'text-red-600',
                    default => 'text-gray-600'
                };

                $html .= "<div class='text-xs {$color}'>";
                $html .= "🔒 {$startTime}-{$endTime}";
                if ($schedule->booking_id) {
                    $html .= " (Booked)";
                }
                $html .= "</div>";
            }
            $html .= "</div>";
        }

        return $html ?: '<span class="text-gray-400 text-xs">No schedule</span>';
    }

    public function previousWeek(): void
    {
        $this->selectedWeek = Carbon::parse($this->selectedWeek)->subWeek()->format('Y-m-d');
        $this->updateWeekRange();
    }

    public function nextWeek(): void
    {
        $this->selectedWeek = Carbon::parse($this->selectedWeek)->addWeek()->format('Y-m-d');
        $this->updateWeekRange();
    }

    public function currentWeek(): void
    {
        $this->selectedWeek = now()->startOfWeek()->format('Y-m-d');
        $this->updateWeekRange();
    }

    protected function updateWeekRange(): void
    {
        $weekStart = Carbon::parse($this->selectedWeek)->startOfWeek();
        $this->weekStart = $weekStart->format('Y-m-d');
        $this->weekEnd = $weekStart->copy()->endOfWeek()->format('Y-m-d');
    }

    public function getWeekDisplayProperty(): string
    {
        $start = Carbon::parse($this->weekStart);
        $end = Carbon::parse($this->weekEnd);
        
        return $start->format('M j') . ' - ' . $end->format('M j, Y');
    }
}
