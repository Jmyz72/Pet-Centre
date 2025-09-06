<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServiceTypesTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('service_types')->insert([
            [ 'name' => 'Vaccination',     'created_at' => $now, 'updated_at' => $now ],
            [ 'name' => 'General Check-up','created_at' => $now, 'updated_at' => $now ],
            [ 'name' => 'Surgery',         'created_at' => $now, 'updated_at' => $now ],
            [ 'name' => 'X-ray',           'created_at' => $now, 'updated_at' => $now ],
            [ 'name' => 'Blood Test',      'created_at' => $now, 'updated_at' => $now ],
            [ 'name' => 'Ultrasound',      'created_at' => $now, 'updated_at' => $now ],
            [ 'name' => 'Dental Care',     'created_at' => $now, 'updated_at' => $now ],
            [ 'name' => 'Deworming',       'created_at' => $now, 'updated_at' => $now ],
            [ 'name' => 'Microchipping',   'created_at' => $now, 'updated_at' => $now ],
            [ 'name' => 'Emergency Care',  'created_at' => $now, 'updated_at' => $now ],
        ]);
    }
}