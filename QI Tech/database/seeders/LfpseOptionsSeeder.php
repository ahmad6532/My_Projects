<?php

namespace Database\Seeders;

use App\Models\LfpseOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LfpseOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LfpseOption::truncate(); // deleting existing data ! 

        // ODS Codes //
        $json_file_contents = file_get_contents(storage_path('/lfpse/ods_codes_v5.json'));
        $codes = json_decode($json_file_contents);
        $to_db = [];
        foreach($codes as $c)
            $to_db[] = ['code' => $c->code, 'val'=>$c->display, 'collection_name' => 'ods_codes', 'version' => 5];
        $pieces = array_chunk($to_db, 100);
        foreach($pieces as $p)
            LfpseOption::insert($p);


        // Specialty //
        $json_file_contents = file_get_contents(storage_path('/lfpse/specialty_v5.json'));
        $codes = json_decode($json_file_contents);
        $to_db = [];
        foreach($codes as $c)
            $to_db[] = ['code' => $c->code, 'val'=>$c->display, 'collection_name' => 'specialty', 'version' => 5];
        
        
        $pieces = array_chunk($to_db, 100);
        foreach($pieces as $p)
            LfpseOption::insert($p);

        // Medical Devices //
        $json_file_contents = file_get_contents(storage_path('/lfpse/medical_devices_v5.json'));
        $codes = json_decode($json_file_contents);
        $to_db = [];
        foreach($codes as $c)
            $to_db[] = ['code' => $c->code, 'val'=>$c->display, 'collection_name' => 'medical_devices', 'version' => 5];
        
        
        $pieces = array_chunk($to_db, 100);
        foreach($pieces as $p)
            LfpseOption::insert($p);
    }
}
