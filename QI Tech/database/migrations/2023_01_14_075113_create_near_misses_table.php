<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('near_misses', function (Blueprint $table) {
            $table->id();
            /* Stage 1 */
            $table->bigInteger('location_id')->unsigned();
            $table->string('status');
            $table->string('time');
            $table->date('date');
            $table->string('error_by')->nullable();
            $table->string('error_by_other')->nullable();
            $table->string('error_detected_by')->nullable();
            $table->string('error_detected_by_other')->nullable();
            /* Stage 2 */
            $table->string('point_of_detection');

            $table->integer('error_prescription')->nullable()->default(0);
            $table->integer('error_labelling')->nullable()->default(0);
            $table->integer('error_picking')->nullable()->default(0);
            $table->integer('error_placing_into_basket')->nullable()->default(0);
            $table->integer('error_bagging')->nullable()->default(0);
            $table->integer('error_preparing_dosette_tray')->nullable()->default(0);
            $table->integer('error_handing_out')->nullable()->default(0);
            

            /** Prescirption */
            $table->integer('prescription_missing_signature')->nullable()->default(0);
                $table->integer('prescription_missing_signature_cause_legal_checks_not_done')->nullable()->default(0);
                $table->integer('prescription_missing_signature_cause_not_trained')->nullable()->default(0);
                $table->integer('prescription_missing_signature_cause_person_in_training')->nullable()->default(0);
                $table->integer('prescription_missing_signature_cause_other')->nullable()->default(0);
                $table->text('prescription_missing_signature_cause_other_field')->nullable();

            $table->integer('prescription_expired')->nullable()->default(0);
                $table->integer('prescription_expired_involve_drug')->nullable()->default(0);
                $table->text('prescription_expired_drug_name')->nullable();

                $table->integer('prescription_expired_cause_date_not_checked')->nullable()->default(0);
                $table->integer('prescription_expired_cause_legal_checks_not_done')->nullable()->default(0);
                $table->integer('prescription_expired_sop_not_understood')->nullable()->default(0);
                $table->integer('prescription_expired_cause_other')->nullable()->default(0);
                $table->text('prescription_expired_cause_other_field')->nullable();

            $table->integer('prescription_old_treatment')->nullable()->default(0);
                $table->integer('prescription_old_treatment_cause_hospital_discharge_not_actioned')->nullable()->default(0);         
                $table->integer('prescription_old_treatment_cause_error_by_prescriber')->nullable()->default(0);
                $table->integer('prescription_old_treatment_cause_change_not_communicated')->nullable()->default(0);
                $table->integer('prescription_old_treatment_cause_other')->nullable()->default(0);
                $table->text('prescription_old_treatment_cause_other_field')->nullable();
            
            $table->integer('prescription_tampered')->nullable()->default(0);
                $table->integer('prescription_tampered_cause_legal_checks_not_done')->nullable()->default(0);
                $table->integer('prescription_tampered_cause_other')->nullable()->default(0);
                $table->text('prescription_tampered_cause_other_field')->nullable();
           
            /** Labelling */
            $table->integer('labelling_wrong_brand')->nullable()->default(0);
                $table->text('labelling_wrong_brand_drug_prescribed')->nullable();
                $table->text('labelling_wrong_brand_drug_labelled')->nullable();

                $table->integer('labelling_wrong_brand_cause_similar_items')->nullable()->default(0);
                $table->integer('labelling_wrong_brand_cause_repeated_pmr_history')->nullable()->default(0);
                $table->integer('labelling_wrong_brand_cause_misread_prescription')->nullable()->default(0);
                $table->integer('labelling_wrong_brand_cause_unclear_handwritten_prescription')->nullable()->default(0);
                $table->integer('labelling_wrong_brand_cause_did_not_recheck')->nullable()->default(0);
                $table->integer('labelling_wrong_brand_cause_other')->nullable()->default(0);
                $table->text('labelling_wrong_brand_cause_other_field')->nullable();

            $table->integer('labelling_wrong_direction')->nullable()->default(0);
                $table->integer('labelling_wrong_direction_cause_unclear_directions')->nullable()->default(0);
                $table->integer('labelling_wrong_direction_cause_unclear_handwritten_prescription')->nullable()->default(0);
                $table->integer('labelling_wrong_direction_cause_use_of_abbreviation')->nullable()->default(0);
                $table->integer('labelling_wrong_direction_cause_abbreviation_not_changed')->nullable()->default(0);
                $table->integer('labelling_wrong_direction_cause_repeated_pmr_history')->nullable()->default(0);
                $table->integer('labelling_wrong_direction_cause_did_not_recheck')->nullable()->default(0);
                $table->integer('labelling_wrong_direction_cause_other')->nullable()->default(0);
                $table->text('labelling_wrong_direction_cause_other_field')->nullable();

            $table->integer('labelling_wrong_item')->nullable()->default(0);
                $table->text('labelling_wrong_item_drug_prescribed')->nullable();
                $table->text('labelling_wrong_item_drug_labelled')->nullable();

                $table->integer('labelling_wrong_item_cause_similar_items')->nullable()->default(0);
                $table->integer('labelling_wrong_item_cause_misread_prescription')->nullable()->default(0);
                $table->integer('labelling_wrong_item_cause_repeated_pmr_history')->nullable()->default(0);
                $table->integer('labelling_wrong_item_cause_unclear_handwritten_prescription')->nullable()->default(0);
                $table->integer('labelling_wrong_item_cause_did_not_recheck')->nullable()->default(0);
                $table->integer('labelling_wrong_item_cause_other')->nullable()->default(0);
                $table->text('labelling_wrong_item_cause_other_field')->nullable();

            $table->integer('labelling_wrong_formulation')->nullable()->default(0);
                $table->text('labelling_wrong_formulation_drug_prescribed')->nullable();
                $table->text('labelling_wrong_formulation_drug_labelled')->nullable();

                $table->integer('labelling_wrong_formulation_cause_similar_items')->nullable()->default(0);
                $table->integer('labelling_wrong_formulation_cause_misread_prescription')->nullable()->default(0);
                $table->integer('labelling_wrong_formulation_cause_repeated_pmr_history')->nullable()->default(0);
                $table->integer('labelling_wrong_formulation_cause_unclear_written_prescription')->nullable()->default(0);
                $table->integer('labelling_wrong_formulation_cause_did_not_recheck')->nullable()->default(0);
                $table->integer('labelling_wrong_formulation_cause_other')->nullable()->default(0);
                $table->text('labelling_wrong_formulation_cause_other_field')->nullable();

            $table->integer('labelling_wrong_patient')->nullable()->default(0);
                $table->integer('labelling_wrong_patient_cause_similar_patient_name')->nullable()->default(0);
                $table->integer('labelling_wrong_patient_cause_misread_prescription')->nullable()->default(0);
                $table->integer('labelling_wrong_patient_cause_unclear_handwritten_prescription')->nullable()->default(0);
                $table->integer('labelling_wrong_patient_cause_did_not_recheck')->nullable()->default(0);
                $table->integer('labelling_wrong_patient_cause_other')->nullable()->default(0);
                $table->text('labelling_wrong_patient_cause_other_field')->nullable();

            $table->integer('labelling_wrong_quantity')->nullable()->default(0);
                $table->integer('labelling_wrong_quantity_cause_unfamiliar_with_item')->nullable()->default(0);
                $table->integer('labelling_wrong_quantity_cause_calculation_error')->nullable()->default(0);
                $table->integer('labelling_wrong_quantity_cause_misread_prescription')->nullable()->default(0);
                $table->integer('labelling_wrong_quantity_cause_use_of_abbreviation')->nullable()->default(0);
                $table->integer('labelling_wrong_quantity_cause_unclear_handwritten_prescription')->nullable()->default(0);
                $table->integer('labelling_wrong_quantity_cause_did_not_recheck')->nullable()->default(0);
                $table->integer('labelling_wrong_quantity_cause_other')->nullable()->default(0);
                $table->text('labelling_wrong_quantity_cause_other_field')->nullable();


            $table->integer('labelling_wrong_strength')->nullable()->default(0);
                $table->text('labelling_wrong_strength_drug_prescribed')->nullable();
                $table->text('labelling_wrong_strength_drug_labelled')->nullable();

                $table->integer('labelling_wrong_strength_cause_misread_prescription')->nullable()->default(0);
                $table->integer('labelling_wrong_strength_cause_repeated_pmr_history')->nullable()->default(0);
                $table->integer('labelling_wrong_strength_cause_unfamiliar_with_item')->nullable()->default(0);
                $table->integer('labelling_wrong_strength_cause_did_not_recheck')->nullable()->default(0);
                $table->integer('labelling_wrong_strength_cause_other')->nullable()->default(0);
                $table->text('labelling_wrong_strength_cause_other_field')->nullable();

            /* Picking */
            $table->integer('picking_out_of_date_item')->nullable()->default(0);
                $table->text('picking_out_of_date_item_drug_name')->nullable();
                
                $table->integer('picking_out_of_date_item_cause_did_not_recheck')->nullable()->default(0);
                $table->integer('picking_out_of_date_item_cause_date_not_checked')->nullable()->default(0);
                $table->integer('picking_out_of_date_item_cause_items_wrong_place')->nullable()->default(0);
                $table->integer('picking_out_of_date_item_cause_other')->nullable()->default(0);
                $table->text('picking_out_of_date_item_cause_other_field')->nullable();


            $table->integer('picking_wrong_brand')->nullable()->default(0);
                $table->text('picking_wrong_brand_drug_prescribed')->nullable();
                $table->text('picking_wrong_brand_drug_labelled')->nullable();
                
                $table->integer('picking_wrong_brand_cause_similar_name_items')->nullable()->default(0);
                $table->integer('picking_wrong_brand_cause_similar_packaging')->nullable()->default(0);
                $table->integer('picking_wrong_brand_cause_change_in_packaging')->nullable()->default(0);
                $table->integer('picking_wrong_brand_cause_item_wrong_place')->nullable()->default(0);
                $table->integer('picking_wrong_brand_cause_unclear_handwritten_prescription')->nullable()->default(0);
                $table->integer('picking_wrong_brand_cause_misread_prescription')->nullable()->default(0);
                $table->integer('picking_wrong_brand_cause_imported_pi_pack')->nullable()->default(0);
                $table->integer('picking_wrong_brand_cause_dispensed_from_picking_label')->nullable()->default(0);
                $table->integer('picking_wrong_brand_cause_sop_not_understood')->nullable()->default(0);
                $table->integer('picking_wrong_brand_cause_unfamiliar_with_item')->nullable()->default(0);
                $table->integer('picking_wrong_brand_cause_did_not_recheck')->nullable()->default(0);
                $table->integer('picking_wrong_brand_cause_other')->nullable()->default(0);
                $table->text('picking_wrong_brand_cause_other_field')->nullable();



            $table->integer('picking_wrong_item')->nullable()->default(0);
                $table->text('picking_wrong_item_drug_prescribed')->nullable();
                $table->text('picking_wrong_item_drug_labelled')->nullable();

                $table->integer('picking_wrong_item_cause_similar_name_items')->nullable()->default(0);
                $table->integer('picking_wrong_item_cause_similar_packaging')->nullable()->default(0);
                $table->integer('picking_wrong_item_cause_change_in_packaging')->nullable()->default(0);
                $table->integer('picking_wrong_item_cause_unclear_handwritten_prescription')->nullable()->default(0);
                $table->integer('picking_wrong_item_cause_misread_prescription')->nullable()->default(0);
                $table->integer('picking_wrong_item_cause_imported_pi_pack')->nullable()->default(0);
                $table->integer('picking_wrong_item_cause_dispensed_from_picking_label')->nullable()->default(0);
                $table->integer('picking_wrong_item_cause_unfamiliar_with_item')->nullable()->default(0);
                $table->integer('picking_wrong_item_cause_did_not_recheck')->nullable()->default(0);
                $table->integer('picking_wrong_item_cause_other')->nullable()->default(0);
                $table->text('picking_wrong_item_cause_other_field')->nullable();


            $table->integer('picking_wrong_quantity')->nullable()->default(0);
                $table->integer('picking_wrong_quantity_cause_counting_mistake')->nullable()->default(0);
                $table->integer('picking_wrong_quantity_cause_calculation_error')->nullable()->default(0);
                $table->integer('picking_wrong_quantity_cause_similar_packaging')->nullable()->default(0);
                $table->integer('picking_wrong_quantity_cause_change_in_packaging')->nullable()->default(0);
                $table->integer('picking_wrong_quantity_cause_did_not_recheck')->nullable()->default(0);
                $table->integer('picking_wrong_quantity_cause_other')->nullable()->default(0);
                $table->text('picking_wrong_quantity_cause_other_field')->nullable();

            $table->integer('picking_wrong_strength')->nullable()->default(0);
                $table->text('picking_wrong_strength_drug_prescribed')->nullable();
                $table->text('picking_wrong_strength_drug_labelled')->nullable();

                $table->integer('picking_wrong_strength_cause_similar_name_items')->nullable()->default(0);
                $table->integer('picking_wrong_strength_cause_similar_packaging')->nullable()->default(0);
                $table->integer('picking_wrong_strength_cause_change_in_packaging')->nullable()->default(0);
                $table->integer('picking_wrong_strength_cause_unclear_handwritten_prescription')->nullable()->default(0);
                $table->integer('picking_wrong_strength_cause_misread_prescription')->nullable()->default(0);
                $table->integer('picking_wrong_strength_cause_imported_pi_pack')->nullable()->default(0);
                $table->integer('picking_wrong_strength_cause_dispensed_from_picking_label')->nullable()->default(0);
                $table->integer('picking_wrong_strength_cause_unfamiliar_with_item')->nullable()->default(0);
                $table->integer('picking_wrong_strength_cause_did_not_recheck')->nullable()->default(0);
                $table->integer('picking_wrong_strength_cause_other')->nullable()->default(0);
                $table->text('picking_wrong_strength_cause_other_field')->nullable();


            $table->integer('picking_wrong_formulation')->nullable()->default(0);
                $table->text('picking_wrong_formulation_drug_prescribed')->nullable();
                $table->text('picking_wrong_formulation_drug_labelled')->nullable();

                $table->integer('picking_wrong_formulation_cause_similar_name_items')->nullable()->default(0);
                $table->integer('picking_wrong_formulation_cause_similar_packaging')->nullable()->default(0);
                $table->integer('picking_wrong_formulation_cause_change_in_packaging')->nullable()->default(0);
                $table->integer('picking_wrong_formulation_cause_unclear_handwritten_prescription')->nullable()->default(0);
                $table->integer('picking_wrong_formulation_cause_misread_prescription')->nullable()->default(0);
                $table->integer('picking_wrong_formulation_cause_imported_pi_pack')->nullable()->default(0);
                $table->integer('picking_wrong_formulation_cause_dispensed_from_picking_label')->nullable()->default(0);
                $table->integer('picking_wrong_formulation_cause_unfamiliar_with_item')->nullable()->default(0);
                $table->integer('picking_wrong_formulation_cause_did_not_recheck')->nullable()->default(0);
                $table->integer('picking_wrong_formulation_cause_other')->nullable()->default(0);
                $table->text('picking_wrong_formulation_cause_other_field')->nullable();

            /* Placing into basket */
            $table->integer('placing_basket_another_patient_label_basket')->nullable()->default(0);
                $table->integer('placing_basket_another_patient_cause_weak_adhesive_labels')->nullable()->default(0);
                $table->integer('placing_basket_another_patient_cause_area_cluttered_baskets')->nullable()->default(0);
                $table->integer('placing_basket_another_patient_cause_basket_was_moved')->nullable()->default(0);
                $table->integer('placing_basket_another_patient_cause_labels_stuck_basket')->nullable()->default(0);
                $table->integer('placing_basket_another_patient_cause_other')->nullable()->default(0);
                $table->text('placing_basket_another_patient_cause_other_field')->nullable();


            $table->integer('placing_basket_wrong_basket')->nullable()->default(0);
                $table->integer('placing_basket_wrong_basket_cause_area_cluttered_baskets')->nullable()->default(0);
                $table->integer('placing_basket_wrong_basket_cause_basket_was_moved')->nullable()->default(0);
                $table->integer('placing_basket_wrong_basket_cause_other')->nullable()->default(0);
                $table->text('placing_basket_wrong_basket_cause_other_field')->nullable();

            $table->integer('placing_basket_missing_item')->nullable()->default(0);
                $table->integer('placing_basket_missing_item_cause_missing_cd_sticket')->nullable()->default(0);
                $table->integer('placing_basket_missing_item_cause_label_lost')->nullable()->default(0);
                $table->integer('placing_basket_missing_item_cause_distracted_item_left_out')->nullable()->default(0);
                $table->integer('placing_basket_missing_item_cause_other')->nullable()->default(0);
                $table->text('placing_basket_missing_item_cause_other_field')->nullable();

            $table->integer('placing_basket_label_wrong_item')->nullable()->default(0);
                $table->integer('placing_basket_wrong_item_cause_weak_adhesive_labels')->nullable()->default(0);
                $table->integer('placing_basket_wrong_item_cause_similar_name_items')->nullable()->default(0);
                $table->integer('placing_basket_wrong_item_cause_similar_packaging')->nullable()->default(0);
                $table->integer('placing_basket_wrong_item_cause_change_in_packaging')->nullable()->default(0);
                $table->integer('placing_basket_wrong_item_cause_did_not_recheck')->nullable()->default(0);
                $table->integer('placing_basket_wrong_item_cause_other')->nullable()->default(0);
                $table->text('placing_basket_wrong_item_cause_other_field')->nullable();

            /** Bagging */
            $table->integer('bagging_wrong_bag_label')->nullable()->default(0);
                $table->integer('bagging_wrong_bag_label_cause_similar_patient_name')->nullable()->default(0);
                $table->integer('bagging_wrong_bag_label_cause_label_reprinted')->nullable()->default(0);
                $table->integer('bagging_wrong_bag_label_cause_cluttered_bagging_area')->nullable()->default(0);
                $table->integer('bagging_wrong_bag_label_cause_other')->nullable()->default(0);
                $table->text('bagging_wrong_bag_label_cause_other_field')->nullable();

            $table->integer('bagging_another_patient_med_in_bag')->nullable()->default(0);
                $table->integer('bagging_another_patient_med_in_bag_cause_similar_patient_name')->nullable()->default(0);
                $table->integer('bagging_another_patient_med_in_bag_cause_cluttered_bagging_area')->nullable()->default(0);
                $table->integer('bagging_another_patient_med_in_bag_cause_bag_different_patient')->nullable()->default(0);
                $table->integer('bagging_another_patient_med_in_bag_cause_other')->nullable()->default(0);
                $table->text('bagging_another_patient_med_in_bag_cause_other_field')->nullable();

            $table->integer('bagging_missed_items')->nullable()->default(0);
                $table->integer('bagging_missed_items_cause_cluttered_bagging_area')->nullable()->default(0);
                $table->integer('bagging_missed_items_cause_distracted_item_left_out')->nullable()->default(0);
                $table->integer('bagging_missed_items_cause_missing_cd_sticker')->nullable()->default(0);
                $table->integer('bagging_missed_items_cause_other')->nullable()->default(0);
                $table->text('bagging_missed_items_cause_other_field')->nullable();

            $table->integer('preparing_dosette_tray_wrong_day_time')->nullable()->default(0);
            $table->integer('preparing_dosette_tray_error_patient_mar_chart')->nullable()->default(0);
            $table->integer('preparing_dosette_tray_extra_quantity_on_tray')->nullable()->default(0);
            $table->integer('preparing_dosette_tray_error_in_description')->nullable()->default(0);
            $table->integer('preparing_dosette_tray_wrong_bag_label')->nullable()->default(0);
            $table->integer('preparing_dosette_tray_external_item_missing')->nullable()->default(0);
            $table->integer('preparing_dosette_tray_tray_item_missing')->nullable()->default(0);
            $table->integer('preparing_dosette_tray_error_on_blister_pack')->nullable()->default(0);
                $table->integer('preparing_dosette_tray_blistring_cause_discharge_not_actioned')->nullable()->default(0);
                $table->integer('preparing_dosette_tray_blistring_cause_change_not_communicated')->nullable()->default(0);
                $table->integer('preparing_dosette_tray_blistring_cause_other')->nullable()->default(0);
                $table->text('preparing_dosette_tray_blistring_cause_other_field')->nullable();

            /** Handing Out */
            $table->integer('handing_out_to_wrong_patient')->nullable()->default(0);
                $table->integer('handing_out_to_wrong_patient_cause_similar_patient_name')->nullable()->default(0);
                $table->integer('handing_out_to_wrong_patient_cause_didnot_confirm_address')->nullable()->default(0);
                $table->integer('handing_out_to_wrong_patient_cause_other')->nullable()->default(0);
                $table->text('handing_out_to_wrong_patient_cause_other_field')->nullable();
            
            # Contributing Factors
            $table->integer('contributing_factor_staff_fewer_staff')->nullable()->default(0);
            $table->integer('contributing_factor_staff_not_usual_pharmacist')->nullable()->default(0);
            $table->integer('contributing_factor_staff_not_usual_dispense')->nullable()->default(0);
            $table->integer('contributing_factor_staff_pharmacist_self_checking')->nullable()->default(0);
            $table->integer('contributing_factor_person_dyslexia')->nullable()->default(0);
            $table->integer('contributing_factor_person_dyscalculia')->nullable()->default(0);
            
            $table->integer('contributing_factor_envirnoment_messy')->nullable()->default(0);
            $table->integer('contributing_factor_training_person_in_training')->nullable()->default(0);
            $table->integer('contributing_factor_training_person_not_trained')->nullable()->default(0);
            $table->integer('contributing_factor_task_and_workload_high_number_patients')->nullable()->default(0);
            $table->integer('contributing_factor_task_and_workload_busy_otc_trade')->nullable()->default(0);
            $table->integer('contributing_factor_task_and_workload_backlog_work')->nullable()->default(0);
            $table->integer('contributing_factor_task_and_workload_quieter_than_usual')->nullable()->default(0);
            $table->integer('contributing_factor_task_and_workload_telephone_interruption')->nullable()->default(0);

            $table->integer('contributing_factor_other')->nullable()->default(0);
            $table->text('contributing_factor_other_field')->nullable();
            

            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('near_misses');
    }
};
