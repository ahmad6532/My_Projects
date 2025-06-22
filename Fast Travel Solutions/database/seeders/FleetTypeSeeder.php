<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FleetType;
use Illuminate\Support\Facades\DB;



class FleetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
            // Using a loop to insert multiple records
            $types = [
                ['type_name' => 'Standard', 'car_name' => 'VW Mercedes', 'car_picture' => 'car.png', 'total_passengers' => '3' , 'luggage_bags' => '2'],
                ['type_name' => 'Premium', 'car_name' =>  'Mercedes BMW', 'car_picture' => 'car.png', 'total_passengers' => '4' , 'luggage_bags' => '3'],
                ['type_name' => 'Luxury', 'car_name' => 'Mercedes Tesla', 'car_picture' => 'car.png', 'total_passengers' => '5' , 'luggage_bags' => '4'],
                ['type_name' => 'VIP', 'car_name' => 'Tesla', 'car_picture' => 'car.png', 'total_passengers' => '6' , 'luggage_bags' => '5'],
                ['type_name' => 'Bussiness', 'car_name' =>  'BMW', 'car_picture' => 'car.png', 'total_passengers' => '7' , 'luggage_bags' => '6'],
                ['type_name' => 'Van', 'car_name' => 'Changan', 'car_picture' => 'car.png', 'total_passengers' => '8' , 'luggage_bags' => '7'],
                // Add more records as needed
            ];
    
            // Insert data into the database
            DB::table('fleet_types')->insert($types);
        }
    }
}
