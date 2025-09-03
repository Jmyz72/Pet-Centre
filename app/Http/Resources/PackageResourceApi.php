<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResourceApi extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'price'    => (float) $this->price,
            'duration' => $this->duration_minutes,
            'types'    => $this->packageTypes->pluck('name'),
            'sizes'    => $this->packageSizes->pluck('label'),
            'pets'     => $this->petTypes->pluck('name'),
            'breeds'   => $this->petBreeds->pluck('name'),
        ];
    }
}
