<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantStaffResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'specialization' => $this->specialization,
            'status' => $this->status,
            'merchant_id' => (int) $this->merchant_id,
            'bookings_this_month' => $this->when(isset($this->bookings_this_month), $this->bookings_this_month),
        ];
    }
}