<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class NewUsersThisWeek extends BaseWidget
{
    protected function getCards(): array
    {
        $count = User::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek(),
        ])->count();

        return [
            Card::make('New Users This Week', $count)
                ->description('Users who registered from Mon–Sun')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success'),
        ];
    }
}
