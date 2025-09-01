<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    public function run(): void
    {
        $sizes = [
            ['label' => 'XS', 'min_weight' => 0,     'max_weight' => 5],
            ['label' => 'S',  'min_weight' => 5.01,  'max_weight' => 10],
            ['label' => 'M',  'min_weight' => 10.01, 'max_weight' => 20],
            ['label' => 'L',  'min_weight' => 20.01, 'max_weight' => 40],
            ['label' => 'XL', 'min_weight' => 40.01, 'max_weight' => null],
        ];

        foreach ($sizes as $size) {
            Size::firstOrCreate(['label' => $size['label']], $size);
        }
    }
}
