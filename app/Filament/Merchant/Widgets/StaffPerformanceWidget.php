<?php

namespace App\Filament\Merchant\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Http;

class StaffPerformanceWidget extends Widget
{
    protected static string $view = 'filament.merchant.widgets.staff-performance-widget';
    
    protected int | string | array $columnSpan = 2;

    public function getStaffData(): array
    {
        try {
            $merchantId = auth()->user()->merchantProfile?->id;
            
            if (!$merchantId) {
                return [];
            }

            $response = Http::get("http://localhost:8001/api/merchants/{$merchantId}/staff", [
                'with_performance' => true,
                'limit' => 10
            ]);

            if ($response->successful() && $response->json('ok')) {
                return $response->json('data', []);
            }

            return [];
        } catch (\Exception $e) {
            return [];
        }
    }
}