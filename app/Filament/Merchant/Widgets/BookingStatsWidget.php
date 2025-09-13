<?php

namespace App\Filament\Merchant\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Http;

class BookingStatsWidget extends ChartWidget
{
    protected static ?string $heading = 'Booking Statistics';
    
    protected static string $color = 'info';
    
    protected int | string | array $columnSpan = 2;

    protected function getData(): array
    {
        $statsData = $this->getBookingStats();
        
        return [
            'datasets' => [
                [
                    'data' => array_values($statsData),
                    'backgroundColor' => [
                        'rgb(34, 197, 94)',   // Completed - Green
                        'rgb(59, 130, 246)',  // Confirmed - Blue  
                        'rgb(245, 158, 11)',  // Pending - Yellow
                        'rgb(239, 68, 68)',   // Cancelled - Red
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => array_map('ucfirst', array_keys($statsData)),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    private function getBookingStats(): array
    {
        try {
            $merchantId = auth()->user()->merchantProfile?->id;
            
            if (!$merchantId) {
                return $this->getDefaultStats();
            }

            $response = Http::get("http://localhost:8001/api/merchants/{$merchantId}/bookings", [
                'limit' => 1000
            ]);

            if ($response->successful() && $response->json('ok')) {
                return $this->processBookingStats($response->json('data', []));
            }

            return $this->getDefaultStats();
        } catch (\Exception $e) {
            return $this->getDefaultStats();
        }
    }

    private function processBookingStats(array $bookings): array
    {
        $stats = [
            'completed' => 0,
            'confirmed' => 0,
            'pending' => 0,
            'cancelled' => 0,
        ];

        foreach ($bookings as $booking) {
            $status = $booking['status'] ?? 'pending';
            if (isset($stats[$status])) {
                $stats[$status]++;
            }
        }

        return $stats;
    }

    private function getDefaultStats(): array
    {
        return [
            'completed' => 0,
            'confirmed' => 0,
            'pending' => 0,
            'cancelled' => 0,
        ];
    }

    public function getDescription(): ?string
    {
        return 'Distribution of booking statuses';
    }
}