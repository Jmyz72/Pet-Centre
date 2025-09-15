<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\StaffTimetableResource\Pages;
use App\Models\Staff;
use App\Models\StaffOperatingHour;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class StaffTimetableResource extends Resource
{
    protected static ?string $model = Staff::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    
    protected static ?string $navigationLabel = 'Staff Timetable (Resource)';
    
    protected static ?string $navigationGroup = 'Staff Management';
    
    protected static ?int $navigationSort = 21;

    protected static ?string $modelLabel = 'Staff Timetable';

    protected static ?string $pluralModelLabel = 'Staff Timetables';

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        $merchantProfile = $user?->merchantProfile;
        
        // Temporarily always show for debugging
        return true;
        
        // Show for clinic and groomer merchants only
        // return $merchantProfile && in_array($merchantProfile->role, ['clinic', 'groomer']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // This resource is primarily for viewing, not editing
                Forms\Components\TextInput::make('name')
                    ->disabled(),
                Forms\Components\TextInput::make('role')
                    ->disabled(),
                Forms\Components\TextInput::make('status')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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

                TextColumn::make('current_week_summary')
                    ->label('This Week\'s Schedule')
                    ->html()
                    ->getStateUsing(function ($record) {
                        return static::getCurrentWeekSummary($record);
                    })
                    ->wrap(),

                TextColumn::make('today_availability')
                    ->label('Today')
                    ->html()
                    ->getStateUsing(function ($record) {
                        return static::getTodayAvailability($record);
                    }),

                TextColumn::make('tomorrow_availability')
                    ->label('Tomorrow')
                    ->html()
                    ->getStateUsing(function ($record) {
                        return static::getTomorrowAvailability($record);
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'groomer' => 'Groomer',
                        'clinic' => 'Clinic',
                        'shelter' => 'Shelter',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('active'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        $merchantProfile = $user->merchantProfile;

        if (!$merchantProfile) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->where('merchant_id', $merchantProfile->id)
            ->with(['operatingHours', 'schedules']);
    }

    protected static function getCurrentWeekSummary(Staff $staff): string
    {
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();
        
        $daysWithSchedule = [];
        
        for ($i = 0; $i < 7; $i++) {
            $date = $weekStart->copy()->addDays($i);
            $dayOfWeek = $date->dayOfWeek === 0 ? 7 : $date->dayOfWeek; // Convert Sunday from 0 to 7
            
            $hasOperatingHours = $staff->operatingHours()
                ->where('day_of_week', $dayOfWeek === 7 ? 0 : $dayOfWeek)
                ->exists();
                
            if ($hasOperatingHours) {
                $hasBookings = $staff->schedules()
                    ->whereDate('start_at', $date->format('Y-m-d'))
                    ->exists();
                    
                $dayName = $date->format('D');
                $status = $hasBookings ? '🔒' : '✅';
                $daysWithSchedule[] = $status . ' ' . $dayName;
            }
        }
        
        return implode('<br>', $daysWithSchedule) ?: '<span class="text-gray-400">No schedule</span>';
    }

    protected static function getTodayAvailability(Staff $staff): string
    {
        return static::getDayAvailability($staff, now());
    }

    protected static function getTomorrowAvailability(Staff $staff): string
    {
        return static::getDayAvailability($staff, now()->addDay());
    }

    protected static function getDayAvailability(Staff $staff, Carbon $date): string
    {
        $dayOfWeek = $date->dayOfWeek === 0 ? 0 : $date->dayOfWeek; // Keep Sunday as 0
        
        $operatingHours = $staff->operatingHours()
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time')
            ->get();

        if ($operatingHours->isEmpty()) {
            return '<span class="text-gray-400 text-xs">Closed</span>';
        }

        $schedules = $staff->schedules()
            ->whereDate('start_at', $date->format('Y-m-d'))
            ->orderBy('start_at')
            ->get();

        $html = '';
        
        // Show operating hours
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

            $html .= "<div class='text-xs {$color}'>{$startTime}-{$endTime}</div>";
        }

        // Show bookings
        if ($schedules->isNotEmpty()) {
            $html .= "<div class='mt-1 pt-1 border-t border-gray-200'>";
            foreach ($schedules as $schedule) {
                $startTime = $schedule->start_at->format('H:i');
                $endTime = $schedule->end_at->format('H:i');
                $html .= "<div class='text-xs text-blue-600 font-medium'>🔒 {$startTime}-{$endTime}</div>";
            }
            $html .= "</div>";
        }

        return $html;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStaffTimetables::route('/'),
            'view' => Pages\ViewStaffTimetable::route('/{record}'),
        ];
    }
}
