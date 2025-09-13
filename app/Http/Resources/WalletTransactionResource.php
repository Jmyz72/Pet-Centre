<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletTransactionResource extends JsonResource
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
            'transaction_id' => $this->transaction_id,
            'type' => $this->type,
            'amount' => (float) $this->amount,
            'status' => $this->status,
            'release_code' => $this->release_code,
            'merchant_amount' => $this->when($this->merchant_amount, (float) $this->merchant_amount),
            'booking_id' => $this->when($this->booking_id, (int) $this->booking_id),
            'description' => $this->description,
            'released_at' => $this->released_at,
            'created_at' => $this->created_at,
        ];
    }
}