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
            // Key metrics overview - full width
            PlatformOverviewWidget::class,

            // Charts section - 2 columns
            BookingsChartWidget::class,

            // Performance metrics - 2 columns
            TopMerchantsWidget::class,

            // Activity feed - full width
            RecentActivitiesWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'default' => 1,
            'md' => 2,
            'xl' => 4,
        ];
    }

    public function getWidgetData(): array
    {
        return [
            'PlatformOverviewWidget' => [
                'columnSpan' => [
                    'default' => 1,
                    'md' => 2,
                    'xl' => 4,
                ],
            ],
            'BookingsChartWidget' => [
                'columnSpan' => [
                    'default' => 1,
                    'md' => 1,
                    'xl' => 2,
                ],
            ],
            'RevenueDistributionWidget' => [
                'columnSpan' => [
                    'default' => 1,
                    'md' => 1,
                    'xl' => 2,
                ],
            ],
            'TopMerchantsWidget' => [
                'columnSpan' => [
                    'default' => 1,
                    'md' => 1,
                    'xl' => 2,
                ],
            ],
            'NewUsersThisWeek' => [
                'columnSpan' => [
                    'default' => 1,
                    'md' => 1,
                    'xl' => 2,
                ],
            ],
            'RecentActivitiesWidget' => [
                'columnSpan' => [
                    'default' => 1,
                    'md' => 2,
                    'xl' => 4,
                ],
            ],
        ];
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