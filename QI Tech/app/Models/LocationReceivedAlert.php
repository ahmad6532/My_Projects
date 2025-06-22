<?php

namespace App\Models;

use App\Models\Headoffices\Organisation\Group;
use App\Models\Headoffices\Organisation\LocationGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Headoffices\ReceivedNationalAlert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\Location;

use Carbon\Carbon;
class LocationReceivedAlert extends Model
{
    use HasFactory;
    public static $unactionedStatus = 'unactioned';
    public static $actionedStatus = 'actioned';

    protected $dates = ['alert_date_time','created_at','updated_at'];
    protected $table = 'location_received_alerts';

    public function location(){
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function national_alert(){
        return $this->belongsTo(NationalAlert::class, 'national_alert_id');
    }
    public function actions(){
        return $this->hasMany(PsaAction::class, 'received_alert_id');
    }
    public function action($user_id = null){
        if(!$user_id){
            $user_id = Auth::guard('web')->user();
            $user_id = $user_id->id;
        }
        return PsaAction::where('received_alert_id', $this->id)->where('user_id', $user_id)->first();
    }

    public function comments(){
        return $this->hasMany(PsaActionComment::class, 'received_alert_id');
    }

    public function updateStatus(){
        if($this->national_alert->type == "Company-Led Medicines Recall/Notification" || $this->national_alert->type == "Medicines Recall" ){
            $count = PsaAction::where('received_alert_id', $this->id)->whereIn('action_type', ['read_alert', 'read_and_taken_action'])->count();
        }else{
            # All actions will mark this as completed.
            $count = PsaAction::where('received_alert_id', $this->id)->count();
        }
        
       if($count > 0){
            $this->status = self::$actionedStatus;
            
       }else{
        $this->status = self::$unactionedStatus;
       }
       $this->save();
    }


    public function canEditAndDelete(){
        if(!$this->action()){
            # action is not set so return true;
            return true;
        }
        $timestamp = $this->action()->created_at->getTimestamp();
      
        $now = time();
        $secondsIn24Hours = 60 * 60 * 24;
        $difference = $now-$timestamp;
        if( $difference < $secondsIn24Hours){
            return true;
        }
        return false;
    }
    public static function actionsBasedOnType($alert_id,$action_type){
        return PsaAction::where('received_alert_id',$alert_id)->where('action_type',$action_type)->get();
    }


    public function timeline_date(){
        return $this->alert_date_time->format('jS F');
        //return date('jS F', strtotime($this->alert_date_time));
    }
    public function date_with_year(){
        return $this->alert_date_time->format('jS F Y');
    }

    public static function receive(Location $location){
        if($location->head_office()){
            if($location->head_office()->holding_area_on){
                //dd('here');
                self::receiveHeadOfficeAlerts($location);
                return;
            }
            // # for now prevenet locations in headoffice to receive alerts/
            // return;
        }
        $already_received = self::where('location_id',$location->id)->get('national_alert_id')->toArray();
        $ids = array();
        foreach( $already_received as $id){
            $ids[] = $id['national_alert_id'];
        }
        # Since no head office find alerts send_to_head_offices_or_location =  ALL / Locations
        # Scheduled are matched using start_time <= now.
        # Set created by CAS or admin as created by headoffice will not be shown here.
        $patient_safety_alerts = NationalAlert::whereNotIn('id',$ids)
                                ->where('status','active')
                                ->where('created_by','CAS')
                                ->where('send_to_head_offices_or_location','!=','head_offices')
                                ->whereDate('start_time','<=', Carbon::now())
                                ->get();
        
        if($location->head_office()){
            $psa_ids = array();
            foreach($patient_safety_alerts as $s){
                $psa_ids[] = $s->id;
            }
            $ids = array_merge($ids,$psa_ids);
            ReceivedNationalAlert::receiveNationalAlerts($location->head_office()->id);
            $head_office_received_alerts = ReceivedNationalAlert::where('head_office_id',$location->head_office()->id)
                                                        ->where('status','approved')
                                                        ->whereDate('alert_date_time','<=', Carbon::now())
                                                        ->whereNotIn('national_alert_id',$ids)
                                                        ->get(['id','national_alert_id']);
            $ids_temp = array();
            foreach($head_office_received_alerts as $received){
                $ids_temp[] = $received->national_alert_id;
            }
            $headoffice_national_alerts = NationalAlert::whereIn('id',$ids_temp)->get();
            if(count( $headoffice_national_alerts)){
                # match to groups/tiers
                $headOfficeLocation = HeadOfficeLocation::where('head_office_id',$location->head_office()->id)->where('location_id',$location->id)->first();
                $location_groups =$headOfficeLocation->groups; 
                foreach($headoffice_national_alerts as $key=>$alert){
                    if($alert->created_by =='CAS' || $alert->send_to_groups == 'all' || $alert->send_to_groups == null){
                        # Send to all groups,
                        # also alerts from CAS do not have groups, its head office specific.
                        continue;
                    }
                    $hasGroup = false;
                    foreach($location_groups as $g){
                        if($alert->hasGroup($g->group_id)){
                            $hasGroup = true;
                            break;
                        }
                    }   
                    if($hasGroup == false){
                        $headoffice_national_alerts->forget($key);
                    }
                }       
                $patient_safety_alerts =  $patient_safety_alerts->merge($headoffice_national_alerts);
            }
        }
        # Match Countries of this location
        foreach($patient_safety_alerts as $key=>$alert){
            if($alert->send_to_all_countries){
                # Alert go to all countries
                continue;
            }
            if($alert->hasCountry($location->country) == false){
                # Country is not matched.
                $patient_safety_alerts->forget($key);
            }
        }
        # Match All \ Locations
        foreach ($patient_safety_alerts as $key => $alert) {
            if($alert->send_to_head_offices_or_location == 'all' || $alert->send_to_all_locations){
                # Alert go to all locations
                continue;
            }
            if($alert->send_to_head_offices_or_location == 'locations'){
                if($alert->hasLocation($location->id) == false){
                    # Selected Location is not matched.
                    $patient_safety_alerts->forget($key);
                }
            }
        }
        self::processReceiveAlerts($location,$patient_safety_alerts);
    }
    public static function receiveHeadOfficeAlerts($location){
        $already_received = self::where('location_id',$location->id)->get('national_alert_id')->toArray();
        $ids = array();
        foreach( $already_received as $id){
            $ids[] = $id['national_alert_id'];
        }
        # make headoffice receive latest alerts
        ReceivedNationalAlert::receiveNationalAlerts($location->head_office()->id);
        $head_office_received_alerts = ReceivedNationalAlert::where('head_office_id',$location->head_office()->id)
                                                    ->where('status','approved')
                                                    ->whereDate('alert_date_time','<=', Carbon::now())
                                                    ->whereNotIn('national_alert_id',$ids)
                                                    ->get(['id','national_alert_id']);
        $ids_temp = array();
        foreach($head_office_received_alerts as $received){
            $ids_temp[] = $received->national_alert_id;
        }
        $headoffice_national_alerts = NationalAlert::whereIn('id',$ids_temp)->get();
        # Match to groups/tiers if exists
        $headOfficeLocation = HeadOfficeLocation::where('head_office_id',$location->head_office()->id)->where('location_id',$location->id)->first();
        $location_groups =$headOfficeLocation->groups; 
        foreach($headoffice_national_alerts as $key=>$alert){
            if($alert->created_by =='CAS' || $alert->send_to_groups == 'all' || $alert->send_to_groups == null){
                # Send to all groups,
                # also alerts from CAS do not have groups, its head office specific.
                continue;
            }
            $hasGroup = false;
            foreach($location_groups as $g){
                if($alert->hasGroup($g->group_id)){
                    $hasGroup = true;
                    break;
                }
            }   
            if($hasGroup == false){
                $headoffice_national_alerts->forget($key);
            }
        } 
        # Match Countries of this location
        foreach($headoffice_national_alerts as $key=>$alert){
            if($alert->send_to_all_countries){
                # Alert go to all countries
                continue;
            }
            if($alert->hasCountry($location->country) == false){
                # Country is not matched.
                $headoffice_national_alerts->forget($key);
            }
        }
        # Match All \ Locations
        foreach ($headoffice_national_alerts as $key => $alert) {
            if($alert->send_to_head_offices_or_location == 'all' || $alert->send_to_all_locations){
                # Alert go to all locations
                continue;
            }
            if($alert->send_to_head_offices_or_location == 'locations'){
                if($alert->hasLocation($location->id) == false){
                    # Selected Location is not matched.
                    $headoffice_national_alerts->forget($key);
                }
            }
        }
        self::processReceiveAlerts($location,$headoffice_national_alerts);
    }
    public static function processReceiveAlerts($location,$patient_safety_alerts = array()){
        foreach($patient_safety_alerts as $alert){

            $received = new self();
            $received->location_id = $location->id;
            $received->national_alert_id  = $alert->id;
            $received->status = self::$unactionedStatus;
            $received->alert_year= date('Y',strtotime($alert->start_time));
            $received->alert_date_time = $alert->start_time;
            $received->received_object_copy = json_encode($alert->attributesToArray());
            $received->save();
        }
    }
    
    public static function createNotificationForUser($user){
        $notications_already = LocationUserNotification::where('type', LocationUserNotification::$userNoticationType)->where('user_id', $user->id)->get('object_id')->toArray();
        $ids = array();
        foreach( $notications_already as $id){
            $ids[] = $id['object_id'];
        }

        
        $recieved_alerts = self::whereNotIn('id',$ids)->get();
        
        # Check if designation matches
        foreach($recieved_alerts as $key=>$received){
            if($received->national_alert->send_to_all_designations){
                # Alert go to all designations
                continue;
            }

            if($received->national_alert->hasDesignation($user->position_id) == false){
                # Designation is not matched.
                $recieved_alerts->forget($key);
            }
        }

        $location = Auth::guard('location')->user();

        # If 3 month older do not show notification.
        foreach ($recieved_alerts as $key=>$alert) {
            $actions = PsaAction::where('received_alert_id',  $alert->id)->get();
            $timeDifference =  time() - strtotime($alert->alert_date_time);
            $ninetyDaysTime = 60*60*24*90;
            
            if(count($actions) > 0 && $timeDifference >= $ninetyDaysTime   )
            {
                $recieved_alerts->forget($key);
                continue;
            }

            # Check someone has already shared it to user
            foreach($actions as $action){
                # If medicine recall, donot show if someone has read and taken action. [headoffice docx 26]
                if($alert->national_alert->type == 'Medicines Recall' &&  $action->action_type =='read_and_taken_action'){
                    $recieved_alerts->forget($key);
                    continue 2;
                }
                
                if($action->shared_this_alert == 'yes' && $action->shared_with_team == 'whole_team'){
                    # if any action is whole team do not show notification
                    $recieved_alerts->forget($key);
                    continue 2;
                }
            }

            # Check in staff table, if found donot show notification;
            $staff = PsaActionStaff::where('received_alert_id', $alert->id)->where('user_id',$user->id)->count();  
            if($staff  > 0){
                # User is selected as staff by someone, donot show notification
                $recieved_alerts->forget($key);
                continue;
            }


        
        }
        foreach($recieved_alerts as $alert){
            $notification = new LocationUserNotification();
            $notification->type = LocationUserNotification::$userNoticationType;
            $notification->title = 'A new Patient Safety Alert "'.$alert->national_alert->small_title().'" requires action.';
            $notification->user_id = $user->id;
            if($location){
                $notification->location_id = $location->id;
            }
            $notification->object_id = $alert->id;
            $notification->status = LocationUserNotification::$statusUnread;
            $notification->url = route('location.view_patient_safety_alert', $alert->id);
            $notification->save();
        }
        
        
    }

    public function quickLoginsWhoReadTheAlert(){
        $location = Auth::guard('location')->user();
        $quickLogins = LocationQuickLogin::where('location_id',$location->id)->get();
        $actions = PsaAction::where('received_alert_id', $this->id)->get();
        foreach($quickLogins as $key=>$q){
            # Check if action is submitted
            # Check if action has shared with WHOLE Team
            foreach($actions as $action){
                if($action->user_id ==  $q->user_id){
                    # User is author of some alert. Authors are already shown
                    $quickLogins->forget($key);
                    continue 2;
                }
                # not author but shared with whole team
                if($action->shared_this_alert == 'yes' && $action->shared_with_team == 'whole_team'){
                    continue 2;
                }
            }
            # no auther, no whole team, Check in staff table;
            $staff = PsaActionStaff::where('received_alert_id', $this->id)->where('user_id',$q->user_id)->count();  
            if($staff  > 0){
                # User is selected as staff by someone
                continue;
            }

            # Forgot as did not matched any condition.
            $quickLogins->forget($key);
        }
        return $quickLogins;
        
    }


}
