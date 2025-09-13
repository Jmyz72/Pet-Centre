<?php

namespace App\Filament\Merchant\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Http;

class WalletSummaryWidget extends Widget
{
    protected static string $view = 'filament.merchant.widgets.wallet-summary-widget';

    protected int | string | array $columnSpan = 2;

    public function getWalletData(): array
    {
        try {
            $merchantId = auth()->user()->merchantProfile?->id;

            if (!$merchantId) {
                return [];
            }

            $response = Http::get("http://localhost:8001/api/merchants/{$merchantId}/wallet", [
                'with_transactions' => true,
                'transactions_limit' => 5
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