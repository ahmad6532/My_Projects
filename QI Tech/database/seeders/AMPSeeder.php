<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AMPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('amp')->truncate(); // Clear the table first (optional)
        
        // Load the XML file
        $xmlString = file_get_contents(storage_path('/dmd/f_amp2_3151222.xml'));
        $xmlObject = simplexml_load_string($xmlString);
        
        // Iterate through the XML and insert data into the `amp` table
        foreach ($xmlObject->AMPS->AMP as $amp) {
            DB::table('amp')->insert([
                'VPID' => (string) $amp->VPID, // Cast to string to avoid type issues
                'APID' => isset($amp->APID) ? (string) $amp->APID : null,
                'NM' => (string) $amp->NM,
                'created_at' => now() // Add timestamps if necessary
            ]);
        }
        
    }
}
