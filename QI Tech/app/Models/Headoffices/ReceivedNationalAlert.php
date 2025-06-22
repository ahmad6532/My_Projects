<?php

namespace App\Models\Headoffices;

use App\Models\HeadOffice;
use App\Models\HeadOfficeLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\NationalAlert;
use Carbon\Carbon;
class ReceivedNationalAlert extends Model
{
    use HasFactory;

    protected $table = 'head_office_received_national_alerts';
    protected $dates = ['alert_date_time','created_at','updated_at'];

    public function national_alert(){
        return $this->belongsTo(NationalAlert::class, 'national_alert_id');
    }
    public function date_with_year(){
        return $this->alert_date_time->format('jS F Y');
    }
    public function has_child_alert(){
        return NationalAlert::where('head_office_id',$this->head_office_id)->where('parent_id',$this->national_alert_id)->count();
    }

    public static function headOfficeLocationsArray($head_office_id){
        $locs = HeadOfficeLocation::where('head_office_id',$head_office_id)->get('location_id');
        $ids = array();
        foreach($locs as $loc){
            $ids[] = $loc->location_id;
        }
        return $ids;
    }
    public static function receiveNationalAlerts($head_office_id){
        $already_received = self::where('head_office_id',$head_office_id)->get('national_alert_id')->toArray();
        $ids = array();
        foreach( $already_received as $id){
            $ids[] = $id['national_alert_id'];
        }
        # send_to_head_offices_or_location =  ALL / Head_offices
        # Scheduled are matched using start_time <= now.
        $patient_safety_alerts = NationalAlert::query();
        //$patient_safety_alerts->where('status','active');
        $patient_safety_alerts->where(function($q) use($ids){
                $q->where('send_to_head_offices_or_location','!=','locations');
                $q->whereNotIn('id',$ids);
                $q->whereDate('start_time','<=', Carbon::now());
        });
        $patient_safety_alerts->orWhere(function($q) use ($head_office_id,$ids){
            $q->where('created_by','head_office');
            $q->where('head_office_id',$head_office_id);
            $q->whereNotIn('id',$ids);
        });         
        $patient_safety_alerts->orWhere(function($q) use ($head_office_id,$ids){
            $q->where('send_to_head_offices_or_location','locations');
            $q->where('created_by','CAS');
            $q->whereDate('start_time','<=', Carbon::now());
            $q->whereNotIn('id',$ids);
        });  
        
        $patient_safety_alerts =  $patient_safety_alerts->get();

       
        # Match headoffice location first -- send to locations hold by some headoffice
        foreach ($patient_safety_alerts as $key => $alert) {
            if($alert->send_to_head_offices_or_location != 'locations' ||   $alert->created_by == 'head_office' ){
                continue;
            }
            if($alert->send_to_all_locations){
                # Alert go to all locations
                continue;
            }
            $headOfficeLocations = self::headOfficeLocationsArray($head_office_id);
            $oneLocationIsMatched = false;
            foreach($headOfficeLocations as $loc){
                if( $alert->hasLocation($loc)){
                    $oneLocationIsMatched = true;
                    break;
                }
            }
            # NO location is matched.
            if($oneLocationIsMatched == false){
                $patient_safety_alerts->forget($key);
            }

        }
       
         # Match All \ Headoffices
         foreach ($patient_safety_alerts as $key => $alert) {
            if($alert->send_to_head_offices_or_location == 'all' || $alert->send_to_all_head_offices){
                # Alert go to all locations
                continue;
            }
            if($alert->created_by !== 'head_office' &&  $alert->send_to_head_offices_or_location == 'head_offices'){
                if($alert->hasHeadOffice($head_office_id) == false){
                    # Selected Head Office is not matched.
                    $patient_safety_alerts->forget($key);
                }
            }
            # Somehow alert created by some other headoffice comes in.
            if($alert->created_by == 'head_office' && $alert->head_office_id != $head_office_id){
                $patient_safety_alerts->forget($key);
            }
        }
        self::processReceiveAlerts($head_office_id,$patient_safety_alerts);
    }

    public static function processReceiveAlerts($head_office_id,$patient_safety_alerts = array()){
        $headOffice = HeadOffice::find($head_office_id);
        foreach($patient_safety_alerts as $alert){
            $received = new self();
            $received->head_office_id = $head_office_id;
            $received->national_alert_id  = $alert->id;
            $received->status =  $headOffice->holding_area_on?'unapproved':'approved';
            $received->alert_date_time = $alert->start_time;
            $received->received_object_copy = json_encode($alert->attributesToArray());
            $received->save();
        }
    }

    public static function createNotificationForHeadOffice($head_office){
        if(!$head_office->holding_area_on){
            # Only show notifications of holding area on.
            return;
        }
        $notications_already = HeadofficeUserNotification::where('type', HeadofficeUserNotification::$userNoticationTypePSA)->where('head_office_id', $head_office->id)->get('object_id')->toArray();
        $ids = array();
        foreach( $notications_already as $id){
            $ids[] = $id['object_id'];
        }
        $recieved_alerts = self::whereNotIn('id',$ids)->where('head_office_id',$head_office->id)->where('status','unapproved')->get();
        # If 3 month older do not show notification.
        foreach ($recieved_alerts as $key=>$alert) {
            $timeDifference =  time() - strtotime($alert->alert_date_time);
            $ninetyDaysTime = 60*60*24*90;
            if($timeDifference >= $ninetyDaysTime)
            {
                $recieved_alerts->forget($key);
                continue;
            }
        }
        
        foreach($recieved_alerts as $alert){
            if($alert->status !='unapproved'){
                # Alert is already approved
                continue;
            }
            $notification = new HeadofficeUserNotification();
            $notification->type = HeadofficeUserNotification::$userNoticationTypePSA;
            $notification->title = 'A new Patient Safety Alert "'.$alert->national_alert->small_title().'" requires approval.';
            $notification->head_office_id = $head_office->id;
            $notification->object_id = $alert->id;
            $notification->status = HeadofficeUserNotification::$statusUnread;
            $notification->url = route('head_office.psa.holding_area');
            $notification->save();
        }
        
        
    }
}
