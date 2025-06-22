<?php

namespace App\Models;

use App\Models\Headoffices\CaseManager\TaskAssign;
use App\Models\Headoffices\Users\AccessRight;
use App\Models\Headoffices\Users\UserProfileAssign;
use App\Traits\BelongsToHeadOffice;
use App\Traits\BelongsToUser;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadOfficeUser extends Model
{
    use HasFactory,BelongsToHeadOffice,BelongsToUser;

    protected static function boot(){
        parent::boot();

        static::created(function ($headOfficeUser) {
            $user = $headOfficeUser->user;
            $contact = new new_contacts();
            $contact->name = $user->first_name . ' ' . $user->surname;
            $contact->head_office_id = $headOfficeUser->head_office_id;
            $contact->registration_no = $user->registration_no;
            $contact->personal_emails = json_encode([$user->email]);
            $contact->personal_mobiles = json_encode([$user->mobile_no]);
            $contact->date_of_birth = $user->dob ?? null;
            $contact->save();
        });
    }

    public function user_profile_assign(){
        return $this->hasOne(UserProfileAssign::class,'head_office_user_id');
    }
    public function access_right(){
        return $this->hasOne(AccessRight::class,'head_office_user_id');
    }

    public function stage_task_assigns() {
        return CaseStageTaskAssign::where('head_office_user_id',$this->id)->get();
    }

    public function temp_forms(){
        return $this->hasMany(temp_forms::class,'head_office_user_id');
    }

    public function get_permissions(){
        if($this->access_right){
            return $this->access_right->head_office_access_rights;
        }else{
            $new_rights = head_office_access_rights::where('head_office_id',$this->head_office_id)->where('system_default_profile','1')->first();
            if(!isset($new_rights)){
                $new_rights = new head_office_access_rights();
                $new_rights->head_office_id = $this->head_office_id;
                $new_rights->profile_name = 'new profile';
                $new_rights->system_default_profile = true;
                $new_rights->save();
            }
            if(!isset($this->user_profile_assign->profile)){
                $new_profile = new UserProfileAssign();
                $new_profile->user_profile_id = $new_rights->id;
                $new_profile->head_office_user_id = $this->id;
                $new_profile->save();
            }
            if(!isset($this->user_profile_assign)){
                return null;
            }
            return $this->user_profile_assign->profile;
        }
    }
    
    public function custom_permissions(){
        if($this->access_right){
            return true;
        }
        return false;
    }

    public function head_office_user_timing() {
        return $this->hasOne(HeadOfficeUserTiming::class,'id');
    }

    public function head_office_user_holidays() {
        return $this->hasMany(HeadOfficeUserHoliday::class,'head_office_user_id');
    }
    public function head_office_user_bank_holiday_selection() {
        return $this->hasMany(HeadOfficeUserBankHolidaySelection::class,'head_office_user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    function user_incident_settings() {
        return $this->hasMany(HeadOfficeUserIncidentSetting::class,'head_office_user_id');
    }
    function user_review_settings() {
        return $this->hasMany(HeadOfficeUserReviewSetting::class,'head_office_user_id');
    }
    function head_office_user_cases() {
        return $this->hasMany(CaseHandlerUser::class,'head_office_user_id');
    }
    function head_office_user_share_cases() {
        $cases = $this->hasMany(CaseHandlerUser::class, 'head_office_user_id')->get();
        $filteredCases = []; 
        foreach($cases as $case){
            $share_case = $case->case->share_cases; 
            if(count($share_case) != 0){
                $filteredCases[$case->case->id] = $share_case;
            }
        }

        return $filteredCases;
    }
    function head_office_user_final_cases() {
        $cases = $this->hasMany(CaseHandlerUser::class, 'head_office_user_id')->get();
        $filteredCases = []; 
        foreach($cases as $case){
            if($case->case->requires_final_approval != 0){
                $filteredCases[] = $case->case;
            }
        }

        return $filteredCases;
    }
    function head_office_user_contact_details()
    {
        return $this->hasMany(HeadOfficeUserContactDetail::class,'head_office_user_id');
    }
    function head_office_user_area()
    {
        return $this->hasMany(HeadOfficeUserArea::class,'head_office_user_id');
    }
    function headOffice(){
        return $this->belongsTo(HeadOffice::class,'head_office_id');
    }
    public function assigned_locations(){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $ho_u = $user->getHeadOfficeUser();
        $locs = isset($ho_u->assigned_locations) ? json_decode($ho_u->assigned_locations,true) : [];
        $locations = $headOffice->locations;
        $filteredLocations = $locations->filter(function($location) use ($locs) {
            return in_array($location->id, $locs);
        });
    
        return $filteredLocations;
    }
    public function user_to_contacts() {
        return $this->hasMany(user_to_contacts::class,'head_office_user_id');
    }
    public function user_favourite_contacts() {
        return $this->hasMany(user_favourite_contacts::class,'head_office_user_id');
    }
}
