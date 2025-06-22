<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class DMDSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {    
         /**
     *  NHS Data from XML is stored into database.
     *  Please note the medicines are called Actual Medicial Products AMPS
     *  Name and Desc will be used for Querying DMDS.
     *  Latest Version  19 December 2022
     *  URL: https://isd.digital.nhs.uk/trud/user/guest/group/0/home
     *  @put file f_amp2_3151222.xml into storage/dmd
     * @return void
     */


        # Empty the table
        DB::table('dmds')->truncate();
        $xmlString = file_get_contents(storage_path('/dmd/f_amp2_3151222.xml'));
        $xmlObject = simplexml_load_string($xmlString);
        set_time_limit(0);
        if(!count($xmlObject->AMPS)){
            return;
        }
       foreach($xmlObject->AMPS->AMP as $amp){
            $dmd = new \App\Models\DMD();
            $dmd->APID = $amp->APID;
            $dmd->VPID =  $amp->VPID;
            $dmd->name = $amp->NM;
            $dmd->description = $amp->DESC;
            $dmd->SUPPCD = $amp->SUPPCD;
            $dmd->LIC_AUTHCD = $amp->LIC_AUTHCD;
            $dmd->AVAIL_RESTRICTCD = $amp->AVAIL_RESTRICTCD;
            $dmd->save();
       }
    }
}
