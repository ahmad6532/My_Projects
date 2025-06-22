<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LocationRegulatoryBody;
use Carbon\Carbon;
use App\Models\LocationType;
use App\Models\LocationPharmacyType;
use App\Models\Position;
use App\Models\Role;

class Initial_Basic_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //


        ///////// Related to Location ///////////
        $now = Carbon::now();
        $regulatory_bodies = [
            [//name,country,regulatory
                "name" => "UK (GPhC)",
                "country" => "UK",
                "regulatory" => "GPhC",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [
                "name" => "Northern Ireland (PSNI)",
                "country" => "Northern Ireland",
                "regulatory" => "PSNI",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [
                "name" => "Republic of Ireland (PSI)",
                "country" => "Republic of Ireland",
                "regulatory" => "PSI",
                "created_at" => $now,
                "updated_at" => $now
            ]
        ];
        
        if(LocationRegulatoryBody::all()->count() == 0)
            LocationRegulatoryBody::insert($regulatory_bodies);
        

        ////
        $location_types = [
            [//name,country,regulatory
                "name" => "Community Pharmacy",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [
                "name" => "Dispensing Doctor's Practice",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [
                "name" => "Hospital Pharmacy",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [
                "name" => "Private Pharmacy",
                "created_at" => $now,
                "updated_at" => $now
            ]
        ];

        if(LocationType::all()->count() == 0)
            LocationType::insert($location_types);



        $location_pharmacy_types = [
            [//name,country,regulatory
                "name" => "Retail Pharmacy",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [
                "name" => "Distance Selling Pharmacy",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [
                "name" => "Private Pharmacy",
                "created_at" => $now,
                "updated_at" => $now
            ]
        ];

        if(LocationPharmacyType::all()->count() == 0)
            LocationPharmacyType::insert($location_pharmacy_types);







        /// Related to User ////////
     

        $positions = [
            [//name,country,regulatory
                "name" => "Accuracy Checking Technician (ACT)",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [//name,country,regulatory
                "name" => "Pharmacy Apprentice",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [//name,country,regulatory
                "name" => "Counter Assistant",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [//name,country,regulatory
                "name" => "Dispenser",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [//name,country,regulatory
                "name" => "Driver",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [//name,country,regulatory
                "name" => "Pharmacist",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [//name,country,regulatory
                "name" => "Pre-registration Pharmacist",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [//name,country,regulatory
                "name" => "Pharmacy Technician",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [//name,country,regulatory
                "name" => "Pharmacy Assistant",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [//name,country,regulatory
                "name" => "Normal User",
                "created_at" => $now,
                "updated_at" => $now
            ],
            
        ];

        if(Position::all()->count() == 0)
            Position::insert($positions);

        $roles = [
            [
                "name" => "None",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [//name,country,regulatory
                "name" => "Branch Manager",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [//name,country,regulatory
                "name" => "Accuracy Checker",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [//name,country,regulatory
                "name" => "Superintendent",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [//name,country,regulatory
                "name" => "Locum",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [//name,country,regulatory
                "name" => "Relief",
                "created_at" => $now,
                "updated_at" => $now
            ],
            [//name,country,regulatory
                "name" => "Independent Prescriber",
                "created_at" => $now,
                "updated_at" => $now
            ],
            
            
        ];

        if(Role::all()->count() == 0)
            Role::insert($roles);


    }
}
