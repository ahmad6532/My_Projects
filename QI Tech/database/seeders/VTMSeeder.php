<?php

namespace Database\Seeders;

use App\Models\dmd_vtm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VTMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('dmd_vtms')->truncate();
        $xmlString = file_get_contents(storage_path('/dmd/f_vtm2_3040724.xml'));
        $xmlObject = simplexml_load_string($xmlString);
        foreach($xmlObject as $vtm){
            $dmd = new dmd_vtm();
            $dmd->VTMID = $vtm->VTMID;
            $dmd->NM =  $vtm->NM;
            $dmd->VTMIDPREV = $vtm->VTMIDPREV ?? null;
            $dmd->VTMIDDT = $vtm->VTMIDDT ?? null;
            $dmd->save();
       }
    }
}
