<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;

class RevenueDistributionWidget extends ChartWidget
{
    protected static ?string $heading = 'Revenue by Payment Provider';
    
    protected static string $color = 'success';
    
    protected int | string | array $columnSpan = 2;

    protected function getData(): array
    {
        $revenueData = $this->getRevenueData();
        
        return [
            'datasets' => [
                [
                    'data' => array_values($revenueData),
                    'backgroundColor' => [
                        'rgb(34, 197, 94)',   // FPX - Green
                        'rgb(59, 130, 246)',  // ToyyibPay - Blue
                        'rgb(245, 158, 11)',  // Stripe - Yellow
                        'rgb(168, 85, 247)',  // Others - Purple
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => array_keys($revenueData),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    private function getRevenueData(): array
    {
        $payments = Payment::where('status', 'completed')
            ->selectRaw('provider, SUM(amount) as total')
            ->groupBy('provider')
            ->pluck('total', 'provider')
            ->toArray();

        return [
            'FPX' => $payments['fpx'] ?? 0,
            'ToyyibPay' => $payments['toyyibpay'] ?? 0,
            'Stripe' => $payments['stripe'] ?? 0,
            'Others' => array_sum(array_diff_key($payments, array_flip(['fpx', 'toyyibpay', 'stripe']))),
        ];
    }

    public function getDescription(): ?string
    {
        return 'Revenue breakdown by payment providers';
    }
}