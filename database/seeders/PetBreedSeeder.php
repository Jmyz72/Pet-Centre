<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PetType;
use App\Models\PetBreed;

class PetBreedSeeder extends Seeder
{
    public function run(): void
    {
        // Define breeds under each type
        $breeds = [
            'Dog' => [
                'Labrador Retriever',
                'Golden Retriever',
                'German Shepherd',
                'French Bulldog',
                'Bulldog',
                'Poodle',
                'Beagle',
                'Rottweiler',
                'Yorkshire Terrier',
                'Boxer',
                'Dachshund',
                'Pembroke Welsh Corgi',
                'Siberian Husky',
                'Australian Shepherd',
                'Great Dane',
                'Doberman Pinscher',
                'Cavalier King Charles Spaniel',
                'Miniature Schnauzer',
                'Shih Tzu',
                'Boston Terrier',
                'Pomeranian',
                'Havanese',
                'Shetland Sheepdog',
                'Bernese Mountain Dog',
                'Brittany',
                'English Springer Spaniel',
                'Cocker Spaniel',
                'Cane Corso',
                'Vizsla',
                'Pug',
                'Chihuahua',
                'Mastiff',
                'Border Collie',
                'Basset Hound',
                'Belgian Malinois',
                'Collie',
                'Weimaraner',
                'Newfoundland',
                'Rhodesian Ridgeback',
                'West Highland White Terrier',
                'Shiba Inu',
                'Bichon Frise',
                'Akita',
                'St. Bernard',
                'Bloodhound',
                'Bullmastiff',
                'Papillon',
                'Airedale Terrier',
                'Alaskan Malamute',
                'Australian Cattle Dog',
                'Basenji',
                'Bearded Collie',
                'Belgian Sheepdog',
                'Borzoi',
                'Cairn Terrier',
                'Cardigan Welsh Corgi',
                'Chesapeake Bay Retriever',
                'Chinese Crested',
                'Chow Chow',
                'Clumber Spaniel',
                'Dalmatian',
                'Dandie Dinmont Terrier',
                'English Setter',
                'English Toy Spaniel',
                'Finnish Spitz',
                'Flat-Coated Retriever',
                'Fox Terrier',
                'German Pinscher',
                'German Shorthaired Pointer',
                'German Wirehaired Pointer',
                'Giant Schnauzer',
                'Glen of Imaal Terrier',
                'Great Pyrenees',
                'Greater Swiss Mountain Dog',
                'Greyhound',
                'Irish Setter',
                'Irish Terrier',
                'Irish Wolfhound',
                'Italian Greyhound',
                'Jack Russell Terrier',
                'Japanese Chin',
                'Keeshond',
                'Kerry Blue Terrier',
                'Komondor',
                'Kuvasz',
                'Lhasa Apso',
                'Maltese',
                'Manchester Terrier',
                'Neapolitan Mastiff',
                'Norfolk Terrier',
                'Norwegian Elkhound',
                'Norwich Terrier',
                'Old English Sheepdog',
                'Otterhound',
                'Pekingese',
                'Petit Basset Griffon Vendeen',
                'Pointer',
                'Polish Lowland Sheepdog',
                'Puli',
                'Rat Terrier',
                'Redbone Coonhound',
                'Saluki',
                'Samoyed',
                'Scottish Terrier',
                'Sealyham Terrier',
                'Silky Terrier',
                'Skye Terrier',
                'Soft Coated Wheaten Terrier',
                'Staffordshire Bull Terrier',
                'Sussex Spaniel',
                'Tibetan Mastiff',
                'Tibetan Spaniel',
                'Tibetan Terrier',
                'Toy Fox Terrier',
                'Treeing Walker Coonhound',
                'Whippet',
                'Wire Fox Terrier',
                'Yorkipoo',
            ],

            'Cat' => [
                'Persian',
                'Siamese',
                'Maine Coon',
                'British Shorthair',
                'Bengal',
                'Ragdoll',
                'Sphynx',
                'Scottish Fold',
                'Abyssinian',
                'American Shorthair',
            ],
            'Rabbit' => [
                'Holland Lop',
                'Netherland Dwarf',
                'Mini Rex',
                'Lionhead',
                'Flemish Giant',
            ],
            'Bird' => [
                'Parakeet (Budgie)',
                'Cockatiel',
                'Lovebird',
                'Canary',
                'African Grey',
                'Macaw',
            ],
            'Fish' => [
                'Goldfish',
                'Betta',
                'Guppy',
                'Molly',
                'Angelfish',
                'Tetra',
                'Koi',
            ],
            'Hamster' => [
                'Syrian Hamster',
                'Roborovski Hamster',
                'Campbell’s Dwarf',
                'Winter White Dwarf',
            ],
            'Guinea Pig' => [
                'American',
                'Abyssinian',
                'Peruvian',
                'Teddy',
                'Silkie',
            ],
            'Ferret' => [
                'Standard Ferret',
                'Angora Ferret',
                'Black Sable',
                'Albino Ferret',
            ],
            'Chinchilla' => [
                'Standard Grey',
                'White Mosaic',
                'Black Velvet',
                'Beige',
            ],
            'Hedgehog' => [
                'African Pygmy',
                'Algerian',
                'Salt and Pepper',
            ],
            'Mouse' => [
                'Fancy Mouse',
                'Albino Mouse',
                'Long-haired Mouse',
            ],
            'Rat' => [
                'Dumbo Rat',
                'Hairless Rat',
                'Standard Fancy Rat',
            ],
            'Turtle' => [
                'Red-Eared Slider',
                'Painted Turtle',
                'Box Turtle',
                'Map Turtle',
            ],
            'Parrot' => [
                'African Grey',
                'Macaw',
                'Amazon',
                'Cockatoo',
                'Quaker Parrot',
            ],
            'Gerbil' => [
                'Mongolian Gerbil',
                'Fat-tailed Gerbil',
                'Shaw’s Jird',
                'Pallid Gerbil',
            ],
            'Tortoise' => [
                'Russian Tortoise',
                'Hermann’s Tortoise',
                'Sulcata Tortoise',
                'Leopard Tortoise',
                'Greek Tortoise',
            ],
            'Gecko' => [
                'Leopard Gecko',
                'Crested Gecko',
                'Tokay Gecko',
                'Gargoyle Gecko',
                'African Fat-Tailed Gecko',
            ],
            'Iguana' => [
                'Green Iguana',
                'Desert Iguana',
                'Spiny-tailed Iguana',
                'Rhinoceros Iguana',
            ],
            'Frog' => [
                'Pacman Frog',
                'White’s Tree Frog',
                'Poison Dart Frog',
                'American Bullfrog',
                'African Dwarf Frog',
            ],
            'Cockatiel' => [
                'Normal Grey Cockatiel',
                'Lutino Cockatiel',
                'Pied Cockatiel',
                'Pearl Cockatiel',
                'Cinnamon Cockatiel',
            ],
            'Lovebird' => [
                'Peach-faced Lovebird',
                'Fischer’s Lovebird',
                'Masked Lovebird',
                'Nyasa (Liliane’s) Lovebird',
                'Black-cheeked Lovebird',
            ],
            'Budgerigar (Budgie)' => [
                'American Budgie',
                'English Budgie',
                'Albino Budgie',
                'Lutino Budgie',
                'Spangle Budgie',
            ],
            'Canary' => [
                'Roller Canary',
                'Border Canary',
                'Gloster Canary',
                'Red Factor Canary',
                'Spanish Timbrado',
            ],
            'Other' => [
                'Mixed Breed',
                'Unknown',
            ],
        ];

        foreach ($breeds as $typeName => $breedList) {
            $petType = PetType::where('name', $typeName)->first();
            if ($petType) {
                // Append a generic "Other {Type}" breed to each type (except the global 'Other' type)
                if (strtolower($typeName) !== 'other') {
                    $otherBreed = 'Other ' . $typeName;
                    if (!in_array($otherBreed, $breedList, true)) {
                        $breedList[] = $otherBreed;
                    }
                }

                // Ensure unique breed names per type before seeding
                $breedList = array_values(array_unique($breedList));

                foreach ($breedList as $breedName) {
                    PetBreed::firstOrCreate([
                        'pet_type_id' => $petType->id,
                        'name'        => $breedName,
                    ]);
                }
            }
        }
    }
}