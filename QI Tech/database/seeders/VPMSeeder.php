<?php

namespace Database\Seeders;

use App\Models\dmd_vmp;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VPMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('dmd_vmps')->truncate();
        $xmlString = file_get_contents(storage_path('/dmd/f_vmp2_3040724.xml'));
        $xmlObject = simplexml_load_string($xmlString);
        foreach($xmlObject->VMPS->VMP as $vmp){
            $dmd = new dmd_vmp();
            $dmd->VPID = $vmp->VPID;
            $dmd->VTMID = $vmp->VTMID ?? null;
            $dmd->NM =  $vmp->NM;
            $dmd->ABBREVNM =  $vmp->ABBREVNM ?? null;
            $dmd->save();
        }
    }
    
}
