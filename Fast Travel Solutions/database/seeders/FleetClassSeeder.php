<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FleetClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Using a loop to insert multiple records
          $make = [
            ['class_name' => 'Saloon Car'],
            ['class_name' => 'SUV'],
            ['class_name' => 'Mini'],
            ['class_name' => 'Minivan'],
            ['class_name' => 'Utility Van'],

            // Add more records as needed
        ];

        // Insert data into the database
        DB::table('fleet_classes')->insert($make);
    }
}
