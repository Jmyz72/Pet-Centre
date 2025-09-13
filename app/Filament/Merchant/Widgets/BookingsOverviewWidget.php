<?php

namespace App\Filament\Merchant\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Http;

class BookingsOverviewWidget extends Widget
{
    protected static string $view = 'filament.merchant.widgets.bookings-overview-widget';
    
    protected int | string | array $columnSpan = 2;

    public function getBookingsData(): array
    {
        try {
            $merchantId = auth()->user()->merchantProfile?->id;
            
            if (!$merchantId) {
                return [];
            }

            $response = Http::get("http://localhost:8001/api/merchants/{$merchantId}/bookings", [
                'limit' => 10,
                'upcoming_only' => true
            ]);

            if ($response->successful() && $response->json('ok')) {
                return $response->json('data', []);
            }

            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getTodayBookingsCount(): int
    {
        try {
            $merchantId = auth()->user()->merchantProfile?->id;
            
            if (!$merchantId) {
                return 0;
            }

            $response = Http::get("http://localhost:8001/api/merchants/{$merchantId}/bookings", [
                'date_from' => now()->startOfDay()->toDateString(),
                'date_to' => now()->endOfDay()->toDateString()
            ]);

            if ($response->successful() && $response->json('ok')) {
                return count($response->json('data', []));
            }

            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getPendingBookingsCount(): int
    {
        try {
            $merchantId = auth()->user()->merchantProfile?->id;
            
            if (!$merchantId) {
                return 0;
            }

            $response = Http::get("http://localhost:8001/api/merchants/{$merchantId}/bookings", [
                'status' => 'confirmed',
                'upcoming_only' => true
            ]);

            if ($response->successful() && $response->json('ok')) {
                return count($response->json('data', []));
            }

            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
}