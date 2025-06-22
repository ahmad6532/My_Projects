<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Models\Forms\Form;
use App\Models\Forms\Record;
use App\Models\Headoffices\CaseManager\HeadOfficeCase;
use App\Models\Headoffices\Organisation\Group;
use App\Models\Headoffices\Organisation\LocationGroup;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Str;

class Location extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'id',
        'location_type_id',
        'location_pharmacy_type_id',
        'location_regulatory_body_id',

        'registered_company_name',
        'trading_name',
        'username',
        'registration_no',
        'address_line1',
        'address_line2',
        'address_line3',
        'town',
        'county',
        'country',
        'postcode',
        'telephone_no',

        'email',
        'password',
        'email_verification_key',
        'email_verified_at',
        'is_active',
        'tag_id',
        'location_code',
        'ods_name'
    ];

    protected $hidden = ['password', 'email_verification_key'];
    protected $dates = ['email_verified_at', 'last_login_at'];

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

    public static function locationRelatedToHeadOffice($location_id = null)
    {
        if ($location_id == null) {
            $location_id = Auth::guard('location')->user()->id;
        }
        # First find head office.
        $locationHeadOffice = HeadOfficeLocation::where('location_id', $location_id)->first();
        # No head office is assigned.
        # above cannot be the case in newer design !
        if (!$locationHeadOffice) {
            return null;//self::where('id', $location_id)->get();
        }

        $locations = HeadOfficeLocation::where('head_office_id', $locationHeadOffice->head_office_id)->get('location_id')->toArray();
        $ids = array();
        foreach ($locations as $loc) {
            $ids[] = $loc['location_id'];
        }
        return self::whereIn('id', $ids)->where('is_active', 1)
            ->where('is_suspended', 0)
            ->where('is_archived', 0)
            ->where('email_verified_at', '!=', null)
            ->get();
    }
    public function regulatory_body()
    {
        return $this->belongsTo(LocationRegulatoryBody::class, 'location_regulatory_body_id');
    }
    public function location_type()
    {
        return $this->belongsTo(LocationType::class);
    }
    // can be nullable
    public function pharmacy_type()
    {
        return $this->belongsTo(LocationPharmacyType::class, 'location_pharmacy_type_id');
    }
    //must will provide some value //
    public function getPharmacyTypeName()
    {
        return optional($this->pharmacy_type)->name;
    }

    public function head_office()
    {
        $head_office_location = HeadOfficeLocation::where('location_id', $this->id)->first();
        if ($head_office_location) {
            return $head_office_location->head_office;
        } else {
            return null;
        }
    }

    public function head_office_location()
    {
        return $this->hasOne(HeadOfficeLocation::class, 'location_id');
    }

    public function group_forms()
{
    // Get all forms with org_groups in one query
    $forms = $this->head_office()->be_spoke_forms()->where('is_external_link',0)->whereNotNull('org_groups')
        ->where('org_groups', '!=', '')
        ->get();

    // Get location groups with relationships in one query
    $loc_groups = LocationGroup::with(['group.parent'])
        ->where([
            'head_office_location_id' => $this->head_office_location->id,
            'head_office_id' => $this->head_office()->id
        ])
        ->get();

    $found_forms = [];
    $group_hierarchies = [];

    // Build group hierarchies first
    foreach ($loc_groups as $loc_group) {
        $hierarchy = [$loc_group->group_id];
        $parent = $loc_group->group->parent;
        
        while ($parent) {
            $hierarchy[] = $parent->id;
            $parent = $parent->parent;
        }
        
        $group_hierarchies[] = $hierarchy;
    }

    foreach ($forms as $form) {
        $org_groups = json_decode($form->org_groups, true) ?? [];
        
        foreach ($group_hierarchies as $hierarchy) {
            if (array_intersect($hierarchy, $org_groups)) {
                $found_forms[] = $form;
                break;
            }
        }
    }

    return collect($found_forms);
}

    public function quick_logins()
    {
        return $this->hasMany(LocationQuickLogin::class);
    }

    public function getBrandingAttribute()
    {
        $bg_path = public_path('data_images/location_branding/bg') . '/' . $this->id . '.png';
        $logo_path = public_path('data_images/location_branding/logo') . '/' . $this->id . '.png';
        $has_bg = file_exists($bg_path);
        $has_logo = file_exists($logo_path);
        $default_path = asset('images/svg/logo_blue.png');
        return (object) [
            "font" => $this->font,
            "bg_color" => $this->bg_color_code,
            "bg_hover" => $this->adjustBrightness($this->bg_color_code, -20),
            "gradient2" => $this->adjustBrightness($this->bg_color_code, -50),
            "logo" => $has_logo ? url('data_images/location_branding/logo') . '/' . $this->id . '.png' : $default_path,
            "bg" => url('data_images/location_branding/bg') . '/' . $this->id . '.png',
            "has_image" => $has_bg,
            "has_logo" => $has_logo,
        ];
    }

    public function getPreviewAttribute()
    {
        $bg_path = public_path('data_images/location_brand_request_files/temp/bg') . '/' . $this->id . '.png';
        $logo_path = public_path('data_images/location_brand_request_files/temp/logo') . '/' . $this->id . '.png';
        $has_bg = file_exists($bg_path);
        $has_logo = file_exists($logo_path);
        $default_path = asset('images/tl.png');
        $t = (object) [
            "font" => session('font'),
            "bg_color" => session('bg_color_code'),
            "bg_hover" => $this->adjustBrightness($this->bg_color_code, -20),
            "gradient2" => $this->adjustBrightness($this->bg_color_code, -50),
            "logo" => $has_logo ? url('data_images/location_brand_request_files/temp/logo') . '/' . $this->id . '.png' : $default_path,
            "bg" => url('data_images/location_brand_request_files/temp/bg') . '/' . $this->id . '.png',
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

    public function getStatusAttribute()
    {
        $status[0] = "Subscribed";
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

    public function getLastLoginTimeAttribute()
    {
        //$now = Carbon::now();
        if ($this->last_login_at) {
            return $this->last_login_at->diffForHumans();
        }

        return "N/A";
    }

    public function getLastLoginUserNameAttribute()
    {
        //$now = Carbon::now();
        if ($this->lastLoginUser) {
            return $this->lastLoginUser->name;
        }

        return "N/A";
    }

    public function drafts(){
        return $this->hasMany(be_spoke_form_record_drafts::class, 'location_id');
    }

    public function getFullAddressAttribute()
    {
        $address = $this->address_line1;
        if (!empty($this->address_line2)) {
            $address .= ", " . $this->address_line2;
        }
        if (!empty($this->address_line3)) {
            $address .= ", " . $this->address_line2;
        }
        $address .= ", " . $this->town . ", " . $this->county . ", " . $this->country . ", " . $this->postcode;
        return $address;
    }

    public function name()
    {
        //return $this->registered_company_name . ' - '.$this->full_address;
        return $this->trading_name . ' - ' . $this->full_address;
    }
    public function short_name()
    {
        return $this->trading_name;
    }
    /**
     * Get the lastLoginUser for this model.
     *
     */
    public function lastLoginUser()
    {
        return $this->belongsTo('App\Models\User', 'last_login_user_id');
    }

    /**
     *
     */
    public function users()
    {
        return $this->hasMany(LocationUser::class, 'location_id');
    }
    public function managers()
    {
        return $this->hasMany(LocationManager::class, 'location_id');
    }

    /**
     * Get the list of users for this model.
     *
     *
     */
    public function getHasMultipleManagersAttribute()
    {
        return count($this->users) > 1;
    }

    /**
     *
     */
    public function service_message()
    {
        $today = Carbon::now();
        $serviceMessage = ServiceMessage::where('expires_at', '>', $today)->get();
        if (in_array('Location', $serviceMessage->receiver_list)) {
            return $serviceMessage;
        }
    }

    public function opening_hours()
    {
        if (!LocationOpeningHours::find($this->id)) {
            $h = new LocationOpeningHours();
            $h->location_id = $this->id;
            $h->save();
        }
        return $this->hasOne(LocationOpeningHours::class, 'location_id');
    }
    public function totalOpenDays()
    {
        $counter = 0;
        if ($this->opening_hours->open_monday) {$counter++;}
        if ($this->opening_hours->open_tuesday) {$counter++;}
        if ($this->opening_hours->open_wednesday) {$counter++;}
        if ($this->opening_hours->open_thursday) {$counter++;}
        if ($this->opening_hours->open_friday) {$counter++;}
        if ($this->opening_hours->open_saturday) {$counter++;}
        if ($this->opening_hours->open_sunday) {$counter++;}
        return $counter;
    }
    public function openToday()
    {
        $today = date('l');
        switch ($today) {
            case 'Monday':
                return $this->opening_hours->open_monday;
            case 'Tuesday':
                return $this->opening_hours->open_tuesday;
            case 'Wednesday':
                return $this->opening_hours->open_wednesday;
            case 'Thursday':
                return $this->opening_hours->open_thursday;
            case 'Friday':
                return $this->opening_hours->open_friday;
            case 'Saturday':
                return $this->opening_hours->open_saturday;
            case 'Sunday':
                return $this->opening_hours->open_sunday;
        }
    }

    public function NearMiss(){
        return NearMiss::where('location_id',$this->id)->get();
    }

    public function getQrCodeLinkAttribute()
    {
        $path = 'public/qr_codes/near_miss/' . $this->id . '.svg';
        if (!Storage::exists($path)) {
            $url = url("/report/near/miss?location_id=" . $this->id);

            $qrCode = QrCode::format('svg')
                ->size(300)
                ->margin(2)
                ->generate($url);

            // Save the QR code to storage
            Storage::put($path, $qrCode, 'public');
        }

        return asset('storage/qr_codes/near_miss/' . $this->id . '.svg');
    }
    public function getFormQr($form_id)
    {
        $path = 'public/qr_codes/forms/' . $form_id . '.svg';
        if (!Storage::exists($path)) {
            $url = url("/bespoke_form_v3/#!/submit/" . $form_id . "?location_id=" . $this->id);

            $qrCode = QrCode::format('svg')
                ->size(300)
                ->margin(2)
                ->generate($url);

            // Save the QR code to storage
            Storage::put($path, $qrCode, 'public');
        }

        return asset('storage/qr_codes/forms/' . $form_id . '.svg');
    }
    public function userIsManager($user_id = null)
    {
        if (!$user_id) {
            $user_id = (Auth::guard('web')->user()) ? Auth::guard('web')->user()->id : null;
        }
        $manager = LocationManager::where('location_id', $this->id)->where('user_id', $user_id)->count();
        return ($manager > 0) ? true : false;
    }
    public function hasManagers()
    {
        return LocationManager::where('location_id', $this->id)->count();
    }
    public function userCanUpdateSettings($user_id = null)
    {
        if (!$user_id) {
            $user_id = (Auth::guard('web')->user()) ? Auth::guard('web')->user()->id : null;
        }
        # First check if headoffice exists.
        $headOffice = $this->head_office();
        if ($headOffice) {
            return false;
        }
        if ($this->hasManagers() && $this->userIsManager($user_id)) {
            return true;
        }
        return false;
        // Later this code can be used, so headoffice will save settings.
        // if($headOffice){
        //     $headOfficeUsers = HeadOfficeUser::where('head_office_id', $headOffice->id)->where('user_id', $user_id)->count();
        //     if($headOfficeUsers > 0){
        //         # This user is headoffice.
        //     }
        // }
    }

    public function be_spoke_forms()
    {
        // if($this->head_office())
        //     return $this->hasOne(OrganisationSettingAssignment::class, 'location_id');
        return $this->hasMany(Form::class, 'reference_id')->where('reference_type', 'location');
    }

    public function records()
    {
        return $this->hasMany(Record::class, 'location_id');
    }

    public function getBeSpokeFormsAttribute()
    {

        return Form::where([['reference_type', 'location'], ['reference_id', $this->id]])->get();
    }
    public function organization_setting_assignment()
    {
        return $this->hasOne(OrganisationSettingAssignment::class, 'location_id');
    }

    public function assigned_bespoke_forms()
    {
        return $this->hasMany(AssignedBespokeForm::class, 'location_id');
    }

    public function beSpokeFormCategories()
    {
        return $this->hasMany(BeSpokeFormCategory::class, 'reference_id')->where('reference_type', 'location');
    }

    public function getRootCauseAnalysisRequestsAttribute()
    {
        $requests = [];
        $records = $this->records;
        foreach ($records as $record) {
            if ($record->recorded_case) {
                $cases = $record->recorded_case->root_cause_analysis_requests;
                if (count($cases) > 0) {
                    foreach ($record->recorded_case->root_cause_analysis_requests()->where('status', 0)->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])->get() as $c) {
                        $requests[] = $c;
                    }

                }
            }
        }
        return $requests;
    }
    public function cases()
    {
        return $this->hasMany(HeadOfficeCase::class, 'location_id');
    }
    public function otp()
    {
        return $this->morphOne(otp::class, 'user');
    }

    public function routeNotificationForMail()
    {
        return $this->email;
    }

    public function getOtpLogs(){
        return $this->hasMany(location_otp_logs::class,'location_id');
    }

    public function logout(){
        Auth::guard('location')->logout();
    }

    public function assigned_tag(){

        return $this->hasOne(location_tags::class,'id','tag_id');
    }

    public function location_tag(){
        return $this->hasMany(location_tags::class,'location_id');
    }

    public function regenerateToken($location_id,$request)
    {
        $user = Auth::guard('web')->user();
        $ho = $user->selected_head_office;
        $newToken = Str::random(20);
        $hashedNewToken = hash('sha256', $newToken);

        // Retrieve the user's current IP address and user agent
        $ipAddress = $request->ip();
        $userAgent = $request->header('User-Agent');

        // Check if there is already an existing token for this location
        $token = remote_location_tokens::where('location_id', $location_id)->where('head_office_id',$ho->id)->where('user_id',$user->id)->first();
        if (isset($token) && $token->expires_at < now()) {
            $token->delete();
        }elseif(!isset($token)){
            $token = new remote_location_tokens();
            $token->location_id = $location_id;
            $token->head_office_id = $ho->id;
            $token->user_id = $user->id;
            $token->token = $hashedNewToken;
            $token->ip = $ipAddress;
            $token->user_agent = $userAgent;
            $token->expires_at = now()->addMinutes(30);
            $token->save();
        }

        session(['remote_access' => $token->token]);

        return $token;
    }
}
