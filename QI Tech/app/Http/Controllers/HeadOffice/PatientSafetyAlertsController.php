<?php

namespace App\Http\Controllers\HeadOffice;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\HeadOfficeLocation;
use App\Models\Headoffices\NationalAlertGroup;
use App\Models\Headoffices\Organisation\Group;
use App\Models\Headoffices\ReceivedNationalAlert;
use App\Models\NationalAlertCountry;
use App\Models\NationalAlertDesignation;
use App\Models\NationalAlertDocument;
use App\Models\NationalAlertHeadOffice;
use App\Models\NationalAlertLocation;
use App\Models\NationalAlertOriginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\NationalAlert;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PatientSafetyAlertsController extends Controller
{
    public $perPage = 25;
    
    # My organization
    public function index(Request $request){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        ReceivedNationalAlert::receiveNationalAlerts($headOffice->id);
        ReceivedNationalAlert::createNotificationForHeadOffice($headOffice);
        $status = 'approved';
        $is_archived = 0;
        if($request->query('status')){
            $status = $request->query('status');
        }

        if($request->query('is_archived')){
            $is_archived = (int)$request->query('is_archived');
        }
        $alerts = ReceivedNationalAlert::where('head_office_id',$headOffice->id)
                                        ->where('status',$status)
                                        ->where('is_archived',$is_archived)
                                        ->orderBy('alert_date_time','desc')
                                        ->paginate($this->perPage);

        return view('head_office.patient_safety_alerts.index',compact('alerts','headOffice'));
    }
    public function holding_area(Request $request){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        ReceivedNationalAlert::receiveNationalAlerts($headOffice->id);
        ReceivedNationalAlert::createNotificationForHeadOffice($headOffice);
        $alerts = ReceivedNationalAlert::where('head_office_id',$headOffice->id)->where('status','unapproved')->orderBy('alert_date_time','desc')->paginate($this->perPage);
        return view('head_office.patient_safety_alerts.holding_area',compact('alerts','headOffice'));
    }

    public function view(Request $request, $id){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $alert = ReceivedNationalAlert::where('head_office_id',$headOffice->id)->where('id',$id)->first();
        if(!$alert){
            abort(404);
        }
        return view('head_office.patient_safety_alerts.view',compact('alert','headOffice'));
    }
    
    public function holding_area_on_off(Request $request){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $holdingAreaWasOn = ($headOffice->holding_area_on)?true:false;
        $headOffice->holding_area_on = (int)$request->holding_area_on;
        $headOffice->save();
        # Case when holding area was on and turned off by user
        if($holdingAreaWasOn && $headOffice->holding_area_on == 0){
           if($request->what_to_do){
            if($request->what_to_do == 'approve_all'){
                $alerts = ReceivedNationalAlert::where('head_office_id',$headOffice->id)->where('status','unapproved')->update(['status'=>'approved']);
            }
           }
        }
        return redirect()->route('head_office.settings')->withFragment('#patient_safety_alert_settings')->with('success_message','Patient safety alerts settings saved successfully.');
    }

    public function record(Request $request, $id = null){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $nationalAlert = NationalAlert::find($id);
         
        $countries = Helper::countries();
        $designations = \App\Models\Position::all();
        $locations = HeadOfficeLocation::where('head_office_id', $headOffice->id)->get();
        $groups = Group::where('head_office_id',$headOffice->id)->where('parent_id',null)->get();

        return view('head_office.patient_safety_alerts.record',compact('nationalAlert','countries','locations','designations','groups'));
    }
    public function approve(Request $request, $id = null){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $alert = ReceivedNationalAlert::where('head_office_id',$headOffice->id)->where('id',$id)->first();
        if(!$alert){
            abort(404);
        }
        $alert->status = 'approved';
        $alert->save();
        return redirect()->route('head_office.psa.holding_area')->with('success','Alert is approved successfully.');
    }
    public function reject(Request $request, $id = null){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $alert = ReceivedNationalAlert::where('head_office_id',$headOffice->id)->where('id',$id)->first();
        if(!$alert){
            abort(404);
        }
        $alert->status = 'rejected';
        $alert->save();
        return redirect()->route('head_office.psa.holding_area')->with('success','Alert is rejected.');
    }
    
    public function archive(Request $request, $id = null){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $alert = ReceivedNationalAlert::where('head_office_id',$headOffice->id)->where('id',$id)->first();
        if(!$alert){
            abort(404);
        }
        $alert->is_archived = $request->query('unarchive')?0:1;
        $alert->save();
        return redirect()->route('head_office.psa')->with('success','Alert is changed successfully.');
    }
    public function save(Request $request){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $nationalAlert = NationalAlert::find($request->id);
        # Created by admin so clone this one. donot edit.
        $message = "National Alert was successfully edited";

        if($nationalAlert && $nationalAlert->created_by =='CAS'){
            $nationalAlert = new NationalAlert();
            $message = "National Alert was successfully added.";
        }

        
        if(!$nationalAlert){
            $nationalAlert = new NationalAlert();
            $message = "National Alert was successfully added.";
        }
        $nationalAlert->title = $request->title;
        $nationalAlert->type = $request->type;
        $nationalAlert->custom_type = ($request->type == 'Custom')?$request->custom_type:'';
        
        $nationalAlert->custom_originator = (in_array('Custom', $request->originator))?$request->custom_originator:'';
        $nationalAlert->class = $request->class;
        $nationalAlert->action_within = $request->action_within;
        $nationalAlert->action_within_days = ($request->action_within == 'Custom')?$request->action_within_days:'';
        $nationalAlert->summary = $request->summary;
        $nationalAlert->suggested_actions = $request->suggested_actions;
        $nationalAlert->patient_level_recall = (int)$request->patient_level_recall;

        $nationalAlert->send_to_groups = $request->send_to_groups;
        // Other hidden fields.
        $nationalAlert->send_to_head_offices_or_location ='locations';
        $nationalAlert->send_to_all_head_offices = 0;
        $nationalAlert->status = 'active';
        $nationalAlert->created_by = 'head_office';
        $nationalAlert->head_office_id = $headOffice->id;
        $nationalAlert->parent_id = ((int)$request->parent_id)?(int)$request->parent_id:null;

        # Schedule Later
        if($nationalAlert->canEditScheduleDateTime()){
            $nationalAlert->schedule_later = $request->schedule_later;
        }
        
        if( $nationalAlert->canEditScheduleDateTime()&& $nationalAlert->schedule_later == 'yes'){
            $date = $request->schedule_date;
            $time = $request->schedule_time;
            $datetime = "$date $time:00";
            $nationalAlert->start_time = $datetime;
        }else{
            # if already created, store that time otherwise store current save time.
            $nationalAlert->start_time = (isset($nationalAlert->created_at))?$nationalAlert->created_at:date('Y-m-d H:i:s');
        }

        $nationalAlert->save();

        if((int)$nationalAlert->parent_id){
           $parentCASAlert =  ReceivedNationalAlert::where('national_alert_id',$nationalAlert->parent_id)->where('head_office_id',$headOffice->id)->first();
           if($parentCASAlert){
                $parentCASAlert->status = 'edited';
                $parentCASAlert->save();
           }
        }
        # Update scheduled alert date time.
        $receivedAlert = ReceivedNationalAlert::where('head_office_id',$headOffice->id)->where('national_alert_id',$nationalAlert->id)->first();
        if($receivedAlert){
            $receivedAlert->alert_date_time = $nationalAlert->start_time;
            $receivedAlert->received_object_copy = json_encode($nationalAlert->attributesToArray());
            $receivedAlert->save();
        }
        NationalAlertHeadOffice::where('national_alert_id', $nationalAlert->id)->delete();

        $originators = (array) $request->originator;
        NationalAlertOriginator::where('national_alert_id', $nationalAlert->id)->delete();
        foreach($originators as $value){
            $originator = new NationalAlertOriginator();
            $originator->national_alert_id = $nationalAlert->id;
            $originator->originator = $value;
            $originator->save();
        }
        $countires = (array) $request->send_to_countries;
        NationalAlertCountry::where('national_alert_id', $nationalAlert->id)->delete();
        foreach($countires as $value){
            if ($value == 'all') {
                $nationalAlert->send_to_all_countries = 1;
                $nationalAlert->save();
                # If all is selected break; do not store other values
                break;
            }else{
                $country = new NationalAlertCountry();
                $country->national_alert_id = $nationalAlert->id;
                $country->country = $value;
                $country->save();
            }
        }
        $designations = (array) $request->send_to_designation;
        NationalAlertDesignation::where('national_alert_id', $nationalAlert->id)->delete();
        foreach($designations as $value){
            if ($value == 'all') {
                $nationalAlert->send_to_all_designations = 1;
                $nationalAlert->save();
                # If all is selected break; do not store other values
                break;
            }else{
                $designation = new NationalAlertDesignation();
                $designation->national_alert_id = $nationalAlert->id;
                $designation->position_id = $value;
                $designation->save();
            }

            
        }

        NationalAlertLocation::where('national_alert_id', $nationalAlert->id)->delete();
        if ($nationalAlert->send_to_head_offices_or_location == 'locations') {
            $locations = (array) $request->send_to_locations;
            $nationalAlert->send_to_all_locations = 0;
            $nationalAlert->save();
            foreach ($locations as $value) {
                if($value == 'all'){
                    $nationalAlert->send_to_all_locations = 1;
                    $nationalAlert->save();
                        # If all is selected break; do not store other values
                    break;
                } else {
                    $loc = new NationalAlertLocation();
                    $loc->national_alert_id = $nationalAlert->id;
                    $loc->location_id = $value;
                    $loc->save();
                }
                
            }
        }
        NationalAlertGroup::where('national_alert_id', $nationalAlert->id)->delete();
        if ($nationalAlert->send_to_groups == 'specific') {
            $groups = (array) $request->group_id;
            foreach ($groups as $value) {
                $gp = new NationalAlertGroup();
                $gp->national_alert_id = $nationalAlert->id;
                $gp->group_id = $value;
                $gp->save();
            }
        }

        $documents = (array) $request->documents;
        NationalAlertDocument::where('national_alert_id', $nationalAlert->id)->delete();
        foreach($documents as $value){
            $doc = new NationalAlertDocument();
            $doc->national_alert_id = $nationalAlert->id;
            $value = Document::where('unique_id',$value)->first();
            if(!$value){
                continue;
            }
            $doc->document_id = $value->id;
            $doc->save();
        }
        return redirect()->route('head_office.psa')->with('success_message', $message);
    }
}
