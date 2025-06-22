<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NearMiss extends Model
{
    use HasFactory;
    protected $table = 'near_misses';
    public static $errorTypePrescription = array(
        'prescription_missing_signature' => 'Missing signature',
        'prescription_expired' => 'Prescription expired',
        'prescription_old_treatment' => 'Old treatment',
        'prescription_tampered' => 'Fraudulent/tampered prescription',
    );
    public static $errorTypeLabelling = array(
        'labelling_wrong_brand' => 'Wrong brand',
        'labelling_wrong_direction' => 'Wrong direction',
        'labelling_wrong_item' => 'Wrong item',
        'labelling_wrong_formulation' => 'Wrong formulation',
        'labelling_wrong_patient' => 'Wrong patient',
        'labelling_wrong_quantity' => 'Wrong quantity',
        'labelling_wrong_strength' => 'Wrong strength',
    );
    public static $errorTypePicking = array(
        'picking_out_of_date_item' => 'Out-of-date item',
        'picking_wrong_brand' => 'Wrong brand',
        'picking_wrong_item' => 'Wrong item',
        'picking_wrong_quantity' => 'Wrong quantity',
        'picking_wrong_strength' => 'Wrong strength',
        'picking_wrong_formulation' => 'Wrong formulation'
    );
    public static $errorTypePlacingIntoBasket = array(
        'placing_basket_another_patient_label_basket' => "Another patient's labels in/on the basket",
        'placing_basket_wrong_basket' => 'Placed into the wrong basket',
        'placing_basket_missing_item' => 'Missing item',
        'placing_basket_label_wrong_item' => 'Label attached to the wrong item',

    );
    public static $errorTypeBagging = array(
        'bagging_wrong_bag_label' => "Wrong bag label",
        'bagging_another_patient_med_in_bag' => "Another patient's medication in bag",
        'bagging_missed_items' => 'Missed out items',
    );
    public static $errorTypePreparingDosetteTray = array(
        'preparing_dosette_tray_wrong_day_time' => "Wrong day/time of day",
        'preparing_dosette_tray_error_patient_mar_chart' => "Error on patient MAR Chart",
        'preparing_dosette_tray_extra_quantity_on_tray' => "Extra quantity in tray",
        'preparing_dosette_tray_error_in_description' => "Error in description of the medication",
        'preparing_dosette_tray_wrong_bag_label' => "Wrong bag label",
        'preparing_dosette_tray_external_item_missing' => "External item missing",
        'preparing_dosette_tray_tray_item_missing' => "Tray item missing",
        'preparing_dosette_tray_error_on_blister_pack' => "Error on blister pack guide sheet",
    );
    public static $errorTypeHandingOut= array(
        'handing_out_to_wrong_patient' => "Handed to wrong patient",
    );
    public static $contributingFactors = array(
        'Staff' => array(
            'contributing_factor_staff_fewer_staff' => "Fewer staff than usual",
            'contributing_factor_staff_not_usual_pharmacist' => "Not the usual pharmacist",
            'contributing_factor_staff_not_usual_dispense' => "Not the usual despneser",
            'contributing_factor_staff_pharmacist_self_checking' => "Pharmacist self-checking",
        ),
        'Person' => array(
            'contributing_factor_person_dyslexia' => 'Dyslexia',
            'contributing_factor_person_dyscalculia' => 'Dyscalculia'
        ),
        'Environment' => array(
            'contributing_factor_envirnoment_messy' => 'Messy environment',
        ),
        'Training' => array(
            'contributing_factor_training_person_in_training' => 'Person in training',
            'contributing_factor_training_person_not_trained' => 'Person not trained in this area',
        ),
        'Tasks & Workload' => array(
            'contributing_factor_task_and_workload_high_number_patients' => 'High number of patients waiting',
            'contributing_factor_task_and_workload_busy_otc_trade' => 'Busy OTC trade',
            'contributing_factor_task_and_workload_backlog_work' => 'Backlog of work',
            'contributing_factor_task_and_workload_quieter_than_usual' => 'Quieter than usual',
            'contributing_factor_task_and_workload_telephone_interruption' => 'Telephone interruption',
        ),
        'Other' => array(
            'contributing_factor_other' => 'Other',
            'contributing_factor_other_field' => 'Other contributing factor'
            )

    );
    public static $DrugsBasedOnErrorType = array(
        'prescription_expired' =>  array('prescription_expired_drug_name' => 'Controlled Drug'),
        'labelling_wrong_brand' => array('labelling_wrong_brand_drug_prescribed' => 'Prescribed Drug',
                                    'labelling_wrong_brand_drug_labelled' => 'Labelled Drug'),

       'labelling_wrong_item' => array('labelling_wrong_item_drug_prescribed' => 'Prescribed Drug',
                                       'labelling_wrong_item_drug_labelled' => 'Labelled Drug'),
       'labelling_wrong_formulation' => array('labelling_wrong_formulation_drug_prescribed' => 'Prescribed Drug',
                                              'labelling_wrong_formulation_drug_labelled' => 'Labelled Drug'),
       'labelling_wrong_strength' => array('labelling_wrong_strength_drug_prescribed' => 'Prescribed Drug',
                                           'labelling_wrong_strength_drug_labelled' => 'Labelled Drug'),
       'picking_out_of_date_item' =>  array('picking_out_of_date_item_drug_name' => 'Prescribed Drug'),
       'picking_wrong_brand' => array('picking_wrong_brand_drug_prescribed' => 'Prescribed Drug',
                                        'picking_wrong_brand_drug_labelled' => 'Labelled Drug'),
       'picking_wrong_item' => array('picking_wrong_item_drug_prescribed' => 'Prescribed Drug',
                                      'picking_wrong_item_drug_labelled' => 'Labelled Drug'),
       'picking_wrong_strength' => array('picking_wrong_strength_drug_prescribed' => 'Prescribed Drug',
                                         'picking_wrong_strength_drug_labelled' => 'Labelled Drug'),
       'picking_wrong_formulation' => array('picking_wrong_formulation_drug_prescribed' => 'Prescribed Drug',
                                            'picking_wrong_formulation_drug_labelled' => 'Labelled Drug'),
    );
    public static $PrescriptionReasonsOfNearMiss = array(
        'prescription_missing_signature' => array(
            'prescription_missing_signature_cause_legal_checks_not_done' => "Legal checks not done",
            'prescription_missing_signature_cause_not_trained' => "Not trained in this area",
            'prescription_missing_signature_cause_person_in_training' => "Person in training",
            'prescription_missing_signature_cause_other' => "Other",
            'prescription_missing_signature_cause_other_field' => "Other reason for error",
        ),
        'prescription_expired' => array(
            'prescription_expired_cause_date_not_checked' => "Date not checked",
            'prescription_expired_cause_legal_checks_not_done' => "Legal checks not done",
            'prescription_expired_sop_not_understood' => "SOP not understood",
            'prescription_expired_cause_other' => "Other",
            'prescription_expired_cause_other_field' => "Other reason for error",
        ),
        'prescription_old_treatment' => array(
            'prescription_old_treatment_cause_hospital_discharge_not_actioned' => "Hospital discharge not actioned",
            'prescription_old_treatment_cause_error_by_prescriber' => "Error by prescriber",
            'prescription_old_treatment_cause_change_not_communicated' => "Change not communicated between team",
            'prescription_old_treatment_cause_other' => "Other",
            'prescription_old_treatment_cause_other_field' => "Other reason for error",
        ),
        'prescription_tampered' => array(
            'prescription_tampered_cause_legal_checks_not_done' => "Legal checks not done",
            'prescription_tampered_cause_other' => "Other",
            'prescription_tampered_cause_other_field' => "Other reason for error",
        )
    );
    public static $LabellingReasonsOfNearMiss = array(
        'labelling_wrong_brand' => array(
            'labelling_wrong_brand_cause_similar_items' => "Similar name of items",
            'labelling_wrong_brand_cause_repeated_pmr_history' => "Repeated from PMR history",
            'labelling_wrong_brand_cause_misread_prescription' => "Misread prescription",
            'labelling_wrong_brand_cause_unclear_handwritten_prescription' => "Unclear handwritten prescription",
            'labelling_wrong_brand_cause_did_not_recheck' => "Did not re-check to confirm",
            'labelling_wrong_brand_cause_other' => "Other",
            'labelling_wrong_brand_cause_other_field' => "Other reason for error",
        ),
        'labelling_wrong_direction' => array(
            'labelling_wrong_direction_cause_unclear_directions' => "Unclear directions (electronic prescription)",
            'labelling_wrong_direction_cause_unclear_handwritten_prescription' => "Unclear handwritten prescription",
            'labelling_wrong_direction_cause_use_of_abbreviation' => "Use of abbreviations/latin",
            'labelling_wrong_direction_cause_abbreviation_not_changed' => "Abbreviations/latin not changed",
            'labelling_wrong_direction_cause_repeated_pmr_history' => "Repeated from PMR history",
            'labelling_wrong_direction_cause_did_not_recheck' => "Did not re-check to confirm",
            'labelling_wrong_direction_cause_other' => "Other",
            'labelling_wrong_direction_cause_other_field' => "Other reason for error",
        ),
        'labelling_wrong_item' => array(
            'labelling_wrong_item_cause_similar_items' => "Similar name of items",
            'labelling_wrong_item_cause_misread_prescription' => "Misread prescription",
            'labelling_wrong_item_cause_repeated_pmr_history' => "Repeated from PMR history",
            'labelling_wrong_item_cause_unclear_handwritten_prescription' => "Unclear handwritten prescription",
            'labelling_wrong_item_cause_did_not_recheck' => "Did not re-check to confirm",
            'labelling_wrong_item_cause_other' => "Other",
            'labelling_wrong_item_cause_other_field' => "Other reason for error",
        ),
        'labelling_wrong_formulation' => array(
            'labelling_wrong_formulation_cause_similar_items' => "Similar name of items",
            'labelling_wrong_formulation_cause_misread_prescription' => "Misread prescription",
            'labelling_wrong_formulation_cause_repeated_pmr_history' => "Repeated from PMR history",
            'labelling_wrong_formulation_cause_unclear_written_prescription' => "Unclear handwritten prescription",
            'labelling_wrong_formulation_cause_did_not_recheck' => "Did not re-check to confirm",
            'labelling_wrong_formulation_cause_other' => "Other",
            'labelling_wrong_formulation_cause_other_field' => "Other reason for error",
        ),
        'labelling_wrong_patient' => array(
            'labelling_wrong_patient_cause_similar_patient_name' => "Similar patient name",
            'labelling_wrong_patient_cause_misread_prescription' => "Misread prescription",
            'labelling_wrong_patient_cause_unclear_handwritten_prescription' => "Unclear handwritten prescription",
            'labelling_wrong_patient_cause_did_not_recheck' => "Did not re-check to confirm",
            'labelling_wrong_patient_cause_other' => "Other",
            'labelling_wrong_patient_cause_other_field' => "Other reason for error",
        ),
        'labelling_wrong_quantity' => array(
            'labelling_wrong_quantity_cause_unfamiliar_with_item' => "Unfamiliar with the item",
            'labelling_wrong_quantity_cause_calculation_error' => "Calculation error",
            'labelling_wrong_quantity_cause_misread_prescription' => "Misread prescription",
            'labelling_wrong_quantity_cause_use_of_abbreviation' => "Use of abbreviations/latin",
            'labelling_wrong_quantity_cause_unclear_handwritten_prescription' => "Unclear handwritten prescription",
            'labelling_wrong_quantity_cause_did_not_recheck' => "Did not re-check to confirm",
            'labelling_wrong_quantity_cause_other' => "Other",
            'labelling_wrong_quantity_cause_other_field' => "Other reason for error",
        ),
        'labelling_wrong_strength' => array(
            'labelling_wrong_strength_cause_misread_prescription' => "Misread prescription",
            'labelling_wrong_strength_cause_repeated_pmr_history' => "Repeated from PMR history",
            'labelling_wrong_strength_cause_unfamiliar_with_item' => "Unfamiliar with the item",
            'labelling_wrong_strength_cause_did_not_recheck' => "Did not re-check to confirm",
            'labelling_wrong_strength_cause_other' => "Other",
            'labelling_wrong_strength_cause_other_field' => "Other reason for error",
        ),
    );
    public static $PickingReasonsOfNearMiss = array(
        'picking_out_of_date_item' => array(
            'picking_out_of_date_item_cause_did_not_recheck' => "Did not re-check to confirm",
            'picking_out_of_date_item_cause_date_not_checked' => "Date not checked",
            'picking_out_of_date_item_cause_items_wrong_place' => "Item in wrong place on the shelf",
            'picking_out_of_date_item_cause_other' => "Other",
            'picking_out_of_date_item_cause_other_field' => "Other reason for error",
        ),
        'picking_wrong_brand' => array(
            'picking_wrong_brand_cause_similar_name_items' => "Similar name of items",
            'picking_wrong_brand_cause_similar_packaging' => "Similar packaging",
            'picking_wrong_brand_cause_change_in_packaging' => "Change in packaging",
            'picking_wrong_brand_cause_item_wrong_place' => "Item in wrong place on the shelf",
            'picking_wrong_brand_cause_unclear_handwritten_prescription' => "Unclear handwritten prescription",
            'picking_wrong_brand_cause_misread_prescription' => "Misread prescription",
            'picking_wrong_brand_cause_imported_pi_pack' => "Imported (PI) pack",
            'picking_wrong_brand_cause_dispensed_from_picking_label' => "Dispensed from the picking label",
            'picking_wrong_brand_cause_sop_not_understood' => "SOP not understood",
            'picking_wrong_brand_cause_unfamiliar_with_item' => "Unfamiliar with the item",
            'picking_wrong_brand_cause_did_not_recheck' => "Did not re-check to confirm",
            'picking_wrong_brand_cause_other' => "Other",
            'picking_wrong_brand_cause_other_field' => "Other reason for error",
        ),
        'picking_wrong_item' => array(
            'picking_wrong_item_cause_similar_name_items' => "Similar name of items",
            'picking_wrong_item_cause_similar_packaging' => "Similar packaging",
            'picking_wrong_item_cause_change_in_packaging' => "Change in packaging",
            'picking_wrong_item_cause_unclear_handwritten_prescription' => "Unclear handwritten prescription",
            'picking_wrong_item_cause_misread_prescription' => "Misread prescription",
            'picking_wrong_item_cause_imported_pi_pack' => "Imported (PI) pack",
            'picking_wrong_item_cause_dispensed_from_picking_label' => "Dispensed from the picking label",
            'picking_wrong_item_cause_unfamiliar_with_item' => "Unfamiliar with the item",
            'picking_wrong_item_cause_did_not_recheck' => "Did not re-check to confirm",
            'picking_wrong_item_cause_other' => "Other",
            'picking_wrong_item_cause_other_field' => "Other reason for error",
        ),
        'picking_wrong_quantity' => array(
            'picking_wrong_quantity_cause_counting_mistake' => "Counting mistake",
            'picking_wrong_quantity_cause_calculation_error' => "Calculation error",
            'picking_wrong_quantity_cause_similar_packaging' => "Similar packaging",
            'picking_wrong_quantity_cause_change_in_packaging' => "Change in packaging",
            'picking_wrong_quantity_cause_did_not_recheck' => "Did not re-check to confirm",
            'picking_wrong_quantity_cause_other' => "Other",
            'picking_wrong_quantity_cause_other_field' => "Other reason for error",
        ),
        'picking_wrong_strength' => array(
            'picking_wrong_strength_cause_similar_name_items' => "Similar name of items",
            'picking_wrong_strength_cause_similar_packaging' => "Similar packaging",
            'picking_wrong_strength_cause_change_in_packaging' => "Change in packaging",
            'picking_wrong_strength_cause_unclear_handwritten_prescription' => "Unclear handwritten prescription",
            'picking_wrong_strength_cause_misread_prescription' => "Misread prescription",
            'picking_wrong_strength_cause_imported_pi_pack' => "Imported (PI) pack",
            'picking_wrong_strength_cause_dispensed_from_picking_label' => "Dispensed from the picking label",
            'picking_wrong_strength_cause_unfamiliar_with_item' => "Unfamiliar with the item",
            'picking_wrong_strength_cause_did_not_recheck' => "Did not re-check to confirm",
            'picking_wrong_strength_cause_other' => "Other",
            'picking_wrong_strength_cause_other_field' => "Other reason for error",
        ),
        'picking_wrong_formulation' => array(
            'picking_wrong_formulation_cause_similar_name_items' => "Similar name of items",
            'picking_wrong_formulation_cause_similar_packaging' => "Similar packaging",
            'picking_wrong_formulation_cause_change_in_packaging' => "Change in packaging",
            'picking_wrong_formulation_cause_unclear_handwritten_prescription' => "Unclear handwritten prescription",
            'picking_wrong_formulation_cause_misread_prescription' => "Misread prescription",
            'picking_wrong_formulation_cause_imported_pi_pack' => "Imported (PI) pack",
            'picking_wrong_formulation_cause_dispensed_from_picking_label' => "Dispensed from the picking label",
            'picking_wrong_formulation_cause_unfamiliar_with_item' => "Unfamiliar with the item",
            'picking_wrong_formulation_cause_did_not_recheck' => "Did not re-check to confirm",
            'picking_wrong_formulation_cause_other' => "Other",
            'picking_wrong_formulation_cause_other_field' => "Other reason for error",
        ),
    );
    public static $PlacingIntoBasketReasonsOfNearMiss = array(
        'placing_basket_another_patient_label_basket' => array(
            'placing_basket_another_patient_cause_weak_adhesive_labels' => "Weak adhesive on labels",
            'placing_basket_another_patient_cause_area_cluttered_baskets' => "Area cluttered with baskets",
            'placing_basket_another_patient_cause_basket_was_moved' => "Basket was moved",
            'placing_basket_another_patient_cause_labels_stuck_basket' => "Labels stuck on basket resulting in them sticking to others",
            'placing_basket_another_patient_cause_other' => "Other",
            'placing_basket_another_patient_cause_other_field' => "Other reason for error",
        ),
        'placing_basket_wrong_basket' => array(
            'placing_basket_wrong_basket_cause_area_cluttered_baskets' => "Area cluttered with baskets",
            'placing_basket_wrong_basket_cause_basket_was_moved' => "Basket was moved",
            'placing_basket_wrong_basket_cause_other' => "Other",
            'placing_basket_wrong_basket_cause_other_field' => "Other reason for error",
        ),
        'placing_basket_missing_item' => array(
            'placing_basket_missing_item_cause_missing_cd_sticket' => "Missing CD/Fridge sticker",
            'placing_basket_missing_item_cause_label_lost' => "Label lost so not dispensed",
            'placing_basket_missing_item_cause_distracted_item_left_out' => "Distracted so item left out",
            'placing_basket_missing_item_cause_other' => "Other",
            'placing_basket_missing_item_cause_other_field' => "Other reason for error",
        ),
        'placing_basket_label_wrong_item' => array(
            'placing_basket_wrong_item_cause_weak_adhesive_labels' => "Weak adhesive on labels",
            'placing_basket_wrong_item_cause_similar_name_items' => "Similar name of items",
            'placing_basket_wrong_item_cause_similar_packaging' => "Similar packaging",
            'placing_basket_wrong_item_cause_change_in_packaging' => "Change in packaging",
            'placing_basket_wrong_item_cause_did_not_recheck' => "Did not re-check to confirm",
            'placing_basket_wrong_item_cause_other' => "Other",
            'placing_basket_wrong_item_cause_other_field' => "Other reason for error",
        ),
    );
    public static $BaggingReasonsOfNearMiss = array(
        'bagging_wrong_bag_label' => array(
            'bagging_wrong_bag_label_cause_similar_patient_name' => "Similar patient name",
            'bagging_wrong_bag_label_cause_label_reprinted' => "Bag label re-printed",
            'bagging_wrong_bag_label_cause_cluttered_bagging_area' => "Cluttered bagging area",
            'bagging_wrong_bag_label_cause_other' => "Other",
            'bagging_wrong_bag_label_cause_other_field' => "Other reason for error",
        ),
        'bagging_another_patient_med_in_bag' => array(
            'bagging_another_patient_med_in_bag_cause_similar_patient_name' => "Similar patient name",
            'bagging_another_patient_med_in_bag_cause_cluttered_bagging_area' => "Cluttered bagging area",
            'bagging_another_patient_med_in_bag_cause_bag_different_patient' => "Bagging different patient's at the same time",
            'bagging_another_patient_med_in_bag_cause_other' => "Other",
            'bagging_another_patient_med_in_bag_cause_other_field' => "Other reason for error",
        ),
        'bagging_missed_items' => array(
            'bagging_missed_items_cause_cluttered_bagging_area' => "Cluttered bagging area",
            'bagging_missed_items_cause_distracted_item_left_out' => "Distracted so item left out",
            'bagging_missed_items_cause_missing_cd_sticker' => "Missing CD/Fridge sticker",
            'bagging_missed_items_cause_other' => "Other",
            'bagging_missed_items_cause_other_field' => "Other reason for error",
        ),
    );
    public static $PreparingDosetteTrayReasonsOfNearMiss = array(
        'preparing_dosette_tray_error_on_blister_pack' => array(
            'preparing_dosette_tray_blistring_cause_discharge_not_actioned' => "Hospital discharge not actioned",
            'preparing_dosette_tray_blistring_cause_change_not_communicated' => "Change not communicated between team",
            'preparing_dosette_tray_blistring_cause_other' => "Other",
            'preparing_dosette_tray_blistring_cause_other_field' => "Other reason for error",
        ),
    );
    public static $HandingReasonsOfNearMiss = array(
        'handing_out_to_wrong_patient' => array(
            'handing_out_to_wrong_patient_cause_similar_patient_name' => "Similar patient name",
            'handing_out_to_wrong_patient_cause_didnot_confirm_address' => "Did not confirm address/DOB",
            'handing_out_to_wrong_patient_cause_other' => "Other",
            'handing_out_to_wrong_patient_cause_other_field' => "Other reason for error",
        ),
    );

    public static function FindResonsBasedOnErrorType($errorType){
        if(isset(self::$PrescriptionReasonsOfNearMiss[$errorType])){
            return self::$PrescriptionReasonsOfNearMiss[$errorType];
        }
        if(isset(self::$LabellingReasonsOfNearMiss[$errorType])){
            return self::$LabellingReasonsOfNearMiss[$errorType];
        }
        if(isset(self::$PickingReasonsOfNearMiss[$errorType])){
            return self::$PickingReasonsOfNearMiss[$errorType];
        }
        if(isset(self::$PlacingIntoBasketReasonsOfNearMiss[$errorType])){
            return self::$PlacingIntoBasketReasonsOfNearMiss[$errorType];
        }
        if(isset(self::$BaggingReasonsOfNearMiss[$errorType])){
            return self::$BaggingReasonsOfNearMiss[$errorType];
        }
        if(isset(self::$PreparingDosetteTrayReasonsOfNearMiss[$errorType])){
            return self::$PreparingDosetteTrayReasonsOfNearMiss[$errorType];
        }
        if(isset(self::$HandingReasonsOfNearMiss[$errorType])){
            return self::$HandingReasonsOfNearMiss[$errorType];
        }
       

        return array();
    }
    public static function DetectIfErrorType($request, $errorType){
        foreach($errorType as $key=>$type){
            if((int)$request->$key){
                return 1;
            }
        }
        return 0;
    }
    public static function allErrorTypes(){
        return array_merge(NearMiss::$errorTypePrescription,NearMiss::$errorTypeLabelling,NearMiss::$errorTypePicking,
         NearMiss::$errorTypePlacingIntoBasket,NearMiss::$errorTypeBagging,NearMiss::$errorTypePreparingDosetteTray
        ,NearMiss::$errorTypeHandingOut);
    }
    public static function MakeNearMissesFromForm($request){
        $nearMisses = array();
        foreach(NearMiss::$errorTypePrescription as $key=>$type){
            if ((int) $request->$key) {
                $nearMisses['Prescription'][] = $key;
            }
        }
        foreach(NearMiss::$errorTypeLabelling as $key=>$type){
            if ((int) $request->$key) {
                $nearMisses['Labelling'][] = $key;
            }
        }
        foreach(NearMiss::$errorTypePicking as $key=>$type){
            if ((int) $request->$key) {
                $nearMisses['Picking'][] = $key;
            }
        }
        foreach(NearMiss::$errorTypePlacingIntoBasket as $key=>$type){
            if ((int) $request->$key) {
                $nearMisses['Placing into Basket'][] = $key;
            }
        }
        foreach(NearMiss::$errorTypeBagging as $key=>$type){
            if ((int) $request->$key) {
                $nearMisses['Bagging'][] = $key;
            }
        }
        foreach(NearMiss::$errorTypePreparingDosetteTray as $key=>$type){
            if ((int) $request->$key) {
                $nearMisses['Preparing Dosette Tray'][] = $key;
            }
        }
        foreach(NearMiss::$errorTypeHandingOut as $key=>$type){
            if ((int) $request->$key) {
                $nearMisses['Handing Out'][] = $key;
            }
        }
        return $nearMisses;
    }


    public function location()
    {
        return $this->hasOne(Location::class,'location_id');
    }
    public function date(){
        return \Carbon\Carbon::createFromFormat('Y-m-d',$this->date)->format('d/m/Y');
    }
    public function day(){
        return \Carbon\Carbon::createFromFormat('Y-m-d',$this->date)->format('D');
    }
    public function time(){
        return date('h:i a', strtotime($this->time));
    }
    public function errorBy(){
        if($this->error_by){
            if($this->error_by == 'Other'){
                return $this->error_by_other;
            }
            return $this->error_by;
        }
        return '';
    }
    public function errorDetectedBy(){
        if($this->error_detected_by){
            if($this->error_detected_by == 'Other'){
                return $this->error_detected_by_other;
            }
            return $this->error_detected_by;
        }
        return '';
    }
    public function error($returnField = false){
        foreach(self::$errorTypePrescription as $key=>$type){
            if($this->$key){
                return ($returnField) ? $key : $type;
            }
        }
        foreach(self::$errorTypeLabelling as $key=>$type){
            if($this->$key){
                return ($returnField) ? $key : $type;
            }
        }
        foreach(self::$errorTypePicking as $key=>$type){
            if($this->$key){
                return ($returnField) ? $key : $type;
            }
        }
        foreach(self::$errorTypePlacingIntoBasket as $key=>$type){
            if($this->$key){
                return ($returnField) ? $key : $type;
            }
        }
        foreach(self::$errorTypeBagging as $key=>$type){
            if($this->$key){
                return ($returnField) ? $key : $type;
            }
        }
        foreach(self::$errorTypePreparingDosetteTray as $key=>$type){
            if($this->$key){
                return ($returnField) ? $key : $type;
            }
        }
        foreach(self::$errorTypeHandingOut as $key=>$type){
            if($this->$key){
                return ($returnField) ? $key : $type;
            }
        }
        return '';
    }

    public function hasDrugsData()
{
    return ($this->prescription_expired && $this->prescription_expired_involve_drug && !empty(trim($this->prescription_expired_drug_name))) ||
           (!empty(trim($this->labelling_wrong_brand_drug_prescribed)) && !empty(trim($this->labelling_wrong_brand_drug_labelled))) ||
           (!empty(trim($this->labelling_wrong_item_drug_prescribed)) && !empty(trim($this->labelling_wrong_item_drug_labelled))) ||
           (!empty(trim($this->labelling_wrong_formulation_drug_prescribed)) && !empty(trim($this->labelling_wrong_formulation_drug_labelled))) ||
           (!empty(trim($this->labelling_wrong_strength_drug_prescribed)) && !empty(trim($this->labelling_wrong_strength_drug_labelled))) ||
           !empty(trim($this->picking_out_of_date_item_drug_name)) ||
           (!empty(trim($this->picking_wrong_brand_drug_prescribed)) && !empty(trim($this->picking_wrong_brand_drug_labelled))) ||
           (!empty(trim($this->picking_wrong_item_drug_prescribed)) && !empty(trim($this->picking_wrong_item_drug_labelled))) ||
           (!empty(trim($this->picking_wrong_strength_drug_prescribed)) && !empty(trim($this->picking_wrong_strength_drug_labelled))) ||
           (!empty(trim($this->picking_wrong_formulation_drug_prescribed)) && !empty(trim($this->picking_wrong_formulation_drug_labelled)));
}

    
    public function generateDrugsData(){
        $data = "";
        //<li>Does this involve a controlled drug?</li> 
        if($this->prescription_expired && $this->prescription_expired_involve_drug){
            $data .= "Controlled Drug:<br>" . $this->prescription_expired_drug_name."<br><br>";
        }

        if($this->labelling_wrong_brand){
            $data .= "Prescribed Drug:<br>" . $this->labelling_wrong_brand_drug_prescribed."<br><br>";
            $data .= "Labelled Drug:<br>" . $this->labelling_wrong_brand_drug_labelled."<br><br>";
        }
        if($this->labelling_wrong_item){
            $data .= "Prescribed Drug:<br>" . $this->labelling_wrong_item_drug_prescribed."<br><br>";
            $data .= "Labelled Drug:<br>" . $this->labelling_wrong_item_drug_labelled."<br><br>";
        }
        if($this->labelling_wrong_formulation){
            $data .= "Prescribed Drug:<br>" . $this->labelling_wrong_formulation_drug_prescribed."<br><br>";
            $data .= "Labelled Drug:<br>" . $this->labelling_wrong_formulation_drug_labelled."<br><br>";
        }

        if($this->labelling_wrong_strength){
            $data .= "Prescribed Drug:<br>" . $this->labelling_wrong_strength_drug_prescribed."<br><br>";
            $data .= "Labelled Drug:<br>" . $this->labelling_wrong_strength_drug_labelled."<br><br>";
        }

        if($this->picking_out_of_date_item){
            $data .= "Out-of-date Drug:<br>" . $this->picking_out_of_date_item_drug_name."<br><br>";
        }

        if($this->picking_wrong_brand){
            $data .= "Prescribed Drug:<br>" . $this->picking_wrong_brand_drug_prescribed."<br><br>";
            $data .= "Labelled Drug:<br>" . $this->picking_wrong_brand_drug_labelled."<br><br>";
        }

        if($this->picking_wrong_item){
            $data .= "Prescribed Drug:<br>" . $this->picking_wrong_item_drug_prescribed."<br><br>";
            $data .= "Labelled Drug:<br>" . $this->picking_wrong_item_drug_labelled."<br><br>";
        }
        if($this->picking_wrong_strength){
            $data .= "Prescribed Drug:<br>" . $this->picking_wrong_strength_drug_prescribed."<br><br>";
            $data .= "Labelled Drug:<br>" . $this->picking_wrong_strength_drug_labelled."<br><br>";
        }
        if($this->picking_wrong_formulation){
            $data .= "Prescribed Drug:<br>" . $this->picking_wrong_formulation_drug_prescribed."<br><br>";
            $data .= "Labelled Drug:<br>" . $this->picking_wrong_formulation_drug_labelled."<br><br>";
        }
        return $data;

    }

    public function reasons(){
        $errorType = $this->error(true);
        $reasons = self::FindResonsBasedOnErrorType($errorType);
        $selectedReasons = array();
        foreach($reasons as $field=>$reason){
            if ($this->$field) {
                # Change specified that other is shown as Other (cause)
                if ($reason == 'Other') {
                    continue;
                }
                elseif(Str::contains($field,'other_field')){
                    $selectedReasons[] = "Other (".$this->$field.")";
                }else{
                    $selectedReasons[] = $reason;
                }
            }
        }
        return $selectedReasons;
    }
    public function generateContributingFactorsData(){
        $factors = array();

        foreach(self::$contributingFactors as $label=>$types){
            foreach($types as $key=>$type){
                if($this->$key){
                    if ($type == 'Other') {
                        continue;
                    }
                    elseif($key == 'contributing_factor_other_field'){
                        $factors[$label][] = "Other (".$this->$key.")";
                    }else{
                        $factors[$label][] = $type;
                    }
                }
            }
        }
        return $factors;
    }
    public function formIcon(){
        switch($this->what_was_error){
            case 'Prescription':
                return 'prescription_timeline.png';
                break;
        }
        
    }
    public function icon(){

        switch($this->what_was_error){
            case 'Prescription':
                return 'prescription_timeline.png';
            case 'Labelling':
                return 'labelling_timeline.png';
            case 'Picking':
                return 'picking_timeline.png';
            case 'Placing into Basket':
                return 'placing_into_basket_timeline.png';
            case 'Bagging':
                return 'bagging_timeline.png';
            case 'Preparing Dosette Tray':
                return 'preparing_dosette_tray_timeline.png';
            case 'Handing Out':
                return 'handing_out_timeline.png';
        }

        // if($this->point_of_detection == 'Labelling'){
        //     return 'labelling-active.png';
        // }
        // if($this->point_of_detection == 'Bagging'){
        //     return 'bagging-active.png';
        // }
        // if($this->point_of_detection == 'Filing Away'){
        //     return 'labelling-active.png';
        // }
        // if($this->point_of_detection == 'Delivering'){
        //     return 'labelling-active.png';
        // }
        // if($this->point_of_detection == 'Picking'){
        //     return 'labelling-active.png';
        // }
        // if($this->point_of_detection == 'Final Check'){
        //     return 'labelling-active.png';
        // }
        // if($this->point_of_detection == 'Handing Out'){
        //     return 'labelling-active.png';
        // }
    }
    public function totalErrors(){
        # Each nearmiss has only one error type for now. such as incorrect brand, wrong item etc.
        return 1;
    }
    public function canDelete(){
        $dateString = $this->date . " " . $this->time.":00";
        $date = strtotime($dateString);
        $today = time();

        $difference = floor(($today - $date)/(60*60));
       if($difference > 24){
            return false;
       }
        return true;
    }

    public function deletedBy(){
        $user = User::find($this->deleted_by);
        if($user){
            return $user->nameWithPosition();
        }
        return '';
    }
    public function near_miss_manager_relation(){
        $location = Auth::guard('location')->user();
        if(isset($location)){
            $headoffice = $location->head_office();
            $near_miss_manager = near_miss_manager::where('head_office_id', $headoffice->id)->first();
            return $near_miss_manager;
        }
        else{return null;}
    }
}
