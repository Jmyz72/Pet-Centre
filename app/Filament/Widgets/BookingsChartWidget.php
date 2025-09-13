<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class BookingsChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Bookings Trends (Last 7 Days)';
    
    protected static string $color = 'info';
    
    protected int | string | array $columnSpan = 2;

    protected function getData(): array
    {
        $bookingsData = $this->getBookingsData();
        
        return [
            'datasets' => [
                [
                    'label' => 'Daily Bookings',
                    'data' => array_column($bookingsData, 'count'),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => array_column($bookingsData, 'date'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getBookingsData(): array
    {
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Booking::whereDate('created_at', $date)->count();
            
            $data[] = [
                'date' => $date->format('M d'),
                'count' => $count
            ];
        }
        
        return $data;
    }

    public function getDescription(): ?string
    {
        return 'Daily booking creation trends';
    }
}