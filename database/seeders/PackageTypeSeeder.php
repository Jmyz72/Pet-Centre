<?php

namespace Database\Seeders;

use App\Models\PackageType;
use Illuminate\Database\Seeder;

class PackageTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Grooming',
            'Bathing',
            'Haircut / Styling',
            'Nail & Paw Care',
            'Ear & Eye Cleaning',
            'Flea & Tick Treatment',
            'De-shedding',
            'Teeth Brushing / Oral Care',
            'Sanitary Trim',
            'Spa & Pampering',
            'Aromatherapy / Massage',
            'Coat Conditioning / Whitening',
            'Puppy Grooming',
            'Senior Pet Grooming',
            'De-matting / Undercoat Removal',
            'Breed-specific Grooming',
            'Medicated / Hypoallergenic Bath',
            'Skin & Allergy Treatment',
            'Paw / Nose Balm Treatment',
            'Pet Boarding',
            'Express Services',
            'Pick-up & Drop-off',
            'Pet Taxi',
            'Other',
        ];

        foreach ($types as $name) {
            PackageType::firstOrCreate(['name' => $name]);
        }
    }
}
