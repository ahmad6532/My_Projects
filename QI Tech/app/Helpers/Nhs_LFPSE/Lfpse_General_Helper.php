<?php
namespace App\Helpers\Nhs_LFPSE;

use App\Models\BeSpokeFormCategory;
use App\Models\Forms\Form;
use App\Models\HeadOfficeBeSpokeFormCategory;

class Lfpse_General_Helper
{
    public static function create_form_if_not_exists($head_office)
    {
        $id = $head_office->id;
        $lfpse_category = BeSpokeFormCategory::where('name', 'General')->where('reference_id', $id)->first();
        if(!$lfpse_category)
        {
            // $lfpse_category = new BeSpokeFormCategory();
            // // $lfpse_category->id = 1000; // setting initially
            // $lfpse_category->name = "General";
            // $lfpse_category->reference_type = 'head_office';
            // $lfpse_category->reference_id = $id;// use HO id here. you can assign to all HOs. #To do
            // $lfpse_category->color = '#000';
            // $lfpse_category->save();
        }

        // $category_link = HeadOfficeBeSpokeFormCategory::where('b_s_f_c_id', $lfpse_category->id)->first();
        // if(!$category_link)
        // {
        //     $category_link = new HeadOfficeBeSpokeFormCategory();
        //     $category_link->head_office_id = $id;// // use HO id here. you can assign to all HOs. #To do
        //     $category_link->b_s_f_c_id = $lfpse_category->id;
        //     $category_link->save();
        // }

        $lfpse_form = Form::Where('submitable_to_nhs_lfpse',true)->where('reference_id', $id)->first();
        //$lfpse_form->delete(); // For testing and updating Each time migraiotn runs ! Remove this line
        //$lfpse_form = false;
        if(!$lfpse_form)
        {
            $lfpse_form = new Form();
            //$lfpse_form->id = 1000; //setting initially.
            $lfpse_form->name = "NHS LFPSE";
            $lfpse_form->add_to_case_manager = true;
            $lfpse_form->reference_type = 'head_office';
            $lfpse_form->reference_id = $id; // use HO id here. you can assign to all HOs. #To do
            // $lfpse_form->be_spoke_form_category_id = $lfpse_category->id;
            // Leaving other fields as Default !
            $json_file_contents = file_get_contents(storage_path('/lfpse/lfpse_form.json'));
            $lfpse_form->note = "For NHS LFPSE Events";
            $lfpse_form->form_json = $json_file_contents;
            $lfpse_form->submitable_to_nhs_lfpse = true;
            $lfpse_form->save();
        }
    }
}