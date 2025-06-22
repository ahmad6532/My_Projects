<?php

namespace App\Models\Forms;

use App\Models\AssignedBespokeForm;
use App\Models\BeSpokeFormCategory;
use App\Models\CalenderEvent;
use App\Models\DefaultCaseStage;
use App\Models\DefaultDocument;
use App\Models\DefaultTask;
use App\Models\form_default_links;
use App\Models\Forms\FormStage;
use App\Models\HeadOffice;
use App\Models\Headoffices\Organisation\Group;
use App\Models\HeadOfficeUser;
use App\Models\HeadOfficeUserIncidentSetting;
use App\Models\HeadOfficeUserReviewSetting;
use App\Models\Location;
use App\Models\OrganisationSettingBespokeForm;
use App\Models\SharedCaseApprovedEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Form extends Model
{
    use HasFactory;

    protected $table = "be_spoke_form";

    protected $guarded = [];

    protected $casts = [
        'allow_editing_state' => 'string',
        'expiry_state' => 'string',
        'schedule_state' => 'string',
    ];

    public function getActiveAttribute()
    {
        if ($this->is_active) {
            return 'Deactivate';
        }
        return 'Activate';
    }
    public function stages()
    {
        return $this->hasMany(FormStage::class, 'form_id');
    }
    public function questions()
    {
        return $this->hasMany(StageQuestion::class, 'form_id');
    }
    public function records()
    {
        return $this->hasMany(Record::class, 'form_id')->orderBy('id', 'desc');
    }

    public function form_owner()
    {
        if ($this->reference_type == 'location') {
            return $this->belongsTo(Location::class, 'reference_id');
        } else {
            return $this->belongsTo(HeadOffice::class, 'reference_id');
        }

    }

    public function head_office_profile_users()
    {
        $head_office = $this->belongsTo(HeadOffice::class, 'reference_id')->first();
        $matchingRecords = [];
        foreach ($head_office->head_office_user_profiles as $profile) {
            foreach ($profile->user_profile_assign as $assign) {
                foreach ($assign->head_office_user->get() as $user) {
                    $matchingRecords[] = $user->user;
                }
            }
        }
        return $matchingRecords;
    }

    public function groups()
    {
        $group_ids = isset($this->attributes['org_groups']) ? json_decode($this->attributes['org_groups'], true) : [];

        return Group::whereIn('id', $group_ids)->get();
    }
    public function group_assigned_locations()
    {
        // Get all groups
        $groups = $this->groups();
        $unique_locations = [];
    
        foreach ($groups as $group) {
            $this->get_group_locations($group, $unique_locations);
        }
    
        return array_values(array_unique($unique_locations, SORT_REGULAR));
    }
    
    protected function get_group_locations($group, &$unique_locations)
    {
        // Loop through the current group's location_groups
        foreach ($group->location_groups as $location_group) {
            // Check if the location group has a head_office_location_id
            if (isset($location_group->head_office_location_id)) {
                $location = Location::find($location_group->head_office_location_id);
    
                if ($location) {
                    if (!in_array($location, $unique_locations, true)) {
                        $unique_locations[] = $location;
                    }
                }
            }
        }
    
        // Recursively process children if they exist
        if ($group->children) {
            foreach ($group->children as $child_group) {
                $this->get_group_locations($child_group, $unique_locations);
            }
        }
    }
    



    public function usersToDisplay()
    {
        # only display relevant users
        $users = User::where('is_active', '1')->get(['id', 'first_name', 'surname', 'email']);
        return $users;
    }

    public function locationAddressToDisplay()
    {
        $locations = Location::where('is_active', '1')->get();
        return $locations;
    }
    public function organisationSettingBespokeForms()
    {
        return $this->hasMany(OrganisationSettingBespokeForm::class, 'be_spoke_form_id');
    }
    public function defaultTasks()
    {
        return $this->hasMany(DefaultTask::class, 'be_spoke_form_id');
    }
    public function category()
    {
        return $this->belongsTo(BeSpokeFormCategory::class, 'be_spoke_form_category_id');
    }
    public function assignedBespokeFroms()
    {
        return $this->hasMany(AssignedBespokeForm::class, 'be_spoke_form_id');
    }
    public function shared_case_approved_emails()
    {
        return $this->hasMany(SharedCaseApprovedEmail::class, 'be_spoke_form_id');
    }

    public function defaultDocuments()
    {
        return $this->hasMany(DefaultDocument::class, 'be_spoke_form_id');
    }
    public function defaultLinks()
    {
        return $this->hasMany(form_default_links::class, 'form_id');
    }
    public function formCards()
    {
        return $this->hasMany(FormCard::class, 'be_spoke_form_id');
    }
    public function form_settings()
    {
        return $this->hasMany(HeadOfficeUserIncidentSetting::class, 'be_spoke_form_id');
    }
    public function form_review_settings()
    {
        return $this->hasMany(HeadOfficeUserReviewSetting::class, 'be_spoke_form_id');
    }
    public function head_office_user_form_setting($user_id, $form_id)
    {
        //$head_office_user = Auth::guard('web')->user()->selected_head_office->users()->where('user_id',$user_id)->first();
        $form_setting = $this->form_settings()->where([['head_office_user_id', $user_id], ['be_spoke_form_id', $form_id]])->first();
        return $form_setting;
    }
    public function head_office_user_form_review_setting($user_id, $form_id)
    {
        // $head_office_user = Auth::guard('web')->user()->selected_head_office->users()->where('user_id',$user_id)->first();
        $form_setting = $this->form_review_settings()->where([['head_office_user_id', $user_id], ['be_spoke_form_id', $form_id]])->first();
        return $form_setting;
    }
    public function default_stages()
    {
        return $this->hasMany(DefaultCaseStage::class, 'be_spoke_form_id');
    }

    public function calenderEvent()
    {
        return $this->hasMany(CalenderEvent::class, 'form_id');
    }

    public function created_by()
    {
        return $this->belongsTo(HeadOfficeUser::class, 'created_by_id');
    }
    public function modified_by()
    {
        return $this->belongsTo(HeadOfficeUser::class, 'updated_by_id');
    }

    public function checkFormLimits()
    {
        $limitsReached = false;
        $form = $this;
        $user = Auth::guard('user')->user();
        $location = Auth::guard("location")->user();
        if ($form->active_limit_by_amount) {
            if ($form->amount_total_max_res && $form->limits > 0 && Record::where('form_id', $form->id)->count() >= $form->limits) {
                $limitsReached = true;
            }
            if ($form->limit_to_one_user && $form->limit_by_per_user_value > 0 && Record::where('user_id', $user->id)->where('form_id', $form->id)->count() >= $form->limit_by_per_user_value) {
                $limitsReached = true;
            }
            if ($form->limit_to_one_location && $form->limit_by_per_location_value > 0 && Record::where('location_id', $location->id)->where('form_id', $form->id)->count() >= $form->limit_by_per_location_value) {
                $limitsReached = true;
            }
        }

        if ($form->active_limit_by_period && !$limitsReached) {
            $updatedAt = Carbon::parse($form->updated_at);
            if ($form->limit_by_period_max_state != 'off') {
                $submissionsThisPeriod = $this->countSubmissionsWithinPeriod($form->id, $form->limit_by_period_max_state, $updatedAt);
                if ($submissionsThisPeriod >= $form->limit_by_period_max_value) {
                    $limitsReached = true;
                }
            }
        }
        return $limitsReached;
    }

    private static function countSubmissionsWithinPeriod($formId, $periodState, $updatedAt)
    {
        $query = Record::where('form_id', $formId);

        switch ($periodState) {
            case 'day':
                $startPeriod = Carbon::now()->startOfDay();
                break;
            case 'month':
                $startPeriod = Carbon::now()->startOfMonth();
                break;
            case 'year':
                $startPeriod = Carbon::now()->startOfYear();
                break;
            default:
                throw new \Exception("Invalid period state: $periodState");
        }

        return $query->where('created_at', '>=', $startPeriod)->count();
    }
    public function get_quick_description()
    {
        return HasMany::share_case_quick_descriptions($this);
    }
    public function get_case_approvers()
    {
        $reviewSettings = $this->hasMany(HeadOfficeUserReviewSetting::class, 'be_spoke_form_id')->get();
        $reviewSettingsWithUsers = [];
        foreach ($reviewSettings as $setting) {
            $user = $setting->head_office_user->user;
            $reviewSettingsWithUsers[] = [
                'user' => $user,
            ];
        }
        return $reviewSettingsWithUsers;
    }

    public function update_time_left($created_at) {
        $time = $this->allow_update_time; 
        $state = $this->allow_update_state;
            if ($state === 'disable') {
            return ['allowed' => false, 'remaining_time' => null];
        }
    
        if ($state === 'always') {
            return ['allowed' => true, 'remaining_time' => null];
        }
            $allowedTime = null;
        switch ($state) {
            case 'hour':
                $allowedTime = $time * 60 * 60; 
                break;
            case 'day':
                $allowedTime = $time * 24 * 60 * 60; 
                break;
            case 'week':
                $allowedTime = $time * 7 * 24 * 60 * 60; 
                break;
            default:
                return ['allowed' => false, 'remaining_time' => null]; 
        }
            $createdAtTimestamp = strtotime($created_at);
        $currentTime = time();
        $elapsedTime = $currentTime - $createdAtTimestamp;
            if ($elapsedTime <= $allowedTime) {
            $remainingTime = $allowedTime - $elapsedTime;
            return ['allowed' => true, 'remaining_time' => $remainingTime];
        } else {
            return ['allowed' => false, 'remaining_time' => 0];
        }
    }
    public function editing_time_left($created_at) {
        $time = $this->allow_editing_time; 
        $state = $this->allow_editing_state;
            if ($state === 'disable') {
            return ['allowed' => false, 'remaining_time' => null];
        }
    
        if ($state === 'always') {
            return ['allowed' => true, 'remaining_time' => null];
        }
            $allowedTime = null;
        switch ($state) {
            case 'hour':
                $allowedTime = $time * 60 * 60; 
                break;
            case 'day':
                $allowedTime = $time * 24 * 60 * 60; 
                break;
            case 'week':
                $allowedTime = $time * 7 * 24 * 60 * 60; 
                break;
            default:
                return ['allowed' => false, 'remaining_time' => null]; 
        }
            $createdAtTimestamp = strtotime($created_at);
        $currentTime = time();
        $elapsedTime = $currentTime - $createdAtTimestamp;
            if ($elapsedTime <= $allowedTime) {
            $remainingTime = $allowedTime - $elapsedTime;
            return ['allowed' => true, 'remaining_time' => $remainingTime];
        } else {
            return ['allowed' => false, 'remaining_time' => 0];
        }
    }
    
}
