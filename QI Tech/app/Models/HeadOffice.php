<?php

namespace App\Models;

use App\Models\Forms\Form;
use App\Models\Headoffices\CaseManager\HeadOfficeCase;
use App\Models\Headoffices\Organisation\Group;
use App\Models\Headoffices\Organisation\LocationGroup;
use App\Models\Headoffices\Organisation\LocationTag;
use App\Models\Headoffices\Organisation\Tag;
use App\Models\Headoffices\Organisation\TagCategory;
use App\Models\Headoffices\ReceivedNationalAlert;
use App\Models\Headoffices\Users\UserProfile;
use App\Models\Headoffices\Users\UserProfileAssign;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class HeadOffice extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'head_offices';


    protected $primaryKey = 'id';

    protected $guarded = [] ;

    protected $dates = [
        'last_login_at'
    ];

    protected $casts = [];




    public function lastLoginUser()
    {
        return $this->belongsTo('App\Models\User', 'last_login_user_id');
    }

    public function users()
    {
        return $this->hasMany(HeadOfficeUser::class);
    }
    public function locations()
    {
        return $this->hasMany(HeadOfficeLocation::class, 'head_office_id');
    }
    public function getHasMultipleSuperAdminsAttribute()
    {
        return count($this->users) > 1;
    }

    public function notifiableUser()
    {

    }

    public function name()
    {
        return $this->company_name;
    }

    public function getBrandingAttribute()
    {
        $bg_path = public_path('data_images/ho_brand_files/bg') . '/' . $this->id . '.png';
        $logo_path = public_path('data_images/ho_brand_files/logo') . '/' . $this->id . '.png';
        $has_bg = file_exists($bg_path);
        $has_logo = file_exists($logo_path);
        $default_path = asset('images/svg/logo_blue.png');
        return (object) [
            "font" => $this->font,
            "bg_color" => $this->bg_color_code,
            "bg_hover" => $this->adjustBrightness($this->bg_color_code, -20),
            "gradient2" => $this->adjustBrightness($this->bg_color_code, -50),
            "logo" => $has_logo ? url('data_images/ho_brand_files/logo') . '/' . $this->id . '.png' : $default_path,
            "bg" => url('data_images/ho_brand_files/bg') . '/' . $this->id . '.png',
            "has_image" => $has_bg,
            "has_logo" => $has_logo,
        ];
    }

    public function getPreviewAttribute()
    {
        $bg_path = public_path('data_images/ho_brand_files/temp/bg') . '/' . $this->id . '.png';
        $logo_path = public_path('data_images/ho_brand_files/temp/logo') . '/' . $this->id . '.png';
        $has_bg = file_exists($bg_path);
        $has_logo = file_exists($logo_path);
        $default_path = asset('images/tl.png');
        $t = (object) [
            "font" => session('font'),
            "bg_color" => session('bg_color_code'),
            "bg_hover" => $this->adjustBrightness($this->bg_color_code, -20),
            "gradient2" => $this->adjustBrightness($this->bg_color_code, -50),
            "logo" => $has_logo ? url('data_images/ho_brand_files/temp/logo') . '/' . $this->id . '.png' : $default_path,
            "bg" => url('data_images/ho_brand_files/temp/bg') . '/' . $this->id . '.png',
            "has_image" => $has_bg,
        ];
        return $t;
    }



    private function adjustBrightness($hex, $steps)
    {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));

        // Normalize into a six character long hex string
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
        }

        // Split into three parts: R, G and B
        $color_parts = str_split($hex, 2);
        $return = '#';

        foreach ($color_parts as $color) {
            $color = hexdec($color); // Convert to decimal
            $color = max(0, min(255, $color + $steps)); // Adjust color
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
        }

        return $return;
    }


    public function getArchivedStatusAttribute()
    {
        if ($this->is_archived) {
            return "Unarchive";
        }
        return "Archive";
    }
    public function getSuspendStatusAttribute()
    {
        if ($this->is_suspended) {
            return "Unsuspend";
        }
        return "Suspend";
    }


    public function getStatusAttribute()
    {
        $status = ["Live", "success"];

        if ($this->is_archived) {
            $status[0] = "Archived";
            $status[1] = "danger";
        }
        if ($this->is_suspended) {
            $status[0] = "Suspended";
            $status[1] = "orange";
        }
        return $status;
    }
    public function canReceiveNationalAlert($nationalAlert)
    {
        if ($nationalAlert->send_to_head_offices_or_location == 'all' || $nationalAlert->send_to_all_head_offices) {
            # Alert go to all headoffices.
            return true;
        }
        if ($nationalAlert->send_to_head_offices_or_location == 'head_offices') {
            if ($nationalAlert->hasHeadOffice($this->id) == false) {
                # Selected Head Office is not matched.
                return false;
            }
        }

        if ($nationalAlert->send_to_head_offices_or_location == 'locations') {
            $headOfficeLocations = ReceivedNationalAlert::headOfficeLocationsArray($this->id);
            if (count($headOfficeLocations) && $nationalAlert->send_to_all_locations) {
                # Alert go to all locations, also headoffice has some locations.
                return true;
            }
            foreach ($headOfficeLocations as $loc) {
                if ($nationalAlert->hasLocation($loc)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function head_office_user_profiles()
    {
        return $this->hasMany(head_office_access_rights::class, 'head_office_id');
    }
    public function head_office_user_profiles_new()
    {
        return $this->hasMany(head_office_access_rights::class, 'head_office_id');
    }

    public function defaultUserAccessProfiles()
    {
        $profiles = UserProfile::where('head_office_id', $this->id)->where('system_default_profile', 1)->get();
        if (!count($profiles)) {
            $profiles = UserProfile::$defaultProfiles;
            foreach ($profiles as $p) {
                $up = new UserProfile();
                $up->head_office_id = $this->id;
                $up->profile_name = $p['profile_name'];
                if (isset($p['system_default_profile'])) {
                    $up->system_default_profile = $p['system_default_profile'];
                }
                if (isset($p['super_access'])) {
                    $up->super_access = $p['super_access'];
                }
                if (isset($p['permissions'])) {
                    # a default list of permissions can be set here.
                }
                $up->save();
            }
        }
    }

    public function defaultUserAccessProfilesNew($head_office_user=null)
    {
        $profiles = head_office_access_rights::where('head_office_id', $this->id)->where('system_default_profile', 1)->get();
        if (!count($profiles)) {
            $new_profile = new head_office_access_rights();
            $new_profile->head_office_id = $this->id;
            $new_profile->profile_name = 'Super User';
            $new_profile->system_default_profile = true;
            $new_profile->super_access = true;
            if(isset($head_office_user)){
                $new_profile->head_office_user_id = $head_office_user->id;
            }
            $new_profile->is_manage_forms = true;
            $new_profile->is_access_contacts = true;
            $new_profile->is_access_company_activity_log = true;
            $new_profile->is_manage_alert_settings = true;
            $new_profile->is_manage_location_users = true;
            $new_profile->is_manage_team = true;
            $new_profile->is_manage_company_account = true;
            $new_profile->save();
        }
    }

    public function makeUserSuperUser($head_office_user)
    {
        $profile = UserProfile::where('head_office_id', $this->id)->where('profile_name', 'Super User')
            ->where('system_default_profile', 1)->first();
        if (!$profile) {
            $this->defaultUserAccessProfiles();
            $profile = UserProfile::where('head_office_id', $this->id)->where('profile_name', 'Super User')
                ->where('system_default_profile', 1)->first();
        }
        $assign = UserProfileAssign::where('user_profile_id', $profile->id)->where('head_office_user_id', $head_office_user->id)->first();
        if (!$assign) {
            $assign = new UserProfileAssign();
        }
        $assign->user_profile_id = $profile->id;
        $assign->head_office_user_id = $head_office_user->id;
        $assign->save();
    }

    public function makeUserSuperUserNew($head_office_user)
{
    $profile = head_office_access_rights::where('head_office_id', $this->id)
        ->where('profile_name', 'Super User')
        ->where('system_default_profile', 1)
        ->first();
    
    if (!$profile) {
        $this->defaultUserAccessProfilesNew($head_office_user);
        $profile = head_office_access_rights::where('head_office_id', $this->id)
            ->where('profile_name', 'Super User')
            ->where('system_default_profile', 1)
            ->first();
    }
    
    $assignNew = UserProfileAssign::where('user_profile_id', $profile->id)
        ->orWhere('head_office_user_id', $head_office_user->id)
        ->first();
        if (!$assignNew) {
            $assignNew = new UserProfileAssign();
            $assignNew->user_profile_id = $profile->id;
            $assignNew->head_office_user_id = $head_office_user->id;
            $assignNew->save();
    }
}



    public function head_office_timing()
    {
        return $this->hasOne(HeadOfficeTiming::class, 'head_office_timing');
    }

    public function head_office_timings(){
        return $this->hasOne(head_office_timings::class,'head_office_id');
    }
    public function head_office_logo()
    {
        if (file_exists(public_path('data_images/ho_brand_files/logo/' . $this->id . '.png'))) {
            return '<img src="' . asset('data_images/ho_brand_files/logo/' . $this->id . '.png') . '" class="img-profile rounded-circle" />';
        }
        $user = Auth::guard('web')->user();
        return '<div class="rounded-circle action_person">' . $user->initials . '</div>';
    }
    public function logo_for_profile_page()
    {
        if (file_exists(public_path('data_images/ho_brand_files/logo/' . $this->id . '.png'))) {
            return '<img src="' . asset('data_images/ho_brand_files/logo/' . $this->id . '.png') . '" alt="profile picture" class="img-fluid rounded-circle img-profile profile-picture-top" width="100">';
        }
        $user = Auth::guard('web')->user();
        return '<img src="' . asset('admin_assets/img/profile-pic.png') . '" alt="profile picture" class="img-fluid rounded-circle img-profile profile-picture-top" width="100">';
    }
    

    public function be_spoke_forms()
    {
        return $this->hasMany(Form::class, 'reference_id')->where('reference_type', 'head_office');
    }
    public function organisationSettings()
    {
        return $this->hasMany(OrganisationSetting::class, 'head_office_id');
    }

    public function headOfficeCategories()
    {
        return $this->hasMany(BeSpokeFormCategory::class, 'reference_id')->where('reference_type', 'head_office');
    }
    public function cases()
    {
        return $this->hasMany(HeadOfficeCase::class, 'head_office_id');
    }
    public function fish_bone_questions()
    {
        return $this->hasMany(DefaultFishBoneQuestion::class,'head_office_id');
    }
    public function five_whys_questions()
    {
        return $this->hasMany(DefaultFiveWhysQuestion::class,'head_office_id');
    }
    public function contacts()
    {
        return $this->hasMany(Contact::class,'head_office_id');
    }
    public function addresses()
    {
        return $this->hasMany(Address::class,'head_office_id');
    }
    public function gdprs()
    {
        return $this->hasMany(GdprTag::class,'head_office_id');
    }
    public function super_users() : array {
        $users = $this->users;
        $super_admins = [];
        foreach($users as $user)
        {
            if($user->user_profile_assign && $user->user_profile_assign->profile && $user->user_profile_assign->profile->system_default_profile)
                $super_admins[] = $user->user;
        }
        return $super_admins; 
    }
    public function approved_groups() 
    {
        return $this->hasMany(ApprovedLocationGroupUser::class,'head_office_id');
    }
    public function approved_locations() 
    {
        return $this->hasMany(ApprovedLocationLocationUser::class,'head_office_id');
    }
    public function findUser($id)
    {
        return User::find($id);
    }
    public function head_office_organisation_groups()
    {
        return $this->hasMany(Group::class,'head_office_id');
    }
    public function head_office_location_groups()
    {
        return $this->hasMany(LocationGroup::class,'head_office_id');
    }
    public function getLogoAttribute()
    { 
        if (file_exists(public_path('v2/head_office_profile/'.$this->id.'.jpg')))
            return asset('v2/head_office_profile/'.$this->id.'.jpg');
        return asset('images/svg/logo_blue.png');
    }

    public function near_miss(){
        return $this->hasOne(near_miss_manager::class,'head_office_id');
    }

    public function near_miss_settings(){
        return $this->hasMany(near_miss_settings::class,'head_office_id');
    }
    public function location_tags(){
        return $this->hasMany(location_tags::class,'head_office_id');
    }
    public function new_contacts(){
        return $this->hasMany(new_contacts::class,'head_office_id');
    }

    public function contact_tags(){
        return $this->hasMany(contact_tags::class,'head_office_id');
    }
    public function contact_groups(){
        return $this->hasMany(contact_groups::class,'head_office_id');
    }
    public function new_contact_addresses(){
        return $this->hasMany(new_contact_addresses::class,'head_office_id');
    }

    public function matching_contacts()
{
    $head_office = $this;
    $matching_contacts = matching_contacts::whereHas('get_contact_1', function ($query) use ($head_office) {
        $query->where('head_office_id', $head_office->id);
    })
    ->orderBy('updated_at', 'desc') 
    ->get();

    return $matching_contacts;
}

public function case_feedbacks(){
    return $this->hasMany(case_feedback::class,'head_office_id');
}

}