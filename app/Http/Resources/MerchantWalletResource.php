<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantWalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'merchant_id' => (int) $this->merchant_id,
            'balance' => (float) $this->balance,
            'pending_balance' => (float) $this->pending_balance,
            'currency' => $this->currency,
            'is_active' => (bool) $this->is_active,
            'updated_at' => $this->updated_at,
            'transactions' => $this->when($this->relationLoaded('transactions'), 
                WalletTransactionResource::collection($this->transactions)
            ),
        ];
    }
}