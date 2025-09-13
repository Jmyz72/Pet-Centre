<?php

namespace App\Filament\Merchant\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Http;

class BusinessMetricsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $metrics = $this->getBusinessMetrics();
        
        return [
            Stat::make('Today\'s Revenue', 'RM ' . number_format($metrics['today_revenue'], 2))
                ->description('Revenue generated today')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('This Month\'s Revenue', 'RM ' . number_format($metrics['month_revenue'], 2))
                ->description('Total revenue this month')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info'),

            Stat::make('Total Bookings', $metrics['total_bookings'])
                ->description('All-time bookings')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning'),

            Stat::make('Active Staff', $metrics['active_staff'])
                ->description('Currently active staff members')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Conversion Rate', number_format($metrics['conversion_rate'], 1) . '%')
                ->description('Confirmed/Total bookings')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($metrics['conversion_rate'] >= 70 ? 'success' : 'danger'),

            Stat::make('Avg. Booking Value', 'RM ' . number_format($metrics['avg_booking_value'], 2))
                ->description('Average revenue per booking')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('info'),
        ];
    }

    private function getBusinessMetrics(): array
    {
        try {
            $merchantId = auth()->user()->merchantProfile?->id;

            if (!$merchantId) {
                return $this->getDefaultMetrics();
            }

            // Get all bookings
            $bookingsResponse = Http::get("http://localhost:8001/api/merchants/{$merchantId}/bookings", [
                'limit' => 100
            ]);

            // Get staff data
            $staffResponse = Http::get("http://localhost:8001/api/merchants/{$merchantId}/staff");

            $bookings = [];
            $staff = [];

            if ($bookingsResponse->successful() && $bookingsResponse->json('ok')) {
                $bookings = $bookingsResponse->json('data', []);
            }

            if ($staffResponse->successful() && $staffResponse->json('ok')) {
                $staff = $staffResponse->json('data', []);
            }

            return $this->calculateMetrics($bookings, $staff);
        } catch (\Exception $e) {
            return $this->getDefaultMetrics();
        }
    }

    private function calculateMetrics(array $bookings, array $staff): array
    {
        $today = now()->startOfDay();
        $monthStart = now()->startOfMonth();

        $todayRevenue = 0;
        $monthRevenue = 0;
        $totalRevenue = 0;
        $confirmedBookings = 0;
        $totalBookings = count($bookings);

        foreach ($bookings as $booking) {
            $bookingCreatedDate = \Carbon\Carbon::parse($booking['created_at']);
            $amount = $booking['price_amount'];

            if ($booking['status'] === 'completed') {
                $totalRevenue += $amount;

                if ($bookingCreatedDate->isSameDay($today)) {
                    $todayRevenue += $amount;
                }

                if ($bookingCreatedDate->gte($monthStart)) {
                    $monthRevenue += $amount;
                }
            }

            if (in_array($booking['status'], ['confirmed', 'completed'])) {
                $confirmedBookings++;
            }
        }

        $conversionRate = $totalBookings > 0 ? ($confirmedBookings / $totalBookings) * 100 : 0;
        $avgBookingValue = $confirmedBookings > 0 ? $totalRevenue / $confirmedBookings : 0;

        return [
            'today_revenue' => $todayRevenue,
            'month_revenue' => $monthRevenue,
            'total_bookings' => $totalBookings,
            'active_staff' => count($staff),
            'conversion_rate' => $conversionRate,
            'avg_booking_value' => $avgBookingValue,
        ];
    }

    private function getDefaultMetrics(): array
    {
        return [
            'today_revenue' => 0,
            'month_revenue' => 0,
            'total_bookings' => 0,
            'active_staff' => 0,
            'conversion_rate' => 0,
            'avg_booking_value' => 0,
        ];
    }
}