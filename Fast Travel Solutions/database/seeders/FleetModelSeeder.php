<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class FleetModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Using a loop to insert multiple records
        $make = [
          ['model_name' => 'Grande'],
          ['model_name' => 'Alsvin'],
          ['model_name' => 'BMW'],
          ['model_name' => 'Tesla'],
          ['model_name' => 'Sportage'],

          // Add more records as needed
      ];

      // Insert data into the database
      DB::table('fleet_models')->insert($make);
  }
}
