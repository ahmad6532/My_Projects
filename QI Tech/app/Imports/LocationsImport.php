<?php

namespace App\Imports;

use App\Models\HeadOfficeLocation;
use App\Models\Location;
use App\Models\location_tags;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class LocationsImport implements ToModel, WithStartRow
{
    protected $headOffice;
    protected $tag_id;
    protected $errors = []; // To collect errors

    public function startRow(): int
    {
        return 2;
    }

    public function __construct()
    {
        $this->headOffice = Auth::user()->selected_head_office;
        $this->tag_id = location_tags::first()->id;
    }

    public function model(array $row)
    {
        try {
            $col1 = $row[0];
            $col2 = $row[1];
            $col3 = $row[2];

            // Map $col1 to appropriate values
            if ($col1 == 'Community Pharmacy') {
                $col1 = 1;
            } elseif ($col1 == "Dispensing Doctor's Practice") {
                $col1 = 2;
            } elseif ($col1 == "Hospital Pharmacy") {
                $col1 = 3;
            } elseif ($col1 == "Private Pharmacy") {
                $col1 = 4;
            }

            // Map $col2 to appropriate values
            if ($col2 == 'Retail Pharmacy') {
                $col2 = 1;
            } elseif ($col2 == "Distance Selling Pharmacy") {
                $col2 = 2;
            } elseif ($col2 == "Private Pharmacy") {
                $col2 = 3;
            }

            // Map $col3 to appropriate values
            if ($col3 == 'UK (GPhC)') {
                $col3 = 1;
            } elseif ($col3 == "Northern Ireland (PSNI)") {
                $col3 = 2;
            } elseif ($col3 == "Republic of Ireland (PSI)") {
                $col3 = 3;
            }

            $location = Location::create([
                'location_type_id' => $col1,
                'location_pharmacy_type_id' => $col2,
                'location_regulatory_body_id' => $col3,
                'registered_company_name' => $row[3],
                'trading_name' => $row[4],
                'username' => $row[5],
                'registration_no' => $row[6],
                'address_line1' => $row[7],
                'address_line2' => $row[8],
                'address_line3' => $row[9],
                'town' => $row[10],
                'county' => $row[11],
                'country' => $row[12],
                'postcode' => $row[13],
                'telephone_no' => $row[14],
                'email' => $row[15],
                'password' => \Hash::make($row[16]),
                'email_verified_at' => now(),
                'tag_id' => $this->tag_id
            ]);

            $head_office_location = new HeadOfficeLocation();
            $head_office_location->head_office_id = $this->headOffice->id;
            $head_office_location->location_id = $location->id;
            $head_office_location->save();

            return $location;
        } catch (\Exception $e) {
            $this->errors[] = [
                'email' => $row[15], // Assuming email is in the 16th column (index 15)
                'error' => $e->getMessage()
            ];
        
            // Optionally, log the error
            Log::error('Error importing row ' . ' for email ' . $row[15] . ': ' . $e->getMessage());
        
            // Skip the row and continue processing
            return null;
        }
    }

    // Method to get the collected errors after import
    public function getErrors()
    {
        return array_map(function($error) {
            return [
                'email' => $error['email'], // Assuming the email is in column 16 (index 15)
            ];
        }, $this->errors);
    }
    

}
