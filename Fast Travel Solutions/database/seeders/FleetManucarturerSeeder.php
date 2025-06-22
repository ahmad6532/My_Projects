<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class FleetManucarturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Using a loop to insert multiple records
          $make = [
            ['manufacturer_name' => 'Toyota'],
            ['manufacturer_name' => 'Suzuki'],
            ['manufacturer_name' => 'Mercedes'],
            ['manufacturer_name' => 'Porche'],
            ['manufacturer_name' => 'Haval'],

            // Add more records as needed
        ];

        // Insert data into the database
        DB::table('fleet_manufacturers')->insert($make);
    }
}
