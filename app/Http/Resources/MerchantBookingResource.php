<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantBookingResource extends JsonResource
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
            'customer_id' => (int) $this->customer_id,
            'customer_name' => optional($this->customer)->name,
            'merchant_id' => (int) $this->merchant_id,
            'staff_id' => $this->when($this->staff_id, (int) $this->staff_id),
            'staff_name' => optional($this->staff)->name,
            'service_id' => $this->when($this->service_id, (int) $this->service_id),
            'service_name' => optional($this->service)->title,
            'package_id' => $this->when($this->package_id, (int) $this->package_id),
            'package_name' => optional($this->package)->name,
            'customer_pet_id' => $this->when($this->customer_pet_id, (int) $this->customer_pet_id),
            'customer_pet_name' => optional($this->customerPet)->name,
            'merchant_pet_id' => $this->when($this->merchant_pet_id, (int) $this->merchant_pet_id),
            'merchant_pet_name' => optional($this->merchantPet)->name,
            'booking_type' => $this->booking_type,
            'status' => $this->status,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'price_amount' => (float) $this->price_amount,
            'payment_ref' => $this->payment_ref,
            'created_at' => $this->created_at,
        ];
    }
}