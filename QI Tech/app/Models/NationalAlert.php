<?php

namespace App\Models;

use App\Models\Headoffices\NationalAlertGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NationalAlert extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'national_alerts';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    protected $dates = [
               'deleted_at'
           ];
    
    protected $casts = [];


    public static $types = array(
        'National Patient Safety Alert','Supply Disruption Alert','Central Alerting Team','Company-Led Medicines Recall/Notification',
        'Medicines Recall','Custom','None'
    );
    public static $originators = array(
        'NHS England','MHRA','NHS Improvement','Office of the Chief Medical Officer','DHSC Supply Disruption','NHS Digital',
        'Public Health England','NHS Improvement Estates and Facilities','NHS England Emergency Preparedness & Response',
        'Custom'
    );

    public static $classes = array(
        'Class 1','Class 2','Class 3','Class 4','None'
    );

    public function hasOriginator($orginator = null){
        return NationalAlertOriginator::where('national_alert_id', $this->id)->where('originator', $orginator)->first();
    }
    public function hasCountry($country = null){
        return NationalAlertCountry::where('national_alert_id', $this->id)->where('country', $country)->first();
    }
    public function hasDesignation($designationId = null){
        return NationalAlertDesignation::where('national_alert_id', $this->id)->where('position_id', $designationId)->first();
    }
    public function hasHeadOffice($head_office_id = null){
        return NationalAlertHeadOffice::where('national_alert_id', $this->id)->where('head_office_id', $head_office_id)->first();
    }
    public function hasLocation($location_id = null){
        return NationalAlertLocation::where('national_alert_id', $this->id)->where('location_id', $location_id)->first();
    }
    public function hasGroup($group_id = null){
        return NationalAlertGroup::where('national_alert_id', $this->id)->where('group_id', $group_id)->first();
    }

    public static function showClassTitle($class = null){
        switch($class){
            case 'Class 1':
                return '(NatPSA, Immediate recall)';
            case 'Class 2':
                return '(Medicines Recall, Recall within 48 hours)';
            case 'Class 3':
                return '(Medicines Recall, Action within 5 days)';
            case 'Class 4':
                return '(Caution in Use, Information Only)';
        }
    }
    public function alertColor(){
        if($this->class == 'Class 1' || $this->class == 'Class 2'){
            return 'text-danger';
        }
        return 'text-primary';
    }
    public static function actionWithInTitle($action_within = null){
        switch($action_within){
            case '1':
                return 'Must be actioned within 24 hours';
            case '2':
                return 'Must be actioned within 48 hours';
            case '5':
                return 'Must be actioned within 5 days';
            case '28':
                return 'Action within 28 days';
            case 'Custom':
                return 'Custom';
        }
    }
    public function showActionWithinTitle(){
        if($this->action_within == 'Custom'){
            return "Action within ".$this->action_within_days." days";
        }else{
            return self::actionWithInTitle($this->action_within);
        }
    }

    public function countries(){
        return $this->hasMany(NationalAlertCountry::class,'national_alert_id');
    }
    public function designations(){
        return $this->hasMany(NationalAlertDesignation::class,'national_alert_id');
    }
    public function documents(){
        return $this->hasMany(NationalAlertDocument::class,'national_alert_id');
    }
    public function head_offices(){
        return $this->hasMany(NationalAlertHeadOffice::class,'national_alert_id');
    }
    public function locations(){
        return $this->hasMany(NationalAlertLocation::class,'national_alert_id');
    }
    public function originators(){
        return $this->hasMany(NationalAlertOriginator::class,'national_alert_id');
    }
    public function groups(){
        return $this->hasMany(NationalAlertGroup::class,'national_alert_id');
    }

    public function type(){
        if($this->type == 'Custom'){
            return "Custom (".$this->type_custom.")";
        }else{
            return $this->type;
        }
    }
    public function action(){
        switch($this->action_within){
            case '1':
                return '';
        }
            
    }
    public function getClassDescripiton($class = null){
        
        if($this->class == 'Class 1')
        {
           return 'Class 1 requires immediate recall, because the product poses a serious or life threatening risk to health.';
        }
        else if($this->class == 'Class 2')
        {
           return 'Class 2 specifies a recall within 48 hours, because the defect could harm the patient but is not life threatening.';
        }
        else if($this->class== 'Class 3')
        {
            return 'Class 3 requires action to be taken within 5 days because the defect is unlikely to harm patients and is being carried out for reasons other than patient safety.';
        }
        else if($this->class== 'Class 4')
        {
           return 'Class 4 alerts advise caution to be exercised when using the product, but indicate that the product poses no threat to patient safety.';
        } 
        else if ($this->class == 'None') {
        }
        return '';
    }

    public function canEditScheduleDateTime($editing = false){
        # New Record
        if(!$this->id){
            return true;
        }
        # Saved record with no scheduling. this will be already dispatched so user can't edit later to schedule.
        if($this->id && $this->schedule_later == 'no'){
            return false;
        }
        # If schedule later  == yes
        if($this->schedule_later == 'yes'){
            # If editing and already dispatched. return false            
            if($this->id && time() > strtotime($this->start_time)){
                
                return false;
            # if editing and not dispatched, can change.
            }elseif($this->id && time() <= strtotime($this->start_time)){
                return true;
            }
        }
        # For safety set it to false.
        return false;
    }

    public function is_overdue(){
        $now = time();
        $start_time = strtotime($this->start_time);
        $over_due_date = $start_time + $this->calculateActionWithDaysInSeconds(); 
        if($now <= $over_due_date){
            # Start date is big. Alert not started
            return false;
        }
        if($now > $over_due_date){
            return true;
        }
    }
    public function generateOverDueString(){
        $now = time();
        $start_time = strtotime($this->start_time);
        $over_due_date = $start_time + $this->calculateActionWithDaysInSeconds();

        $difference = $now - $over_due_date;
        if($difference < 60){
            return "1 minute";
        }
        return self::time_elapsed_string($difference);
    }
    public static function  time_elapsed_string($etime)
    {
        //$etime = time() - $ptime;
        if ($etime < 1)
        {
            return '0 seconds';
        }
        $a = array( 365 * 24 * 60 * 60  =>  'year',
                     30 * 24 * 60 * 60  =>  'month',
                          24 * 60 * 60  =>  'day',
                               60 * 60  =>  'hour',
                                    60  =>  'minute',
                                     1  =>  'second'
                    );
        $a_plural = array( 'year'   => 'years',
                           'month'  => 'months',
                           'day'    => 'days',
                           'hour'   => 'hours',
                           'minute' => 'minutes',
                           'second' => 'seconds'
                    );
    
        foreach ($a as $secs => $str)
        {
            $d = $etime / $secs;
            if ($d >= 1)
            {
                $r = round($d);
                return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . '';
            }
        }
    }
    public function calculateActionWithDaysInSeconds(){
        $seconds_in_one_day = 86400;
        switch($this->action_within){
            case '1':
            return 1*$seconds_in_one_day;
            case '2':
            return 2*$seconds_in_one_day;
            case '5':
            return 5*$seconds_in_one_day;
            case '28':
                return 28*$seconds_in_one_day;
            case 'Custom':
                return ((int) $this->action_within_days) * $seconds_in_one_day;
        }
    }

    public function short_summary(){
        if(strlen($this->summary) > 200){
            return substr($this->summary, 0, 200) . ' ...';
        }
        return $this->summary;
    }
    public function small_title(){
        if(strlen($this->title) > 100){
            return substr($this->title, 0, 100) . ' ...';
        }
        return $this->title;
    }

}
