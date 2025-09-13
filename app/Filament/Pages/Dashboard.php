<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\PlatformOverviewWidget;
use App\Filament\Widgets\BookingsChartWidget;
use App\Filament\Widgets\RevenueDistributionWidget;
use App\Filament\Widgets\TopMerchantsWidget;
use App\Filament\Widgets\RecentActivitiesWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public function getWidgets(): array
    {
        return [
            PlatformOverviewWidget::class,
            BookingsChartWidget::class,
            RevenueDistributionWidget::class,
            TopMerchantsWidget::class,
            RecentActivitiesWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return 4;
    }

    public function getTitle(): string
    {
        return 'Platform Dashboard';
    }

    public function getSubheading(): ?string
    {
        return 'Complete overview of your pet services marketplace';
    }
}