<?php

namespace App\Filament\Merchant\Pages;

use App\Filament\Merchant\Widgets\BusinessMetricsWidget;
use App\Filament\Merchant\Widgets\RevenueChartWidget;
use App\Filament\Merchant\Widgets\BookingStatsWidget;
use App\Filament\Merchant\Widgets\BookingsOverviewWidget;
use App\Filament\Merchant\Widgets\StaffPerformanceWidget;
use App\Filament\Merchant\Widgets\WalletSummaryWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.merchant.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            BusinessMetricsWidget::class,
            RevenueChartWidget::class,
            BookingStatsWidget::class,
            WalletSummaryWidget::class,
            BookingsOverviewWidget::class,
            StaffPerformanceWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return 4;
    }
}