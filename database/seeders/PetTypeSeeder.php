<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PetType;

class PetTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            // Classic household
            'Dog',
            'Cat',
            'Rabbit',
            'Bird',
            'Fish',

            // Small mammals
            'Hamster',
            'Guinea Pig',
            'Gerbil',
            'Ferret',
            'Chinchilla',
            'Hedgehog',
            'Mouse',
            'Rat',

            // Reptiles & Amphibians (commonly kept at home)
            'Turtle',
            'Tortoise',
            'Gecko',
            'Iguana',
            'Frog',

            // Popular birds
            'Parrot',
            'Cockatiel',
            'Lovebird',
            'Budgerigar (Budgie)',
            'Canary',

            // Misc
            'Other',
        ];

        foreach ($types as $type) {
            PetType::firstOrCreate(['name' => $type]);
        }
    }
}