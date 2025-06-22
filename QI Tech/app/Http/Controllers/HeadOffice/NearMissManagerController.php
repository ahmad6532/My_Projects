<?php

namespace App\Http\Controllers\HeadOffice;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\near_miss_manager;
use App\Models\near_miss_settings;
use App\Models\NearMiss;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NearMissManagerController extends Controller
{
    //

    public function near_miss_manager($id)
    {
        $user = Auth::guard('web')->user();
        $headOffice = $user->selected_head_office;
        $near_miss = near_miss_manager::find($id);
        return view('head_office.near_miss_manager', compact('near_miss', 'headOffice'));
    }

    public function near_active($id)
{
    $user = Auth::guard('web')->user();
    $bespoke = near_miss_manager::find($id);

    if (!$bespoke) {
        return redirect()->back()->with(['error' => 'Form not found']);
    }

    // Check if a category is assigned before activating
    if ($bespoke->isActive == 0 && $bespoke->be_spoke_form_category_id == null) {
        return redirect()->back()->with(['error' => 'Cannot activate form without assigning a category.']);
    }

    // Toggle activation status
    $bespoke->isActive = 1 - $bespoke->isActive;
    $message = $bespoke->isActive ? "Form is Activated." : "Form is Deactivated.";

    $bespoke->save();

    return redirect()->back()->with(['success' => $message]);
}

    public function near_miss_manager_update($id, Request $request)
{
    $user = Auth::guard('web')->user();
    $headOffice = $user->selected_head_office;
    $near_miss = near_miss_manager::find($id);
    if(!isset($near_miss)){
        return back()->with(['error' => 'Record not found!']);
    }
    if($near_miss->be_spoke_form_category_id == null){
        return back()->with(['error' => 'Please assign a category first!']);
    }
    $near_miss->name = $request->name;
    $near_miss->description = $request->description;
    $near_miss->color = $request->color;
    $near_miss->allow_editing_time = $request->allow_editing_time ?? null;
    $near_miss->purpose = $request->purpose;
    if (isset($request->allow_editing) && isset($request->allow_editing_time_always)) {
        $near_miss->allow_editing_state = 'always';
    } elseif (!isset($request->allow_editing)) {
        $near_miss->allow_editing_state = 'disable';
    } else {
        if($request->allow_editing_select == 1){
            $near_miss->allow_editing_state = 'minutes';
        }else if($request->allow_editing_select == 2){
            $near_miss->allow_editing_state = 'hour';
        }else if ($request->allow_editing_select == 3){
            $near_miss->allow_editing_state = 'day';
        } 
        else if ($request->allow_editing_select == 4){
            $near_miss->allow_editing_state = 'month';
        }
    }
    if (isset($request->quick_report) && $request->quick_report == 'on') {
        $near_miss->is_quick_report = true;
    } else {
        $near_miss->is_quick_report = false;
    }
    if (isset($request->qr) && $request->qr == 'on') {
        $near_miss->is_qr_code = true;
    } else {
        $near_miss->is_qr_code = false;
    }
    // $near_miss->color = $request->color_code;
    $near_miss->color = $request->color;
    $near_miss->allow_editing_time = isset($request->allow_editing_time) ? $request->allow_editing_time : null;
    $near_miss->save();

    return redirect()->back()->with(['success' => 'Near Miss Manager updated successfully!']);
}

    public function name_update(Request $request)
    {
        $near_miss = near_miss_manager::find($request->id);
        if (isset($near_miss)) {
            $near_miss->name = $request->value;
            $near_miss->save();
            return response()->json(['message' => 'name updated successfully!'], 200);
        }
        return response()->json(['Error' => 'Not found!'], 404);
    }

    public function add_setting(Request $request)
{
    $user = Auth::guard('web')->user();
    $headOffice = $user->selected_head_office;
    $near_miss = near_miss_manager::find($request->near_miss);
    // if(empty($request->location)){
        //     return back()->with(['error' => 'Please select location!']);
        // }
        if ($request->location !== null && $headOffice->near_miss_settings->contains('location_id', $request->location)) {
        return back()->with(['error' => 'Near miss is already assigned to this location!']);
    }

    if (isset($near_miss)) {
        $setting = new near_miss_settings();
        $setting->name = $request->name;
        $setting->near_miss_id = $near_miss->id;
        $setting->head_office_id = $headOffice->id;
        $setting->location_id = $request->location ?? null;
        $setting->purpose = $request->purpose ?? null;
        if (isset($request->is_active) && $request->is_active == 'on') {
            $setting->is_active = true;
        } else {
            $setting->is_active = false;
        }
        $setting->save();
        return back()->with(['success' => 'New Setting added!']);
    }
    return back()->with(['error' => 'Record not found!']);
}

    public function delete_setting($id)
    {
        $near_miss_setting = near_miss_settings::find($id);
        if (isset($near_miss_setting)) {
            $near_miss_setting->delete();
            return back()->with(['success' => 'Setting deleted!']);
        }
        return back()->with(['error' => 'Record not found!']);
    }

    public function status_setting($id)
    {
        $user = Auth::guard('web')->user();
        $headOffice = $user->selected_head_office;
        $setting = $headOffice->near_miss_settings()->find($id);
        if(!isset($setting)){
            return abort(404);
        }
        if(($setting->location_id == null)){
            return back()->with(['error'=> 'Please assign a location first!']);
        }

    
        $setting->is_active = !$setting->is_active ;
        $setting->save();

        return back()->with(['success'=> 'Status updated!']);

        
    }
    public function assign_location(Request $request)
{

    
    $request->validate([
        'setting_id' => 'required|exists:near_miss_settings,id',
        'location' => 'required|exists:locations,id',
    ]);

    $user = Auth::guard('web')->user();
    $headOffice = $user->selected_head_office;

    $setting = near_miss_settings::find($request->setting_id);

    if (!$setting) {
        return back()->with(['error' => 'Setting not found!']);
    }

    if ($headOffice->near_miss_settings->contains('location_id', $request->location)) {
        return back()->with(['error' => 'Location is already assigned to a setting in this head office!']);
    }

    $setting->location_id = $request->location;
    $setting->save();


    return back()->with('success', 'Location assigned successfully!');
    }

    public function edit_setting($id)
    {
        $user = Auth::guard('web')->user();
        $headOffice = $user->selected_head_office;
        $setting = near_miss_settings::find($id);
        $nearmiss = null;
        $location = Location::find($setting->location_id);
        $where = $headOffice->locations;
        // if (!$location || !$location->id) {
        //     return redirect()->back()->with('error', 'Please assign a location first.');
        // }
        $who = User::relatedToLocation($location?->id);
                $positions = Position::all();
        
        $data = isset($setting->settings) ? json_decode($setting->settings, true) : null;
        if (isset($setting)) {
            return view('head_office.near_miss_template', compact('setting', 'nearmiss', 'positions', 'where', 'who', 'location', 'data'))->with('standalone', true);
        }
        return back()->with(['error' => 'Record not found!']);
    }

    public function template_submit(Request $request)
    {
        $near_miss_setting = near_miss_settings::find($request->setting);
        $data = [
            "who" => [
                "hidden" => false,
                "allow_responder_to_report_near_miss" => $request->responder_diff_location == 'on',
                "location_ids" => $request->locations,
                "error_by_who" => $request->error_by_who == 'on',
                "error_by_who_label" => $request->error_by_label,
                "error_detected_by_who_label" => $request->error_detected_label,
                "error_detected_by_who" => $request->error_detected_by_who == 'on'
            ],
            "what" => [
                "hidden" => false,
                "point_of_detection" => [
                "hidden" => false, //$request->point_hide == 'on'
                "labelling" => $request->labelling == 'on',
                "bagging" => $request->bagging == 'on',
                "filling_away" => $request->filling_away == 'on',
                "delivering" => $request->delivering == 'on',
                "picking" => $request->picking == 'on',
                "final_check" => $request->final_check == 'on',
                "handing_out" => $request->handing_out == 'on',
                "labelling_text" => $request->input("labelling_text", ""),
                "bagging_text" => $request->input("bagging_text", ""),
                "filling_away_text" => $request->input("filling_away_text", ""),
                "delivering_text" => $request->input("delivering_text", ""),
                "picking_text" => $request->input("picking_text", ""),
                "final_check_text" => $request->input("final_check_text", ""),
                "handing_out_text" => $request->input("handing_out_text", ""),

            ],
                "what_was_error" => [
                "hidden" => false,
                "error_prescription" => $request->error_prescription == 'on',
                "error_prescription_name" => $request->input('what_was_error.error_prescription.name'),
                "error_labelling" => $request->error_labelling == 'on',
                "error_labelling_name" => $request->input('what_was_error.error_labelling.name'),
                "error_picking" => $request->error_picking == 'on',
                "error_picking_name" => $request->input('what_was_error.error_picking.name'),
                "error_placing_into_basket" => $request->error_placing_into_basket == 'on',
                "error_placing_into_basket_name" => $request->input('what_was_error.error_placing_into_basket.name'),
                "error_bagging" => $request->error_bagging == 'on',
                "error_bagging_name" => $request->input('what_was_error.error_bagging.name'),
                "error_preparing_dosette_tray" => $request->error_preparing_dosette_tray == 'on',
                "error_preparing_dosette_tray_name" => $request->input('what_was_error.error_preparing_dosette_tray.name'),
                "error_handing_out" => $request->error_handing_out == 'on',
                "error_handing_out_name" => $request->input('what_was_error.error_handing_out.name'),
            ]
            ],
            "why" => [
                "hidden" => false,
            ],
            "contribution" => [
                "hidden" => $request->hide_contribution == 'on',
                "staff" => [
                    "hidden" => $request->Staff == 'on',
                    "pharmacist_self_checking" => $request->pharmacist_self_checking == 'on',
                    "not_the_usual_despneser" => $request->not_the_usual_despneser == 'on',
                    "not_the_usual_pharmacist" => $request->not_the_usual_pharmacist == 'on',
                    "fewer_staff_than_usual" => $request->fewer_staff_than_usual == 'on',
                    'fewer_staff_than_usual_label' => $request->input('fewer_staff_than_usual_label'),
                    'not_the_usual_pharmacist_label' => $request->input('not_the_usual_pharmacist_label'),
                    'not_the_usual_despneser_label' => $request->input('not_the_usual_despneser_label'),
                    'pharmacist_self_checking_label' => $request->input('pharmacist_self_checking_label'),
            ],
                "environment" => [
                    "hidden" => $request->Environment == 'on',
                    "messy_environment" => $request->messy_environment == 'on',
                    'messy_environment_label' => $request->input('messy_environment_label', 'Messy environment')
                ],
                'tasks' => [
                'hidden' => $request->input('Tasks') == 'on',
                'high_number_of_patients_waiting' => $request->input('high_number_of_patients_waiting') == 'on',
                'busy_otc_trade' => $request->input('busy_otc_trade') == 'on',
                'backlog_of_work' => $request->input('backlog_of_work') == 'on',
                'quieter_than_usual' => $request->input('quieter_than_usual') == 'on',
                'telephone_interruption' => $request->input('telephone_interruption') == 'on',
                
                'Tasks_label' => $request->input('Tasks_label', 'Hide Tasks & Workload'),
                'high_number_of_patients_waiting_label' => $request->input('high_number_of_patients_waiting_label', 'High number of patients waiting'),
                'busy_otc_trade_label' => $request->input('busy_otc_trade_label', 'Busy OTC trade'),
                'backlog_of_work_label' => $request->input('backlog_of_work_label', 'Backlog of work'),
                'quieter_than_usual_label' => $request->input('quieter_than_usual_label', 'Quieter than usual'),
                'telephone_interruption_label' => $request->input('telephone_interruption_label', 'Telephone interruption'),
            ],
                "person" => [
                    "hidden" => $request->Person == 'on',
                    "dyslexia" => $request->dyslexia == 'on',
                    "dyscalculia" => $request->dyscalculia == 'on',

                    "Person_labe" => $request->input('Person_label', 'Hide Person'),
                    "dyslexia_label" => $request->input('dyslexia_label', 'Dyslexia'),
                    "dyscalculia_label" => $request->input('dyscalculia_label', 'Dyscalculia'),
                ],
                "training" => [
                    "hidden" => $request->Training == 'on',
                    "person_in_training" => $request->person_in_training == 'on',
                    "person_not_trained_in_this_area" => $request->person_not_trained_in_this_area == 'on',

                    "Training_label" => $request->input("Training_label", "Training"),
                    "person_in_training_label" => $request->input("person_in_training_label", "Person in training"),
                    "person_not_trained_in_this_area_label" => $request->input("person_not_trained_in_this_area_label", "Person not trained in this area"),
                ],
                "other" => [
                    "hidden" => $request->Other == 'on',
                    'name' => $request->OtherText
                ]

            ],
            "actions" => [
                "hidden" => $request->hide_actions == 'on'
            ],
            "extra_fields" => [
                'prescription' => [
                    'missing_signature' => [
                        "hidden" => $request->missing_signature == 'on',
                        "label" => $request->missing_signature_label,
                        "reason" => [
                            "prescription_missing_signature_cause_legal_checks_not_done_field" => $request->prescription_missing_signature_cause_legal_checks_not_done_field == 'on',
                            "prescription_missing_signature_cause_not_trained_field" => $request->prescription_missing_signature_cause_not_trained_field == 'on',
                            "prescription_missing_signature_cause_person_in_training_field" => $request->prescription_missing_signature_cause_person_in_training_field == 'on',
                            "prescription_missing_signature_cause_other_field" => $request->prescription_expired_cause_other_field == 'on',
                            "prescription_missing_signature_cause_legal_checks_not_done_label" => $request->prescription_missing_signature_cause_legal_checks_not_done_label,
                            "prescription_missing_signature_cause_not_trained_label" => $request->prescription_missing_signature_cause_not_trained_label,
                            "prescription_missing_signature_cause_person_in_training_label" => $request->prescription_missing_signature_cause_person_in_training_label,
                            "prescription_missing_signature_cause_other_label" => $request->prescription_expired_cause_other_label,
                        ]
                    ],
                    'prescription_expired' => [
                        "hidden" => $request->prescription_expired_field == 'on',
                        "label" => $request->prescription_expired_label,
                        "does_this_involve_a_controlled_drug"=>false,
                        "reason" => [
                            "prescription_expired_cause_date_not_checked_field" => $request->prescription_expired_cause_date_not_checked_field == 'on',
                            "prescription_expired_cause_legal_checks_not_done_field" => $request->prescription_expired_cause_legal_checks_not_done_field == 'on',
                            "prescription_expired_sop_not_understood_field" => $request->prescription_expired_sop_not_understood_field == 'on',
                            "prescription_expired_cause_other_field" => $request->prescription_expired_cause_other_field == 'on',
                            "prescription_expired_cause_date_not_checked_label" => $request->prescription_expired_cause_date_not_checked_label,
                            "prescription_expired_cause_legal_checks_not_done_label" => $request->prescription_expired_cause_legal_checks_not_done_label,
                            "prescription_expired_sop_not_understood_label" => $request->prescription_expired_sop_not_understood_label,
                            "prescription_expired_cause_other_label" => $request->prescription_expired_cause_other_label
                            ]
                    ],
                    'old_treatment' => [
                        "hidden" => $request->old_treatment == 'on',
                        "label" => $request->old_treatment_label,
                        "reason" => [
                            "prescription_old_treatment_cause_hospital_discharge_not_actioned_field" => $request->prescription_old_treatment_cause_hospital_discharge_not_actioned_field == 'on',
                            "prescription_old_treatment_cause_error_by_prescriber_field" => $request->prescription_old_treatment_cause_error_by_prescriber_field == 'on',
                            "prescription_old_treatment_cause_change_not_communicated_field" => $request->prescription_old_treatment_cause_change_not_communicated_field == 'on',
                            "prescription_old_treatment_cause_other_field" => $request->prescription_old_treatment_cause_other_field == 'on',
                            "prescription_old_treatment_cause_hospital_discharge_not_actioned_label" => $request->prescription_old_treatment_cause_hospital_discharge_not_actioned_label,
                            "prescription_old_treatment_cause_error_by_prescriber_label" => $request->prescription_old_treatment_cause_error_by_prescriber_label,
                            "prescription_old_treatment_cause_change_not_communicated_label" => $request->prescription_old_treatment_cause_change_not_communicated_label,
                            "prescription_old_treatment_cause_other_label" => $request->prescription_old_treatment_cause_other_label,
                        ]
                    ],
                    'fraudulent_tampered_prescription' => [
                        "hidden" => $request->fraudulent_tampered_prescription == 'on',
                        "label" => $request->fraudulent_tampered_prescription_label,
                        "reason" => [
                            "prescription_tampered_cause_legal_checks_not_done_field" => $request->prescription_tampered_cause_legal_checks_not_done_field == 'on',
                            "prescription_tampered_cause_other_field" => $request->prescription_tampered_cause_other_field == 'on',
                            "prescription_tampered_cause_legal_checks_not_done_label" => $request->prescription_tampered_cause_legal_checks_not_done_label,
                            "prescription_tampered_cause_other_label" => $request->prescription_tampered_cause_other_label
                        ]
                    ],
                ],
                'labelling' => [
                    'wrong_brand' => [
                        'hidden' => $request->wrong_brand == 'on',
                        "wrong_brand_text" => $request->wrong_brand_text,
                        'prescribed_item' => [
                            'hidden' => $request->labelling_wrong_brand_prescribed_field == 'on',
                            'mandatory' => $request->labelling_wrong_brand_prescribed_mandatory == 'on',
                        ],
                        'labelled_item' => [
                            'hidden' => $request->labelling_wrong_brand_labelled_field == 'on',
                            'mandatory' => $request->labelling_wrong_brand_labelled_mandatory == 'on',
                        ],
                        "reason" => [
                            "labelling_wrong_brand_cause_similar_items_field" => $request->labelling_wrong_brand_cause_similar_items_field == 'on',
                            "labelling_wrong_brand_cause_repeated_pmr_history_field" => $request->labelling_wrong_brand_cause_repeated_pmr_history_field == 'on',
                            "labelling_wrong_brand_cause_misread_prescription_field" => $request->labelling_wrong_brand_cause_misread_prescription_field == 'on',
                            "labelling_wrong_brand_cause_unclear_handwritten_prescription_field" => $request->labelling_wrong_brand_cause_unclear_handwritten_prescription_field == 'on',
                            "labelling_wrong_brand_cause_did_not_recheck_field" => $request->labelling_wrong_brand_cause_did_not_recheck_field == 'on',
                            "labelling_wrong_brand_cause_other_field" => $request->labelling_wrong_brand_cause_other_field == 'on',
                            "labelling_wrong_brand_cause_similar_items_label" => $request->labelling_wrong_brand_cause_similar_items_label,
                            "labelling_wrong_brand_cause_repeated_pmr_history_label" => $request->labelling_wrong_brand_cause_repeated_pmr_history_label,
                            "labelling_wrong_brand_cause_misread_prescription_label" => $request->labelling_wrong_brand_cause_misread_prescription_label,
                            "labelling_wrong_brand_cause_unclear_handwritten_prescription_label" => $request->labelling_wrong_brand_cause_unclear_handwritten_prescription_label,
                            "labelling_wrong_brand_cause_did_not_recheck_label" => $request->labelling_wrong_brand_cause_did_not_recheck_label,
                            "labelling_wrong_brand_cause_other_label" => $request->labelling_wrong_brand_cause_other_label
                        ]
                        
                    ],
                    'wrong_direction' => [
                        'hidden' => $request->wrong_direction == 'on',
                        "wrong_direction_label" => $request->input("wrong_direction_label", "Wrong direction"),
                        "reason" => [
                            "labelling_wrong_direction_cause_unclear_directions_field" => $request->labelling_wrong_direction_cause_unclear_directions_field == 'on',
                            "labelling_wrong_direction_cause_unclear_handwritten_prescription_field" => $request->labelling_wrong_direction_cause_unclear_handwritten_prescription_field == 'on',
                            "labelling_wrong_direction_cause_use_of_abbreviation_field" => $request->labelling_wrong_direction_cause_use_of_abbreviation_field == 'on',
                            "labelling_wrong_direction_cause_abbreviation_not_changed_field" => $request->labelling_wrong_direction_cause_abbreviation_not_changed_field == 'on',
                            "labelling_wrong_direction_cause_repeated_pmr_history_field" => $request->labelling_wrong_direction_cause_repeated_pmr_history_field == 'on',
                            "labelling_wrong_direction_cause_did_not_recheck_field" => $request->labelling_wrong_direction_cause_did_not_recheck_field == 'on',
                            "labelling_wrong_direction_cause_other_field" => $request->labelling_wrong_direction_cause_other_label,
                            "labelling_wrong_direction_cause_unclear_directions_label" => $request->labelling_wrong_direction_cause_unclear_directions_label,
                            "labelling_wrong_direction_cause_unclear_handwritten_prescription_label" => $request->labelling_wrong_direction_cause_unclear_handwritten_prescription_label,
                            "labelling_wrong_direction_cause_use_of_abbreviation_label" => $request->labelling_wrong_direction_cause_use_of_abbreviation_label,
                            "labelling_wrong_direction_cause_abbreviation_not_changed_label" => $request->labelling_wrong_direction_cause_abbreviation_not_changed_label,
                            "labelling_wrong_direction_cause_repeated_pmr_history_label" => $request->labelling_wrong_direction_cause_repeated_pmr_history_label,
                            "labelling_wrong_direction_cause_did_not_recheck_label" => $request->labelling_wrong_direction_cause_did_not_recheck_label,
                            "labelling_wrong_direction_cause_other_label" => $request->labelling_wrong_direction_cause_other_label
                        ]
                    ],
                    'wrong_item' => [
                        'hidden' => $request->wrong_item == 'on',
                        "wrong_item_text" => $request->wrong_item_text,
                        'prescribed_item' => [
                            'hidden' => $request->labelling_wrong_item_prescribed_field == 'on',
                            'mandatory' => $request->labelling_wrong_item_prescribed_mandatory == 'on',
                        ],
                        'labelled_item' => [
                            'hidden' => $request->labelling_wrong_item_labelled_field == 'on',
                            'mandatory' => $request->labelling_wrong_item_labelled_mandatory == 'on',
                        ],
                        "reason" => [
                            "labelling_wrong_item_cause_similar_items_field" => $request->labelling_wrong_item_cause_similar_items_field == 'on',
                            "labelling_wrong_item_cause_misread_prescription_field" => $request->labelling_wrong_item_cause_misread_prescription_field == 'on',
                            "labelling_wrong_item_cause_repeated_pmr_history_field" => $request->labelling_wrong_item_cause_repeated_pmr_history_field == 'on',
                            "labelling_wrong_item_cause_unclear_handwritten_prescription_field" => $request->labelling_wrong_item_cause_unclear_handwritten_prescription_field == 'on',
                            "labelling_wrong_item_cause_did_not_recheck_field" => $request->labelling_wrong_item_cause_did_not_recheck_field == 'on',
                            "labelling_wrong_item_cause_other_field" => $request->labelling_wrong_item_cause_other_label,
                            "labelling_wrong_item_cause_similar_items_label" => $request->labelling_wrong_item_cause_similar_items_label,
                            "labelling_wrong_item_cause_misread_prescription_label" => $request->labelling_wrong_item_cause_misread_prescription_label,
                            "labelling_wrong_item_cause_repeated_pmr_history_label" => $request->labelling_wrong_item_cause_repeated_pmr_history_label,
                            "labelling_wrong_item_cause_unclear_handwritten_prescription_label" => $request->labelling_wrong_item_cause_unclear_handwritten_prescription_label,
                            "labelling_wrong_item_cause_did_not_recheck_label" => $request->labelling_wrong_item_cause_did_not_recheck_label,
                            "labelling_wrong_item_cause_other_label" => $request->labelling_wrong_item_cause_other_label                  
                            ]                        
                    ],
                    'wrong_formulation' => [
                        'hidden' => $request->wrong_formulation == 'on',
                        "wrong_formulation_text" => $request->wrong_formulation_text,
                        'prescribed_item' => [
                            'hidden' => $request->labelling_wrong_formulation_prescribed_field == 'on',
                            'mandatory' => $request->labelling_wrong_formulation_prescribed_mandatory == 'on',
                        ],
                        'labelled_item' => [
                            'hidden' => $request->labelling_wrong_formulation_labelled_field == 'on',
                            'mandatory' => $request->labelling_wrong_formulation_labelled_mandatory == 'on',
                        ],
                        "reason" => [
                            "labelling_wrong_formulation_cause_similar_items_field" => $request->labelling_wrong_formulation_cause_similar_items_field == 'on',
                            "labelling_wrong_formulation_cause_misread_prescription_field" => $request->labelling_wrong_formulation_cause_misread_prescription_field == 'on',
                            "labelling_wrong_formulation_cause_repeated_pmr_history_field" => $request->labelling_wrong_formulation_cause_repeated_pmr_history_field == 'on',
                            "labelling_wrong_formulation_cause_unclear_written_prescription_field" => $request->labelling_wrong_formulation_cause_unclear_written_prescription_field == 'on',
                            "labelling_wrong_formulation_cause_did_not_recheck_field" => $request->labelling_wrong_formulation_cause_did_not_recheck_field == 'on',
                            "labelling_wrong_formulation_cause_other_field" => $request->labelling_wrong_formulation_cause_other_field == 'on',
                            "labelling_wrong_formulation_cause_similar_items_label" => $request->labelling_wrong_formulation_cause_similar_items_label,
                            "labelling_wrong_formulation_cause_misread_prescription_label" => $request->labelling_wrong_formulation_cause_misread_prescription_label,
                            "labelling_wrong_formulation_cause_repeated_pmr_history_label" => $request->labelling_wrong_formulation_cause_repeated_pmr_history_label,
                            "labelling_wrong_formulation_cause_unclear_written_prescription_label" => $request->labelling_wrong_formulation_cause_unclear_written_prescription_label,
                            "labelling_wrong_formulation_cause_did_not_recheck_label" => $request->labelling_wrong_formulation_cause_did_not_recheck_label,
                            "labelling_wrong_formulation_cause_other_label" => $request->labelling_wrong_formulation_cause_other_label,
                            ]                        
                    ],
                    'wrong_patient' => [
                        'hidden' => $request->wrong_patient == 'on',
                        "wrong_patient_label" => $request->input("wrong_patient_label", "Wrong patient"),
                        "reason" => [
                            "labelling_wrong_patient_cause_similar_patient_name_field" => $request->labelling_wrong_patient_cause_similar_patient_name_field == 'on',
                            "labelling_wrong_patient_cause_misread_prescription_field" => $request->labelling_wrong_patient_cause_other_field == 'on',
                            "labelling_wrong_patient_cause_unclear_handwritten_prescription_field" => $request->labelling_wrong_patient_cause_unclear_handwritten_prescription_field == 'on',
                            "labelling_wrong_patient_cause_did_not_recheck_field" => $request->labelling_wrong_patient_cause_did_not_recheck_field == 'on',
                            "labelling_wrong_patient_cause_other_field" => $request->labelling_wrong_patient_cause_other_field == 'on',
                            "labelling_wrong_patient_cause_similar_patient_name_label" => $request->labelling_wrong_patient_cause_similar_patient_name_label,
                            "labelling_wrong_patient_cause_misread_prescription_label" => $request->labelling_wrong_patient_cause_other_label,
                            "labelling_wrong_patient_cause_unclear_handwritten_prescription_label" => $request->labelling_wrong_patient_cause_unclear_handwritten_prescription_label,
                            "labelling_wrong_patient_cause_did_not_recheck_label" => $request->labelling_wrong_patient_cause_did_not_recheck_label,
                            "labelling_wrong_patient_cause_other_label" => $request->labelling_wrong_patient_cause_other_label,

                        ]
                        
                    ],
                    'wrong_quantity' => [
                        'hidden' => $request->wrong_quantity == 'on',
                        "wrong_quantity_text" => $request->wrong_quantity_text,
                        "reason" => [
                            "labelling_wrong_quantity_cause_unfamiliar_with_item_field" => $request->labelling_wrong_quantity_cause_unfamiliar_with_item_field == 'on',
                            "labelling_wrong_quantity_cause_calculation_error_field" => $request->labelling_wrong_quantity_cause_calculation_error_field == 'on',
                            "labelling_wrong_quantity_cause_misread_prescription_field" => $request->labelling_wrong_quantity_cause_misread_prescription_field == 'on',
                            "labelling_wrong_quantity_cause_use_of_abbreviation_field" => $request->labelling_wrong_quantity_cause_use_of_abbreviation_field == 'on',
                            "labelling_wrong_quantity_cause_unclear_handwritten_prescription_field" => $request->labelling_wrong_quantity_cause_unclear_handwritten_prescription_field == 'on',
                            "labelling_wrong_quantity_cause_did_not_recheck_field" => $request->labelling_wrong_quantity_cause_did_not_recheck_field == 'on',
                            "labelling_wrong_quantity_cause_other_field" => $request->labelling_wrong_quantity_cause_other_field == 'on',
                            "labelling_wrong_quantity_cause_unfamiliar_with_item_label" => $request->labelling_wrong_quantity_cause_unfamiliar_with_item_label,
                            "labelling_wrong_quantity_cause_calculation_error_label" => $request->labelling_wrong_quantity_cause_calculation_error_label,
                            "labelling_wrong_quantity_cause_misread_prescription_label" => $request->labelling_wrong_quantity_cause_misread_prescription_label,
                            "labelling_wrong_quantity_cause_use_of_abbreviation_label" => $request->labelling_wrong_quantity_cause_use_of_abbreviation_label,
                            "labelling_wrong_quantity_cause_unclear_handwritten_prescription_label" => $request->labelling_wrong_quantity_cause_unclear_handwritten_prescription_label,
                            "labelling_wrong_quantity_cause_did_not_recheck_label" => $request->labelling_wrong_quantity_cause_did_not_recheck_label,
                            "labelling_wrong_quantity_cause_other_label" => $request->labelling_wrong_quantity_cause_other_label,

                        ]
                        
                    ],
                    'wrong_strength' => [
                        'hidden' => $request->wrong_strength == 'on',
                        "wrong_strength_text" => $request->wrong_strength_text,
                        'prescribed_item' => [
                            'hidden' => $request->labelling_wrong_strength_prescribed_field == 'on',
                            'mandatory' => $request->labelling_wrong_strength_prescribed_mandatory == 'on',
                        ],
                        'labelled_item' => [
                            'hidden' => $request->labelling_wrong_strength_labelled_field == 'on',
                            'mandatory' => $request->labelling_wrong_strength_labelled_mandatory == 'on',
                        ],
                        "reason" => [
                            "labelling_wrong_strength_cause_misread_prescription_field" => $request->labelling_wrong_strength_cause_misread_prescription_field == 'on',
                            "labelling_wrong_strength_cause_repeated_pmr_history_field" => $request->labelling_wrong_strength_cause_repeated_pmr_history_field == 'on',
                            "labelling_wrong_strength_cause_unfamiliar_with_item_field" => $request->labelling_wrong_strength_cause_unfamiliar_with_item_field == 'on',
                            "labelling_wrong_strength_cause_did_not_recheck_field" => $request->labelling_wrong_strength_cause_did_not_recheck_field == 'on',
                            "labelling_wrong_strength_cause_other_field" => $request->labelling_wrong_strength_cause_other_field == 'on',
                            "labelling_wrong_strength_cause_misread_prescription_label" => $request->labelling_wrong_strength_cause_misread_prescription_label,
                            "labelling_wrong_strength_cause_repeated_pmr_history_label" => $request->labelling_wrong_strength_cause_repeated_pmr_history_label,
                            "labelling_wrong_strength_cause_unfamiliar_with_item_label" => $request->labelling_wrong_strength_cause_unfamiliar_with_item_label,
                            "labelling_wrong_strength_cause_did_not_recheck_label" => $request->labelling_wrong_strength_cause_did_not_recheck_label,
                            "labelling_wrong_strength_cause_other_label" => $request->labelling_wrong_strength_cause_other_label,

                        ]                                                
                    ],
                ],
                'picking' => [
                    'out_of_date_item' => [
                        'hidden' => $request->out_of_date_item == 'on',
                        'label' => $request->out_of_date_item_label,
                        'prescribed_item' => [
                            'hidden' => false,
                            'mandatory' => false,
                        ],
                        "reason" => [
                            "picking_out_of_date_item_cause_did_not_recheck_field" => $request->picking_out_of_date_item_cause_did_not_recheck_field == 'on',
                            "picking_out_of_date_item_cause_date_not_checked_field" => $request->picking_out_of_date_item_cause_date_not_checked_field == 'on',
                            "picking_out_of_date_item_cause_items_wrong_place_field" => $request->picking_out_of_date_item_cause_items_wrong_place_field == 'on',
                            "picking_out_of_date_item_cause_other_field" => $request->picking_out_of_date_item_cause_other_field == 'on',
                            "picking_out_of_date_item_cause_did_not_recheck_label" => $request->picking_out_of_date_item_cause_did_not_recheck_label,
                            "picking_out_of_date_item_cause_date_not_checked_label" => $request->picking_out_of_date_item_cause_date_not_checked_label,
                            "picking_out_of_date_item_cause_items_wrong_place_label" => $request->picking_out_of_date_item_cause_items_wrong_place_label,
                            "picking_out_of_date_item_cause_other_label" => $request->picking_out_of_date_item_cause_other_label,

                        ]
                    ],
                    'wrong_brand' => [
                        'hidden' => $request->picking_wrong_brand_field == 'on',
                        'label' => $request->wrong_brand_label,
                        'prescribed_item' => [
                            'hidden' => $request->picking_wrong_brand_prescribed_field == 'on',
                            'mandatory' => $request->picking_wrong_brand_prescribed_mandatory == 'on',
                        ],
                        'labelled_item' => [
                            'hidden' => $request->picking_wrong_brand_labelled_field == 'on',
                            'mandatory' => $request->picking_wrong_brand_labelled_mandatory == 'on',
                        ],
                        "reason" => [
                            "picking_wrong_brand_cause_similar_name_items_field" => $request->picking_wrong_brand_cause_similar_name_items_field == 'on',
                            "picking_wrong_brand_cause_similar_packaging_field" => $request->picking_wrong_brand_cause_similar_packaging_field == 'on',
                            "picking_wrong_brand_cause_change_in_packaging_field" => $request->picking_wrong_brand_cause_change_in_packaging_field == 'on',
                            "picking_wrong_brand_cause_item_wrong_place_field" => $request->picking_wrong_brand_cause_item_wrong_place_field == 'on',
                            "picking_wrong_brand_cause_unclear_handwritten_prescription_field" => $request->picking_wrong_brand_cause_unclear_handwritten_prescription_field == 'on',
                            "picking_wrong_brand_cause_misread_prescription_field" => $request->picking_wrong_brand_cause_misread_prescription_field == 'on',
                            "picking_wrong_brand_cause_imported_pi_pack_field" => $request->picking_wrong_brand_cause_imported_pi_pack_field == 'on',
                            "picking_wrong_brand_cause_dispensed_from_picking_label_field" => $request->picking_wrong_brand_cause_dispensed_from_picking_label_field == 'on',
                            "picking_wrong_brand_cause_sop_not_understood_field" => $request->picking_wrong_brand_cause_sop_not_understood_field == 'on',
                            "picking_wrong_brand_cause_unfamiliar_with_item_field" => $request->picking_wrong_brand_cause_unfamiliar_with_item_field == 'on',
                            "picking_wrong_brand_cause_did_not_recheck_field" => $request->picking_wrong_brand_cause_did_not_recheck_field == 'on',
                            "picking_wrong_brand_cause_other_field" => $request->picking_wrong_brand_cause_other_field == 'on',
                            "picking_wrong_brand_cause_similar_name_items_label" => $request->picking_wrong_brand_cause_similar_name_items_label,
                            "picking_wrong_brand_cause_similar_packaging_label" => $request->picking_wrong_brand_cause_similar_packaging_label,
                            "picking_wrong_brand_cause_change_in_packaging_label" => $request->picking_wrong_brand_cause_change_in_packaging_label,
                            "picking_wrong_brand_cause_item_wrong_place_label" => $request->picking_wrong_brand_cause_item_wrong_place_label,
                            "picking_wrong_brand_cause_unclear_handwritten_prescription_label" => $request->picking_wrong_brand_cause_unclear_handwritten_prescription_label,
                            "picking_wrong_brand_cause_misread_prescription_label" => $request->picking_wrong_brand_cause_misread_prescription_label,
                            "picking_wrong_brand_cause_imported_pi_pack_label" => $request->picking_wrong_brand_cause_imported_pi_pack_label,
                            "picking_wrong_brand_cause_dispensed_from_picking_label_label" => $request->picking_wrong_brand_cause_dispensed_from_picking_label_label,
                            "picking_wrong_brand_cause_sop_not_understood_label" => $request->picking_wrong_brand_cause_sop_not_understood_label,
                            "picking_wrong_brand_cause_unfamiliar_with_item_label" => $request->picking_wrong_brand_cause_unfamiliar_with_item_label,
                            "picking_wrong_brand_cause_did_not_recheck_label" => $request->picking_wrong_brand_cause_did_not_recheck_label,
                            "picking_wrong_brand_cause_other_label" => $request->picking_wrong_brand_cause_other_label,
                        ]
                    ],
                    'wrong_item' => [
                        'hidden' => $request->picking_wrong_item_field == 'on',
                        'label' => $request->wrong_item_label,
                        'prescribed_item' => [
                            'hidden' => $request->picking_wrong_item_prescribed_field == 'on',
                            'mandatory' => $request->picking_wrong_item_prescribed_mandatory == 'on',
                        ],
                        'labelled_item' => [
                            'hidden' => $request->picking_wrong_item_labelled_field == 'on',
                            'mandatory' => $request->picking_wrong_item_labelled_mandatory == 'on',
                        ],
                        "reason" => [
                            "picking_wrong_item_cause_similar_name_items_field" => $request->picking_wrong_item_cause_similar_name_items_field == 'on',
                            "picking_wrong_item_cause_similar_packaging_field" => $request->picking_wrong_item_cause_similar_packaging_field == 'on',
                            "picking_wrong_item_cause_change_in_packaging_field" => $request->picking_wrong_item_cause_change_in_packaging_field == 'on',
                            "picking_wrong_item_cause_unclear_handwritten_prescription_field" => $request->picking_wrong_item_cause_unclear_handwritten_prescription_field == 'on',
                            "picking_wrong_item_cause_misread_prescription_field" => $request->picking_wrong_item_cause_misread_prescription_field == 'on',
                            "picking_wrong_item_cause_imported_pi_pack_field" => $request->picking_wrong_item_cause_imported_pi_pack_field == 'on',
                            "picking_wrong_item_cause_dispensed_from_picking_label_field" => $request->picking_wrong_item_cause_dispensed_from_picking_label_field == 'on',
                            "picking_wrong_item_cause_unfamiliar_with_item_field" => $request->picking_wrong_item_cause_unfamiliar_with_item_field == 'on',
                            "picking_wrong_item_cause_did_not_recheck_field" => $request->picking_wrong_item_cause_did_not_recheck_field == 'on',
                            "picking_wrong_item_cause_other_field" => $request->picking_wrong_item_cause_other_field == 'on',
                            "picking_wrong_item_cause_similar_name_items_label" => $request->picking_wrong_item_cause_similar_name_items_label,
                            "picking_wrong_item_cause_similar_packaging_label" => $request->picking_wrong_item_cause_similar_packaging_label,
                            "picking_wrong_item_cause_change_in_packaging_label" => $request->picking_wrong_item_cause_change_in_packaging_label,
                            "picking_wrong_item_cause_unclear_handwritten_prescription_label" => $request->picking_wrong_item_cause_unclear_handwritten_prescription_label,
                            "picking_wrong_item_cause_misread_prescription_label" => $request->picking_wrong_item_cause_misread_prescription_label,
                            "picking_wrong_item_cause_imported_pi_pack_label" => $request->picking_wrong_item_cause_imported_pi_pack_label,
                            "picking_wrong_item_cause_dispensed_from_picking_label_label" => $request->picking_wrong_item_cause_dispensed_from_picking_label_label,
                            "picking_wrong_item_cause_unfamiliar_with_item_label" => $request->picking_wrong_item_cause_unfamiliar_with_item_label,
                            "picking_wrong_item_cause_did_not_recheck_label" => $request->picking_wrong_item_cause_did_not_recheck_label,
                            "picking_wrong_item_cause_other_label" => $request->picking_wrong_item_cause_other_label,
                        ]                                                                      
                    ],
                    'wrong_formulation' => [
                        'hidden' => $request->picking_wrong_formulation_field == 'on',
                        'label' => $request->wrong_formulation_label,
                        'prescribed_item' => [
                            'hidden' => $request->picking_wrong_formulation_prescribed_field == 'on',
                            'mandatory' => $request->picking_wrong_formulation_prescribed_mandatory == 'on',
                        ],
                        'labelled_item' => [
                            'hidden' => $request->picking_wrong_formulation_labelled_field == 'on',
                            'mandatory' => $request->picking_wrong_formulation_labelled_mandatory == 'on',
                        ],
                        "reason" => [
                            "picking_wrong_formulation_cause_similar_name_items_field" => $request->picking_wrong_formulation_cause_similar_name_items_field == 'on',
                            "picking_wrong_formulation_cause_similar_packaging_field" => $request->picking_wrong_formulation_cause_similar_packaging_field == 'on',
                            "picking_wrong_formulation_cause_change_in_packaging_field" => $request->picking_wrong_formulation_cause_change_in_packaging_field == 'on',
                            "picking_wrong_formulation_cause_unclear_handwritten_prescription_field" => $request->picking_wrong_formulation_cause_unclear_handwritten_prescription_field == 'on',
                            "picking_wrong_formulation_cause_misread_prescription_field" => $request->picking_wrong_formulation_cause_misread_prescription_field == 'on',
                            "picking_wrong_formulation_cause_imported_pi_pack_field" => $request->picking_wrong_formulation_cause_imported_pi_pack_field == 'on',
                            "picking_wrong_formulation_cause_dispensed_from_picking_label_field" => $request->picking_wrong_formulation_cause_dispensed_from_picking_label_field == 'on',
                            "picking_wrong_formulation_cause_unfamiliar_with_item_field" => $request->picking_wrong_formulation_cause_unfamiliar_with_item_field == 'on',
                            "picking_wrong_formulation_cause_did_not_recheck_field" => $request->picking_wrong_formulation_cause_did_not_recheck_field == 'on',
                            "picking_wrong_formulation_cause_other_field" => $request->picking_wrong_formulation_cause_other_field == 'on',
                            "picking_wrong_formulation_cause_similar_name_items_label" => $request->picking_wrong_formulation_cause_similar_name_items_label,
                            "picking_wrong_formulation_cause_similar_packaging_label" => $request->picking_wrong_formulation_cause_similar_packaging_label,
                            "picking_wrong_formulation_cause_change_in_packaging_label" => $request->picking_wrong_formulation_cause_change_in_packaging_label,
                            "picking_wrong_formulation_cause_unclear_handwritten_prescription_label" => $request->picking_wrong_formulation_cause_unclear_handwritten_prescription_label,
                            "picking_wrong_formulation_cause_misread_prescription_label" => $request->picking_wrong_formulation_cause_misread_prescription_label,
                            "picking_wrong_formulation_cause_imported_pi_pack_label" => $request->picking_wrong_formulation_cause_imported_pi_pack_label,
                            "picking_wrong_formulation_cause_dispensed_from_picking_label_label" => $request->picking_wrong_formulation_cause_dispensed_from_picking_label_label,
                            "picking_wrong_formulation_cause_unfamiliar_with_item_label" => $request->picking_wrong_formulation_cause_unfamiliar_with_item_label,
                            "picking_wrong_formulation_cause_did_not_recheck_label" => $request->picking_wrong_formulation_cause_did_not_recheck_label,
                            "picking_wrong_formulation_cause_other_label" => $request->picking_wrong_formulation_cause_other_label,
                        ]                                                                        
                    ],
                    'wrong_quantity' => [
                        'hidden' => $request->picking_wrong_quantity_field == 'on',
                        'label' => $request->wrong_quantity_label,
                        "reason" => [
                            "picking_wrong_quantity_cause_counting_mistake_field" => $request->picking_wrong_quantity_cause_counting_mistake_field == 'on',
                            "picking_wrong_quantity_cause_calculation_error_field" => $request->picking_wrong_quantity_cause_calculation_error_field == 'on',
                            "picking_wrong_quantity_cause_similar_packaging_field" => $request->picking_wrong_quantity_cause_similar_packaging_field == 'on',
                            "picking_wrong_quantity_cause_change_in_packaging_field" => $request->picking_wrong_quantity_cause_change_in_packaging_field == 'on',
                            "picking_wrong_quantity_cause_did_not_recheck_field" => $request->picking_wrong_quantity_cause_did_not_recheck_field == 'on',
                            "picking_wrong_quantity_cause_other_field" => $request->picking_wrong_quantity_cause_other_field == 'on',
                            "picking_wrong_quantity_cause_counting_mistake_label" => $request->picking_wrong_quantity_cause_counting_mistake_label,
                            "picking_wrong_quantity_cause_calculation_error_label" => $request->picking_wrong_quantity_cause_calculation_error_label,
                            "picking_wrong_quantity_cause_similar_packaging_label" => $request->picking_wrong_quantity_cause_similar_packaging_label,
                            "picking_wrong_quantity_cause_change_in_packaging_label" => $request->picking_wrong_quantity_cause_change_in_packaging_label,
                            "picking_wrong_quantity_cause_did_not_recheck_label" => $request->picking_wrong_quantity_cause_did_not_recheck_label,
                            "picking_wrong_quantity_cause_other_label" => $request->picking_wrong_quantity_cause_other_label,
                        ]
                        
                    ],
                    'wrong_strength' => [
                        'hidden' => $request->picking_wrong_strength_field == 'on',
                        'label' => $request->wrong_strength_label,
                        'prescribed_item' => [
                            'hidden' => $request->picking_wrong_strength_prescribed_field == 'on',
                            'mandatory' => $request->picking_wrong_strength_prescribed_mandatory == 'on',
                        ],
                        'labelled_item' => [
                            'hidden' => $request->picking_wrong_strength_labelled_field == 'on',
                            'mandatory' => $request->picking_wrong_strength_labelled_mandatory == 'on',
                        ],
                        "reason" => [
                            "picking_wrong_strength_cause_similar_name_items_field" => $request->picking_wrong_strength_cause_similar_name_items_field == 'on',
                            "picking_wrong_strength_cause_similar_packaging_field" => $request->picking_wrong_strength_cause_similar_packaging_field == 'on',
                            "picking_wrong_strength_cause_change_in_packaging_field" => $request->picking_wrong_strength_cause_change_in_packaging_field == 'on',
                            "picking_wrong_strength_cause_unclear_handwritten_prescription_field" => $request->picking_wrong_strength_cause_unclear_handwritten_prescription_field == 'on',
                            "picking_wrong_strength_cause_misread_prescription_field" => $request->picking_wrong_strength_cause_misread_prescription_field == 'on',
                            "picking_wrong_strength_cause_imported_pi_pack_field" => $request->picking_wrong_strength_cause_imported_pi_pack_field == 'on',
                            "picking_wrong_strength_cause_dispensed_from_picking_label_field" => $request->picking_wrong_strength_cause_dispensed_from_picking_label_field == 'on',
                            "picking_wrong_strength_cause_unfamiliar_with_item_field" => $request->picking_wrong_strength_cause_unfamiliar_with_item_field == 'on',
                            "picking_wrong_strength_cause_did_not_recheck_field" => $request->picking_wrong_strength_cause_did_not_recheck_field == 'on',
                            "picking_wrong_strength_cause_other_field" => $request->picking_wrong_strength_cause_other_field == 'on',
                            "picking_wrong_strength_cause_similar_name_items_label" => $request->picking_wrong_strength_cause_similar_name_items_label,
                            "picking_wrong_strength_cause_similar_packaging_label" => $request->picking_wrong_strength_cause_similar_packaging_label,
                            "picking_wrong_strength_cause_change_in_packaging_label" => $request->picking_wrong_strength_cause_change_in_packaging_label,
                            "picking_wrong_strength_cause_unclear_handwritten_prescription_label" => $request->picking_wrong_strength_cause_unclear_handwritten_prescription_label,
                            "picking_wrong_strength_cause_misread_prescription_label" => $request->picking_wrong_strength_cause_misread_prescription_label,
                            "picking_wrong_strength_cause_imported_pi_pack_label" => $request->picking_wrong_strength_cause_imported_pi_pack_label,
                            "picking_wrong_strength_cause_dispensed_from_picking_label_label" => $request->picking_wrong_strength_cause_dispensed_from_picking_label_label,
                            "picking_wrong_strength_cause_unfamiliar_with_item_label" => $request->picking_wrong_strength_cause_unfamiliar_with_item_label,
                            "picking_wrong_strength_cause_did_not_recheck_label" => $request->picking_wrong_strength_cause_did_not_recheck_label,
                            "picking_wrong_strength_cause_other_label" => $request->picking_wrong_strength_cause_other_label,

                        ]                                               
                    ],
                ],
                'placing_to_basket' => [
                    "another_patient_label_basket" => [
                        'hidden' => $request->another_patient_label_basket == 'on',
                        'label' => $request->another_patient_label_basket_label ?? 'Another patient\'s labels in/on the basket',
                        "reason" => [
                            "placing_basket_another_patient_cause_weak_adhesive_labels_field" => $request->placing_basket_another_patient_cause_weak_adhesive_labels_field == 'on',
                            "placing_basket_another_patient_cause_area_cluttered_baskets_field" => $request->placing_basket_another_patient_cause_area_cluttered_baskets_field == 'on',
                            "placing_basket_another_patient_cause_basket_was_moved_field" => $request->placing_basket_another_patient_cause_basket_was_moved_field == 'on',
                            "placing_basket_another_patient_cause_labels_stuck_basket_field" => $request->placing_basket_another_patient_cause_labels_stuck_basket_field == 'on',
                            "placing_basket_another_patient_cause_other_field" => $request->placing_basket_another_patient_cause_other_field == 'on',
                            "placing_basket_another_patient_cause_weak_adhesive_labels_label" => $request->placing_basket_another_patient_cause_weak_adhesive_labels_label,
                            "placing_basket_another_patient_cause_area_cluttered_baskets_label" => $request->placing_basket_another_patient_cause_area_cluttered_baskets_label,
                            "placing_basket_another_patient_cause_basket_was_moved_label" => $request->placing_basket_another_patient_cause_basket_was_moved_label,
                            "placing_basket_another_patient_cause_labels_stuck_basket_label" => $request->placing_basket_another_patient_cause_labels_stuck_basket_label,
                            "placing_basket_another_patient_cause_other_label" => $request->placing_basket_another_patient_cause_other_label,

                        ]                                               
                    ],
                    'wrong_basket' => [
                        'hidden' => $request->wrong_basket == 'on',
                        'label' => $request->wrong_basket_label ?? 'Placed into the wrong basket',
                        "reason" => [
                            "placing_basket_wrong_basket_cause_area_cluttered_baskets_field" => $request->placing_basket_wrong_basket_cause_area_cluttered_baskets_field == 'on',
                            "placing_basket_wrong_basket_cause_basket_was_moved_field" => $request->placing_basket_wrong_basket_cause_basket_was_moved_field == 'on',
                            "placing_basket_wrong_basket_cause_other_field" => $request->placing_basket_wrong_basket_cause_other_field == 'on',
                            "placing_basket_wrong_basket_cause_area_cluttered_baskets_label" => $request->placing_basket_wrong_basket_cause_area_cluttered_baskets_label,
                            "placing_basket_wrong_basket_cause_basket_was_moved_label" => $request->placing_basket_wrong_basket_cause_basket_was_moved_label,
                            "placing_basket_wrong_basket_cause_other_label" => $request->placing_basket_wrong_basket_cause_other_label,

                        ]                                               
                    ],
                    'missing_item' => [
                        'hidden' => $request->missing_item == 'on',
                        'label' => $request->missing_item_label ?? 'Missing item',
                        "reason" => [
                            "placing_basket_missing_item_cause_missing_cd_sticket_field" => $request->placing_basket_missing_item_cause_missing_cd_sticket_field == 'on',
                            "placing_basket_missing_item_cause_label_lost_field" => $request->placing_basket_missing_item_cause_label_lost_field == 'on',
                            "placing_basket_missing_item_cause_distracted_item_left_out_field" => $request->placing_basket_missing_item_cause_distracted_item_left_out_field == 'on',
                            "placing_basket_missing_item_cause_other_field" => $request->placing_basket_missing_item_cause_other_field == 'on',
                            "placing_basket_missing_item_cause_missing_cd_sticket_label" => $request->placing_basket_missing_item_cause_missing_cd_sticket_label,
                            "placing_basket_missing_item_cause_label_lost_label" => $request->placing_basket_missing_item_cause_label_lost_label,
                            "placing_basket_missing_item_cause_distracted_item_left_out_label" => $request->placing_basket_missing_item_cause_distracted_item_left_out_label,
                            "placing_basket_missing_item_cause_other_label" => $request->placing_basket_missing_item_cause_other_label,

                        ]                                               
                    ],
                    'label_wrong_item' => [
                        'hidden' => $request->label_wrong_item == 'on',
                        'label' => $request->label_wrong_item_label ?? 'Label attached to the wrong item',
                        "reason" => [
                            "placing_basket_wrong_item_cause_weak_adhesive_labels_field" => $request->placing_basket_wrong_item_cause_weak_adhesive_labels_field == 'on',
                            "placing_basket_wrong_item_cause_similar_name_items_field" => $request->placing_basket_wrong_item_cause_similar_name_items_field == 'on',
                            "placing_basket_wrong_item_cause_similar_packaging_field" => $request->placing_basket_wrong_item_cause_similar_packaging_field == 'on',
                            "placing_basket_wrong_item_cause_change_in_packaging_field" => $request->placing_basket_wrong_item_cause_change_in_packaging_field == 'on',
                            "placing_basket_wrong_item_cause_did_not_recheck_field" => $request->placing_basket_wrong_item_cause_did_not_recheck_field == 'on',
                            "placing_basket_wrong_item_cause_other_field" => $request->placing_basket_wrong_item_cause_other_field == 'on',
                            "placing_basket_wrong_item_cause_weak_adhesive_labels_label" => $request->placing_basket_wrong_item_cause_weak_adhesive_labels_label,
                            "placing_basket_wrong_item_cause_similar_name_items_label" => $request->placing_basket_wrong_item_cause_similar_name_items_label,
                            "placing_basket_wrong_item_cause_similar_packaging_label" => $request->placing_basket_wrong_item_cause_similar_packaging_label,
                            "placing_basket_wrong_item_cause_change_in_packaging_label" => $request->placing_basket_wrong_item_cause_change_in_packaging_label,
                            "placing_basket_wrong_item_cause_did_not_recheck_label" => $request->placing_basket_wrong_item_cause_did_not_recheck_label,
                            "placing_basket_wrong_item_cause_other_label" => $request->placing_basket_wrong_item_cause_other_label,

                        ]                                                                        
                    ],
                ],
                'bagging' => [
                    "wrong_bag_label" => [
                        'hidden' => $request->wrong_bag_label == 'on',
                        'label' => $request->wrong_bag_label_text,
                        "reason" => [
                            "reason_text" => $request->another_patient_med_in_bag_reason,
                            "bagging_wrong_bag_label_cause_similar_patient_name_field" => $request->bagging_wrong_bag_label_cause_similar_patient_name_field == 'on',
                            "bagging_wrong_bag_label_cause_label_reprinted_field" => $request->bagging_wrong_bag_label_cause_label_reprinted_field == 'on',
                            "bagging_wrong_bag_label_cause_cluttered_bagging_area_field" => $request->bagging_wrong_bag_label_cause_cluttered_bagging_area_field == 'on',
                            "bagging_wrong_bag_label_cause_other_field" => $request->bagging_wrong_bag_label_cause_other_field == 'on',
                            "bagging_wrong_bag_label_cause_similar_patient_name_label" => $request->bagging_wrong_bag_label_cause_similar_patient_name_label,
                            "bagging_wrong_bag_label_cause_label_reprinted_label" => $request->bagging_wrong_bag_label_cause_label_reprinted_label,
                            "bagging_wrong_bag_label_cause_cluttered_bagging_area_label" => $request->bagging_wrong_bag_label_cause_cluttered_bagging_area_label,
                            "bagging_wrong_bag_label_cause_other_label" => $request->bagging_wrong_bag_label_cause_other_label,

                        ]                                                                       
                    ],
                    "another_patient_med_in_bag" => [
                        'hidden' => $request->another_patient_med_in_bag == 'on',
                        'label' => $request->another_patient_med_in_bag_text,
                        "reason" => [
                            "bagging_another_patient_med_in_bag_cause_similar_patient_name_field" => $request->bagging_another_patient_med_in_bag_cause_similar_patient_name_field == 'on',
                            "bagging_another_patient_med_in_bag_cause_cluttered_bagging_area_field" => $request->bagging_another_patient_med_in_bag_cause_cluttered_bagging_area_field == 'on',
                            "bagging_another_patient_med_in_bag_cause_bag_different_patient_field" => $request->bagging_another_patient_med_in_bag_cause_bag_different_patient_field == 'on',
                            "bagging_another_patient_med_in_bag_cause_other_field" => $request->bagging_another_patient_med_in_bag_cause_other_field == 'on',
                            "bagging_another_patient_med_in_bag_cause_similar_patient_name_label" => $request->bagging_another_patient_med_in_bag_cause_similar_patient_name_label,
                            "bagging_another_patient_med_in_bag_cause_cluttered_bagging_area_label" => $request->bagging_another_patient_med_in_bag_cause_cluttered_bagging_area_label,
                            "bagging_another_patient_med_in_bag_cause_bag_different_patient_label" => $request->bagging_another_patient_med_in_bag_cause_bag_different_patient_label,
                            "bagging_another_patient_med_in_bag_cause_other_label" => $request->bagging_another_patient_med_in_bag_cause_other_label,

                        ]                                                                      
                    ],
                    'missed_items' => [
                        'hidden' => $request->missed_items == 'on',
                        'label' => $request->missed_items_text,
                        "reason" => [
                            "bagging_missed_items_cause_cluttered_bagging_area_field" => $request->bagging_missed_items_cause_cluttered_bagging_area_field == 'on',
                            "bagging_missed_items_cause_distracted_item_left_out_field" => $request->bagging_missed_items_cause_distracted_item_left_out_field == 'on',
                            "bagging_missed_items_cause_missing_cd_sticker_field" => $request->bagging_missed_items_cause_missing_cd_sticker_field == 'on',
                            "bagging_missed_items_cause_other_field" => $request->bagging_missed_items_cause_other_field == 'on',
                            "bagging_missed_items_cause_cluttered_bagging_area_label" => $request->bagging_missed_items_cause_cluttered_bagging_area_label,
                            "bagging_missed_items_cause_distracted_item_left_out_label" => $request->bagging_missed_items_cause_distracted_item_left_out_label,
                            "bagging_missed_items_cause_missing_cd_sticker_label" => $request->bagging_missed_items_cause_missing_cd_sticker_label,
                            "bagging_missed_items_cause_other_label" => $request->bagging_missed_items_cause_other_label,

                        ]                                                                     
                    ]
                ],
                'preparing_dosette_tray' => [
                    "wrong_day_or_time_of_day" => [
                        "hidden" => $request->has('wrong_day_or_time_of_day') && $request->wrong_day_or_time_of_day == 'on',
                        "label" => $request->wrong_day_or_time_of_day_label
                    ],
                    "error_on_patient_mar_chart" => [
                        "hidden" => $request->has('error_on_patient_mar_chart') && $request->error_on_patient_mar_chart == 'on',
                        "label" => $request->error_on_patient_mar_chart_label
                    ],
                    "extra_quantity_in_tray" => [
                        "hidden" => $request->has('extra_quantity_in_tray') && $request->extra_quantity_in_tray == 'on',
                        "label" => $request->extra_quantity_in_tray_label
                    ],
                    "error_in_description_of_the_medication" => [
                        "hidden" => $request->has('error_in_description_of_the_medication') && $request->error_in_description_of_the_medication == 'on',
                        "label" => $request->error_in_description_of_the_medication_label
                    ],
                    "wrong_bag_label" => [
                        "hidden" => $request->has('tray_wrong_bag_label') && $request->tray_wrong_bag_label == 'on',
                        "label" => $request->wrong_bag_label_label
                    ],
                    "external_item_missing" => [
                        "hidden" => $request->has('external_item_missing') && $request->external_item_missing == 'on',
                        "label" => $request->external_item_missing_label
                    ],
                    "tray_item_missing" => [
                        "hidden" => $request->has('tray_item_missing') && $request->tray_item_missing == 'on',
                        "label" => $request->tray_item_missing_label
                    ],
                    "preparing_dosette_tray_error_on_blister_pack" => [
                        "hidden" => $request->has('preparing_dosette_tray_error_on_blister_pack') && $request->preparing_dosette_tray_error_on_blister_pack == 'on',
                        "label" => $request->preparing_dosette_tray_error_on_blister_pack_label,
                        "reason" => [
                            "preparing_dosette_tray_blistring_cause_discharge_not_actioned_field" => $request->has('preparing_dosette_tray_blistring_cause_discharge_not_actioned_field') && $request->preparing_dosette_tray_blistring_cause_discharge_not_actioned_field == 'on',
                            "preparing_dosette_tray_blistring_cause_change_not_communicated_field" => $request->has('preparing_dosette_tray_blistring_cause_change_not_communicated_field') && $request->preparing_dosette_tray_blistring_cause_change_not_communicated_field == 'on',
                            "preparing_dosette_tray_blistring_cause_other_field" => $request->has('preparing_dosette_tray_blistring_cause_other_field') && $request->preparing_dosette_tray_blistring_cause_other_field == 'on',
                            "preparing_dosette_tray_blistring_cause_discharge_not_actioned_label" => $request->preparing_dosette_tray_blistring_cause_discharge_not_actioned_label,
                            "preparing_dosette_tray_blistring_cause_change_not_communicated_label" => $request->preparing_dosette_tray_blistring_cause_change_not_communicated_label,
                            "preparing_dosette_tray_blistring_cause_other_label" => $request->preparing_dosette_tray_blistring_cause_other_label,
                        ]
                    ]
                ],

                'handing_out' => [
                    "handed_to_wrong_patient" => [
                    "hidden" => $request->has('handed_to_wrong_patient') && $request->handed_to_wrong_patient == 'on',
                    "reason" => [
                        "handing_out_to_wrong_patient_cause_similar_patient_name_field" => $request->has('handing_out_to_wrong_patient_cause_similar_patient_name_field') && $request->handing_out_to_wrong_patient_cause_similar_patient_name_field == 'on',
                        "handing_out_to_wrong_patient_cause_didnot_confirm_address_field" => $request->has('handing_out_to_wrong_patient_cause_didnot_confirm_address_field') && $request->handing_out_to_wrong_patient_cause_didnot_confirm_address_field == 'on',
                        "handing_out_to_wrong_patient_cause_other_field" => $request->has('handing_out_to_wrong_patient_cause_other_field') && $request->handing_out_to_wrong_patient_cause_other_field == 'on',
                        "handing_out_to_wrong_patient_cause_similar_patient_name_label" => $request->handing_out_to_wrong_patient_cause_similar_patient_name_label,
                        "handing_out_to_wrong_patient_cause_didnot_confirm_address_label" => $request->handing_out_to_wrong_patient_cause_didnot_confirm_address_label,
                        "handing_out_to_wrong_patient_cause_other_label" => $request->handing_out_to_wrong_patient_cause_other_label,
                  
                    ]
            ],
            'handed_to_wrong_patient_label' => $request->input('handed_to_wrong_patient_label', 'Handed to wrong patient') 
        ]
                
            ]
        ];
        // $sections = ['missing_signature', 'old_treatment' , 'fraudulent_tampered_prescription' , 'prescription_expired' ];
        // foreach ($sections as $section) {
        //     if (isset($request['reason'][$section]) &&!empty($request['reason'][$section])) {
        //         $data["extra_fields"]["prescription"][$section] = [
        //             "hidden" => $request->input("$section") == 'on',
        //             "label" => $request->input("{$section}_label"),
        //             "reason" => []
        //         ];
    
        //         // Process the dynamic fields for the section
        //         foreach ($request['reason'][$section] as $key => $value) {
        //             if (strpos($key, '_field') !== false) {
        //                 // Handle checkboxes (field)
        //                 $data["extra_fields"]["prescription"][$section]["reason"][$key] = $value == 'on';
        //             } 
        //             if (strpos($key, '_label') !== false) {
        //                 // Handle labels (text input)
        //                 $data["extra_fields"]["prescription"][$section]["reason"][$key] = $value;
        //             }
        //         }
        //     }
        // }
        
        $near_miss_setting->settings = json_encode($data);
        $near_miss_setting->save();
        return back()->with(['success' => 'Settings updated!']);
    }
}
