<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerPetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => (int) $this->id,
            'name'        => $this->name,
            'pet_type_id' => $this->pet_type_id,
            'type_name'   => optional($this->type)->name,
            'breed_id'    => $this->pet_breed_id,
            'breed_name'  => optional($this->breed)->name,
            'size_id'     => $this->size_id,
            'size_label'  => optional($this->size)->label,
            'sex'         => $this->sex,
            'photo_path'  => $this->photo_path,
        ];
    }
}
