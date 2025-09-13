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
    protected function getStats(): array
    {
        $totalMerchants = MerchantProfile::count();
        $activeMerchants = MerchantProfile::whereHas('bookings')->count();
        $totalCustomers = User::whereDoesntHave('merchantProfile')->count();
        $totalBookings = Booking::count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $monthlyRevenue = Payment::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $conversionRate = $totalBookings > 0 ? ($completedBookings / $totalBookings * 100) : 0;
        $totalWalletBalance = MerchantWallet::sum('balance');

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
        ];
    }
}