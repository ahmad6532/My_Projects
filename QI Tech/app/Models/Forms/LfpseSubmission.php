<?php

namespace App\Models\Forms;

use App\Helpers\Nhs_LFPSE\Contained;
use App\Helpers\Nhs_LFPSE\Extension;
use App\Helpers\Nhs_LFPSE\ExtensionInner;
use App\Helpers\Nhs_LFPSE\Recorder;
use App\Helpers\Nhs_LFPSE\Root;
use App\Helpers\Nhs_LFPSE\Location as Nhs_LFPSELocation;
use App\Helpers\Nhs_LFPSE\Subject;
use App\Models\ActivityLog;
use App\Models\Headoffices\CaseManager\Comment;
use App\Models\lfpse_errors;
use App\Models\LfpseOption;
use Auth;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class LfpseSubmission extends Model
{
    use HasFactory;

    protected $table = 'be_spoke_form_lfpse_submissions';

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function Record()
    {
        return $this->belongsTo(Record::class, 'be_spoke_form_records_id');
    }

    public function getOutcomeTypeStringAttribute()
    {
        switch ($this->outcome_type) {
            case 'AdverseEvent':
                return 'success';
            case 'OperationOutcome':
                return 'warning';
        }
        return "NA";
    }

    public static function prepare_request($request_form)
    {
        $api_version = config("lfpse.api_endpoint_6");
        $api_ver_num = '-6';
        //prepare the request for nhs lfpse
        $all_questions = [];
        $contained = [];
        $suspect_entites = [];
        $additional_extensions_set= [];
        foreach ($request_form['pages'] as $page) {
            foreach ($page['items'] as $item) {
                if (array_key_exists('is_nhs_field', $item) && $item['is_nhs_field'] && $item['type'] == "field" && array_key_exists('nhs_extension_url', $item))
                    $all_questions[$item['nhs_extension_url']] = $item['input'];
            }
        }
        $all_task_questions = [];
            foreach ($request_form['task_list']['tasks'] as $task) {
                foreach ($task['pages'] as $taskpage) {
                    foreach ($taskpage['items'] as $taskitems) {
                        if (array_key_exists('is_nhs_field', $taskitems) && $taskitems['is_nhs_field'] && $taskitems['type'] == "field" && array_key_exists('nhs_extension_url', $taskitems)) {
                            $all_task_questions[$taskitems['nhs_extension_url']] = $taskitems['input'];
                        }
                    }
                }
            }
        
        if ($all_questions['Event']['value'] == '1') {
            $location_extensions_set = [];
            $location_extensions_set[] = new ExtensionInner("LocationKnown", valueCode: $all_questions["LocationKnown"]['value']);
            if ($all_questions["LocationKnown"]['value'] == 'y') {
                $location_extensions_set[] = new ExtensionInner("Organisation", valueCode: 'Z45');
            } elseif ($all_questions["LocationKnown"]['value'] != 'u') // if y or n
            {
                if(isset($all_questions["Organisation"]['value']['id']) && !empty($all_questions["Organisation"]['value']['id'])){
                    $location_extensions_set[] = new ExtensionInner("Organisation", valueCode: LfpseOption::findOrFail($all_questions["Organisation"]['value']['id'])->code);
                }elseif(isset($all_questions["OrganisationOther"]['value']) && !empty($all_questions["OrganisationOther"]['value'])){
                    $location_extensions_set[] = new ExtensionInner("OrganisationOther", valueString: $all_questions["OrganisationOther"]['value']);
                }
            }
            //Other if not available

            // =========== LocationWithinService ===========
            if (isset($all_questions["LocationWithinService"]['value']) && $all_questions["LocationWithinService"]['value'] !== null) {
                if ($all_questions["LocationWithinService"]['value'] == 5) {
                    // If the value is 5, add "LocationWithinServiceOther" with valueString
                    if (isset($all_questions["LocationWithinServiceOther"]) && $all_questions["LocationWithinServiceOther"]['value'] !== null) {
                        $location_extensions_set[] = new ExtensionInner(
                            "LocationWithinServiceOther",
                            valueString: $all_questions["LocationWithinServiceOther"]['value']
                        );
                    }
                } else {
                    // Otherwise, add the "LocationWithinService" with valueCode
                    $location_extensions_set[] = new ExtensionInner(
                        "LocationWithinService",
                        valueCode: $all_questions["LocationWithinService"]['value']['code'] ?? $all_questions["LocationWithinService"]['value'] 
                    );
                }
            }

            if (isset($all_questions["ServiceArea"]['value'])) {
                foreach ($all_questions["ServiceArea"]['value'] as $sav)
                    $location_extensions_set[] = new ExtensionInner("ServiceArea", valueCode: $sav);
            }

            // =========== ResponsibleSpecialty ===========
            if (isset($all_questions["ResponsibleSpecialty"]['value']) && $all_questions["ResponsibleSpecialty"]['value'] !== null) {
                $location_extensions_set[] = new ExtensionInner("ResponsibleSpecialty", valueCode: LfpseOption::findOrFail($all_questions["ResponsibleSpecialty"]['value']['id'])->code);

            } else if ( isset($all_questions["ResponsibleSpecialtyOther"]['value']) && $all_questions["ResponsibleSpecialtyOther"]['value'] !== null) {
                $location_extensions_set[] = new ExtensionInner(
                    "ResponsibleSpecialtyOther",
                    valueString: $all_questions["ResponsibleSpecialtyOther"]['value']
                );
            }


            //other if not available
            $location_extension = new Extension($location_extensions_set, $api_version . "/taxonomy/fhir/StructureDefinition/location-details" . $api_ver_num);
            $location = new Contained("Location", "location1", [$location_extension]);

            // Medication Adminstration
            $med_Admin_extension_set = [];
            if (!empty($all_task_questions['MedicationAdministration']['value'])) {
                $med_Admin_extension_set[] = new ExtensionInner('MedicationAdministration', valueCode: $all_task_questions['MedicationAdministration']['value']);
                $med_admin_extension = new Extension($med_Admin_extension_set, $api_version . "/taxonomy/fhir/StructureDefinition/medication-administration" . $api_ver_num);
                $med_resource = new Contained("Medication", "medication1", [$med_admin_extension]);
            }

            $dt_task_extension = [];
            if (isset($all_task_questions["DeviceType"]["value"]['id'])) {
                $dt_task_extension[] = new ExtensionInner("DeviceType", valueCode: LfpseOption::findOrFail($all_task_questions["DeviceType"]['value']['id'])->code);
            } elseif (isset($all_task_questions["DeviceTypeOther"]["value"])) {
                $dt_task_extension[] = new ExtensionInner("DeviceTypeOther", valueCode: $all_task_questions["DeviceTypeOther"]["value"]);
            }

            if (!empty($dt_task_extension)) {
                $dt_extenstion[] = new Extension(
                    $dt_task_extension,
                    $api_version . "/taxonomy/fhir/StructureDefinition/adverse-event-device-details" . $api_ver_num
                );
                $device = ["resourceType" => "Device", "id" => "device1", "extension" => $dt_extenstion];
                if (!empty($all_task_questions["manufacturer"]["value"])) {
                    $device["manufacturer"] = $all_task_questions["manufacturer"]["value"];
                }
                if (!empty($all_task_questions["ModelNumber"]["value"])) {
                    $device["ModelNumber"] = $all_task_questions["ModelNumber"]["value"];
                }
                if (!empty($all_task_questions["SerialNumber"]["value"])) {
                    $device["SerialNumber"] = $all_task_questions["SerialNumber"]["value"];
                }
                $contained[] = $device;
                $suspect_entites[] = "device1" ;
            }

            // Learning Points Additional questions
            $learning_url = ['StaffAvailabilityIncidentFactor','PeopleInvolvementFactors','PeopleUnavailableDetails','ProblemDescriptionInvolvement','PeopleActionsDiffered','PeopleActionFactors','PeopleActionTooMuch','PeopleInsufficientActionDetails','PeopleWrongActionDetails','PersonsActionsDiffered','PeopleOmittedActionDetails','ProblemDescriptionActions'];
            $learningArray = [];
            foreach ($learning_url as $urlName) {
                if (isset($all_task_questions[$urlName]) && isset($all_task_questions[$urlName]['type']) && isset($all_task_questions[$urlName]['value'])) {
                    $item = $all_task_questions[$urlName];
                    $url = $urlName;

                    if ($item['type'] == 'checkbox') {
                        foreach ($item['value'] as $value) {
                            if ($value !== null && $value !== "") {
                                $learningArray[] = new ExtensionInner($url, valueCode: $value);
                            }
                        }
                    } else if ($item['type'] == 'textarea' || $item['type'] == 'text') {
                        if ($item['value'] !== null && $item['value'] !== "") {
                            $learningArray[] = new ExtensionInner($url, valueString: $item['value']);
                        }
                    }else if($item['type'] == 'radio'){
                        if ($item['value'] !== null && $item['value'] !== "" || ($item['value'] == 'y' || $item['value'] == 'n')) {
                            $learningArray[] = new ExtensionInner($url, valueBoolean: $item['value'] == 'y' ? true : false);
                        }else{
                            foreach ((array)$item['value'] as $value) {
                                if ($value !== null && $value !== "") {
                                    $learningArray[] = new ExtensionInner($url, valueCode: $value);
                                }
                            }
                        }
                    }
                }
            };

            if (!empty($learningArray)) {
                $additional_extensions_set[] = new Extension($learningArray, $api_version . "/taxonomy/fhir/StructureDefinition/adverse-event-problem-people" . $api_ver_num);
            }

            if(!empty($all_task_questions['DetectionPoint']['value'])){
                $detection_extension = [];
                foreach((array)$all_task_questions['DetectionPoint']['value'] as $value){
                    if(empty($value)) continue;
                    if($value == "8" && !empty($all_task_questions['DetectionPointOther']['value'])){
                        $detection_extension[] = new ExtensionInner("DetectionPointOther", valueString: $all_task_questions['DetectionPointOther']['value']);
                    }else{
                        $detection_extension[] = new ExtensionInner("DetectionPoint", valueCode: $value);
                    }
                }
                $additional_extensions_set[] = new Extension($detection_extension, $api_version . "/taxonomy/fhir/StructureDefinition/detection-factors" . $api_ver_num);
            }

            $wentWell = [];
            if(!empty($all_task_questions['WentWell']['value'])){
                $wentWell[] = new ExtensionInner("WentWell", valueString: $all_task_questions['WentWell']['value']);
            }
            if(!empty($all_task_questions['ImmediateActions']['value'])){
                $wentWell[] = new ExtensionInner("ImmediateActions", valueString: $all_task_questions['ImmediateActions']['value']);
            }
            if(!empty($wentWell)){
                $additional_extensions_set[] = new Extension($wentWell, $api_version . "/taxonomy/fhir/StructureDefinition/adverse-event-outcome" . $api_ver_num);
            }

            if(!empty($all_task_questions['HealthcareProcess']['value'])){
                $healthProccess = [];
                foreach((array)$all_task_questions['HealthcareProcess']['value'] as $value){
                    $healthProccess[] = new ExtensionInner("HealthcareProcess", valueCode: $value);
                }
                $additional_extensions_set[] = new Extension($healthProccess, $api_version . "/taxonomy/fhir/StructureDefinition/adverse-event-process" . $api_ver_num);
            }

            $practitioner_extensions_set = [];
            if(!empty($all_task_questions['ReporterRoleTask']['value'])){
                if($all_task_questions['ReporterRoleTask']['value'] == "9" && !empty($all_task_questions['ReporterRoleOtherTask']['value'])){
                    $practitioner_extensions_set[] = new ExtensionInner("ReporterRoleOther", valueString: $all_task_questions['ReporterRoleOtherTask']['value']);
                }else{
                    $practitioner_extensions_set[] = new ExtensionInner("ReporterRole", valueCode: $all_task_questions['ReporterRoleTask']['value']);
                }
            }
            if(!empty($all_task_questions['ReporterContactTask']['value'])){
                $practitioner_extensions_set[] = new ExtensionInner("ReporterContact", valueString: $all_task_questions['ReporterContactTask']['value']);
            }
            if(!empty($all_task_questions['ReporterInvolvement']['value'])){
                if($all_task_questions['ReporterInvolvement']['value'] == "6" && !empty($all_task_questions['ReporterInvolvementOther']['value'])){
                    $practitioner_extensions_set[] = new ExtensionInner("ReporterInvolvementOther", valueString: $all_task_questions['ReporterInvolvementOther']['value']);
                }else{
                    $practitioner_extensions_set[] = new ExtensionInner("ReporterInvolvement", valueCode: $all_task_questions['ReporterInvolvement']['value']);
                }
            }
            $practitioner_extensions_set[] = new ExtensionInner("ReporterOrganisation", valueCode: "Z45"); // temp value. get from user side
            $practitioner_extension = new Extension($practitioner_extensions_set, $api_version . "/taxonomy/fhir/StructureDefinition/practitioner-details" . $api_ver_num);
            $practitioner = new Contained("Practitioner", "practitioner1", [$practitioner_extension]);

            //after location//

            // Adverse Event Agents
            $ae_agents_set = [];
            if (!empty($all_task_questions['YellowCardReference']['value'])) {
                $ae_agents_set[] = new ExtensionInner("YellowCardReference", valueString: $all_task_questions['YellowCardReference']['value']);
            }
            foreach ($all_questions["InvolvedAgents"]['value'] as $ia) {
                // additional tasks can also be collected at this point !
                $ae_agents_set[] = new ExtensionInner("InvolvedAgents", valueCode: $ia);

                if ($ia == "11") {
                    if (isset($all_questions["SabreReportNumber"]['value']) && $all_questions["SabreReportNumber"]['value'] !== "") {
                        $ae_agents_set[] = new ExtensionInner("SabreReportNumber", valueString: $all_questions["SabreReportNumber"]['value']);
                    }
                    if (isset($all_questions["ShotReportNumber"]['value']) && $all_questions["ShotReportNumber"]['value'] !== "") {
                        $ae_agents_set[] = new ExtensionInner("ShotReportNumber", valueString: $all_questions["ShotReportNumber"]['value']);
                    }

                }
                if ($ia == "8") {
                    if (isset($all_questions["NhsbtReportNumber"]['value']) && $all_questions["NhsbtReportNumber"]['value'] !== "") {
                        $ae_agents_set[] = new ExtensionInner("NhsbtReportNumber", valueString: $all_questions["NhsbtReportNumber"]['value']);
                    }
                }

                if($ia == '10'){
                    if (isset($all_task_questions['InvolvedPersonsActions']['value']) && !empty($all_task_questions['InvolvedPersonsActions']['value']) && $all_task_questions['InvolvedPersonsActions']['value'] !== "" && $all_task_questions['InvolvedPersonsActions']['value'] !== "5"){ 
                            foreach ((array)$all_task_questions['InvolvedPersonsActions']['value'] as $value) {
                                if ($value !== null && $value !== "") {
                                    $ae_agents_set[] = new ExtensionInner('InvolvedPersonsActions', valueCode: $value);
                                }
                            }
                    }elseif(isset($all_task_questions['InvolvedPersonsActions']['value']) && !empty($all_task_questions['InvolvedPersonsActions']['value']) && $all_task_questions['InvolvedPersonsActions']['value'] == "5" && isset($all_task_questions['InvolvedPersonsActionsOther']['value']) && !empty($all_task_questions['InvolvedPersonsActionsOther']['value']) && $all_task_questions['InvolvedPersonsActionsOther']['value'] !== "") {
                        $ae_agents_set[] = new ExtensionInner("InvolvedPersonsActionsOther", valueString: $all_task_questions['InvolvedPersonsActionsOther']['value']);
                    }
                    
                }

                // Additional Tasks ! : For ITSystemsInvolvementFactors
                $additional_extensions_set = self::prepare_task($ia, $additional_extensions_set, $request_form, $ae_agents_set);
            }

            $additional_extensions_set[] = new Extension($ae_agents_set, $api_version . "/taxonomy/fhir/StructureDefinition/adverse-event-agent" . $api_ver_num);
            // version 6 description extension
            $description[] = new ExtensionInner("Description", valueString: $all_questions["description"]['value']);
            $additional_extensions_set[] = new Extension($description, $api_version . "/taxonomy/fhir/StructureDefinition/adverse-event-core" . $api_ver_num);

            // Adverse Event Medications
            $ae_medication_set = [];
            if (in_array("4", $all_questions["InvolvedAgents"]['value'])) {
                foreach ($all_questions["medication"]['value'] as $medication) {
                    if (isset($medication['vtm']) && !empty($medication['vtm']['vtm_id']) && !empty($medication['vtm']['vtm_string'])) {
                        $ae_medication_set[] = new ExtensionInner("VTMCode", valueCode: $medication['vtm']['vtm_id']);
                        $ae_medication_set[] = new ExtensionInner("VTMString", valueString: $medication['vtm']['vtm_string']);
                    }
                    if (isset($medication['vmp']) && !empty($medication['vmp']['vp_id']) && !empty($medication['vmp']['vp_string'])) {
                        $ae_medication_set[] = new ExtensionInner("VMPCode", valueCode: $medication['vmp']['vp_id']);
                        $ae_medication_set[] = new ExtensionInner("VMPString", valueString: $medication['vmp']['vp_string']);
                    }
                    // ============ Other field should be added later ==============
                }

                $additional_extensions_set[] = new Extension($ae_medication_set, 'https://developer.learn-from-patient-safety-events.nhs.uk/taxonomy/fhir/StructureDefinition/medication-dmd-6');
            }

            // Adverse Event Safety Challenges
            $ae_safety_challenges_set = [];
            foreach ($all_questions["SafetyChallenges"]['value'] as $sc) {
                $ae_safety_challenges_set[] = new ExtensionInner("SafetyChallenges", valueCode: $sc);
            }
            if ($all_questions["SafetyChallenges"]['value'] == "4")
                $ae_safety_challenges_set[] = new ExtensionInner("RadiotherapyIncidentCode", valueString: $all_questions["RadiotherapyIncidentCode"]['value']);
            if ($all_questions["SafetyChallenges"]['value'] == "7")
                $ae_safety_challenges_set[] = new ExtensionInner("MarvinReferenceNumber", valueString: $all_questions["MarvinReferenceNumber"]['value']);
            $additional_extensions_set[] = new Extension($ae_safety_challenges_set, $api_version . "/taxonomy/fhir/StructureDefinition/adverse-event-safety-challenges" . $api_ver_num);
            //Adverse Event Estimated Date
            $ae_estimated_date_set = [];
            $ae_estimated_date_set[] = new ExtensionInner("IncidentOccurredToday", valueCode: $all_questions["IncidentOccurredToday"]['value'] ?? 'u');
            // dd($all_questions["IncidentOccurredToday"]);
            if ($all_questions["IncidentOccurredToday"]['value'] != 'u' && $all_questions["IncidentOccurredToday"]['value'] == 'y') {
                $dateValue = $all_questions["TodaysDate"]['value'] ?? '';
                if ($dateValue !== '') {
                    $datePart = explode("T", $dateValue)[0];
                } else {
                    $datePart = date('Y-m-d');
                }
                $ae_agents_set[] = new ExtensionInner("TodaysDate", valueDate: $datePart);
            }
            if ($all_questions["IncidentOccurredToday"]['value'] == 'u' || $all_questions["IncidentOccurredToday"]['value'] == null) {
                if (
                    isset($all_questions["ApproximateDateMonthYear"]['value']) && !empty($all_questions["ApproximateDateMonthYear"]['value'])
                    ) {
                    // Parse the Date string using Carbon (e.g., '2025-01-06T20:48:14.910Z')
                    $date = Carbon::parse($all_questions["ApproximateDateMonthYear"]['value']);
                    
                    $year = $date->year;  // Extracts the year (e.g., 2025)
                    $month = $date->month; // Extracts the month (e.g., 1)
            
                    $month = str_pad($month, 2, '0', STR_PAD_LEFT); // Ensures the month is two digits
                    // Add the values to the array
                    $ae_estimated_date_set[] = new ExtensionInner("ApproximateDate", valueString: $year . "-" . $month);
                }
            }
// dd($ae_estimated_date_set);
            if (isset($all_questions["PreciseTime"]['value']) && $all_questions["PreciseTime"]['value'] !== "") {
                $ae_estimated_date_set[] = new ExtensionInner("PreciseTime", valueTime: explode(".", explode("T", $all_questions["PreciseTime"]['value'])[1])[0]); // probably need to extract as 24 hours time.
            }
            $additional_extensions_set[] = new Extension($ae_estimated_date_set, $api_version . "/taxonomy/fhir/StructureDefinition/adverse-event-estimated-date" . $api_ver_num);
            //test.split('T')[1].split('.')[0]
            $patient_extensions_set = [];
            $zero_patients = $all_questions["NoOfPatients"]['value'] == "None";
            if ($zero_patients) {
                //Adverse Event Risk
                $ae_risk_set = [];
                $ae_risk_set[] = new ExtensionInner("RiskImminent", valueCode: $all_questions["RiskImminent"]['value']);
                if ($all_questions["RiskPopulation"]['value']) {
                    $ae_risk_set[] = new ExtensionInner("RiskPopulation", valueString: $all_questions["RiskPopulation"]['value']);
                }
                $additional_extensions_set[] = new Extension($ae_risk_set, $api_version . "/taxonomy/fhir/StructureDefinition/adverse-event-risk-details" . $api_ver_num);
            } else {
                // patients can be multiple. need to find a way to do so.
                if (array_key_exists('patients', $request_form)) {

                    $patients_questions = [];
                    foreach ($request_form['patients'] as $key => $p) {
                        foreach ($p as $i_id => $p_data) {
                            $i_extension = $p_data['extension'];
                            $i_value = $p_data['value'];
                            $patients_questions[$key][$i_extension] = $i_value;
                        }
                    }
                    foreach ($patients_questions as $sr => $pq) {
                        // Check if all required keys are present
                        if (
                            isset($pq['Gender']) &&
                            isset($pq['PhysicalHarm']) &&
                            isset($pq['PsychologicalHarm']) &&
                            isset($pq['GenderSameAsAtBirth'])
                        ) {
                            $p_extension_set = [];
                            $p_extension_set[] = new ExtensionInner("PatientSequence", valueInteger: ($sr + 1));
                            
                            $age_category = $pq['PatientAgeCustom'];
                            $age = false;
                            $age_bracket = false;
                    
                            if ($age_category == "Over one year old") {
                                $age = (int)($pq['PatientAgeYears'] * 372);
                            } elseif ($age_category == "At least one month old but less than one year") {
                                $age = (int)($pq['PatientAgeMonths'] * 31);
                            } elseif ($age_category == "Less than one month old") {
                                $age = $pq['PatientAgeDays'];
                            } elseif ($age_category == "I don't know but I could give an estimate") {
                                $age_bracket = $pq['AgeBracket'];
                            }
                    
                            if ($age) {
                                $p_extension_set[] = new ExtensionInner("AgeAtTimeOfIncidentDays", valueInteger: $age);
                            } elseif ($age_bracket) {
                                $p_extension_set[] = new ExtensionInner("AgeBracket", valueCode: $age_bracket);
                                // $cal_age = LfpseSubmission::estimateAgeBracket($age_bracket);
                                // $p_extension_set[] = new ExtensionInner("AgeAtTimeOfIncidentDays", valueInteger: (int)$cal_age);

                            }
                    
                            $p_extension_set[] = new ExtensionInner("GenderIdentity", valueCode: $pq['Gender']);
                            $p_extension_set[] = new ExtensionInner("GenderSameAsAtBirth", valueCode: $pq['GenderSameAsAtBirth']);
                            
                            if (!empty($pq['PatientEthnicity'])) {
                                $p_extension_set[] = new ExtensionInner("PatientEthnicity", valueCode: $pq['PatientEthnicity']);
                            }
                    
                            $p_extension_set[] = new ExtensionInner("PhysicalHarm", valueCode: $pq['PhysicalHarm']);
                            
                            if ($pq['PhysicalHarm'] != "1") { // Not Fatal
                                $p_extension_set[] = new ExtensionInner("PsychologicalHarm", valueCode: $pq['PsychologicalHarm']);
                            }
                            
                            if(isset($pq['ClinicalOutcome']) && !empty($pq['ClinicalOutcome'])) {
                                $p_extension_set[] = new ExtensionInner("ClinicalOutcome", valueString: $pq['ClinicalOutcome']);
                            }
                            
                            if ((!empty($pq['PsychologicalHarm'] ) && ($pq['PsychologicalHarm'] == 1) || $pq['PhysicalHarm'] <= 2)) {
                                if(isset($pq['StrengthOfAssociation'])){
                                    $p_extension_set[] = new ExtensionInner("StrengthOfAssociation", valueCode: $pq['StrengthOfAssociation']);
                                }
                            }
                    
                            $patient_extensions_set[] = new Extension($p_extension_set, $api_version . "/taxonomy/fhir/StructureDefinition/patient-information" . $api_ver_num);
                        }
                    }
                }
            }

            // Adverse Event Classification
            $ae_concern_ext = [];
            $ae_concern_ext[] = new ExtensionInner("LevelOfConcern", valueCode: $all_questions["LevelOfConcern"]['value']);
            $additional_extensions_set[] = new Extension($ae_concern_ext, $api_version . "/taxonomy/fhir/StructureDefinition/adverse-event-classification" . $api_ver_num);
            
            $contained[] = $location;
            $contained[] = $practitioner;
            if (!empty($med_resource)) {
                $contained[] = $med_resource;
                $suspect_entites[] = "medication1";
            }

            $patient_ref = null;
            // if (true) {
            $patient1 = new Contained("Patient", "patient", $patient_extensions_set);
            $contained[] = $patient1;
            $patient_ref = new Subject("#patient");
            // }
            $todayDate = $all_questions["IncidentOccurredToday"]['value'] == 'y' ?  now()->format("Y-m-d") : null;
            $lfpse_obj = new Root($contained, $additional_extensions_set, "1", $patient_ref, $todayDate, new Nhs_LFPSELocation("#location1"), new Recorder("#practitioner1"), $all_questions["description"]['value'], $suspect_entites);

        } else {

            // =========================== Good Care =============
            $location_extensions_set = [];
            $location_extensions_set[] = new ExtensionInner("LocationKnown", valueCode: $all_questions["LocationKnownGood"]['value']);
            if ($all_questions["LocationKnownGood"]['value'] == 'y') {
                $location_extensions_set[] = new ExtensionInner("Organisation", valueCode: 'Z45');
            } elseif ($all_questions["LocationKnownGood"]['value'] != 'u') // if y or n
            {
                if(isset($all_questions["Organisation"]['value']['id']) && !empty($all_questions["Organisation"]['value']['id'])){
                    $location_extensions_set[] = new ExtensionInner("Organisation", valueCode: LfpseOption::findOrFail($all_questions["OrganisationGood"]['value']['id'])->code);
                }elseif(isset($all_questions["OrganisationOtherGood"]['value']) && !empty($all_questions["OrganisationOtherGood"]['value'])){
                    $location_extensions_set[] = new ExtensionInner("OrganisationOther", valueString: $all_questions["OrganisationOtherGood"]['value']);
                }
            }
            //Other if not available


            //other if not available
            $location_extension = new Extension($location_extensions_set, $api_version . "/taxonomy/fhir/StructureDefinition/location-details" . $api_ver_num);
            $location = new Contained("Location", "location1", [$location_extension]);


            $practitioner_extensions_set = [];
            if(!empty($all_questions['ReporterRoleGood']['value'])){
                if($all_questions['ReporterRoleGood']['value'] == "9" && !empty($all_questions['ReporterRoleOtherGood']['value'])){
                    $practitioner_extensions_set[] = new ExtensionInner("ReporterRoleOther", valueString: $all_questions['ReporterRoleOtherGood']['value']);
                }else{
                    $practitioner_extensions_set[] = new ExtensionInner("ReporterRole", valueCode: $all_questions['ReporterRoleGood']['value']);
                }
            }
            $practitioner_extensions_set[] = new ExtensionInner("ReporterOrganisation", valueCode: "Z45"); // temp value. get from user side
            $practitioner_extension = new Extension($practitioner_extensions_set, $api_version . "/taxonomy/fhir/StructureDefinition/practitioner-details" . $api_ver_num);
            $practitioner = new Contained("Practitioner", "practitioner1", [$practitioner_extension]);

            //after location//


            // version 6 description extension
            $description[] = new ExtensionInner("Description", valueString: $all_questions["description"]['value']);
            $additional_extensions_set[] = new Extension($description, $api_version . "/taxonomy/fhir/StructureDefinition/adverse-event-core" . $api_ver_num);



            //Adverse Event Estimated Date
            $ae_estimated_date_set[] = new ExtensionInner("IncidentOccurredToday", valueCode: $all_questions["IncidentOccurredTodayGood"]['value'] == 'u' ? 'n' : $all_questions["IncidentOccurredTodayGood"]['value'] );
            if ($all_questions["IncidentOccurredTodayGood"]['value'] != 'u' && $all_questions["IncidentOccurredTodayGood"]['value'] == 'y') {
                $dateValue = $all_questions["dateGood"]['value'] ?? '';
                if ($dateValue !== '') {
                    $datePart = explode("T", $dateValue)[0];
                } else {
                    $datePart = date('Y-m-d');
                }
                $ae_estimated_date_set[] = new ExtensionInner("TodaysDate", valueDate: $datePart);
            }

            $additional_extensions_set[] = new Extension($ae_estimated_date_set, $api_version . "/taxonomy/fhir/StructureDefinition/adverse-event-estimated-date" . $api_ver_num);

            $ae_future = [];
            $ae_future[] = new ExtensionInner("HowFutureOccurrence", valueString: $all_questions["HowFutureOccurrence"]['value']);
            if(isset($all_questions["GoodCareDetectionFactor"]['value']) && !empty($all_questions["GoodCareDetectionFactor"]['value'])){
                $ae_future[] = new ExtensionInner("GoodCareDetectionFactor", valueCode: $all_questions["GoodCareDetectionFactor"]['value']);
                if($all_questions["GoodCareDetectionFactor"]['value'] == '6'){
                    $ae_future[] = new ExtensionInner("GoodCareDetectionFactorOther", valueString: $all_questions["GoodCareDetectionFactorOther"]['value']);
                }
            }

            $additional_extensions_set[] = new Extension($ae_future, $api_version . "/taxonomy/fhir/StructureDefinition/adverse-event-went-well" . $api_ver_num);

            // Adverse Event Classification

            $contained = [$location, $practitioner];
            $patient1 = new Contained("Patient", "patient", []);
            $contained[] = $patient1;
            $patient_ref = new Subject("#patient");
            $lfpse_obj = new Root($contained, $additional_extensions_set, "4", $patient_ref, now()->format("Y-m-d"), new Nhs_LFPSELocation("#location1"), new Recorder("#practitioner1"), $all_questions["description"]['value']);
        }

        // echo json_encode($lfpse_obj);
        // exit();
        return $lfpse_obj;
    }

    private static function prepare_task($code, $additional_extensions_set, $request_form, &$ae_agents_set)
    {
        $api_version = config("lfpse.api_endpoint_6");
        $api_ver_num = '-6';
        // $additional_extensions_set = [];

        $all_questions = [];
        foreach ($request_form['task_list']['tasks'][$code]['pages'] as $page) {
            foreach ($page['items'] as $item) {
                if (array_key_exists('is_nhs_field', $item) && $item['is_nhs_field'] && $item['type'] == "field" && array_key_exists('nhs_extension_url', $item))
                    $all_questions[$item['nhs_extension_url']] = $item['input'];
            }
        }

        switch ($code) {
            case "4":
                $extensionInnerArray = [];

                foreach ($all_questions as $urlName => $item) {
                    if (isset($item['type']) && isset($item['value'])) {
                        $url = $urlName;

                        if ($item['type'] == 'checkbox') {
                            foreach ($item['value'] as $value) {
                                if ($value !== null && $value !== "" && $urlName != 'MedicationAdministration') {
                                    $extensionInnerArray[] = new ExtensionInner($url, valueCode: $value);
                                }
                            }
                        } else if ($item['type'] == 'textarea' || $item['type'] == 'text') {
                            if ($item['value'] !== null && $item['value'] !== "") {

                                if ($urlName == 'InvolvedProcessesOther') {
                                    if (in_array('4', $all_questions['InvolvedProcesses']['value'])) {
                                        $extensionInnerArray[] = new ExtensionInner($url, valueString: $item['value']);
                                    }
                                } else {
                                    $extensionInnerArray[] = new ExtensionInner($url, valueString: $item['value']);
                                }
                            }
                        }
                    }
                }

                if (!empty($extensionInnerArray)) {
                    $additional_extensions_set[] = new Extension($extensionInnerArray, $api_version . "/taxonomy/fhir/StructureDefinition/adverse-event-problem-medication" . $api_ver_num);
                }
                break;
            case '3': // Devices

                $device_urls = ['DeviceInvolvementFactors', 'DeviceInsufficientDetails', 'DeviceWrongUsageDetails', 'DeviceBrokenDetails', 'DeviceUsedUnnecessarily', 'ProblemDescriptionDevices'];
                $extensionInnerArray = [];

                foreach ($device_urls as $urlName) {
                    if (isset($all_questions[$urlName]) && isset($all_questions[$urlName]['type']) && isset($all_questions[$urlName]['value'])) {
                        $item = $all_questions[$urlName];
                        $url = $urlName;

                        if ($item['type'] == 'checkbox') {
                            foreach ($item['value'] as $value) {
                                if ($value !== null && $value !== "") {
                                    $extensionInnerArray[] = new ExtensionInner($url, valueCode: $value);
                                }
                            }
                        } else if ($item['type'] == 'textarea' || $item['type'] == 'text') {
                            if ($item['value'] !== null && $item['value'] !== "") {
                                $extensionInnerArray[] = new ExtensionInner($url, valueString: $item['value']);
                            }
                        }
                    }
                }

                if (!empty($extensionInnerArray)) {
                    $additional_extensions_set[] = new Extension($extensionInnerArray, $api_version . "/taxonomy/fhir/StructureDefinition/adverse-event-problem-devices" . $api_ver_num);
                }
                break;

            case "8": // Tissue Organs
                    $extensionInnerArray = [];

                    foreach ($all_questions as $urlName => $item) {
                        if (isset($item['type']) && isset($item['value'])) {
                            $url = $urlName;

                            if ($item['type'] == 'checkbox') {
                                foreach ($item['value'] as $value) {
                                    if ($value !== null && $value !== "") {
                                        $extensionInnerArray[] = new ExtensionInner($url, valueCode: $value);
                                    }
                                }
                            } else if ($item['type'] == 'textarea' || $item['type'] == 'text') {
                                if ($item['value'] !== null && $item['value'] !== "") {

                                        $extensionInnerArray[] = new ExtensionInner($url, valueString: $item['value']);
                                }
                            }
                        }
                    }

                    if (!empty($extensionInnerArray)) {
                        $additional_extensions_set[] = new Extension($extensionInnerArray, $api_version . "/taxonomy/fhir/StructureDefinition/adverse-event-problem-tissues-organs" . $api_ver_num);
                    }
                break;
            case '9': // IT Software etc.
                $extensionInnerArray = [];
                    foreach ($all_questions as $urlName => $item) {
                        if (isset($item['type']) && isset($item['value'])) {
                            $url = $urlName;

                            if ($item['type'] == 'checkbox') {
                                foreach ($item['value'] as $value) {
                                    if ($value !== null && $value !== "") {
                                        $extensionInnerArray[] = new ExtensionInner($url, valueCode: $value);
                                    }
                                }
                            } else if ($item['type'] == 'textarea' || $item['type'] == 'text') {
                                if ($item['value'] !== null && $item['value'] !== "") {
                                        $extensionInnerArray[] = new ExtensionInner($url, valueString: $item['value']);
                                }
                            }
                        }
                    }

                    if (!empty($extensionInnerArray)) {
                        $additional_extensions_set[] = new Extension($extensionInnerArray, $api_version . "/taxonomy/fhir/StructureDefinition/adverse-event-problem-it-systems" . $api_ver_num);
                    }
                break;
            case '10': // None of above: People - Actions
                // agent extensions
                $extensionInnerArray = [];
                    foreach ($all_questions as $urlName => $item) {
                        if($urlName == 'InvolvedPersonsActions' || $urlName == 'InvolvedPersonsActionsOther'){
                            continue;
                        }
                        if (isset($item['type']) && isset($item['value'])) {
                            $url = $urlName;

                            if ($item['type'] == 'checkbox') {
                                foreach ($item['value'] as $value) {
                                    if ($value !== null && $value !== "") {
                                        $extensionInnerArray[] = new ExtensionInner($url, valueCode: $value);
                                    }
                                }
                            } else if ($item['type'] == 'textarea' || $item['type'] == 'text') {
                                if ($item['value'] !== null && $item['value'] !== "") {
                                        $extensionInnerArray[] = new ExtensionInner($url, valueString: $item['value']);
                                }
                            }else if($item['type'] == 'radio'){
                                if ($item['value'] !== null && $item['value'] !== "" || ($item['value'] == 'y' || $item['value'] == 'n')) {
                                    $extensionInnerArray[] = new ExtensionInner($url, valueBoolean: $item['value'] == 'y' ? true : false);
                                }else{
                                    foreach ((array)$item['value'] as $value) {
                                        if ($value !== null && $value !== "") {
                                            $extensionInnerArray[] = new ExtensionInner($url, valueCode: $value);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    
                break;
            case "11": //blood
                $extensionInnerArray = [];
                foreach ($all_questions as $urlName => $item) {
                    if (isset($item['type']) && isset($item['value'])) {
                        $url = $urlName;

                        if ($item['type'] == 'checkbox') {
                            foreach ($item['value'] as $value) {
                                if ($value !== null && $value !== "") {
                                    $extensionInnerArray[] = new ExtensionInner($url, valueCode: $value);
                                }
                            }
                        } else if ($item['type'] == 'textarea' || $item['type'] == 'text') {
                            if ($item['value'] !== null && $item['value'] !== "") {
                                $extensionInnerArray[] = new ExtensionInner($url, valueString: $item['value']);
                            }
                        }
                    }
                }

                if (!empty($extensionInnerArray)) {
                    $additional_extensions_set[] = new Extension($extensionInnerArray, $api_version . "/taxonomy/fhir/StructureDefinition/adverse-event-problem-blood" . $api_ver_num);
                }
                break;
            case "12": //blood products
                $extensionInnerArray = [];
                foreach ($all_questions as $urlName => $item) {
                    if (isset($item['type']) && isset($item['value'])) {
                        $url = $urlName;

                        if ($item['type'] == 'checkbox') {
                            foreach ($item['value'] as $value) {
                                if ($value !== null && $value !== "") {
                                    $extensionInnerArray[] = new ExtensionInner($url, valueCode: $value);
                                }
                            }
                        } else if ($item['type'] == 'textarea' || $item['type'] == 'text') {
                            if ($item['value'] !== null && $item['value'] !== "") {
                                $extensionInnerArray[] = new ExtensionInner($url, valueString: $item['value']);
                            }
                        }
                    }
                }

                if (!empty($extensionInnerArray)) {
                    $additional_extensions_set[] = new Extension($extensionInnerArray, $api_version . "/taxonomy/fhir/StructureDefinition/adverse-event-problem-blood-products" . $api_ver_num);
                }

                break;
            case "13": //Building
                $extensionInnerArray = [];

                foreach ($all_questions as $urlName => $item) {
                    if (isset($item['type']) && isset($item['value'])) {
                        $url = $urlName;
                        // if($url == 'BuildingsInfrastructure'){
                        //     continue;
                        // }

                        if ($item['type'] == 'checkbox') {
                            foreach ($item['value'] as $value) {
                                if ($value !== null && $value !== "") {
                                    $extensionInnerArray[] = new ExtensionInner($url, valueCode: $value);
                                }
                            }
                        } else if ($item['type'] == 'textarea' || $item['type'] == 'text') {
                            if ($item['value'] !== null && $item['value'] !== "") {

                                if ($urlName == 'BuildingsInfrastructureOther') {
                                    if (in_array('16', $all_questions['BuildingsInfrastructure']['value'])) {
                                        $extensionInnerArray[] = new ExtensionInner($url, valueString: $item['value']);
                                    }
                                } else {
                                    $extensionInnerArray[] = new ExtensionInner($url, valueString: $item['value']);
                                }
                            }
                        }
                    }
                }

                if (!empty($extensionInnerArray)) {
                    $additional_extensions_set[] = new Extension($extensionInnerArray, $api_version . "/taxonomy/fhir/StructureDefinition/adverse-event-problem-buildings-infrastructure" . $api_ver_num);
                }
                break;
            case "14": //Estate Services
                $extensionInnerArray = [];

                foreach ($all_questions as $urlName => $item) {
                    if (isset($item['type']) && isset($item['value'])) {
                        $url = $urlName;
                        // ======= Temp for now only Remove it ======
                        // if($url == 'EstatesServices'){
                        //     continue;
                        // }

                        if ($item['type'] == 'checkbox') {
                            foreach ($item['value'] as $value) {
                                if ($value !== null && $value !== "") {
                                    $extensionInnerArray[] = new ExtensionInner($url, valueCode: $value);
                                }
                            }
                        } else if ($item['type'] == 'textarea' || $item['type'] == 'text') {
                            if ($item['value'] !== null && $item['value'] !== "") {

                                if ($urlName == 'EstatesServicesOther') {
                                    if (in_array('9', $all_questions['EstatesServices']['value'])) {
                                        $extensionInnerArray[] = new ExtensionInner($url, valueString: $item['value']);
                                    }
                                } else {
                                    $extensionInnerArray[] = new ExtensionInner($url, valueString: $item['value']);
                                }
                            }
                        }
                    }
                }

                if (!empty($extensionInnerArray)) {
                    $additional_extensions_set[] = new Extension($extensionInnerArray, $api_version . "/taxonomy/fhir/StructureDefinition/adverse-event-problem-estates-services" . $api_ver_num);
                }
                break;
            case "20":
                // dd($all_questions);
                break;

        }
        return $additional_extensions_set;
    }

    public static function submit_request($record, $lfpse_json)
    {
        $api_version = config("lfpse.api_endpoint");
        $api_ver_num = '-6';

        if (config('lfpse.service_active')) {
            $url = $api_version . "/adverse-event/fhir/AdverseEvent";
            try {
                $response = Http::withHeaders([
                    'Content-type' => 'application/json',
                    'Ocp-Apim-Subscription-Key' => config('lfpse.ocp_apim_subscription_key')
                ])->withOptions(['verify' => false])
                    ->withBody($lfpse_json, 'application/json')
                    ->timeout(config('lfpse.request_timeout_seconds'))
                    ->post($url);

                $status = $response->status();
                $result_json = $response->body();
                $outcome = json_decode($result_json, true);
                $user = Auth::guard('web')->user();

                $code = $outcome['issue'][0]['details']['coding'][0]['code'] ?? 'Unknown code';
                    $text = $outcome['issue'][0]['details']['text'] ?? 'Unknown error text';

                if (isset($outcome['issue'][0]['diagnostics'])) {
                    $diagnostics = $outcome['issue'][0]['diagnostics'];
                    // Save the diagnostics for further investigation or logging
                    $error_save = new lfpse_errors();
                    $error_save->status = $status;
                    $error_save->severity = $outcome['issue'][0]['severity'];
                    $error_save->message = $outcome['issue'][0]['diagnostics'];
                    $error_save->record_id = $record->id;
                    $error_save->save();

                    
                    $comment = new Comment();
                    $comment->case_id = $record->recorded_case->id;
                    $comment->user_id = $user->id;
                    $comment->comment = 'LFPSE Submission Failed. Severity: ' . $outcome['issue'][0]['severity'];
                    $comment->type = 'LFPSE Submission';
                    $comment->save();

                    ActivityLog::create([
                        'user_id' => $user->id,
                        'head_office_id' => Auth::guard('web')->user()->selected_head_office->id,
                        'action' => 'Case: #' . $record->recorded_case->id . ' LFPSE Submission Failed. Severity: ' . $outcome['issue'][0]['severity'],
                        'type' => 'LFPSE Submission',
                        'timestamp' => now(),
                    ]);
                }
                if (isset($outcome['issue'][0]['details']) && ($status < 200 || $status > 299) ) {
                    $diagnostics = $outcome['issue'][0]['details'];
                    // Save the diagnostics for further investigation or logging
                    $error_save = new lfpse_errors();
                    $error_save->status = $status;
                    $error_save->severity = $outcome['issue'][0]['severity'];
                    

                    $error_save->message = 'Code: ' . $code . ' -- ' . $text;

                    $error_save->record_id = $record->id;
                    $error_save->save();


                    $comment = new Comment();
                    $comment->case_id = $record->recorded_case->id;
                    $comment->user_id = Auth::guard('web')->user()->id;
                    $comment->comment = 'LFPSE Submission Failed. Error Code: ' . $code ;
                    $comment->type = 'LFPSE Submission';
                    $comment->save();

                    ActivityLog::create([
                        'user_id' => $user->id,
                        'head_office_id' => Auth::guard('web')->user()->selected_head_office->id,
                        'action' => 'Case: #' . $record->recorded_case->id . ' LFPSE Submission Failed. Error Code: ' . $code,
                        'type' => 'LFPSE Submission',
                        'timestamp' => now(),
                    ]);
                }

                if ($status >= 200 && $status < 299) {
                    $resource_id = $outcome['id'];
                    $issues_details = [];
                    if ($outcome['resourceType'] == 'OperationOutcome') {
                        foreach ($outcome['issue'] as $issue) {
                            $issues_details[] = $issue['details']['text'];
                        }
                    }

                    $lfpse_sub = new LfpseSubmission();
                    $lfpse_sub->lfpse_id = $resource_id;
                    $lfpse_sub->be_spoke_form_records_id = $record->id;
                    if ($outcome['resourceType'] == "AdverseEvent") {
                        $lfpse_sub->version = $outcome['meta']['versionId'];
                    }
                    $lfpse_sub->outcome_type = $outcome['resourceType'];
                    $remarks = implode(", ", $issues_details);
                    $lfpse_sub->remarks = strlen($remarks) > 190 ? substr($remarks, 0, 190) : $remarks;

                    if (isset($outcome['extension']) && count($outcome['extension']) > 0) {
                        $last_extension = $outcome['extension'][count($outcome['extension']) - 1];
                        if (isset($last_extension['extension'][0]['url']) && $last_extension['extension'][0]['url'] == 'ReferenceNumber') {
                            $lfpse_sub->reference_id = $last_extension['extension'][0]['valueString'];
                        }
                    }

                    $comment = new Comment();
                    $comment->case_id = $record->recorded_case->id;
                    $comment->user_id = Auth::guard('web')->user()->id;
                    $comment->comment = 'Form Data Submitted to LFPSE';
                    $comment->type = 'LFPSE Submission';
                    $comment->save();

                    $code = $outcome['issue'][0]['details']['coding'][0]['code'] ?? 'Unknown code';
                    $text = $outcome['issue'][0]['details']['text'] ?? 'Unknown error text';

                    ActivityLog::create([
                        'user_id' => $user->id,
                        'head_office_id' => Auth::guard('web')->user()->selected_head_office->id,
                        'action' => 'Case: #' . $record->recorded_case->id . ' Form Data Submitted to LFPSE ' . $code,
                        'type' => 'LFPSE Submission',
                        'timestamp' => now(),
                    ]);

                    $lfpse_sub->save();
                    return true;
                }

                return redirect()->back()->with('error', 'LFPSE Submission Failed! Status: ' . $status);
            } catch (RequestException $e) {
                if ($e->getCode() === 28) {
                    return redirect()->back()->with('error', 'LFPSE Submission Failed due to a timeout! Please try again.');
                }
                return redirect()->back()->with('error', 'LFPSE Submission Failed! Please try again.');
            } catch (Exception $e) {
                return redirect()->back()->with('error', 'An unexpected error occurred! Please try again.');
            }
        }

        return false;
    }


    public static function submit_request_update($record, $lfpse_json, $ref_id, $ref_num)
    {
        $api_version = config("lfpse.api_endpoint");
        $api_ver_num = '-6';

        // sending data to LFPSE
        $case_id = isset($record->recorded_case) ? $record->recorded_case->id : $record->first_lfpse_record()->recorded_case->id;
        if (config('lfpse.service_active')) {
            $url = $api_version . "/adverse-event/fhir/AdverseEvent/" . $ref_id;
            try {
                $response = Http::withHeaders([
                    'Content-type' => 'application/json',
                    'Ocp-Apim-Subscription-Key' => config('lfpse.ocp_apim_subscription_key')
                ])->withOptions(['verify' => false])
                    ->withBody($lfpse_json, 'application/json')
                    ->timeout(config('lfpse.request_timeout_seconds'))
                    ->put($url);

                $status = $response->status();
                $result_json = $response->body();
                $outcome = json_decode($result_json, true);
                $code = $outcome['issue'][0]['details']['coding'][0]['code'] ?? 'Unknown code';
                    $text = $outcome['issue'][0]['details']['text'] ?? 'Unknown error text';
                $issues_details = [];


                if ($outcome['resourceType'] == 'OperationOutcome') {
                    foreach ($outcome['issue'] as $issue) {
                        if (isset($issue['details']['text'])) {
                            $issues_details[] = $issue['details']['text'];
                        } elseif (isset($issue['diagnostics'])) {
                            $issues_details[] = $issue['diagnostics'];
                        }
                    }
                }
                $user = Auth::guard('web')->user();
                if (isset($outcome['issue'][0]['diagnostics'])) {
                    $diagnostics = $outcome['issue'][0]['diagnostics'];
                    // Save the diagnostics for further investigation or logging
                    $error_save = new lfpse_errors();
                    $error_save->status = $status;
                    $error_save->severity = $outcome['issue'][0]['severity'];
                    $error_save->message = $outcome['issue'][0]['diagnostics'];
                    $error_save->record_id = $record->id;
                    $error_save->save();

                    
                    $comment = new Comment();
                    $comment->case_id = $case_id;
                    $comment->user_id = Auth::guard('web')->user()->id;
                    $comment->comment = 'LFPSE Update Submission Failed. Severity: ' . $outcome['issue'][0]['severity'];
                    $comment->type = 'LFPSE Submission';
                    $comment->save();

                    ActivityLog::create([
                        'user_id' => $user->id,
                        'head_office_id' => Auth::guard('web')->user()->selected_head_office->id,
                        'action' => 'Case: #' . $case_id . ' LFPSE Update Submission Failed. Severity: ' . $outcome['issue'][0]['severity'],
                        'type' => 'LFPSE Submission',
                        'timestamp' => now(),
                    ]);
                }
                if (isset($outcome['issue'][0]['details']) && ($status < 200 || $status > 299)) {
                    $diagnostics = $outcome['issue'][0]['details'];
                    // Save the diagnostics for further investigation or logging
                    $error_save = new lfpse_errors();
                    $error_save->status = $status;
                    $error_save->severity = $outcome['issue'][0]['severity'];
                    

                    $error_save->message = 'Code: ' . $code . ' -- ' . $text;

                    $error_save->record_id = $record->id;
                    $error_save->save();

                    $comment = new Comment();
                    $comment->case_id = $case_id;
                    $comment->user_id = Auth::guard('web')->user()->id;
                    $comment->comment = 'LFPSE Update Submission Failed. Error Code: ' . $code;
                    $comment->type = 'LFPSE Submission';
                    $comment->save();

                    ActivityLog::create([
                        'user_id' => $user->id,
                        'head_office_id' => Auth::guard('web')->user()->selected_head_office->id,
                        'action' => 'Case: #' . $case_id . ' LFPSE Update Submission Failed. Error Code: ' . $code,
                        'type' => 'LFPSE Submission',
                        'timestamp' => now(),
                    ]);
                }

                $lfpse_sub = new LfpseSubmission();
                $lfpse_sub->lfpse_id = $ref_id;
                $lfpse_sub->reference_id = $ref_num;
                $lfpse_sub->be_spoke_form_records_id = $record->id;
                $lfpse_sub->outcome_type = $outcome['resourceType'];
                $remarks = implode(", ", $issues_details);
                if (strlen($remarks) > 190) {
                    $remarks = substr($remarks, 0, 190);
                }
                $lfpse_sub->remarks = $remarks;

                if ($status >= 200 && $status < 299) {
                    $resource_id = $outcome['id'];
                    $lfpse_sub->lfpse_id = $ref_id;
                    if ($outcome['resourceType'] == "AdverseEvent") {
                        $lfpse_sub->version = $outcome['meta']['versionId'];
                    }
                    // Getting National Id
                    if (count($outcome['extension']) > 0) {
                        $last_extension = $outcome['extension'][count($outcome['extension']) - 1];
                        $ref_ext = $last_extension['extension'][0];
                        if ($ref_ext['url'] == 'ReferenceNumber') {
                            $lfpse_sub->reference_id = $ref_ext['valueString'];
                        }
                    }
                } else {
                    $lfpse_sub->save();
                    return redirect()->back()->with('error', 'LFPSE Submission Failed! Check remarks for details.');
                }

                $comment = new Comment();
                    $comment->case_id = $case_id;
                    $comment->user_id = Auth::guard('web')->user()->id;
                    $comment->comment = 'LFPSE Update Submission Succeeded. Reference Number: ' . $ref_num;
                    $comment->type = 'LFPSE Submission';
                    $comment->save();

                    ActivityLog::create([
                        'user_id' => $user->id,
                        'head_office_id' => Auth::guard('web')->user()->selected_head_office->id,
                        'action' => 'Case: #' . $case_id . ' LFPSE Update Submission Succeeded. Reference Number: ' . $ref_num,
                        'type' => 'LFPSE Submission',
                        'timestamp' => now(),
                    ]);
                
                $lfpse_sub->lfpse_id = $ref_id;
                $lfpse_sub->save();
                return true;
            } catch (Exception $e) {
                // Handle timeout specifically
                if ($e->getCode() === 28) {
                    dd($e);
                    return redirect()->back()->with('error', 'LFPSE Submission Failed due to a timeout! Please try again.');
                }
                dd($e);
                return redirect()->back()->with('error', 'LFPSE Submission Failed! Please try again.');
            }
        }

        return false;
    }




    public static function submit_request_bulk($record, $lfpse_json)
    {
        $api_version = config("lfpse.api_endpoint");
        $api_ver_num = '-6';

        // Sending data to LFPSE
        if (config('lfpse.service_active')) {
            $url = $api_version . "/adverse-event/fhir/AdverseEvent";

            $response = Http::withHeaders([
                'Content-type' => 'application/json',
                'Ocp-Apim-Subscription-Key' => config('lfpse.ocp_apim_subscription_key')
            ])->withOptions(['verify' => false])
                ->withBody($lfpse_json, 'application/json')
                ->timeout(config('lfpse.request_timeout_seconds'))
                ->post($url);

            $status = $response->status();
            $result_json = $response->body();
            $outcome = json_decode($result_json, true);
            $code = $outcome['issue'][0]['details']['coding'][0]['code'] ?? 'Unknown code';
                $text = $outcome['issue'][0]['details']['text'] ?? 'Unknown error text';

            $user = Auth::guard('web')->user();
            if (isset($outcome['issue'][0]['diagnostics'])) {
                $diagnostics = $outcome['issue'][0]['diagnostics'];
                // Save the diagnostics for further investigation or logging
                $error_save = new lfpse_errors();
                $error_save->status = $status;
                $error_save->severity = $outcome['issue'][0]['severity'];
                $error_save->message = $outcome['issue'][0]['diagnostics'];
                $error_save->record_id = $record->id;
                $error_save->save();

                ActivityLog::create([
                    'user_id' => $user->id,
                    'head_office_id' => Auth::guard('web')->user()->selected_head_office->id,
                    'action' => 'Case: #' . $record->recorded_case->id . ' LFPSE Bulk Submission Failed. ',
                    'type' => 'LFPSE Submission',
                    'timestamp' => now(),
                ]);

                $comment = new Comment();
                $comment->case_id = $record->recorded_case->id;
                $comment->user_id = $user->id;
                $comment->comment = 'LFPSE Bulk Submission Failed!';
                $comment->type = 'LFPSE Submission';
                $comment->save();
            }
            if (isset($outcome['issue'][0]['details']) && ($status < 200 || $status > 299)) {
                $diagnostics = $outcome['issue'][0]['details'];
                // Save the diagnostics for further investigation or logging
                $error_save = new lfpse_errors();
                $error_save->status = $status;
                $error_save->severity = $outcome['issue'][0]['severity'];
                

                $error_save->message = 'Code: ' . $code . ' -- ' . $text;

                $error_save->record_id = $record->id;
                $error_save->save();

                

                $comment = new Comment();
                $comment->case_id = $record->recorded_case->id;
                $comment->user_id = $user->id;
                $comment->comment = 'LFPSE Bulk Submission Failed!';
                $comment->type = 'LFPSE Submission';
                $comment->save();

                ActivityLog::create([
                    'user_id' => $user->id,
                    'head_office_id' => Auth::guard('web')->user()->selected_head_office->id,
                    'action' => 'Case: #' . $record->recorded_case->id . ' LFPSE Bulk Submission Failed. ',
                    'type' => 'LFPSE Submission',
                    'timestamp' => now(),
                ]);
            }

            if ($status >= 200 && $status < 300) {
                $result_json = $response->body();
                $outcome = json_decode($result_json, true);
                $resource_id = $outcome['id'];
                $issues_details = [];

                if ($outcome['resourceType'] == 'OperationOutcome') {
                    foreach ($outcome['issue'] as $issue) {
                        $issues_details[] = $issue['details']['text'];
                    }
                }

                // Save the data
                $lfpse_sub = new LfpseSubmission();
                $lfpse_sub->lfpse_id = $resource_id;
                $lfpse_sub->be_spoke_form_records_id = $record->id;

                if ($outcome['resourceType'] == "AdverseEvent") {
                    $lfpse_sub->version = $outcome['meta']['versionId'];
                }

                $lfpse_sub->outcome_type = $outcome['resourceType'];
                $remarks = implode(", ", $issues_details);

                if (strlen($remarks) > 190) {
                    $remarks = substr($remarks, 0, 190);
                }

                $lfpse_sub->remarks = $remarks;

                // Getting National ID
                if (isset($outcome['extension']) && count($outcome['extension']) > 0) {
                    $last_extension = $outcome['extension'][count($outcome['extension']) - 1];
                    $ref_ext = $last_extension['extension'][0];

                    if ($ref_ext['url'] == 'ReferenceNumber') {
                        $lfpse_sub->reference_id = $ref_ext['valueString'];
                    }
                }

                $comment = new Comment();
                $comment->case_id = $record->recorded_case->id;
                $comment->user_id = Auth::guard('web')->user()->id;
                $comment->comment = 'LFPSE Data Submitted via Bulk Submission Successfull!';
                $comment->type = 'LFPSE Submission';
                $comment->save();

                ActivityLog::create([
                    'user_id' => $user->id,
                    'head_office_id' => Auth::guard('web')->user()->selected_head_office->id,
                    'action' => 'Case: #' . $record->recorded_case->id . ' LFPSE Data Submitted via Bulk Submission Successfull ',
                    'type' => 'LFPSE Submission',
                    'timestamp' => now(),
                ]);

                $lfpse_sub->save();
                return true;
            } else {
                // Collect error details
                $error_details = json_decode($response->body(), true);
                $error_message = isset($error_details['issue'][0]['details']['text']) ? $error_details['issue'][0]['details']['text'] : 'LFPSE Submission Failed';
                throw new Exception("LFPSE Submission Failed: $error_message");
            }
        }

        throw new Exception('LFPSE service is not active.');
    }

    public static function estimateAgeBracket($ageBracket) {
        $day = 0;
        $month = 0;
        $year = 0;
    
        // Determine day, month, and year estimates based on the age bracket code
        switch ($ageBracket) {
            case "1": // 0-14 days
                $day = 7; // Midpoint of 0-14 days
                break;
            case "2": // 15-28 days
                $day = 21; // Midpoint of 15-28 days
                break;
            case "3": // 1-11 months
                $day = 15; // Mid-month
                $month = 6; // Midpoint of 1-11 months
                break;
            case "4": // 1-4 years
                $day = 15;
                $month = 6; // Mid-year (June)
                $year = 2; // Midpoint of 1-4 years
                break;
            case "5": // 5-9 years
                $day = 15;
                $month = 6;
                $year = 7; // Midpoint of 5-9 years
                break;
            case "6": // 10-15 years
                $day = 15;
                $month = 6;
                $year = 12; // Midpoint of 10-15 years
                break;
            case "7": // 16 and 17 years
                $day = 15;
                $month = 6;
                $year = 16; // Midpoint of 16 and 17 years
                break;
            case "8": // 18-25 years
                $day = 15;
                $month = 6;
                $year = 21; // Midpoint of 18-25 years
                break;
            case "9": // 26-45 years
                $day = 15;
                $month = 6;
                $year = 35; // Midpoint of 26-45 years
                break;
            case "10": // 46-65 years
                $day = 15;
                $month = 6;
                $year = 55; // Midpoint of 46-65 years
                break;
            case "11": // 66-85 years
                $day = 15;
                $month = 6;
                $year = 75; // Midpoint of 66-85 years
                break;
            case "12": // 85+ years
                $day = 15;
                $month = 6;
                $year = 90; // Assume 90 as an estimate for 85+ years
                break;
            default:
                return null; // Invalid age bracket
        }
    
        // Apply the formula: Patient_Age_In_Days = DD + (MM / 31) + (YYY / 372)
        $ageInDays = $day + ($month / 31) + ($year * 365);
    
        // Return the age rounded to the nearest integer
        return round($ageInDays);
    }
}
