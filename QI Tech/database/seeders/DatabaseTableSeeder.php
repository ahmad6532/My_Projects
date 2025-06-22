<?php

namespace Database\Seeders;

use App\Models\databases\Database;
use App\Models\databases\DispensingDoctor;
use App\Models\databases\GPhCLocation;
use App\Models\databases\GPhCPharmacist;
use App\Models\databases\GPhCTechnician;
use App\Models\databases\NorthernIrelandList;
use App\Models\databases\PharmacyList;
use App\Models\databases\PSIPharmacy;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        $databases = [
            [
                "name" => "UK (GPhC) Pharmacies",
                "table_name" => "gphc_locations",
                "recordable_type" => GPhCLocation::class,
                "created_at" => $now,
                "updated_at" => $now
            ],
            [
                "name" => "UK (GPhC) Pharmacist",
                "table_name" => "gphc_pharmacists",
                "recordable_type" => GPhCPharmacist::class,
                "created_at" => $now,
                "updated_at" => $now
            ],
            [
                "name" => "UK (GPhC) Technician",
                "table_name" => "gphc_technicians",
                "recordable_type" => GPhCTechnician::class,
                "created_at" => $now,
                "updated_at" => $now
            ],
            [
                "name" => "Republic of Ireland (PSI) Pharmacies",
                "table_name" => "psi_pharmacies",
                "recordable_type" => PSIPharmacy::class,
                "created_at" => $now,
                "updated_at" => $now
            ],
            [
                "name" => "Dispensing Doctors",
                "table_name" => "dispensing_doctors",
                "recordable_type" => DispensingDoctor::class,
                "created_at" => $now,
                "updated_at" => $now
            ],
            [
                "name" => "Northern Ireland (PSNI) Pharmacies",
                "table_name" => "northern_ireland_list",
                "recordable_type" => NorthernIrelandList::class,
                "created_at" => $now,
                "updated_at" => $now
            ],

            ];


        if(Database::all()->count() == 0)
            Database::insert($databases);


    }
}
