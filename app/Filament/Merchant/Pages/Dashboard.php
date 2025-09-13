<?php

namespace App\Filament\Merchant\Pages;

use App\Filament\Merchant\Widgets\BusinessMetricsWidget;
use App\Filament\Merchant\Widgets\BookingsOverviewWidget;
use App\Filament\Merchant\Widgets\StaffPerformanceWidget;
use App\Filament\Merchant\Widgets\WalletSummaryWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.merchant.pages.dashboard';

    public function getWidgetData(): array
    {
        return [
            'BusinessMetricsWidget' => [
                'columnSpan' => [
                    'default' => 1,
                    'md' => 2,
                    'xl' => 3,
                ],
            ],
            'WalletSummaryWidget' => [
                'columnSpan' => [
                    'default' => 1,
                    'md' => 1,
                    'xl' => 1,
                ],
            ],
            'BookingsOverviewWidget' => [
                'columnSpan' => [
                    'default' => 1,
                    'md' => 1,
                    'xl' => 2,
                ],
            ],
            'StaffPerformanceWidget' => [
                'columnSpan' => [
                    'default' => 1,
                    'md' => 2,
                    'xl' => 3,
                ],
            ],
        ];
    }

    public function getWidgets(): array
    {
        return [
            // Key business metrics - full width
            BusinessMetricsWidget::class,

            // Financial and booking overview - 2 columns
            WalletSummaryWidget::class,
            BookingsOverviewWidget::class,

            // Performance tracking - full width
            StaffPerformanceWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'default' => 1,
            'md' => 2,
            'xl' => 3,
        ];
    }

    public function getTitle(): string
    {
        $merchantName = auth()->user()->merchantProfile?->name ?? 'Your Business';
        return $merchantName . ' Dashboard';
    }

    public function getSubheading(): ?string
    {
        return 'Monitor your business performance and manage operations';
    }


}