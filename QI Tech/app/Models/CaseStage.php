<?php

namespace App\Models;

use App\Models\Headoffices\CaseManager\HeadOfficeCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CaseStage extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        // Run this function after a new record is created
        static::created(function ($caseStage) {

            $caseStage->stage_started();

        });

        // Run this function after an existing record is updated
        static::updated(function ($caseStage) {
            $caseStage->stage_re_open();
        });
    }

    public function stage_started()
    {
        if($this->percentComplete() != 0 && empty($this->tasks)){
            return;
        }
        $current_stage = $this->is_current_stage;
        $head_office = $this->case->case_head_office;
        $form = $this->case->link_case_with_form->form;

        $data = json_decode($this->stage_rules, true);
        $startedData = isset($data['started']) && !empty($data['started']) ? $data['started']: null; 
        if ($current_stage == 1) {
            if (isset($startedData)) {
                $usersArray = [];
            
                foreach ($startedData as $singleRule) {
                    $conditionType = $singleRule['condition_type'];
                    $viewPreviousStages = $singleRule['view_previous_stages'] ?? false;
                    $viewFutureStages = $singleRule['view_future_stages'] ?? false;
            
                    if ($conditionType == 1) {
                        $uniqueProfiles = $singleRule['user_profiles'];
                        $condition = $singleRule['add_user']; // 1 for all users & 2 for single random user
                        $matchedProfiles = $head_office
                            ->head_office_user_profiles()
                            ->whereIn('id', $uniqueProfiles)
                            ->get();
                    $profileUsers = [];

                    foreach ($matchedProfiles as $profile) {
                        $usersForProfile = [];
                        foreach ($profile->user_profile_assign as $assign) {
                            $user = $assign->head_office_user->user ?? null;
                            if ($user) {
                                $userId = $user->id;
                                $usersForProfile[$userId] = [
                                    'condition_type' => $conditionType,
                                    'data' => $user,
                                    'view_previous_stages' => $viewPreviousStages,
                                    'view_future_stages' => $viewFutureStages,
                                ];
                            }
                        }
                        $profileUsers[] = $usersForProfile;
                    }

                        if ($condition == 2) {
                            foreach ($profileUsers as $usersForProfile) {
                                if (!empty($usersForProfile)) {
                                    $randomUserId = array_rand($usersForProfile);
                                    $usersArray[$randomUserId] = $usersForProfile[$randomUserId];
                                }
                            }
                        } else {
                            foreach ($profileUsers as $usersForProfile) {
                                $usersArray = array_merge($usersArray, $usersForProfile);
                            }
                        }
                    } elseif ($conditionType == 2) {
                        $uniqueUsers = $singleRule['users'];
                        $matchedUsers = User::whereIn('id', $uniqueUsers)->get();
            
                        foreach ($matchedUsers as $user) {
                            $userId = $user->id;
                            $usersArray[$userId] = [
                                'condition_type' => $conditionType,
                                'data' => $user,
                                'view_previous_stages' => $viewPreviousStages,
                                'view_future_stages' => $viewFutureStages,
                            ];
                        }
                    }elseif($conditionType == 3){
                        $email_message = $singleRule['message'];
                        if($singleRule['email_user_type'] == 1){
                            $email_users = $singleRule['users'];
                            foreach ($email_users as $email_user) {
                                $e_user = User::find($email_user);
                                if($e_user){
                                    Mail::html($email_message, function($message) use ($e_user) {
                                        $message->to($e_user->email)
                                                ->subject('Qitech - Stage Started ' . $this->name);
                                    });
                                }
                            }
                        }elseif($singleRule['email_user_type'] == 2){
                            $profiles = $singleRule['user_profiles'];
                            $Email_Profiles = $head_office
                            ->head_office_user_profiles()
                            ->whereIn('id', $profiles)
                            ->get();
                            foreach ($Email_Profiles as $profile) {
                                foreach ($profile->user_profile_assign as $assign) {
                                    $user = $assign->head_office_user->user ?? null;
                                    if ($user) {
                                        Mail::html($email_message, function($message) use ($user) {
                                            $message->to($user->email)
                                                    ->subject('Qitech - Stage Started ' . $this->name);
                                        });
                                    }
                                }
                            }
                        }elseif($singleRule['email_user_type'] == 3){
                            $location_email = $this->case->location_email;
                            if($form->is_external_link == true){
                                // Handle external Users email here
                                
                            }else{
                                if(isset($location_email) && $location_email != 'external@qitech.com'){
                                    Mail::html($email_message, function($message) use ($location_email) {
                                        $message->to($location_email)
                                                ->subject('Qitech - Stage Started ' . $this->name);
                                    });
                                }
                            }
                        }
                    }
                }
                
                $matchingRecords = array_values($usersArray);
                foreach ($matchingRecords as $record) {
                    $head_office_user = $record['data']->getHeadOfficeUser($head_office->id);
                    $case_handler = CaseHandlerUser::where('head_office_user_id', $head_office_user->id)->first();
                    if(!isset($case_handler_id)){
                        $case_handler = CaseHandlerUser::create([
                            'head_office_user_id' => $head_office_user->id,
                            'case_id' => $this->case->id,

                        ]);
                    };
                    stage_case_handler::updateOrCreate(
                        [
                            'case_handler_id' => $case_handler->id,
                            'stage_id' => $this->id,
                        ],
                        [
                            'can_view_future_stages' => $record['view_future_stages'],
                            'can_view_past_stages' => $record['view_previous_stages'],
                        ]
                    );
                }
            }
        }
    }

    public function stage_re_open()
    {
        if($this->percentComplete() != 0){
            return;
        }
        $current_stage = $this->is_current_stage;
        $head_office = $this->case->case_head_office;
        $form = $this->case->link_case_with_form->form;

        $data = json_decode($this->stage_rules, true);
        $startedData = isset($data['started']) && !empty($data['started']) ? $data['started']: null; 
            if (isset($startedData)) {
                $usersArray = [];
            
                foreach ($startedData as $singleRule) {
                    $conditionType = $singleRule['condition_type'];
                    $viewPreviousStages = $singleRule['view_previous_stages'] ?? false;
                    $viewFutureStages = $singleRule['view_future_stages'] ?? false;
            
                    if ($conditionType == 1) {
                        $uniqueProfiles = $singleRule['user_profiles'];
                        $condition = $singleRule['add_user']; // 1 for all users & 2 for single random user
                        $matchedProfiles = $head_office
                            ->head_office_user_profiles()
                            ->whereIn('id', $uniqueProfiles)
                            ->get();
                    $profileUsers = [];

                    foreach ($matchedProfiles as $profile) {
                        $usersForProfile = [];
                        foreach ($profile->user_profile_assign as $assign) {
                            $user = $assign->head_office_user->user ?? null;
                            if ($user) {
                                $userId = $user->id;
                                $usersForProfile[$userId] = [
                                    'condition_type' => $conditionType,
                                    'data' => $user,
                                    'view_previous_stages' => $viewPreviousStages,
                                    'view_future_stages' => $viewFutureStages,
                                ];
                            }
                        }
                        $profileUsers[] = $usersForProfile;
                    }

                        if ($condition == 2) {
                            foreach ($profileUsers as $usersForProfile) {
                                if (!empty($usersForProfile)) {
                                    $randomUserId = array_rand($usersForProfile);
                                    $usersArray[$randomUserId] = $usersForProfile[$randomUserId];
                                }
                            }
                        } else {
                            foreach ($profileUsers as $usersForProfile) {
                                $usersArray = array_merge($usersArray, $usersForProfile);
                            }
                        }
                    } elseif ($conditionType == 2) {
                        $uniqueUsers = $singleRule['users'];
                        $matchedUsers = User::whereIn('id', $uniqueUsers)->get();
            
                        foreach ($matchedUsers as $user) {
                            $userId = $user->id;
                            $usersArray[$userId] = [
                                'condition_type' => $conditionType,
                                'data' => $user,
                                'view_previous_stages' => $viewPreviousStages,
                                'view_future_stages' => $viewFutureStages,
                            ];
                        }
                    }elseif($conditionType == 3){
                        $email_message = $singleRule['message'];
                        if($singleRule['email_user_type'] == 1){
                            $email_users = $singleRule['users'];
                            foreach ($email_users as $email_user) {
                                $e_user = User::find($email_user);
                                if($e_user){
                                    Mail::html($email_message, function($message) use ($e_user) {
                                        $message->to($e_user->email)
                                                ->subject('Qitech - Stage Started ' . $this->name);
                                    });
                                }
                            }
                        }elseif($singleRule['email_user_type'] == 2){
                            $profiles = $singleRule['user_profiles'];
                            $Email_Profiles = $head_office
                            ->head_office_user_profiles()
                            ->whereIn('id', $profiles)
                            ->get();
                            foreach ($Email_Profiles as $profile) {
                                foreach ($profile->user_profile_assign as $assign) {
                                    $user = $assign->head_office_user->user ?? null;
                                    if ($user) {
                                        Mail::html($email_message, function($message) use ($user) {
                                            $message->to($user->email)
                                                    ->subject('Qitech - Stage Started ' . $this->name);
                                        });
                                    }
                                }
                            }
                        }elseif($singleRule['email_user_type'] == 3){
                            $location_email = $this->case->location_email;
                            if($form->is_external_link == true){
                                // Handle external Users email here
                                
                            }else{
                                if(isset($location_email) && $location_email != 'external@qitech.com'){
                                    Mail::html($email_message, function($message) use ($location_email) {
                                        $message->to($location_email)
                                                ->subject('Qitech - Stage Started ' . $this->name);
                                    });
                                }
                            }
                        }
                    }
                }
                
                $matchingRecords = array_values($usersArray);
                foreach ($matchingRecords as $record) {
                    $head_office_user = $record['data']->getHeadOfficeUser($head_office->id);
                    $case_handler = CaseHandlerUser::where('head_office_user_id', $head_office_user->id)->first();
                    if(!isset($case_handler)){
                        $case_handler = CaseHandlerUser::create([
                            'head_office_user_id' => $head_office_user->id,
                            'case_id' => $this->case->id,

                        ]);
                    };
                    stage_case_handler::updateOrCreate(
                        [
                            'case_handler_id' => $case_handler->id,
                            'stage_id' => $this->id,
                        ],
                        [
                            'can_view_future_stages' => $record['view_future_stages'],
                            'can_view_past_stages' => $record['view_previous_stages'],
                        ]
                    );
                }
            }
    }

    public function stage_completed(){
        if($this->percentComplete() == 100){
            $current_stage = $this->is_current_stage;
            $head_office = $this->case->case_head_office;
            $form = $this->case->link_case_with_form->form;

            $data = json_decode($this->stage_rules, true);
            $completedData = isset($data['completed']) && !empty($data['completed']) ? $data['completed']: null;
            if (isset($completedData)) {
                $usersArray = [];
            
                foreach ($completedData as $singleRule) {
                    $conditionType = $singleRule['condition_type'];
                    $viewPreviousStages = $singleRule['view_previous_stages'] ?? false;
                    $viewFutureStages = $singleRule['view_future_stages'] ?? false;
            
                    if ($conditionType == 1) {
                        $uniqueProfiles = $singleRule['user_profiles'];
                        $condition = $singleRule['add_user']; // 1 for all users & 2 for single random user
                        $matchedProfiles = $head_office
                            ->head_office_user_profiles()
                            ->whereIn('id', $uniqueProfiles)
                            ->get();
                    $profileUsers = [];

                    foreach ($matchedProfiles as $profile) {
                        $usersForProfile = [];
                        foreach ($profile->user_profile_assign as $assign) {
                            $user = $assign->head_office_user->user ?? null;
                            if ($user) {
                                $userId = $user->id;
                                $usersForProfile[$userId] = [
                                    'condition_type' => $conditionType,
                                    'data' => $user,
                                    'view_previous_stages' => $viewPreviousStages,
                                    'view_future_stages' => $viewFutureStages,
                                ];
                            }
                        }
                        $profileUsers[] = $usersForProfile;
                    }

                        if ($condition == 2) {
                            foreach ($profileUsers as $usersForProfile) {
                                if (!empty($usersForProfile)) {
                                    $randomUserId = array_rand($usersForProfile);
                                    $usersArray[$randomUserId] = $usersForProfile[$randomUserId];
                                }
                            }
                        } else {
                            foreach ($profileUsers as $usersForProfile) {
                                $usersArray = array_merge($usersArray, $usersForProfile);
                            }
                        }
                    } elseif ($conditionType == 2) {
                        $uniqueUsers = $singleRule['users'];
                        $matchedUsers = User::whereIn('id', $uniqueUsers)->get();
            
                        foreach ($matchedUsers as $user) {
                            $userId = $user->id;
                            $usersArray[$userId] = [
                                'condition_type' => $conditionType,
                                'data' => $user,
                                'view_previous_stages' => $viewPreviousStages,
                                'view_future_stages' => $viewFutureStages,
                            ];
                        }
                    }elseif($conditionType == 3){
                        $email_message = $singleRule['message'];
                        if($singleRule['email_user_type'] == 1){
                            $email_users = $singleRule['users'];
                            foreach ($email_users as $email_user) {
                                $e_user = User::find($email_user);
                                if($e_user){
                                    Mail::html($email_message, function($message) use ($e_user) {
                                        $message->to($e_user->email)
                                                ->subject('Qitech - Stage Started ' . $this->name);
                                    });
                                }
                            }
                        }elseif($singleRule['email_user_type'] == 2){
                            $profiles = $singleRule['user_profiles'];
                            $Email_Profiles = $head_office
                            ->head_office_user_profiles()
                            ->whereIn('id', $profiles)
                            ->get();
                            foreach ($Email_Profiles as $profile) {
                                foreach ($profile->user_profile_assign as $assign) {
                                    $user = $assign->head_office_user->user ?? null;
                                    if ($user) {
                                        Mail::html($email_message, function($message) use ($user) {
                                            $message->to($user->email)
                                                    ->subject('Qitech - Stage Started ' . $this->name);
                                        });
                                    }
                                }
                            }
                        }elseif($singleRule['email_user_type'] == 3){
                            $location_email = $this->case->location_email;
                            if($form->is_external_link == true){
                                // Handle external Users email here
                                
                            }else{
                                if(isset($location_email) && $location_email != 'external@qitech.com'){
                                    Mail::html($email_message, function($message) use ($location_email) {
                                        $message->to($location_email)
                                                ->subject('Qitech - Stage Started ' . $this->name);
                                    });
                                }
                            }
                        }
                    }
                }
                
                $matchingRecords = array_values($usersArray);
                foreach ($matchingRecords as $record) {
                    $head_office_user = $record['data']->getHeadOfficeUser($head_office->id);
                    $current_stage_handler = stage_case_handler::where('stage_id',$this->id)->first();
                    if(isset($current_stage_handler)){
                        $current_stage_handler->delete();
                    }
                    foreach($this->case->stages as $stage){
                        $stage_handler = $stage->stage_case_handlers;
                        if(empty($stage_handler)){
                            CaseHandlerUser::where('case_id',$this->case_id)->where('head_office_user_id',$head_office_user->id)->where('master_stage_handler',false)->delete();
                        }
                    }
                }

            }
        }
    }



    public function my_tasks()
    {
        // Check if the user is authenticated
    if (!Auth::guard('web')->check()) {
        // If not authenticated, return an empty collection
        return collect();
    }

    // If authenticated, return the tasks assigned to the authenticated user
    return $this->hasMany(CaseStageTask::class, 'case_stage_id')
        ->whereHas('assigned', function ($query) {
            $query->where('head_office_user_id', Auth::guard('web')->user()->id);
        });
    }
    public function tasks()
    {
        return $this->hasMany(CaseStageTask::class, 'case_stage_id');
    }

    public function tasks_completed()
    {
        return $this->hasMany(CaseStageTask::class, 'case_stage_id')
            ->where('case_stage_id', $this->id)
            ->where(function ($query) {
                $query->where('status', 'completed')
                    ->orWhere('status', 'completed_not_applicable');
            });
    }
    public function case()
    {
        return $this->belongsTo(HeadOfficeCase::class, 'case_id');
    }
    public function percentComplete()
    {
        $totalTasks = $this->tasks()->count();
        if ($totalTasks == 0) {
            return 0; // Return 0% if there are no tasks
        }

        $completedTasks = $this->tasks_completed()->count();
        $percentage = floor(($completedTasks / $totalTasks) * 100);

        return $percentage;
    }

    // public function current_stage()
    // {
    //     if($this->tasks()->count())
    //     {
    //         $counter =  $this->tasks()->count();
    //         $percentage = floor(($this->tasks_completed()->count()/$counter)*100); 
    //         if($percentage == 100)
    //             return 'Completed';
    //         elseif()
    //     }
    //     else
    //         return "No Task Assgin";
    // }
    public function status()
    {
        $counter = $this->tasks()->count();
        if (!$counter)
            return 0;
        $percentage = floor(($this->tasks_completed()->count() / $counter) * 100);
        if ($percentage == 100)
            return 'Completed';
    }

    public function stage_case_handlers(){
        return $this->hasMany(stage_case_handler::class,'stage_id');
    }
    
}
