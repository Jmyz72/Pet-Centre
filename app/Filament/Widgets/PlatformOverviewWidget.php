<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\MerchantProfile;
use App\Models\User;
use App\Models\Payment;
use App\Models\MerchantWallet;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class PlatformOverviewWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $totalMerchants = MerchantProfile::count();
        $activeMerchants = MerchantProfile::whereHas('bookings')->count();
        $totalCustomers = Booking::distinct('customer_id')->count('customer_id');
        $totalBookings = Booking::count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $totalRevenue = Payment::where('status', 'succeeded')->sum('amount');
        $monthlyRevenue = Payment::where('status', 'succeeded')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $conversionRate = $totalBookings > 0 ? ($completedBookings / $totalBookings * 100) : 0;
        $totalWalletBalance = MerchantWallet::sum('balance');

        $thisWeekStart = Carbon::now()->startOfWeek();
        $thisWeekEnd = Carbon::now()->endOfWeek();
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();

        $thisWeekCount = User::whereBetween('created_at', [$thisWeekStart, $thisWeekEnd])->count();
        $lastWeekCount = User::whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->count();

        $percentageChange = $lastWeekCount > 0
            ? (($thisWeekCount - $lastWeekCount) / $lastWeekCount) * 100
            : ($thisWeekCount > 0 ? 100 : 0);

        return [
            Stat::make('Total Merchants', $totalMerchants)
                ->description('Registered merchants on platform')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('success'),

            Stat::make('Total Customers', $totalCustomers)
                ->description('Active customers')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Total Bookings', $totalBookings)
                ->description("Completed: {$completedBookings}")
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning'),

            Stat::make('Platform Revenue', 'RM ' . number_format($totalRevenue, 2))
                ->description('All-time platform revenue')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Monthly Revenue', 'RM ' . number_format($monthlyRevenue, 2))
                ->description('This month\'s revenue')
                ->descriptionIcon('heroicon-m-chart-bar-square')
                ->color('info'),

            Stat::make('Conversion Rate', number_format($conversionRate, 1) . '%')
                ->description('Booking completion rate')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color($conversionRate >= 70 ? 'success' : 'danger'),

            Stat::make('Total Wallet Balance', 'RM ' . number_format($totalWalletBalance, 2))
                ->description('All merchant wallet balances')
                ->descriptionIcon('heroicon-m-wallet')
                ->color('warning'),

            Stat::make('Active Merchants', $activeMerchants)
                ->description('Merchants with bookings')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
            Stat::make('New Users This Week', $thisWeekCount)
                ->description(sprintf(
                    '%s%s from last week',
                    $percentageChange >= 0 ? '+' : '',
                    number_format($percentageChange, 1) . '%'
                ))
                ->descriptionIcon($percentageChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($percentageChange >= 0 ? 'success' : 'danger'),
        ];
    }
}