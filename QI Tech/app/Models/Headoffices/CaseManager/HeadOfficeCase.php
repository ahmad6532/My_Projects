<?php

namespace App\Models\Headoffices\CaseManager;


use App\Helpers\Helper;
use App\Models\CaseContact;
use App\Models\CaseHandlerUser;
use App\Models\CaseInterestedParty;
use App\Models\CaseManagerCaseDocument;
use App\Models\CaseRequestInformation;
use App\Models\CaseStage;
use App\Models\contact_to_case;
use App\Models\Forms\Form;
use App\Models\Forms\Record;
use App\Models\HeadOffice;
use App\Models\Link;
use App\Models\linked_cases;
use App\Models\Location;
use App\Models\RootCauseAnalysis;
use App\Models\RootCauseAnalysisRequest;
use App\Models\ShareCase;
use App\Models\SystemLink;
use App\Models\User;
use App\Models\user_case_restrictions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class HeadOfficeCase extends Model
{
    use HasFactory;

    protected $table = 'head_office_cases';


    public function comments()
    {
        return $this->hasMany(Comment::class, 'case_id');
    }
    public function tasks()
    {
        return $this->stages()
            ->with('tasks')
            ->get()          // Eager load tasks with stages
            ->pluck('tasks') // Collect all tasks from each stage
            ->flatten();     // Flatten the collection to a single array of tasks
    }

    public function saved_loc()
    {
        return $this->belongsTo(Location::class, 'saved_location');
    }


    public function stages()
    {
        return $this->hasMany(CaseStage::class, 'case_id');
    }

    public function current_stage()
    {
        return $this->hasOne(CaseStage::class, 'case_id')->where('is_current_stage', true);
    }

    public function my_tasks()
    {
        return $this->hasMany(Task::class, 'case_id')->whereHas('assigned', function ($query) {
            $query->where('head_office_user_id', Auth::guard('web')->user()->id);
        });
    }
    public function tasks_completed()
    {
        return $this->hasMany(Task::class, 'case_id')->where('status', 'completed')->orWhere('status', 'completed_not_applicable');
    }
    public function comments_top_level()
    {
        return $this->hasMany(Comment::class, 'case_id')->where('parent_id', null);
    }

    public function linked_location_incident()
    {
        return $this->hasOne(HeadOfficeLinkedCase::class);
    }
    # Also called case NUMBER
    public function id()
    {
        # for now make it 
        return $this->id;
    }
    public function last_accessed()
    {
        return Helper::time_elapsed_string(strtotime($this->last_accessed)) . " ago";
    }

    public function last_action()
    {
        return Helper::time_elapsed_string(strtotime($this->last_action)) . " ago";
    }

    public function days_ago()
    {
        return Helper::time_elapsed_string(strtotime($this->created_at)) . " ago";
    }
    public function incident_occured()
    {
        return date('d M Y', strtotime($this->created_at));
    }
    public function reported_date()
    {
        return date('D d M Y, h:i a', strtotime($this->created_at));
    }

    public function status()
    {
        // if($this->status == 'open'){
        //     return 'In Progress';
        // }
        return ucfirst($this->status);
    }
    public function percentComplete()
    {
        $totalTasks = 0;
        $completedTasks = 0;

        foreach ($this->stages as $stage) {
            $tasks = $stage->tasks()->count();
            $completedTasksInStage = $stage->tasks_completed()->count();

            $totalTasks += $tasks;
            $completedTasks += $completedTasksInStage;
        }

        if ($totalTasks == 0) {
            return 100; // If there are no tasks at all, we consider it 100% complete.
        } else {
            $percentage = floor(($completedTasks / $totalTasks) * 100);
            return $percentage;
        }
    }

    public function case_links()
    {
        return $this->hasMany(Link::class, 'head_office_case_id');
    }

    public function system_links()
    {
        return $this->hasMany(SystemLink::class, 'case_id')->orderBy('created_at', 'desc');
    }
    public function all_links()
    {
        $default_links = $this->link_case_with_form->form->defaultLinks;
        $system_links = $this->hasMany(SystemLink::class, 'case_id')->get();
        $system_links_array = $system_links->toArray();

        if (isset($default_links) && !empty($default_links)) {
            $system_links_array = array_merge($system_links_array, $default_links->toArray());
        }
        return collect($system_links_array);
    }

    public function case_head_office()
    {
        return $this->belongsTo(HeadOffice::class, 'head_office_id');
    }
    public function case_documents()
    {
        return $this->hasMany(CaseManagerCaseDocument::class, 'case_id');
    }

    public function root_cause_analysis()
    {
        return $this->hasMany(RootCauseAnalysis::class, 'case_id');
    }
    public function completed_root_cause_analysis()
    {
        return $this->root_cause_analysis->where('status', 2);
    }
    public function root_cause_analysis_requests()
    {
        return $this->hasMany(RootCauseAnalysis::class, 'case_id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function root_cause_analysis_request()
    {
        return $this->hasOne(RootCauseAnalysis::class, 'case_id');
    }
    public function link_case_with_form()
    {
        return $this->belongsTo(Record::class, 'last_linked_incident_id');
    }
    public function case_contacts()
    {
        return $this->hasMany(CaseContact::class, 'case_id');
    }
    public function case_request_informations()
    {
        return $this->hasMany(CaseRequestInformation::class, 'case_id');
    }
    public function share_cases()
    {
        return $this->hasMany(ShareCase::class, 'case_id');
    }

    public function getShareCaseExtensionsAttribute()
    {
        $value = 0;
        foreach ($this->share_cases as $share_case) {
            if ($share_case->extension)
                $value = $value + count($share_case->extension);
        }
        return $value;
    }
    public function case_handlers()
    {
        return $this->hasMany(CaseHandlerUser::class, 'case_id');
    }
    public function case_interested_parties()
    {
        return $this->hasMany(CaseInterestedParty::class, 'case_id');
    }
    public function getCanShareCaseResponsibilityAttribute()
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $head_office_user = $head_office->users()->where('user_id', $user->id)->first();
        $case_handler_user = $this->case_handlers()->where('head_office_user_id', $head_office_user->id)->first();

        if ($case_handler_user)
            return $case_handler_user;
        return false;
    }
    function getCaseHandlerIdsAttribute()
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $head_office_user = $head_office->users()->where('user_id', $user->id)->first();
        $ids = [];
        foreach ($this->case_handlers as $handler) {
            $ids[] = $handler->head_office_user_id;
        }
        return $ids;
    }
    function getCasePartyIdsAttribute()
    {
        $ids = [];
        foreach ($this->case_interested_parties as $case_interested_party) {
            $ids[] = $case_interested_party->id;
        }
        return $ids;
    }

    function getReporter()
    {
        return User::find($this->reported_by_id);
    }

    public function linkedCasesFirst()
    {
        return $this->hasMany(linked_cases::class, 'case_id_1');
    }

    public function linkedCasesSecond()
    {
        return $this->hasMany(linked_cases::class, 'case_id_2');
    }

    // To get all linked cases
    public function allLinkedCases()
    {
        return $this->linkedCasesFirst->merge($this->linkedCasesSecond);
    }

    public function getExternal()
    {
        return User::where('email', 'external@qitech.com')->first();
    }

    public function linked_contacts()
    {
        return $this->hasMany(contact_to_case::class, 'case_id');
    }

    public function userCaseRestriction(){
        return $this->hasMany(user_case_restrictions::class, 'case_id');
    }

    public function getAllInvolvedUsers()
    {
        // Collect unique users from case handlers
        $caseHandlerUsers = collect($this->case_handlers())
            ->pluck('case_head_office_user')
            ->filter() // Remove null values
            ->unique();
    
        // Collect unique users from stages -> tasks -> assigned
        $stageAssignedUsers = collect($this->stages)
            ->flatMap(fn($stage) => $stage->tasks)
            ->flatMap(fn($task) => $task->assigned)
            ->pluck('head_office_user')
            ->filter() // Remove null values
            ->unique();
    
        // Collect users from interested parties
        $casePartiesUsers = collect($this->case_interested_parties())
            ->pluck('case_head_office_user')
            ->filter() // Remove null values
            ->unique();
    
        // Merge all collections and ensure uniqueness
        $uniqueHeadOfficeUsers = $caseHandlerUsers
            ->merge($stageAssignedUsers)
            ->merge($casePartiesUsers)
            ->unique()
            ->values(); // Reindex to avoid gaps in keys
    
        return $uniqueHeadOfficeUsers->all(); // Return as an array
    }
    



    public function getUsersWithAccess()
    {
        // Get the current case
        $case = $this;
        $caseHandlers = $case->case_handlers;

        // Get all users related to the case via the head office
        $users = $case->case_head_office->users;
        // Filter users based on their settings
        return $users->filter(function ($user) use ($case) {
            // Decode the JSON settings for the user
            $user_can_view = isset($user->user_can_view) ? json_decode($user->user_can_view, true) : null;
            $locations = isset($user->certain_locations) ? json_decode($user->certain_locations, true) : [];
            $assigned_locations = isset($user->assigned_locations) ? json_decode($user->assigned_locations, true) : [];

            $hasAccess = false;
            if (isset($user_can_view['1'])) {
                $hasAccess = true;
            }

            if (isset($user_can_view['2']) && $user->id === $case->reported_by_id) {
                $hasAccess = true;
            }

            if (isset($user_can_view['3'])) {
                $hasAccess = $case->link_case_with_form->whereIn('form_id', $user_can_view['3'])
                    ->exists();
            }
            if (isset($user_can_view['3']) && isset($locations)) {
                $hasAccess = $case->location
                    ->whereIn('id', $locations)
                    ->exists();
            }

            // Check if the user has access to assigned locations
            if (isset($user_can_view['5'])) {
                $hasAccess = $case->link_case_with_form->whereIn('form_id', $user_can_view['3'])
                    ->whereIn('location_id', $assigned_locations)
                    ->isNotEmpty();
            }

            // Check if the user can view cases reported by specific users
            if (isset($user_can_view['4']) && in_array($case->reported_by_id, $user_can_view['4'])) {
                $hasAccess = true;
            }

            $is_case_handler = $case->case_handlers->where('head_office_user_id',$user->id)->first();
            if($is_case_handler){
                $hasAccess = true;
            }
            $caseRestriction = user_case_restrictions::where('case_id', $case->id)
            ->where('ho_user_id', $user->id)
            ->first();

            if ($caseRestriction) {
                $hasAccess = false;
            }

            return $hasAccess;
        });
    }
}
