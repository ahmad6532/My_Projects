<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'position_id',
        'is_registered',
        'registration_no',
        'location_regulatory_body_id',
        'country_of_practice',
        'first_name',
        'surname',
        'mobile_no',
        'email',
        'password',
        'email_verification_key',
        'email_verified_at',
        'password_updated_at',
        'selected_head_office_id',
    ];

    protected $dates = [
        'password_updated_at',
        'email_verified_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function scopeWithoutExternal(Builder $query)
    {
        $query->where('email', '!=', 'external@email.com');
    }

    public function scopeWhere(Builder $query, ...$parameters)
    {
        // Call the original where method
        $query->withoutExternal();
        return $query->where(...$parameters);
    }

    public static function relatedToLocation($location_id)
    {

        $currentUser = Auth::guard('web')->user();
        $userIds = LocationQuickLogin::where('location_id', $location_id)->get('user_id')->toArray();
        $locationUserIds = LocationUser::where('location_id', $location_id)->get('user_id')->toArray();

        $ids = array();
        foreach ($userIds as $value) {
            $ids[] = $value['user_id'];
        }
        if ($currentUser) {
            array_push($ids, $currentUser->id);
        }
        $ids = array_unique($ids);
        $quickLoginUsers = self::whereIn('id', $ids)
            ->where('is_active', 1)
            ->where('is_suspended', 0)
            ->where('is_archived', 0)
            ->where('email_verified_at', '!=', null)->get();

        $idsLocationUsers = array();
        foreach ($locationUserIds as $value1) {
            # Check if user is already obtained in quick login records.
            if (!in_array($value1['user_id'], $ids)) {
                $idsLocationUsers[] = $value1['user_id'];
            }
        }
        # If Locations users ids are empty. just return quick login users.
        if (empty($idsLocationUsers)) {
            return $quickLoginUsers;
        }

        $locationUsers = self::whereIn('id', $idsLocationUsers)->where('is_active', 1)
            ->where('is_suspended', 0)
            ->where('is_archived', 0)
            ->where('email_verified_at', '!=', null)->get();
        $merged = $quickLoginUsers->merge($locationUsers);
        return $merged;
    }
    public function isManager()
    {
        $location = Auth::guard('location')->user();

    }
    public function getNameAttribute()
    {
        return $this->first_name . " " . $this->surname;
    }

    public function getInitialsAttribute()
    {
        return $this->first_name[0] . $this->surname[0];
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function selected_head_office()
    {
        return $this->belongsTo(HeadOffice::class, 'selected_head_office_id');
    }

    public function getSelectedHeadOfficePositionAttribute()
    {
        $pos = "";
        $sho = $this->head_office_admins->where('head_office_id', $this->selected_head_office?->id)->first();
        if ($sho) {
            $pos = $sho->position;
        }

        return $pos;
    }

    //For intemediate table connection with selected head office !
    public function getSelectedHeadOfficeUserAttribute()
    {
        if( !isset($this->selected_head_office->id)){
            return null;
        }
        return $this->head_office_admins->where('head_office_id', $this->selected_head_office->id)->first();
    }

    public function nameWithPosition()
    {
        return $this->name . " (" . $this->position->name . ")";
    }

    public function getHasMultipleHeadOfficesAttribute()
    {
        //        dd(count($this->head_office_admins));
        return count($this->head_office_admins) > 1;
    }

    public function locationRegulatoryBody()
    {
        return $this->belongsTo('App\Models\LocationRegulatoryBody', 'location_regulatory_body_id');
    }

    public function getActiveStatusAttribute()
    {
        if ($this->is_active) {
            return "Deactivate";
        }
        return "Activate";
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

    public function head_office_admins()
    {
        return $this->hasMany(HeadOfficeUser::class);
    }

    public function getSelectedHeadOfficeAdminAttribute()
    {
        return $this->head_office_admins()->where('head_office_id', $this->selected_head_office_id)->first();
    }

    public function getSelectedHeadOfficeTimingAttribute()
    {
        $ho_admin = $this->selected_head_office_admin;
        if ($ho_admin) {
            return $ho_admin->head_office_user_timing;
        }

    }

    public function getSelectedHeadOfficeUserHolidaysAttribute()
    {
        $ho_admin = $this->selected_head_office_admin;
        if ($ho_admin) {
            return $ho_admin->head_office_user_holidays;
        }

    }

    public function getSelectedHeadOfficeUserBankHolidaySelectionsAttribute()
    {
        $ho_admin = $this->selected_head_office_admin;
        if ($ho_admin) {
            return $ho_admin->head_office_user_bank_holiday_selection;
        }

    }

    // this attribute is defined to check whether a user is logged as super admin of head office or not
    public function getLoggedInAsSuperAdminAttribute()
    {
        return false;
    }

    public function getStatusAttribute()
    {
        $status[0] = "Live";
        $status[1] = "success";

        if ($this->is_archived) {
            $status[0] = "Archived";
            $status[1] = "danger";
        }
        if (!($this->email_verified_at)) {
            $status[0] = "Awaiting Activation";
            $status[1] = "warning";
        }
        if ($this->is_suspended) {
            $status[0] = "Suspended";
            $status[1] = "orange";
        }
        return $status;
    }

    // public function selected_head_office(){
    //     return HeadOffice::find($this->selected_head_office_id);
    // }
    public function head_office_permission($permission, $object, $headOffice)
    {

    }


    public function userLoginSessions()
    {
        return $this->hasMany(UserLoginSession::class, 'user_id');
    }
    public function case_request_informations()
    {
        return $this->hasMany(CaseRequestInformation::class, 'user_id');
    }
    public function share_cases()
    {
        return $this->hasMany(ShareCase::class, 'user_id')->where('is_deleted', 0);
    }
    public function active_shared_cases()
    {
        return $this->share_cases->where('duration_of_access', '>=', Carbon::now());
    }
    public function defualt_requests_text()
    {
        return $this->hasMany(DefaultRequestInformation::class, 'user_id');
    }
    // public function profile()
    // {
    //     return $this->belongsTo(UserProfile::class,'user_id');
    // }
    // public function profileAssign()
    // {
    //     return $this->belongsTo(UserProfileAssign::class,'user_id');
    // }
    public function requests()
    {
        return $this->hasMany(CaseRequestInformation::class, 'user_id');
    }
    public function contacts()
    {
        return $this->hasMany(UserContactDetail::class, 'user_id');
    }
    public function getLogoAttribute()
    {
        if (file_exists(public_path('v2/user_profile/' . $this->id . '.jpg'))) {
            return asset('v2/user_profile/' . $this->id . '.jpg');
        }

        $nameParts = explode(' ', trim($this->first_name . ' ' . $this->surname));
        $initials = implode('', array_map(fn($word) => strtoupper(substr($word, 0, 1)), $nameParts));
        $backgroundColor = '#' . substr(md5($this->id), 0, 6);
        $svg = <<<SVG
        <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" style="background-color: {$backgroundColor}; border-radius: 50%;">
            <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-size="50" fill="white" font-family="Arial, sans-serif">{$initials}</text>
        </svg>
        SVG;

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public function getActiveEmailAttribute()
    {
        if ($this->is_email_hidden && $this->contacts()->where([['type', 1], ['is_contact_show', 1]])->first()) {
            return $this->contacts()->where([['type', 1], ['is_contact_show', 1]])->first()->contact;
        } else {
            return $this->email;
        }

    }
    public function getActiveContactAttribute()
    {
        if ($this->is_email_hidden && $this->contacts()->where([['type', 0], ['is_contact_show', 1]])->first()) {
            return $this->contacts()->where([['type', 0], ['is_contact_show', 1]])->first()->contact;
        } else {
            return $this->mobile_no;
        }

    }

    public function otp()
    {
        return $this->morphOne(otp::class, 'user');
    }

    public function routeNotificationForMail(){
        return $this->email;
    }
    public function logout(){
        Auth::guard('web')->logout();
    }

    public function getRoleName(){
        $user_role = UserRole::where('user_id',$this->id)->first();
        if(!isset($user_role)){
            $role_name = 'Not assigned';
            return $role_name;
        }
        $role_name = Role::where('id',$user_role->role_id)->first();
        return $role_name ? $role_name->name : null;

    }

    public function getHeadOfficeUser($head_office_id=null){
        if($head_office_id==null){
            $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
            if(!isset($user->selected_head_office)){
                return null;
            }
            $head_office_id = $user->selected_head_office->id;
        }
        $head_office_user = HeadOfficeUser::where('user_id',$this->id)->where('head_office_id',$head_office_id)->first();
        return $head_office_user;
    }

    public function getLocation(){
        $location_user = LocationUser::where('user_id',$this->id)->first();
        return Location::find($location_user->location_id);
    }

    public function getCaseFeedbacks(){
        return $this->hasMany(case_feedback::class, 'reported_by_user_id')->where('is_feedback_user',true)->get();
    }
}
