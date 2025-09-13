<?php

namespace App\Filament\Merchant\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Http;

class RevenueChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Revenue Trends (Last 6 Months)';
    
    protected static string $color = 'success';
    
    protected int | string | array $columnSpan = 2;

    protected function getData(): array
    {
        $revenueData = $this->getRevenueData();
        
        return [
            'datasets' => [
                [
                    'label' => 'Revenue (RM)',
                    'data' => array_column($revenueData, 'amount'),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => array_column($revenueData, 'month'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getRevenueData(): array
    {
        try {
            $merchantId = auth()->user()->merchantProfile?->id;
            
            if (!$merchantId) {
                return $this->getDefaultData();
            }

            $response = Http::get("http://localhost:8001/api/merchants/{$merchantId}/bookings", [
                'status' => 'completed',
                'limit' => 1000
            ]);

            if ($response->successful() && $response->json('ok')) {
                return $this->processRevenueData($response->json('data', []));
            }

            return $this->getDefaultData();
        } catch (\Exception $e) {
            return $this->getDefaultData();
        }
    }

    private function processRevenueData(array $bookings): array
    {
        $monthlyRevenue = [];
        
        // Generate last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthKey = $month->format('Y-m');
            $monthlyRevenue[$monthKey] = [
                'month' => $month->format('M Y'),
                'amount' => 0
            ];
        }

        // Process bookings data
        foreach ($bookings as $booking) {
            $bookingDate = \Carbon\Carbon::parse($booking['start_at']);
            $monthKey = $bookingDate->format('Y-m');
            
            if (isset($monthlyRevenue[$monthKey])) {
                $monthlyRevenue[$monthKey]['amount'] += $booking['price_amount'];
            }
        }

        return array_values($monthlyRevenue);
    }

    private function getDefaultData(): array
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $data[] = [
                'month' => $month->format('M Y'),
                'amount' => 0
            ];
        }
        return $data;
    }

    public function getDescription(): ?string
    {
        return 'Monthly revenue from completed bookings';
    }
}