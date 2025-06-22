<?php

namespace Database\Seeders;

use App\Models\BeSpokeFormCategory;
use App\Models\Forms\Form;
use App\Models\HeadOffice;
use App\Models\HeadOfficeBeSpokeFormCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LfpseFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ho = HeadOffice::take(1)->first();
        if($ho)
        {
            // Create a new Category if not exists //
            $lfpse_category = BeSpokeFormCategory::where('name', 'General')->first();
            if(!$lfpse_category)
            {
                // $lfpse_category = new BeSpokeFormCategory();
                // // $lfpse_category->id = 1000; // setting initially
                // $lfpse_category->name = "General";
                // $lfpse_category->reference_type = 'head_office';
                // $lfpse_category->reference_id = $ho->id;// use HO id here. you can assign to all HOs. #To do
                // $lfpse_category->color = '#000';
                // $lfpse_category->save();
            }

            $lfpse_form = Form::Where('submitable_to_nhs_lfpse',true)->where('reference_id', $ho->id)->first();
            if($lfpse_form){
                $lfpse_form->delete(); // For testing and updating Each time migraiotn runs ! Remove this line
            }
            $lfpse_form = false;
            if(!$lfpse_form)
            {
                $lfpse_form = new Form();
                //$lfpse_form->id = 1000; //setting initially.
                $lfpse_form->name = "NHS LFPSE";
                $lfpse_form->add_to_case_manager = true;
                $lfpse_form->reference_type = 'head_office';
                $lfpse_form->reference_id = $ho->id; // use HO id here. you can assign to all HOs. #To do
                // $lfpse_form->be_spoke_form_category_id = $lfpse_category->id;
                // Leaving other fields as Default !
                $json_file_contents = file_get_contents(storage_path('/lfpse/lfpse_form.json'));
                $lfpse_form->note = "For NHS LFPSE Events";
                $lfpse_form->form_json = $json_file_contents;
                $lfpse_form->submitable_to_nhs_lfpse = true;
                $lfpse_form->save();
            }
        }
        // upon creation of any newer Company Account, a new similar form needs to be created !
    }
}
