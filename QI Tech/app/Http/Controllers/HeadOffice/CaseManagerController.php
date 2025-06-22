<?php

namespace App\Http\Controllers\HeadOffice;

use App\Helpers\Helper;
use App\Models\ActivityLog;
use App\Models\case_feedback;
use App\Models\case_transfer_links;
use App\Models\CaseHandlerUser;
use App\Models\CaseInterestedParty;
use App\Models\deadlineCaseTask;
use App\Models\DefaultDocument;
use App\Models\DefaultRequestInformation;
use App\Models\Document;
use App\Models\FishBoneRootCauseAnalysisAnswer;
use App\Models\FiveWhysRootCauseAnalysisAnswer;
use App\Models\Forms\LfpseSubmission;
use App\Models\HeadOffice;
use App\Models\HeadOfficeLocation;
use App\Models\Headoffices\CaseManager\Comment;
use App\Models\Headoffices\CaseManager\CommentDocument;
use App\Models\Headoffices\CaseManager\CommentView;
use App\Models\Headoffices\CaseManager\HeadOfficeCase;
use App\Http\Controllers\Controller;
use App\Mail\FormEmail;
use App\Models\Address;
use App\Models\CaseContact;
use App\Models\CaseManagerCaseDocument;
use App\Models\CaseManagerCaseDocumentDocument;
use App\Models\CaseRequestInformation;
use App\Models\CaseRequestInformationQuestion;
use App\Models\CaseRequestInformationSavedQuestion;
use App\Models\CaseStageTask;
use App\Models\CaseStageTaskAssign;
use App\Models\CaseStageTaskDocument;
use App\Models\Contact;
use App\Models\ContactAddress;
use App\Models\ContactConnection;
use App\Models\DataRadact;
use App\Models\FishBoneRootCauseAnalysis;
use App\Models\FiveWhysRootCauseAnalysis;
use App\Models\Forms\Record;
use App\Models\Forms\RecordData;
use App\Models\Headoffices\CaseManager\Task;
use App\Models\Headoffices\CaseManager\TaskAssign;
use App\Models\Headoffices\CaseManager\TaskDocument;
use App\Models\HeadOfficeUser;
use App\Models\lfpse_delete;
use App\Models\lfpse_errors;
use App\Models\Link;
use App\Models\link_access_logs;
use App\Models\linked_cases;
use App\Models\LinkLog;
use App\Models\Location;
use App\Models\PatientContact;
use App\Models\Position;
use App\Models\PrescriberContact;
use App\Models\RecordDataEditedHistory;
use App\Models\RootCauseAnalysis;
use App\Models\RootCauseAnalysisRequest;
use App\Models\share_case_quick_description;
use App\Models\ShareCase;
use App\Models\ShareCaseCommunication;
use App\Models\ShareCaseDataRadact;
use App\Models\ShareCaseDocument;
use App\Models\ShareCaseLog;
use App\Models\SystemLink;
use App\Models\task_deadline_records;
use App\Models\User;
use App\Models\user_case_restrictions;
use DOMDocument;
use DOMXPath;
use Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use CrestApps\CodeGenerator\Support\Str;

class CaseManagerController extends Controller
{
    public function __construct()
    {
        View::share('hide_sidebar', true);
        View::share('case_manager', true);
    }
    public $perPage = 25;
    public function index(Request $request)
{
    $headOffice = Auth::guard('web')->user()->selected_head_office;
    $user = Auth::guard('web')->user();
    $headOfficeUser = Auth::guard('web')->user()->getHeadOfficeUser($headOffice->id);

    $query = HeadOfficeCase::where('head_office_id', $headOffice->id)
    ->where('isArchived', 0)
    ->whereDoesntHave('userCaseRestriction', function ($q) use ($headOfficeUser) {
        $q->where('ho_user_id', $headOfficeUser->id);
    });


    $status = $request->query('status', 'open'); 
    $search = $request->query('search');

    $settings = $headOfficeUser->user_incident_settings->first();
    if(!isset($headOfficeUser->user_can_view)){
        $headOfficeUser->user_can_view = '{"1":[1]}';
        $headOfficeUser->save();
    }

    if ($settings) {
        $user_can_view = isset($headOfficeUser->user_can_view) ? json_decode($headOfficeUser->user_can_view, true) : null;
        $locations = isset($headOfficeUser->certain_locations) ? json_decode($headOfficeUser->certain_locations, true) : [];
        $assigned_locations = isset($headOfficeUser->assigned_locations) ? json_decode($headOfficeUser->assigned_locations, true) : [];

        $query->where(function ($q) use ($user_can_view, $user, $locations, $assigned_locations) {
            if (isset($user_can_view['1'])) {
                $q->orWhere('id', '>', 0);
            }

            if (isset($user_can_view['2'])) {
                $q->orWhere('reported_by_id', $user->id);
            }

            if (isset($user_can_view['3'])) {
                // User can view cases in specific locations with specific forms
                $q->orWhereHas('link_case_with_form', function ($subQuery) use ($user_can_view, $locations) {
                    $subQuery->whereIn('form_id', $user_can_view['3'])
                        ->whereIn('location_id', $locations);
                });
            }

            if (isset($user_can_view['5'])) {
                // User can view assigned locations with specific forms
                $q->orWhereHas('link_case_with_form', function ($subQuery) use ($user_can_view, $assigned_locations) {
                    $subQuery->whereIn('form_id', $user_can_view['3'])
                        ->whereIn('location_id', $assigned_locations);
                });
            }

            if (isset($user_can_view['4'])) {
                // User can view cases reported by specific users
                $q->orWhereIn('reported_by_id', $user_can_view['4']);
            }
            
        });
    }


    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->orWhere('id', 'LIKE', '%' . $search . '%')
                ->orWhere('status', 'LIKE', '%' . $search . '%')
                ->orWhere('description', 'LIKE', '%' . $search . '%');
        });
    }

    $query->orderBy('prority', 'desc')->orderBy('created_at', 'desc');
    

    // Handle AJAX requests for infinite scroll
    if ($request->query('ajax') === 'true') {
        if (!$request->has('_token') && $request->_token != csrf_token()) {
            return response()->json('invalid Data!.', 304);  
        }
        $count = (int) $request->query('count', 0);  // Get the number of cases already displayed
        $page = (int) ceil($count / 10) + 1; // Calculate the page number

        $cases = $query->paginate(10, ['*'], 'page', $page); // Use 'page' for pagination
        
        if ($cases->isEmpty()) {
            return response('No more cases available.', 204);  
        }
        return view('head_office.case_manager.cases', compact('cases', 'headOffice'))->render();
    }

    // Initial Page Load (First 10 cases)
    $cases = $query->paginate(10);  // Use paginate instead of get()

    return view('head_office.case_manager.index', compact('cases', 'headOffice'));
}

    public function remove_case_access($id,$user_id){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = $headOffice->users->find($user_id);
        if(!isset($user)){
            abort('404');
        }
        $case = $headOffice->cases->find($id);
        if(!isset($case)){
            abort('404');
        }

        $restrict = user_case_restrictions::where('ho_user_id', $user->id)->where('case_id', $case->id)->first();
        if(isset($restrict)){
            abort('403','Access Restricted');
        }

        $restrict = new user_case_restrictions();
        $restrict->ho_user_id = $user->id;
        $restrict->case_id = $case->id;
        $restrict->save();


        $caseHandlerUserIds = $case->case_handlers->pluck('head_office_user_id')->unique();

        // Get all restricted user IDs for the given case
        $restrictedUserIds = user_case_restrictions::where('case_id', $case->id)
            ->whereIn('ho_user_id', $caseHandlerUserIds) // Filter only for case handlers
            ->pluck('ho_user_id')
            ->unique();

        // Check if all case handler user IDs are present in the restricted user IDs
        $allUsersRestricted = $caseHandlerUserIds->diff($restrictedUserIds)->isEmpty();

        if($allUsersRestricted){
            $headOfficeUserIds = $headOffice->users->pluck('id')->toArray();
            $restrictedUserIds = user_case_restrictions::where('case_id', $case->id)
            ->pluck('ho_user_id')
            ->toArray();
            $eligibleUserIds = array_diff($headOfficeUserIds, $restrictedUserIds);
            if (!empty($eligibleUserIds)) {
                // Randomly pick a user from eligible users
                $newUserId = collect($eligibleUserIds)->random();
        
                // Ensure the user is not already a case handler for this case
                $isAlreadyHandler = $case->case_handlers()
                    ->withTrashed() // Include soft-deleted records in the query
                    ->where('head_office_user_id', $newUserId)
                    ->exists();

                if (!$isAlreadyHandler) {
                    // Create a new case handler
                    $new_case_handler = new CaseHandlerUser();
                    $new_case_handler->head_office_user_id = $newUserId;
                    $new_case_handler->case_id = $case->id;
                    $new_case_handler->save();
                } else {
                    // If the handler exists but is soft-deleted, restore it
                    $softDeletedHandler = $case->case_handlers()
                        ->onlyTrashed() // Fetch only soft-deleted entries
                        ->where('head_office_user_id', $newUserId)
                        ->first();

                    if ($softDeletedHandler) {
                        $softDeletedHandler->restore();
                    }
                }

            }
        }
        
        return back()->with('success','Access removed successfully!');

    }

    public function overview(Request $request)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $query = HeadOfficeCase::query();
        $query->where('head_office_id', $headOffice->id);
        $status = 'open';
        if ($request->query('status')) {
            $status = $request->query('status');
        }
        if ($request->query('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->orWhere('id', 'LIKE', '%' . $search . '%');
                $q->orWhere('status', 'LIKE', '%' . $search . '%');
                $q->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }
        $query->where('status', $status);
        $query->orderBy('created_at', 'desc');
        $cases = $query->paginate($this->perPage);

        // Actual requirements. Then we will apply queries. above code needs to be cleared !
        $ho_users = $headOffice->users;
        View::share('hide_top_header_bar', true);
        return view('head_office.case_manager.overview', compact('headOffice', 'ho_users'));
    }
    public function case_archives(Request $request)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $headOfficeUser = Auth::guard('web')
            ->user()
            ->getHeadOfficeUser($headOffice->id);
        $query = HeadOfficeCase::query();
        $query->where('head_office_id', $headOffice->id);
        $query->where('isArchived', '1');
        $status = 'open';
        if ($request->query('status')) {
            $status = $request->query('status');
        }
        if ($request->query('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->orWhere('id', 'LIKE', '%' . $search . '%');
                $q->orWhere('status', 'LIKE', '%' . $search . '%');
                $q->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }
        // $query->where('status',$status); // it allows to appear both closed or open cases in case manager
        $query->orderBy('created_at', 'desc');
        if ($request->query('ajax')) {
            $counter = (int) $request->query('count');
            $query = $query->get();
            $cases = $query->slice($counter, 10)->values();
            $realCount = count($cases);
            if ($realCount == 0) {
                return 'exit';
            }
            return view('head_office.case_manager.cases', compact('cases', 'headOffice'));
        }
        $cases = $query->take(10)->get();
        $settings = $headOfficeUser->user_incident_settings->first();
        if (isset($settings)) {
            $user_can_view = isset($headOfficeUser->user_can_view) ? json_decode($headOfficeUser->user_can_view, true) : null;

            $filtered_results = [];
            $case_ids = [];

            foreach ($cases as $case) {
                $include_case = false;

                if (isset($user_can_view['1'])) {
                    $include_case = true;
                }

                if (isset($user_can_view['2']) && $case->reported_by_id == $user->id) {
                    $include_case = true;
                }

                if (isset($user_can_view['3']) && in_array($case->link_case_with_form->form_id, $user_can_view['3'])) {
                    $include_case = true;
                }

                if (isset($user_can_view['4']) && in_array($case->reported_by_id, $user_can_view['4'])) {
                    $include_case = true;
                }

                if ($include_case && !in_array($case->id, $case_ids)) {
                    $filtered_results[] = $case;
                    $case_ids[] = $case->id;
                }
            }

            $cases = $filtered_results;
        }
        //View::share('hide_top_header_bar',true);

        return view('head_office.case_manager.index', compact('cases', 'headOffice'));
    }
    public function case_updates(Request $request)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $ho_users = $headOffice->users;
        View::share('hide_top_header_bar', true);
        return view('head_office.case_manager.case_updates', compact('ho_users'));
    }
    public function case_record(Request $request)
    {
        return 'Cases are automatically added when a location reports';
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $locations = HeadOfficeLocation::where('head_office_id', $headOffice->id)->get();
        $users = HeadOfficeUser::where('head_office_id', $headOffice->id)->get();
        return view('head_office.case_manager.case_record', compact('locations', 'users'));
    }
    public function case_record_save(Request $request)
    {
        return 'Cases are automatically added when a location reports';
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $validated = $request->validate([
            'description' => 'required|min:1',
            // 'location_id' => 'required|min:1',
            // 'user_id' => 'required|min:1',
        ]);
        $case = HeadOfficeCase::where('id', $request->id)
            ->where('head_office_id', $headOffice->id)
            ->first();
        if (!$case) {
            $case = new HeadOfficeCase();
            # For now default to open.
            $case->status = 'open';
        }
        $case->head_office_id = $headOffice->id;
        $case->description = $request->description;
        $case->case_closed = 0;
        $case->last_accessed = Carbon::now();
        $case->last_action = Carbon::now();
        $case->save();
        return redirect()->route('case_manager.index')->with('success_message', 'Case saved successfully.');
    }
    public function view(Request $request, $id)
    {
        $draftComment = null;
        $comment_id = $request->query('comment_id') ?? null;
        if (isset($comment_id)) {
            $draftComment = Comment::find($comment_id);
        }
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $headOfficeUser = Auth::guard('web')->user()->getHeadOfficeUser();
        $permissions = $headOfficeUser->get_permissions();
        $case = HeadOfficeCase::where('id', $id)
            ->where('head_office_id', $headOffice->id)
            ->first();
        if(!isset($case)){
            return redirect()->back()->with('error', 'Case not found!');
        }
        if (!perm('view_case', $case)) {
            abort(403, 'You have no access to this case');
        }

        $query = Comment::query();
        $query->where('case_id', $case->id);
        if ($request->query('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->orWhere('comment', 'LIKE', '%' . $search . '%');
            });
        } else {
            # Only select top level comments when search is off.
            $query->where('parent_id', null);
        }
        $comments = $query->where('isDraft', false)->orderBy('created_at', 'desc')->get();
        $tasks = Task::where('case_id', $case->id)
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        $stages = $case->stages;
        $my_tasks = $case->my_tasks()->orderBy('status', 'asc')->orderBy('created_at', 'desc')->get();
        $case->timestamps = false; // Disable timestamps
        $case->last_accessed = Carbon::now();
        $case->save();
        $case->timestamps = true;
        $head_office_users = HeadOfficeUser::where('head_office_id', $headOffice->id)->get();
        $form = $case->link_case_with_form->form;
        $form_json = $form?->form_json ? json_decode($form->form_json, true) : null;
        $incident_date_items = [];

        if (!empty($form_json['pages'])) {
            foreach ($form_json['pages'] as $page) {
                if (!empty($page['items'])) {
                    foreach ($page['items'] as $item) {
                        if (isset($item['input']['incident_date']) && $item['input']['incident_date'] === true) {
                            $incident_date_items[] = $item;
                        }
                    }
                }
            }
        }
        if(!isset($case->link_case_with_form->form))
        {
            return redirect()->back()->with('error', 'Form not found for this case!');
        }
        return view('head_office.case_manager.notes.view_case', compact('case', 'comments', 'head_office_users', 'tasks', 'my_tasks', 'stages', 'draftComment','incident_date_items','permissions'));
    }

    public function save_comment(Request $request)
    {
        
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        if ($request->close_comment) {
            $validated = $request->validate(
                [
                    'close_comment' => 'required|min:1',
                    'case_id' => 'required|min:1',
                ],
                [
                    'close_comment.required' => 'Please add a comment.',
                ],
            );
        } else {
            $validator = Validator::make($request->all(), [
                'case_id' => 'required|min:1',
                'comment' => 'nullable|string',
                'reminder_links' => 'nullable|array',
                'documents' => 'nullable|array',
                'audios' => 'nullable|array',
            ], [
                'case_id.required' => 'The case ID field is required.',
            ]);
            
            $validator->after(function ($validator) use ($request) {
                // Check if the 'comment' is non-empty
                $hasComment = !empty($request->comment);
            
                $hasReminderLinks = is_array($request->reminder_links) && array_filter($request->reminder_links, function($value) {
                    return !is_null($value); // Return true if at least one value is not null
                });
            
                $hasDocuments = is_array($request->documents) && array_filter($request->documents, function($value) {
                    return !is_null($value); // Return true if at least one value is not null
                });
            
                $hasAudios = is_array($request->audios) && array_filter($request->audios, function($value) {
                    return !is_null($value); // Return true if at least one value is not null
                });
            
                if (!$hasComment && !$hasReminderLinks && !$hasDocuments && !$hasAudios) {
                    $validator->errors()->add('at_least_one', 'At least one of comment, reminder links, documents, or audios must be present.');
                }
            });

            if ($validator->fails()) {
                return redirect()->back()->with('error','Empty comment not allowed!');
            }
        }

        $case = HeadOfficeCase::where('id', $request->case_id)
            ->where('head_office_id', $headOffice->id)
            ->first();
        if (!$case) {
            abort(403, 'Data access denied');
        }

        if ($case->case_closed) {
            return redirect()->back()->with('error', 'Case is already closed');
        }

        $comment = Comment::where('user_id', $user->id)
            ->where('id', $request->id)
            ->first();
        if ($comment && $comment->case_id != $case->id) {
            abort(403, 'Data access denied');
        }
        $editing = true;
        if (!$comment) {
            $comment = new Comment();
            $editing = false;
        }
        $comment->case_id = $case->id;
        $comment->user_id = $user->id;
        $comment->comment = '';
        $comment->save();
        $this->processCommentLinks($request,$comment);

        if (!$editing) {
            $comment->parent_id = $request->parent_id ? $request->parent_id : null;
        }

        if ($request->close_comment) {
            $string = Helper::check_link(null, null, $request->close_comment, $case->id, $comment->id);
            $comment->comment = $string ? $string : $request->close_comment;
        } else {
            $string = Helper::check_link($request->link_title, $request->link_comment, $request->comment, $case->id, $comment->id);
            $comment->comment = $string ? $string : $request->comment ?? ' ';
        }

        $activity_log = new ActivityLog();
        $activity_log->type = 'Comment';
        $activity_log->user_id = $user->id;
        $activity_log->head_office_id = $headOffice->id;
        $activity_log->action = 'Comment added by ' . $user->first_name . ' ' . $user->surname;
        $activity_log->comment_id = $comment->id;
        $activity_log->save();

        $comment->save();

        $case->last_action = Carbon::now();
        if ($request->close_case) {
            $case->status = 'closed';
            $case->case_closed = 1;
            $record = $case->linked_location_incident->incident;
            $record->case_status = 1;
            $record->save();
        }
        $case->save();
        if ($request->reminder_links) {
            foreach ($request->reminder_links as $link) {
                if ($link) {
                    $data = json_decode($link);
                    $link = new Link();
                    $link->title = $data->title;
                    $link->link = $data->url;
                    $link->description = $data->comment;

                    $removel_date = Carbon::now()->add($data->duration . ' ' . $data->unit);
                    //$removel_date = $request->days ? Carbon::now()->addMonths($request->duration) : Carbon::now()->addDays($request->duration) ;
                    $link->date_to_be_removed = $removel_date;
                    $link->user_id = Auth::guard('web')->user()->id;
                    $link->head_office_case_id = $case->id;
                    $link->save();

                    $link_log = new LinkLog();
                    $link_log->link_id = $link->id;
                    $link_log->link = $data->url;
                    $link_log->title = $data->title;
                    $link_log->date_to_be_removed = $removel_date;

                    $link_log->user_id = Auth::guard('web')->user()->id;
                    $link_log->save();
                }
            }
        }

        $documents = (array) $request->documents;
        CommentDocument::where('comment_id', $comment->id)->delete();
        foreach ($documents as $value) {
            $doc = new CommentDocument();
            $doc->comment_id = $comment->id;
            $value = Document::where('unique_id', $value)->first();
            if (!$value) {
                continue;
            }
            $doc->document_id = $value->id;
            $doc->type = $value->isImage() ? 'image' : 'document';
            $doc->save();
        }

        if (isset($request->audios)) {
            CommentDocument::where('comment_id', $comment->id)->delete();
            foreach ($request->audios as $audio) {
                $doc = new CommentDocument();
                $doc->comment_id = $comment->id;
                $audio = Document::where('unique_id', $audio)->first();
                if (!$audio) {
                    continue;
                }
                $doc->document_id = $audio->id;

                $doc->type = 'audio';
                $doc->save();
            }
        }

        return redirect()
            ->route('case_manager.view', $case->id)
            ->with('success_message', 'Comment saved successfully.');
    }

    public function unseen_comment($id){
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:case_manager_case_comment_views,comment_id',
        ]);
        if($validator->fails()){
            return abort('404');
        }
        $user = Auth::guard('web')->user();
        $ho_u = $user->getHeadOfficeUser();
        $comment = CommentView::where('comment_id',$id)->where('head_office_user_id',$ho_u->id)->first();
        if(isset($comment)){
            $comment->is_seen = false;
            $comment->save();
            return back()->with('success','Message marked as unseen!');
        }

        return back()->with('error','not found');
    }
    public function seen_comment($comment_id){
        $validator = Validator::make(['id' => $comment_id], [
            'id' => 'required|exists:case_manager_case_comment_views,comment_id',
        ]);
        if($validator->fails()){
            return abort('404');
        }
        $user = Auth::guard('web')->user();
        $ho_u = $user->getHeadOfficeUser();
        $comment = CommentView::where('comment_id',$comment_id)->where('head_office_user_id',$ho_u->id)->first();
        if(isset($comment)){
            $comment->is_seen = true;
            $comment->save();
            return back()->with('success','Message marked as seen!');
        }

        return back()->with('error','not found');
    }

    private function processCommentLinks(Request $request,$comment)
{
    // Initialize an empty array to store link data
    $linksToStore = [];
    
    // Iterate over all request parameters
    foreach ($request->all() as $key => $value) {
        // Check if the parameter name starts with 'commentBox-'
        if (strpos($key, 'commentBox-') === 0) {
            // Process the value to extract and store links
            if(isset($value)){
                $linksToStore = array_merge($linksToStore, $this->extractLinks($value));
            }
        }
    }


    // Store the links in the database
    foreach ($linksToStore as $linkData) {
        $systemLink = SystemLink::firstOrNew(['link' => $linkData['url']]);
        $systemLink->link = $linkData['url'];
        $systemLink->random = bin2hex(random_bytes(20));
        $systemLink->case_id = $request->case_id;
        $systemLink->comment_id = $comment->id;
        $systemLink->title = $linkData['title'];
        $systemLink->description = $linkData['description'];
        $systemLink->save();
    }
}

private function extractLinks($html)
{
    $dom = new DOMDocument;
@$dom->loadHTML($html);

// Use XPath to target only the <a> elements
$xpath = new DOMXPath($dom);
$anchors = $xpath->query('//a');

    $links = [];
    // $anchors = $doc->getElementsByTagName('a');
    foreach ($anchors as $anchor) {
        // dd($html);
        $href = $anchor->getAttribute('href');
        $title = $anchor->getAttribute('title');
        $linkText = trim($anchor->nodeValue);
        $title = !empty($title) ? $title : $linkText;
        // Add the link details to the array
        $links[] = [
            'url' => $href,
            'title' => $title,
            'description' => '' // Add description logic if needed
        ];
    }

    return $links;
}

    public function save_comment_draft(Request $request)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $draftComment = new Comment();
        $draftComment->case_id = $request->case_id;
        $draftComment->user_id = $user->id;
        $draftComment->comment = $request->comment;
        $draftComment->isDraft = true;
        $draftComment->save();
        return response(['result' => $draftComment, 'time' => $draftComment->created_at->format('D d/m/Y g:ia')], 200);
    }
    public function view_comment($id)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $comment = Comment::where('user_id', $user->id)
            ->where('id', $id)
            ->first();
        if (!$comment) {
            abort(404);
        }

        return view('head_office.case_manager.comment_view', compact('comment'));
    }
    public function delete_comment(Request $request, $id)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $comment = Comment::where('user_id', $user->id)
            ->where('id', $id)
            ->first();
        if (!$comment) {
            abort(404);
        }
        if ($comment->case->head_office_id != $headOffice->id) {
            abort(403, 'Data access denied');
        }
        $case_id = $comment->case->id;
        $comment->delete();
        return redirect()->route('case_manager.view', $case_id)->with('success_message', 'Comment deleted successfully.');
    }
    public function delete_comment_multi(Request $request)
    {
        if ($request->sessionIds) {
            $headOffice = Auth::guard('web')->user()->selected_head_office;
            $user = Auth::guard('web')->user();
            $ids = explode(',', $request->sessionIds[0]);
            foreach ($ids as $id) {
                $comment = Comment::where('user_id', $user->id)
                    ->where('id', $id)
                    ->first();
                if (!$comment) {
                    continue;
                }
                if ($comment->case->head_office_id != $headOffice->id) {
                    continue;
                }
                $case_id = $comment->case->id;
                $comment->delete();
            }
        }

        return redirect()->back()->with('success_message', 'Comment deleted successfully.');
    }

    public function delete_tracking_link($id)
    {
        $link = SystemLink::find($id);
        if (!$link) {
            abort(404);
        }
        $link->delete();
        return redirect()->back()->with('success', 'Link removed successfully!');
    }
    public function active_tracking_link($id)
    {
        $link = SystemLink::find($id);
        if (!$link) {
            abort(404);
        }
        $link->is_active = !$link->is_active;
        $link->save();
        return redirect()->back()->with('success', 'Link status changed Successfully!');
    }
    public function update_tracking_link(Request $request)
    {
        $link = SystemLink::find($request->id);
        if (!$link) {
            abort(404);
        }
        $link->link = $request->value;
        $link->save();
        return response()->json(['message' => 'link updated Successfully'], 200);
    }
    public function users_list(Request $request)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $q = $request->query('q');
        $head_office_users = HeadOfficeUser::where('head_office_id', $headOffice->id)->get('user_id');
        if (!count($head_office_users)) {
            return response()->json([]);
        }
        $ids = [];
        foreach ($head_office_users as $u) {
            $ids[] = $u->user_id;
        }
        $users = User::whereIn('id', $ids);
        if (!empty($q)) {
            $users->where(function ($query) use ($q) {
                $query->orWhere('first_name', 'LIKE', '%' . $q . '%');
                $query->orWhere('surname', 'LIKE', '%' . $q . '%');
            });
        }
        $users = $users->get();
        if (!count($users)) {
            return response()->json([]);
        }
        $to_return = [];
        foreach ($users as $u) {
            $to_return[] = [
                'id' => $u->id,
                'key' => $u->name,
                'value' => $u->name,
                'template' => '<a href="#"><input type="hidden" name="users[]" value="' . $u->id . '">@' . $u->name . '</a>',
            ];
        }
        return response()->json($to_return);
    }
    public function save_task(Request $request)
{
    $headOffice = Auth::guard('web')->user()->selected_head_office;
    $user = Auth::guard('web')->user();
    
    $validated = $request->validate([
        'title' => 'required|min:1',
        'assigned' => 'required|array|min:1',
        'assigned.*' => 'required|numeric|min:1',
    ]);

    // $case = HeadOfficeCase::where('id',$request->case_id)->where('head_office_id',$headOffice->id)->first();
    $case = $headOffice->cases()->findOrFail($request->case_id);
        // if(!$case){
        //abort(403,'Data access denied');
        // }
    // Find the stage of the case
    $stage = $case->stages()->findOrFail($request->stage_id);
    // Attempt to find the task by its ID, if provided
    $task = $stage->tasks()->find($request->id);
    $editing = true;

    if (!$task) {
        $task = new CaseStageTask(); // Create a new task if none is found
        $editing = false;
    }

    // Update task attributes
    $task->case_stage_id = $request->stage_id;
    $task->user_id = $user->id;
    $task->title = $request->title;
    $task->description = $request->description;
    $task->mandatory = $request->mandatory == 'on' ? true : false;
    // Log activity
    $activity_log = new ActivityLog();
    $activity_log->type = 'Assign Task';
    $activity_log->user_id = $user->id;
    $activity_log->head_office_id = $headOffice->id;
    $activity_log->action = 'Task ' . $task->title . ' assigned to ' . $user->name;
    $activity_log->save();
    // Set task status
    if (!$editing) {
        $task->status = 'in_progress'; // Set status if creating a new task
    }

    $task->save(); // Save the task
    $case->last_action = Carbon::now(); // Update case last action time
    $case->save(); // Save the case
    if (!$request->is_dead_line) {
        $comment = new Comment();
        $comment->user_id = Auth::guard('web')->user()->id;
        $comment->case_id = $case->id;
        $comment->type = 'Add new Task';
        $comment->comment = 'Task title is' .'"'.$task->title.'"' ;
        $comment->save();
    }
    
    if ($editing && $request->is_dead_line) {
        $comment = new Comment();
        $comment->user_id = Auth::guard('web')->user()->id;
        $comment->case_id = $case->id;
        $comment->type = 'set new deadline for Task';
        $comment->comment = $user->name . ' ' . 'set new deadline for task' . ' ' . $task->title;
        $comment->save();
    }
    // Count the tasks in progress and update session
    $tasksInProgressCount = $stage->tasks()->where('status', 'in_progress')->count();
    session(['tasksInProgressCount' => $tasksInProgressCount]); // Update session with count

    // Handle deadlines and documents...
    if($request->is_dead_line){
        $deadline = task_deadline_records::create(
            [
                'task_type' => 'deadline',
                'duration' => $request->is_dead_line ? $request->dead_line_duration : $request->over_due_duration,
                'unit' => $request->is_dead_line ? $request->dead_line_unit : $request->over_due_unit,
                'start_from' => $request->is_dead_line ? $request->dead_line_start_from : null,
                'incident_date_selected' => $request->is_dead_line && $request->dead_line_start_from === 'incident_date' ? $request->incident_date_selected : null,
                'task_started_selected' => $request->is_dead_line && $request->dead_line_start_from === 'task_started' ? $request->task_started_selected : null,
                'task_completed_selected' => $request->is_dead_line && $request->dead_line_start_from === 'task_complete' ? $request->task_completed_selected : null,
                'stage_started_selected' => $request->is_dead_line && $request->dead_line_start_from === 'stage_started' ? $request->stage_started_selected : null,
                'stage_completed_selected' => $request->is_dead_line && $request->dead_line_start_from === 'stage_complete' ? $request->stage_completed_selected : null,
                'action_option' => $request->dead_line_option ,
                'user_ids' =>  json_encode($request->dead_line_user),
                'profile_ids' =>  json_encode($request->dead_line_profile) ,
                'email_profile_type' => $request->dead_line_user_email_profile,
                'emails' =>  json_encode($request->custom_dead_line_emails) ,
                'email_template' =>  $request->dead_line_email_template 
            ]
        );
        $link = new deadlineCaseTask();
        $link->case_task_id = $task->id;
        $link->deadline_id = $deadline->id;
        $link->save();

        if(!$request->is_task_over_due){
        $comment = new Comment();
        $comment->user_id = Auth::guard('web')->user()->id;
        $comment->case_id = $case->id;
        $comment->type = 'Add new Task';
        $comment->comment = $user->name . ' Add new task' .$task->title . ' with deadline' ;
        $comment->save();
        }
    }


    
    if($request->is_task_over_due){
        $overdue = task_deadline_records::create(
            [
                'task_type' => 'overdue',
                'duration' => $request->over_due_duration,
                'unit' => $request->over_due_unit,
                'action_option' => $request->over_due_line_option,
                'user_ids' =>  json_encode($request->over_due_user) ,
                'profile_ids' =>  json_encode($request->over_due_profile) ,
                'email_profile_type' => $request->over_due_user_email_profile,
                'emails' => json_encode([$request->custom_over_due_emails]),
                'email_template' => $request->task_over_due_email_template ,
                'is_task_overdue' => true,
            ]
        );

        $link = new deadlineCaseTask();
        $link->case_task_id = $task->id;
        $link->deadline_id = $overdue->id;
        $link->save();
        $comment = new Comment();
        $comment->user_id = Auth::guard('web')->user()->id;
        $comment->case_id = $case->id;
        $comment->type = 'Set new deadline';
        $comment->comment = 'for task '. '" '. $task->title . ' "';
        $comment->save();
    }
    $documents = (array) $request->documents;
    CaseStageTaskDocument::where('case_stage_task_id', $task->id)->delete();
    foreach ($documents as $value) {
        $doc = new CaseStageTaskDocument();
        $doc->case_stage_task_id = $task->id;
        $value = Document::where('unique_id', $value)->first();
        if (!$value) {
            continue;
        }
        $doc->document_id = $value->id;
        $doc->type = $value->isImage() ? 'image' : 'document';
        $doc->save();
    }

    // Handle task assignments...
    $assigned = (array) $request->assigned;
    CaseStageTaskAssign::where('task_id', $task->id)->delete();
    foreach ($assigned as $value) {
        $assign = new CaseStageTaskAssign();
        $assign->task_id = $task->id;
        $value = HeadOfficeUser::where('id', $value)
            ->where('head_office_id', $headOffice->id)
            ->first();
        if (!$value) {
            continue;
        }
        $assign->head_office_user_id = $value->id;
        $assign->save();
    }

    // Update current stage
    $c = $case->stages();
    $c->update(['is_current_stage' => 0]);
    foreach ($c->get() as $cc) {
        if ($cc->percentComplete() != 100 && $cc->tasks()->count() > 0) {
            $cc->is_current_stage = 1;
            $cc->save();
            break;
        }
    }

    return redirect()
        ->route('case_manager.view', [$case->id, '#cm_case_tasks'])
        ->with('success_message', 'Task saved successfully.');
}

    public function assign_task_user(Request $request){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        if(!isset($request->selected_task_id) || !isset($request->assigned)){
            return back()->with('error','bad request');
        }

        $task = CaseStageTaskAssign::where('task_id', $request->selected_task_id)->first();
        if(!isset($task)){
            return back()->with('error','Not found!');
        }
        $task_case = $task->task->caseStage->case; 
        if($task->head_office_user->head_office_id != $headOffice->id){
            return back()->with('error','Data access denied');
        }
        foreach($request->assigned as $ho_user_id){
            $assigned_user = CaseStageTaskAssign::where('task_id', $request->selected_task_id)->where('head_office_user_id', $ho_user_id)->first();
            $ho_user = $headOffice->users()->where('id', $ho_user_id)->first();
            if(!isset($assigned_user) || !isset($ho_user)){
                $new_assigned = new CaseStageTaskAssign();
                $new_assigned->task_id = $request->selected_task_id;
                $new_assigned->head_office_user_id = $ho_user_id;
                $new_assigned->save();
            }
        }
        $assignedUserId = $request->assigned[0];
        $assignedUser = HeadOfficeUser::find($assignedUserId);
        $assignedUserName = User::where('id', $assignedUser->user_id)->value('first_name') ?? null;

        $comment = new Comment();
        $comment->user_id = Auth::guard('web')->user()->id;
        $comment->case_id = $task_case->id;
        $comment->type = 'Task assigned';
        $comment->comment = $user->name . ' ' . 'Assign' . $task->task->title . ' to' . $assignedUserName;
        $comment->save();
        return back()->with('success','Task assigned successfully');
    }


    public function delete_task(Request $request, $id)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $task = CaseStageTask::where('user_id', $user->id)
            ->where('id', $id)
            ->first();
        if (!$task) {
            abort(404);
        }
        if ($task->caseStage->case->case_head_office->id != $headOffice->id) {
            abort(403, 'Data access denied');
        }
        $case = $task->caseStage->case;
        $case->last_action = Carbon::now();
        $case->save();
        $comment = new Comment();
        $comment->user_id = Auth::guard('web')->user()->id;
        $comment->case_id = $case->id;
        $comment->type = 'Delete a Task';
        $comment->comment = $task->title . ' deleted by ' . $user->name;
        $comment->save();

        $case_id = $task->caseStage->case->id;
        $task->delete();
        return redirect()->route('case_manager.view', $case_id)->with('success_message', 'Task deleted successfully.');
    }
/*************  ✨ Codeium Command ⭐  *************/
    /**
     * Changes the status of a task.
     *
     * @param Request $request
     * @param int $stage_id
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
/******  ec12cfe4-dfe9-429e-89ba-0bab6725b503  *******/    public function change_status(Request $request, $stage_id, $id)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $case = $headOffice->cases()->findOrFail($request->case_id);
        $stage = $case->stages()->findOrFail($stage_id);
        $task = $stage->tasks()->findOrFail($id);
        // if(!$task){
        //     abort(404);
        // }
        // if($task->case->head_office_id != $headOffice->id){
        //     abort(403,'Data access denied');
        // }
        //$case = $task->case;
        $case->last_action = Carbon::now();
        $case->save();

        $status = 'completed';
        if ($request->query('not_applicable')) {
            $status = 'completed_not_applicable';
        } elseif ($request->query('re_open')) {
            $status = 'in_progress';
        }
        $task->status = $status;

        $activity_log = new ActivityLog();
        $activity_log->type = 'Task';
        $activity_log->user_id = $user->id;
        $activity_log->head_office_id = $headOffice->id;
        if ($task->status == 'completed') {
            $activity_log->action = $task->title . ' ' . '- Completed' . ' - Case ' . $case->id;
            $activity_log->save();
        } elseif ($task->status == 'completed_not_applicable') {
            $activity_log->action = $task->title . ' ' . '- Not Applicable' . ' - Case ' . $case->id;
            $activity_log->save();
        } elseif ($request->query('re_open')) {
            $activity_log->action = $task->title . ' ' . '- Re-Opened' . ' - Case ' . $case->id;
            $activity_log->save();
        }

        $case_comment = new Comment();
        $case_comment->case_id = $case->id;
        $case_comment->type = 'Marked TAs as ' . $task->status;
        $case_comment->user_id = $user->id;
        $case_comment->comment = $activity_log->action;
        $case_comment->save();

        $task->save();
        $next_stage = $case
            ->stages()
            ->where('id', '>', $stage->id)
            ->orderBy('id')
            ->first();
        // if($stage->percentComplete() == 100)
        // {
        //     if($next_stage->percentComplete() == 100)
        //         $next_stage = $case->stages()->where('id' , '>', $next_stage->id)->orderBy('id')->first();
        $c = $case->stages();
        $c->update(['is_current_stage' => 0]);
        foreach ($c->get() as $cc) {
            if ($cc->percentComplete() != 100 && $cc->tasks()->count() > 0) {
                $cc->is_current_stage = 1;
                $cc->save();
                break;
            }
        }
        //     $stage->is_current_stage = 0;
        //     $stage->save();

        //     if($next_stage && count($next_stage->tasks) > 0)
        //     {
        //         $next_stage->is_current_stage = 1;
        //         $next_stage->save();
        //     }
        // }
        return redirect()
            ->route('case_manager.view', $case->id)
            ->with('success_message', 'Task status changed to completed.');
    }
    public function random_link($id)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $system_link = SystemLink::where('random', $id)->first();
        $user = Auth::user();
        $system_link->clicks += 1;
        $system_link->last_accessed = now();
        $system_link->last_accessed_user = $user->id;
        $system_link->save();
        $link_log = new link_access_logs();
        $link_log->link_id = $system_link->id;
        $link_log->user_id = $user->id;
        $link_log->head_office_id = $headOffice->id;
        $link_log->save();
        if ($system_link->is_active) {
            return redirect()
                ->away($system_link->link)
                ->with('target', '_blank');
        }
        return back()->with('error', 'Sorry Link expires');
    }
    public function close_cases(Request $request)
    {
        foreach ($request->ids as $id) {
            try {
                $case = HeadOfficeCase::find($id);
                $case->status = 'closed';
                if ($request->has('is_approved')) {
                    if ($request->is_approved) {
                        $case->case_closed = 1;
                    } else {
                        $case->case_closed = 0;
                    }
                } else {
                    $case->case_closed = 1;
                }
                $case->save();

                $record = $case->linked_location_incident->incident;
                $record->case_status = 1;
                $record->save();

                $case_comment = new Comment();
                $case_comment->case_id = $case->id;
                $case_comment->user_id = Auth::guard('web')->user()->id;
                $string = Helper::check_link(null, null, $request->close_comment, $case->id, $case_comment->id);

                $case_comment->comment = $string ? $string : $request->close_comment;
                $case_comment->save();

                $documents = (array) $request->documents;
                CommentDocument::where('comment_id', $case_comment->id)->delete();
                foreach ($documents as $value) {
                    $doc = new CommentDocument();
                    $doc->comment_id = $case_comment->id;
                    $value = Document::where('unique_id', $value)->first();
                    if (!$value) {
                        continue;
                    }
                    $doc->document_id = $value->id;
                    $doc->type = $value->isImage() ? 'image' : 'document';
                    $doc->save();
                }
            } catch (Exception $e) {
            }
        }
        return back()->with('success_message', 'Selected cases have been closed successfully');
    }
    public function close_case(Request $request, $case_id, $type)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $case = $headOffice->cases()->find($case_id);
        if ($type && $case->status == 'waiting') {
            return back()->with('error', 'Case is already in clouser approval state');
        }
        if ($case && $case->status != 'closed') {
            ShareCase::where('case_id', $case->id)->update(['removed_by_user' => 1]);
            if ($request->send_feedback_to_reporter) {
                $user = Auth::guard('web')->user();
                $feedback = $request->feedback;
                $case_comment = new Comment();
                $case_comment->case_id = $case->id;
                $case_comment->type = ' provided feedback to ' . $case->location->trading_name;
                $case_comment->user_id = $user->id;
                $case_comment->comment = $feedback;
                $case_comment->save();
                $location = $case->location;
                Mail::send('emails.request_information', ['case' => $case, 'heading' => 'case close feedback', 'msg' => $feedback], function ($message) use ($user, $location) {
                    $message->to($location->email);
                    $message->subject(env('APP_NAME') . ' - Fill requested information');
                });
            }
            if ($request->send_feedback_to_reporter_user) {
                $feedback = $request->feedback_user;
                $reported_user = $case->getReporter();

                if(isset($reported_user)){
                    $case_comment = new Comment();
                    $case_comment->case_id = $case->id;
                    $case_comment->type = 'provided feedback to reporter ' . $case->getReporter()->name;
                    $case_comment->user_id = $user->id;
                    $case_comment->comment = $feedback;
                    $case_comment->save();
                    Mail::send('emails.bulk_case_close_email', ['user' => $reported_user, 'cases' => null, 'headOffice' => $headOffice], function ($message) use ($reported_user) {
                        $message->to($reported_user->email);
                        $message->subject(env('APP_NAME') . ' -New feedback');
                    });
                }
            }

            if ($request->send_feedback_to_reporter_user || $request->send_feedback_to_reporter) {
                $newFeedback = new case_feedback();
                $newFeedback->head_office_id = $headOffice->id;
                $newFeedback->reported_by_user_id = $case->getReporter()->id ?? 0;
                $newFeedback->feedback_by_user_id = $user->id;
                $newFeedback->location_id = $case->location->id;
                $newFeedback->case_ids = json_encode([$case->id]);
                $newFeedback->is_feedback_user = true;
                $newFeedback->feedback_user = $feedback;
                if ($request->send_feedback_to_reporter) {
                    $newFeedback->is_feedback_location = true;
                    $newFeedback->feedback_location = $request->feedback;
                }
                $newFeedback->save();
            }

            $case_comment = new Comment();
            $case_comment->case_id = $case->id;
            $case_comment->user_id = $user->id;
            $string = Helper::check_link(null, null, $request->close_comment, $case->id, $case_comment->id);
            $case_comment->comment = $string ? $string : $request->close_comment;

            if (!$type) {
                $activity_log = new ActivityLog();
                $activity_log->type = 'Case Closed';
                $activity_log->user_id = $user->id;
                $activity_log->head_office_id = $headOffice->id;
                $activity_log->action = 'Case Closed by ' . $user->first_name . ' ' . $user->surname . ' ' . '(' . $user->email . ')';
                $activity_log->save();

                $case_comment->type = ' closed case';
                $case_comment->save();
                $case->status = 'closed';
                CaseRequestInformation::where('case_id', $case->id)->delete();
                //$case->case_closed = 1;
                $case->save();
            } else {
                $activity_log = new ActivityLog();
                $activity_log->type = 'Closure approval';
                $activity_log->user_id = $user->id;
                $activity_log->head_office_id = $headOffice->id;
                $activity_log->action = 'Moved case for final closure approval ';
                $activity_log->save();

                $case_comment->type = ' moved case for final closure approval.';
                $case_comment->save();
                $case->status = 'waiting';
                //$case->case_closed = 1;
                $case->save();
            }

            $documents = (array) $request->documents;
            CommentDocument::where('comment_id', $case_comment->id)->delete();

            foreach ($documents as $value) {
                $doc = new CommentDocument();
                $doc->comment_id = $case_comment->id;
                $value = Document::where('unique_id', $value)->first();
                if (!$value) {
                    continue;
                }
                $doc->document_id = $value->id;
                $doc->type = $value->isImage() ? 'image' : 'document';
                $doc->save();
            }
            if (!$type) {
                return back()->with('success_message', 'Case closed successfully');
            }

            return back()->with('success_message', 'Case moved to final closure approval');
        }
        return back()->with('error', 'Case not found');
    }

    public function close_case_bulk(Request $request)
    {
        // dd($request->all());

        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $same_case_locations = [];
        $same_case_reported_users = [];
        $location_feedback_ids = [];
        $user_feedback_ids = [];
        if (empty($request->case_ids) && !isset($request->case_ids[0])) {
            return back()->with('error', 'please provide valid cases!');
        }
        if (!empty($request->location_feedback_ids)) {
            $location_feedback_ids = explode(',', $request->location_feedback_ids[0]);
        }
        if (!empty($request->user_feedback_ids)) {
            $user_feedback_ids = explode(',', $request->user_feedback_ids[0]);
        }

        $case_ids = explode(',', $request->case_ids[0]);
        $main_case_ids = explode(',', $request->case_ids[0]);
        foreach ($case_ids as $case_id) {
            $case = $headOffice->cases()->find($case_id);
            if (!$case) {
                continue;
            }
            $type = $case->requires_final_approval && $case->status == 'waiting';
            if ($type && $case->status == 'waiting') {
                continue;
            }
            if ($case && $case->status != 'closed') {
                if ($request->send_feedback_to_reporter) {
                    $feedback = $request->feedback;

                    $location = $case->location;

                    if (isset($same_case_locations[$location->id])) {
                        $same_case_locations[$location->id]['cases'][] = $case;
                    } else {
                        $same_case_locations[$location->id] = [
                            'location' => $location,
                            'cases' => [$case],
                        ];
                    }

                    if (!in_array($location->id, $location_feedback_ids)) {
                        continue;
                    }
                    $case_comment = new Comment();
                    $case_comment->case_id = $case->id;
                    $case_comment->type = 'provided feedback to ' . $case->location->trading_name;
                    $case_comment->user_id = $user->id;
                    $case_comment->comment = $feedback ?? ' ';
                    $case_comment->save();

                    $newFeedback = new case_feedback();
                    $newFeedback->head_office_id = $headOffice->id;
                    $newFeedback->reported_by_user_id = HeadOfficeCase::find($case_id)->getReporter()->id ?? 0;
                    $newFeedback->feedback_by_user_id = $user->id;
                    $newFeedback->location_id = $case->location->id;
                    $newFeedback->case_ids = json_encode($case_ids);
                    $newFeedback->is_feedback_location = true;
                    $newFeedback->feedback_location = $request->feedback;
                    if ($request->send_feedback_to_reporter_user) {
                        $newFeedback->feedback_user = $feedback;
                        $newFeedback->is_feedback_location = true;
                    }
                    $newFeedback->save();
                }

                if ($request->send_feedback_to_reporter_user) {
                    $feedback = $request->feedback_user;
                    $reported_user = $case->getReporter();

                    if (isset($same_case_reported_users[$reported_user->id])) {
                        $same_case_reported_users[$reported_user->id]['cases'][] = $case;
                    } else {
                        $same_case_reported_users[$reported_user->id] = [
                            'reported_user' => $reported_user,
                            'cases' => [$case],
                        ];
                    }
                    if (!in_array($reported_user->id, $user_feedback_ids)) {
                        continue;
                    }
                    $case_comment = new Comment();
                    $case_comment->case_id = $case->id;
                    $case_comment->type = 'provided feedback to reporter ' . $case->getReporter()->name ?? 'External User';
                    $case_comment->user_id = $user->id;
                    $case_comment->comment = $feedback ?? '';
                    $case_comment->save();
                }

                $case_comment = new Comment();
                $case_comment->case_id = $case->id;
                $case_comment->user_id = $user->id;
                $string = Helper::check_link(null, null, $request->close_comment, $case->id, $case_comment->id);
                $case_comment->comment = $string ? $string : $request->close_comment;

                if (!$type) {
                    $case_comment->type = ' closed case';
                    $case_comment->save();
                    $case->status = 'closed';
                    //$case->case_closed = 1;
                    $case->save();
                } else {
                    $case_comment->type = ' moved case for final closure approval.';
                    $case_comment->save();
                    $case->status = 'waiting';
                    //$case->case_closed = 1;
                    $case->save();
                }

                $documents = (array) $request->documents;
                CommentDocument::where('comment_id', $case_comment->id)->delete();

                foreach ($documents as $value) {
                    $doc = new CommentDocument();
                    $doc->comment_id = $case_comment->id;
                    $value = Document::where('unique_id', $value)->first();
                    if (!$value) {
                        continue;
                    }
                    $doc->document_id = $value->id;
                    $doc->type = $value->isImage() ? 'image' : 'document';
                    $doc->save();
                }
            }
        }

        
        // send email to unique users only
        if ($request->send_feedback_to_reporter_user) {
            foreach ($same_case_reported_users as $case_user) {
                $feedback = $request->feedback_user;
                $reported_by = $case_user['reported_user'];
                $reported_by_cases = $case_user['cases'];
                if (isset($same_case_reported_users[1]['cases'])) {
                    $case_ids = array_map(function ($item) {
                        return $item['id'];
                    }, $same_case_reported_users[1]['cases']);
                } else {
                    $case_ids = [];
                }
                if (!in_array($reported_by->id, $user_feedback_ids)) {
                    continue;
                }
                $newFeedback = new case_feedback();
                $newFeedback->head_office_id = $headOffice->id;
                $newFeedback->reported_by_user_id = $reported_by->id;
                $newFeedback->feedback_by_user_id = $user->id;
                $newFeedback->location_id = $reported_by->getLocation()->id;
                $newFeedback->case_ids = json_encode($main_case_ids);
                $newFeedback->is_feedback_user = true;
                $newFeedback->feedback_user = $feedback;
                if ($request->send_feedback_to_reporter) {
                    $newFeedback->is_feedback_location = true;
                    $newFeedback->feedback_location = $request->feedback;
                }
                $newFeedback->save();
                Mail::send('emails.bulk_case_close_email', ['user' => $reported_by, 'cases' => $reported_by_cases, 'headOffice' => $headOffice], function ($message) use ($reported_by) {
                    $message->to($reported_by->email);
                    $message->subject(env('APP_NAME') . ' - Case Closed!');
                });
            }
        }

        $activity_log = new ActivityLog();
        $activity_log->type = 'Bulk Case Closed';
        $activity_log->user_id = $user->id;
        $activity_log->head_office_id = $headOffice->id;
        $activity_log->action = '';
        $activity_log->save();
        return back()->with('success', 'Cases closed!');
    }
    public function delete_link($id)
    {
        $link = Link::findOrFail($id);
        $case_user = $link->link_case->case_head_office
            ->users()
            ->where('user_id', $link->user_id)
            ->first();
        if ($case_user) {
            $link_log = new LinkLog();
            $link_log->link_id = $link->id;
            $link_log->removal_date = Carbon::now();
            $link_log->is_active = 0;
            $link->is_active = 0;
            $link->save();
            $link_log->title = $link->title;
            $link_log->link = $link->link;
            $link_log->user_id = Auth::guard('web')->user()->id;
            $link_log->save();
            return back()->with('success_message', 'Linked removed');
        }
        return back()->with('error', "You don't have access to this page");
    }
    public function update_link($id)
    {
        $link = Link::findOrFail($id);
        $case_user = $link->link_case->case_head_office
            ->users()
            ->where('user_id', $link->user_id)
            ->first();
        if ($case_user) {
            $link_log = new LinkLog();
            $link_log->link_id = $link->id;
            $link_log->date_to_be_removed = $link->date_to_be_removed;
            $link_log->is_active = 1;
            $link_log->title = $link->title;
            $link_log->link = $link->link;
            $link_log->user_id = Auth::guard('web')->user()->id;
            $link_log->save();
            return back()->with('error', 'Link updated');
        }
        return back()->with('error', "You don't have access to this page");
    }
    public function removeable_links()
    {
        $ho = Auth::guard('web')->user()->selected_head_office;
        $cases = $ho->cases;
        $to_be_removed_links = [];
        foreach ($cases as $case) {
            if (count($case->case_links) > 0) {
                foreach ($case->case_links()->whereDate('date_to_be_removed', '<=', Carbon::now()->toDateString())->where('is_active', 1)->get() as $link) {
                    $to_be_removed_links[] = $link;
                }
            }
        }
        return view('head_office.case_manager.to_be_removed_links', compact('to_be_removed_links'));
    }

    public function store_document(Request $request)
{
    $request->validate([
        'case_id' => 'required|exists:head_office_cases,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'documents' => 'nullable|array',
    ]);

    $user = Auth::guard('web')->user();
    $form = HeadOfficeCase::findOrFail($request->case_id);
    $caseHandlerUser = $form->case_head_office_user ? $form->case_head_office_user->user : null;

    if ($user->id === optional($caseHandlerUser)->id) {
        return $this->handleDocumentSave($request, $form);
    }
    $profile = $user->getHeadOfficeUser()->get_permissions();
    if (!$profile || !($profile->super_access || $profile->is_manage_forms)) {
        abort(403, 'Permission Denied');
    }
    return $this->handleDocumentSave($request, $form);
}

     private function handleDocumentSave(Request $request, $form)
{    
    if ($request->has('document_id')) {
        $task = $form->case_documents()->find($request->document_id);
    } else {
        $task = new CaseManagerCaseDocument();
        if ($request->has('case_log')) {
            $task->is_default_document = true;
        }
    }

    $task->case_id = $request->case_id;
    $task->uploaded_by = Auth::guard('web')->user()->id;
    $task->title = $request->title;
    $task->description = $request->description;
    $task->save();

    $documents = (array) $request->documents;
    CaseManagerCaseDocumentDocument::where('c_m_c_d_id', $task->id)->delete();
    foreach ($documents as $value) {
        $doc = new CaseManagerCaseDocumentDocument();
        $doc->c_m_c_d_id = $task->id;
        $value = Document::where('unique_id', $value)->first();
        if (!$value) {
            continue;
        }
        $doc->document_id = $value->id;
        $doc->type = $value->isImage() ? 'image' : 'document';
        $doc->save();
    }

    $case_comment = new Comment();
    $case_comment->case_id = $request->case_id;
    $case_comment->type = ' added new documents';
    $case_comment->comment = 'Added new document';
    $case_comment->user_id = Auth::guard('web')->user()->id;
    $case_comment->save();

    return redirect()
        ->route('case_manager.view', [$request->case_id, '#cm_case_documents'])
        ->with('success_message', 'Default Task created successfully');
}

    public function activate_document($id)
    {
        // $document = CaseManagerCaseDocument::findOrFail($id);
        $document = DefaultDocument::findOrFail($id);

        // $case = $document->case;
        // $case_comment = new Comment();
        // $case_comment->case_id = $case->id;

        if ($document->active) {
            $document->active = false;
            // $case_comment->type = ' Deactived a docment';
            // $case_comment->comment = 'Deactived a document';
        } else {
            $document->active = true;
            // $case_comment->type = ' Actived a docment';
            // $case_comment->comment = 'Actived a document';
        }

        $document->updated_by = Auth::guard('web')->user()->id;
        // $case_comment->user_id = Auth::guard('web')->user()->id;
        // $case_comment->save();

        $document->save();
        return back()->with('success_message', 'Document statues updated successfully');
    }

    public function delete_document($id)
    {
        $document = CaseManagerCaseDocument::findOrFail($id);
        foreach ($document->documents as $doc) {
            $doc->document ? $doc->document->delete() : '';
        }
        $case = $document->case;
        $case_comment = new Comment();
        $case_comment->case_id = $case->id;
        $case_comment->type = ' deleted a docments';
        $case_comment->comment = 'Deleted a document';
        $case_comment->user_id = Auth::guard('web')->user()->id;
        $case_comment->save();

        $document->delete();
        return back()->with('success_message', 'Document successfully deleted');
    }

    public function view_root_cause_analysis($id)
    {
        $user = Auth::guard('web')->user()->selected_head_office;
        $case = $user->cases()->find($id);
        if ($case) {
            return view('head_office.case_manager.root_cause_analysis.show', compact('case'));
        }
        return back()->with('error', 'Case not found');
    }
    public function request_new_analysis(Request $r, $id, $root_cause_analysis_id)
    {
        $user = Auth::guard('web')->user()->selected_head_office;
        $case = $user->cases()->find($id);
        if ($case) {
            $request = $case->root_cause_analysis()->find($root_cause_analysis_id);

            $request->status = 2;
            //$request->note = $r->note;
            $request->save();
            $rca = new RootCauseAnalysis();
            $rca->case_id = $request->case_id;
            $rca->type = $request->type;
            $rca->name = $request->name;
            $rca->is_editable = $request->is_editable;
            $rca->status = 0;
            $rca->note = $r->note;
            dd('asdfa');
            $rca->save();
            if ($rca->type == 'fish_bone') {
                foreach ($request->fish_bone_questions as $question) {
                    $fbrca = new FishBoneRootCauseAnalysis();
                    $fbrca->root_cause_analysis_id = $rca->id;
                    $fbrca->question = $question->question;
                    $fbrca->save();
                    foreach ($question->answers as $answer) {
                        $fbrcaa = new FishBoneRootCauseAnalysisAnswer();
                        $fbrcaa->fish_bone_root_cause_analysis_id = $question->id;
                        $fbrca->answer = $answer->answer;
                        $fbrcaa->save();
                    }
                };
            } else {
                foreach ($request->five_whys_questions as $question) {
                    $fbrca = new FiveWhysRootCauseAnalysis();
                    $fbrca->root_cause_analysis_id = $rca->id;
                    $fbrca->question = $question->question;
                    $fbrca->save();
                    // foreach($question->answers as $answer)
                    // {
                    $fbrcaa = new FiveWhysRootCauseAnalysisAnswer();
                    $fbrcaa->five_whys_root_cause_analysis_id = $question->id;
                    $fbrca->answer = $question->answers->answer;
                    $fbrcaa->save();
                    // }
                }
            }
            return back()->with('success_message', 'Root casue analysis requested');
        }
        return back()->with('error', 'Case not found');
    }
    public function view_intelligence($id)
    {
        $user = Auth::guard('web')->user()->selected_head_office;
        $case = $user->cases()->find($id);
        if (!$case) {
            return back()->with('errro', "You don't have access to this page");
        }
        $case_fields = '';
        //$questions = $case->linked_location_incident->incident->form->questions()->where([['form_card_id','!=',null],['default_card_field_id','!=',null]])->get();

        //$form_cards = $case->linked_location_incident->incident->form->formCards;

        //$form_cards = $case->linked_location_incident->incident->attached_cards();

        ///******************** */
        $case_contacts = $case->case_contacts;

        $patient_linked_contacts = [(object) ['contact' => '', 'matches' => []]];
        $prescriber_linked_contacts = [(object) ['contact' => '', 'matches' => []]];
        $possible_linked_cases_count = 0;

        // foreach($contacts as $cp)
        // {
        //     //$PatientContact = $cp->patient_contact;

        //     $found_contacts = Contact::where('id','!=',$cp->id)
        //     ->where('first_name','like','%'.$cp->first_name.'%')
        //     ->Where('last_name','like','%'.$cp->last_name.'%')
        //     ->get();
        //     $possible_linked_cases_count += $found_contacts->sum('cases_count');
        // }
        $inked_contacts = [];
        foreach ($case_contacts as $cc) {
            $cp = $cc->contact;
            $found_contacts = Contact::where('id', '!=', $cp->id)
                ->where('first_name', 'like', '%' . $cp->first_name . '%')
                ->Where('last_name', 'like', '%' . $cp->last_name . '%')
                ->orWhere('user_id', $cp->user_id)
                ->get();
            $possible_linked_cases_count += count($found_contacts);
            $inked_contacts[] = (object) ['contact' => $cp, 'matches' => $found_contacts];
            // if($cp->nhs_number)
            // {
            //     $PatientContact = $cp;
            //     $found_contacts = Contact::where('id','!=',$cp->id)
            //     ->where('first_name','like','%'.$cp->first_name.'%')
            //     ->Where('last_name','like','%'.$cp->last_name.'%')
            //     ->get();
            //     $possible_linked_cases_count += count($found_contacts);
            //     $patient_linked_contacts[] = (object)['contact'=>$PatientContact, 'matches'=> $found_contacts];
            // }
            // else if($cp->registration_no)
            // {
            //     $PrescriberContact = $cp;
            //     $found_contacts = Contact::where('id','!=',$cp->id)
            //     ->where('first_name','like','%'.$cp->first_name.'%')
            //     ->Where('last_name','like','%'.$cp->last_name.'%')
            //     ->get();
            //     $possible_linked_cases_count += count($found_contacts);
            //     $prescriber_linked_contacts[] = (object)['contact'=>$PrescriberContact, 'matches'=> $found_contacts];
            // }
        }
        // foreach($case_prescribers as $cp)
        // {
        //     $PrescriberContact = $cp->prescriber_contact;
        //     $found_contacts = PrescriberContact::where('first_name','like','%'.$PrescriberContact->first_name.'%')
        //     ->Where('sur_name','like',$PrescriberContact->sur_name.'%')->where('registration_no','!=',$PrescriberContact->registration_no)
        //     ->where('id','!=',$PrescriberContact->id)->get();
        //     $prescriber_linked_contacts[] = (object)['contact'=>$PrescriberContact, 'matches'=> $found_contacts];
        //     $possible_linked_cases_count += $found_contacts->sum('cases_count');
        // }
        return view('head_office.case_manager.case_intelligence', compact('case', 'case_contacts', 'possible_linked_cases_count', 'inked_contacts'));
    }
    public function request_fish_bone(Request $request, $id)
    {
        $user = Auth::guard('web')->user()->selected_head_office;
        $case = $user->cases()->find($id);
        $problem = $request->problem;
        $editable = $request->editable;
        $root_cause_analysis_request = new RootCauseAnalysis();
        if ($case && $case->root_cause_analysis()->where('type', 'fish_bone')->first()) {
            $root_cause_analysis_request = $case->root_cause_analysis()->where('type', 'fish_bone')->first();
        }

        $root_cause_analysis_request->case_id = $case->id;
        $root_cause_analysis_request->type = 'fish_bone';
        $root_cause_analysis_request->name = $problem;
        if ($request->get('editable')) {
            $root_cause_analysis_request->is_editable = $editable;
        }
        $root_cause_analysis_request->status = 0; // 0 requested 1 in progress draft 2 com completed
        $root_cause_analysis_request->save();

        $activity_log = new ActivityLog();
        $activity_log->type = 'Root Cause Analysis';
        $activity_log->user_id = Auth::guard('web')->user()->id;
        $activity_log->head_office_id = Auth::guard('web')->user()->selected_head_office->id;
        $activity_log->action = 'Root cause analysis requested (Fish Bone) ';
        $activity_log->save();

        $case_comment = new Comment();
        $case_comment->case_id = $case->id;
        $case_comment->type = 'Root cause analysis requested (Fish Bone) ';
        $case_comment->comment = $request->problem ?? '';
        $case_comment->user_id = Auth::guard('web')->user()->id;
        $case_comment->save();

        if (count($case->root_cause_analysis()->where('type', 'fish_bone')->first()->fish_bone_questions) == 0) {
            if ($request->custom_question == 'default') {
                foreach ($user->fish_bone_questions as $question) {
                    $root_cause_analysis_question = new FishBoneRootCauseAnalysis();

                    $root_cause_analysis_question->root_cause_analysis_id = $root_cause_analysis_request->id;
                    $root_cause_analysis_question->question = $question->question;
                    $root_cause_analysis_question->save();
                }
            } else {
                foreach ($request->questions as $question) {
                    $root_cause_analysis_question = new FishBoneRootCauseAnalysis();
                    $root_cause_analysis_question->root_cause_analysis_id = $root_cause_analysis_request->id;

                    $root_cause_analysis_question->question = $question;
                    $root_cause_analysis_question->save();
                }
            }
        }
        return back()->with('success_message', 'Root cause request successfully');
    }

    public function request_five_whys(Request $request, $id)
    {
        $user = Auth::guard('web')->user()->selected_head_office;
        $case = $user->cases()->find($id);
        $problem = $request->problem;
        $editable = $request->editable;
        $root_cause_analysis_request = new RootCauseAnalysis();
        if ($case && $case->root_cause_analysis()->where('type', 'five_whys')->first()) {
            $root_cause_analysis_request = $case->root_cause_analysis()->where('type', 'five_whys')->first();
        }

        $root_cause_analysis_request->case_id = $case->id;
        $root_cause_analysis_request->type = 'five_whys';
        $root_cause_analysis_request->name = $problem;
        if ($request->get('editable')) {
            $root_cause_analysis_request->is_editable = $editable;
        }
        $root_cause_analysis_request->status = 0; // 0 requested 1 in progress draft 2 com completed
        $root_cause_analysis_request->save();

        $activity_log = new ActivityLog();
        $activity_log->type = 'Root Cause Analysis';
        $activity_log->user_id = Auth::guard('web')->user()->id;
        $activity_log->head_office_id = Auth::guard('web')->user()->selected_head_office->id;
        $activity_log->action = "Root cause analysis requested (5 Why's) ";
        $activity_log->save();

        $case_comment = new Comment();
        $case_comment->case_id = $case->id;
        $case_comment->type = "Root cause analysis requested (5 Why's) ";
        $case_comment->comment = $request->problem ?? '';
        $case_comment->user_id = Auth::guard('web')->user()->id;
        $case_comment->save();

        if (count($case->root_cause_analysis()->where('type', 'five_whys')->first()->five_whys_questions) == 0) {
            // if($request->custom_question == 'default')
            // {
            // dd($user->five_whys_questions);
            foreach ($user->five_whys_questions as $question) {
                $root_cause_analysis_question = new FiveWhysRootCauseAnalysis();

                $root_cause_analysis_question->root_cause_analysis_id = $root_cause_analysis_request->id;
                $root_cause_analysis_question->question = $question->question;
                $root_cause_analysis_question->save();
            }

            // }
            // else
            // {
            //     foreach($request->questions as $question)
            //     {
            //         $root_cause_analysis_question = new FiveWhysRootCauseAnalysis();
            //         $root_cause_analysis_question->root_cause_analysis_id = $root_cause_analysis_request->id;

            //         $root_cause_analysis_question->question = $question;
            //         $root_cause_analysis_question->save();
            //     }
            // }
        }
        return back()->with('success_message', 'Root cause request successfully');
    }
    public function request_fish_bone_edit(Request $request, $case_id, $root_cause_analysis_id)
    {
        $user = Auth::guard('web')->user()->selected_head_office;
        $case = $user->cases()->find($case_id);
        if ($case && $case->root_cause_analysis()->find($root_cause_analysis_id)) {
            $root_cause_analysis = $case->root_cause_analysis()->find($root_cause_analysis_id);
            if ($request->has('is_editable')) {
                $root_cause_analysis->is_editable = 1;
            }
            $root_cause_analysis->name = $request->problem;
            $root_cause_analysis->save();
            foreach ($root_cause_analysis->fish_bone_questions as $question) {
                $r = 'question_id_' . $question->id;
                $question = $root_cause_analysis->fish_bone_questions()->find($request->$r);
                $q = 'question_' . $question->id;
                $question->question = $request->$q;
                $question->save();
            }
        }
        return back()->with('success_message', 'Questions updated successfully');
    }

    public function request_five_why_edit(Request $request, $case_id, $root_cause_analysis_id)
    {
        $user = Auth::guard('web')->user()->selected_head_office;
        $case = $user->cases()->find($case_id);
        if ($case && $case->root_cause_analysis()->find($root_cause_analysis_id)) {
            $root_cause_analysis = $case->root_cause_analysis()->find($root_cause_analysis_id);
            if ($request->has('is_editable')) {
                $root_cause_analysis->is_editable = 1;
            }
            $root_cause_analysis->name = $request->problem;
            $root_cause_analysis->save();
            foreach ($root_cause_analysis->five_whys_questions as $question) {
                $r = 'question_id_' . $question->id;
                $question = $root_cause_analysis->five_whys_questions()->find($request->$r);
                $q = 'question_' . $question->id;
                $question->question = $q;
                $question->save();
            }
        }
        return back()->with('success_message', 'Questions updated successfully');
    }

    public function view_root_cause_analysis_results($case_id, $root_cause_analysis_id)
    {
        $user = Auth::guard('web')->user()->selected_head_office;
        $case = $user->cases()->find($case_id);
        if ($case && $case->root_cause_analysis()->find($root_cause_analysis_id)) {
            $root_cause_analysis = $case->root_cause_analysis()->find($root_cause_analysis_id);

            if ($root_cause_analysis->type == 'fish_bone' && $root_cause_analysis->status) {
                return view('head_office.case_manager.root_cause_analysis.fish_bone', compact('case', 'root_cause_analysis'));
            }
            if ($root_cause_analysis->type == 'five_whys' && $root_cause_analysis->status) {
                return view('head_office.case_manager.root_cause_analysis.five_whys', compact('case', 'root_cause_analysis'));
            }

            return back()->with('success_message', 'Root cause analysis not found');
        }
        return back()->with('success_message', 'Root cause analysis not found');
    }
    public function mrege_contact(Request $request)
    {
        //dd($request->c1, $request->c2, $request->type);

        $case_contact_to_be_updated = CaseContact::where('contact_id', $request->c1)->get();

        DB::statement("UPDATE case_contacts SET contact_id = $request->c1 WHERE contact_id = $request->c2;");

        DB::statement("DELETE FROM contacts WHERE id = $request->c2;");
        return back()->with('success_message', 'Cases and Contact has been merged !');
        // ab ider say agay kaam samjata hun.
        //right ?

        // we have c1 and c2 and type//
        // c2 to utha k c1 mai daalna hai.

        // case_contact k ander jis jider c1 ki contact_id pari hai, us ko replace kr k wahan c2 ki id thok deni hai //
        // phir c2 ko contacts say delete kr dena hai.

        // is it clear ?

        // case_contact ko kuch b ni krna us k ander sirf contact ids replace krni hain //
        // aur patientContact ya PrescriberContact mai say 1 ko delete krna hai. depending on type
        // type is function k ander already i hai.
    }
    public function view_report($id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case = $head_office->cases()->find($id);
        return view('head_office.case_manager.notes.view_report', compact('case'));
    }
    public function edit_report($id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case = $head_office->cases()->find($id);
        $record = $case->link_case_with_form;
        $form = $record->form;
        return view('head_office.case_manager.notes.edit_report', compact('case', 'record', 'form'));
    }
    public function request_information(Request $request, $id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case = $head_office->cases()->find($id);
        $saved_questions = CaseRequestInformationSavedQuestion::where('incident_type', $case->incident_type)->get();
        if ($case) {
            $case_request_informations = $case->case_request_informations;
            if (count($case_request_informations) > 0) {
                // if($case_request_informations->last()->status == 0)
                //     return back()->with('error','Already requested');
                // $case_request_information = new CaseRequestInformation();
                // if($request->has('user_id'))
                //     $case_request_information->user_id = $request->user_id;
                // else
                // {
                //     $case_request_information->email = $request->email;
                //     $case_request_information->first_name = $request->first_name;
                //     $case_request_information->last_name = $request->last_name;
                //     $case_request_information->save();
                //     $case_comment = new Comment();
                //     $case_comment->case_id = $case->id;
                //     $case_comment->comment = 'Case requested Information';
                //     $case_comment->user_id = Auth::guard('web')->user()->id;
                //     $case_comment->save();
                //     return redirect()->route('case_manager.view')->with('success_message','Information requested');
                // }
            }
            $default_texts = Auth::guard('web')->user()->defualt_requests_text;
            return view('head_office.case_manager.case_request_information', compact('case', 'default_texts', 'saved_questions'));
        }
        return back()->with('error', 'Case not foudn');
    }

    public function comment_drafts(Request $request, $id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $case = $head_office->cases()->find($id);
        if (isset($case)) {
            $drafts = Comment::where('case_id', $id)
                ->where('user_id', $user->id)
                ->where('isDraft', true)
                ->get();
            return view('head_office.case_manager.notes.comment_drafts', compact('case', 'drafts'));
        }
    }
    public function comment_links(Request $request, $id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $case = $head_office->cases()->find($id);
        if (isset($case)) {
            $links = SystemLink::where('case_id', $id)->get();
            return view('head_office.case_manager.notes.comment_links', compact('case', 'links'));
        }
    }
    public function search_user(Request $requset)
    {
        $email = $requset->email;
        $location = $requset->location;
        $mobile = $requset->mobile;
        $registration = $requset->registration;
        // $query = User::query();
        //  $query->where(function($q) use ($email,$registration,$mobile){
        //     $q->orWhere('email', 'LIKE', '%' . $email . '%');
        //     $q->orWhere('registration_no', 'LIKE', '%' . $registration . '%');
        //     $q->orWhere('mobile_no', 'LIKE', '%' . $mobile . '%');
        // });
        //        $users = User::Where('email','LIKE',"%". $email. "%")->get();
        $users = '';
        if (!empty($email)) {
            $users = User::Where('email', $email)->get();
        } elseif (!empty($mobile)) {
            $users = User::Where('mobile_no', $mobile)->get();
        } elseif (!empty($registration)) {
            $users = User::Where('registration_no', $registration)->get();
        } elseif (!empty($location)) {
            $users = User::Where('last_login_location_id', $location)->get();
        }

        $data = [];
        $usr = [];
        foreach ($users as $user) {
            $ur = [];
            $ur['id'] = $user->id;
            $ur['name'] = $user->name;
            $ur['email'] = $user->email;
            $ur['registration_no'] = $user->registration_no;
            $ur['position'] = $user->position ? $user->position->name : 'No position assigned';
            $usr[] = $ur;
        }
        if (count($users) == 0) {
            $data['result'] = false;
        } else {
            $data['result'] = true;
            $data['users'] = $usr;
        }
        return response($data);
    }
    function request_information_save(Request $request, $id)
{
    $head_office = Auth::guard('web')->user()->selected_head_office;
    $logged_user = Auth::guard('web')->user();
    $case = $head_office->cases()->find($id);
    if ($case) {
        $case_request_informations = $case->case_request_informations;
        // if(count($case_request_informations) > 0 && $case_request_informations->last()->status == 0)
        //     return redirect()->route('case_manager.view',$case->id)->with('error','Already requested');
        $case_request_information = new CaseRequestInformation();
        $case_request_information->case_id = $case->id;
        $case_request_information->note = $request->note;
        $case_request_information->requested_by = Auth::guard('web')->user()->id;
        $case_request_information->is_available_to_person = $request->choice ? 1 : 0;
        if(isset($request->question_ids)){
            $case_request_information->question_ids = json_encode($request->question_ids);
        }
        //Attachment >3 (;)
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('uploads', 'public');
            $case_request_information->attachment = $path;
        }  

        if ($request->has('user_id')) {
            $user = User::findOrFail($request->user_id);
            $case_request_information->user_id = $user->id;
            $case_request_information->email = $user->email;
            $case_request_information->first_name = $user->first_name;
            $case_request_information->last_name = $user->surname;
            Mail::send('emails.request_information', ['case' => $case, 'heading' => 'case information requested', 'msg' => 'Head Office request case information. kindly login to your account in order to fill the details'], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject(env('APP_NAME') . ' - Fill requested information');
            });
        } else {
            $case_request_information->email = $request->manual_email;
            $case_request_information->first_name = $request->manual_first_name;
            $case_request_information->last_name = $request->manual_last_name;
            $user = User::where('email', $request->manual_email)->first();
            if ($user) {
                Mail::send('emails.request_information', ['case' => $case, 'heading' => 'case information requested', 'msg' => 'Head Office request case information. kindly login to your account in order to fill the details'], function ($message) use ($user) {
                    $message->to($user->email);
                    $message->subject(env('APP_NAME') . ' - Fill requested information');
                });
            } else {
                //account will create here
                $user = new User();
                $user->email = $request->manual_email;
                $user->password = Hash::make('123456');
                $user->first_name = $request->manual_first_name;
                $user->surname = $request->manual_last_name;
                $user->mobile_no = $request->manual_mobile_no;
                $user->email_verification_key = Str::random(64);
                $user->position_id = 10; //ya temporary id hai.
                $user->save();

                Mail::send('emails.emailVerification', ['type' => 2, 'token' => $user->email_verification_key], function ($message) use ($user) {
                    $message->to($user->email);
                    $message->subject(env('APP_NAME') . ' - Verify your email and then Fill requested information');
                });
            }
            $case_request_information->user_id = $user->id;
        }

        $case_request_information->save();
        foreach ($case->link_case_with_form->data as $data) {
            $query = 'answer_' . $data->id;
            if ($request->has($query)) {
                if ($request->$query) {
                    $data_radact = new DataRadact();
                    $data_radact->data_id = $data->id;
                    $data_radact->is_radact = 1;
                    $data_radact->save();
                }
            }
        }

        foreach ($request->questions as $q) {
            $question = new CaseRequestInformationQuestion();
            $question->case_request_information_id = $case_request_information->id;
            $question->question = $q;
            $question->save();
        }

        // Fetch existing saved questions
        $saved_questions = CaseRequestInformationSavedQuestion::where('incident_type', $case->incident_type)
            ->get()
            ->keyBy('saved_question');
        // Convert the request questions to a collection for easy manipulation
        $request_questions = collect($request->s_questions);

        // Delete questions that are not in the request
        $saved_questions->each(function ($question) use ($request_questions) {
            if (!$request_questions->contains($question->saved_question)) {
                $question->delete();
            }
        });

        // Iterate over the incoming questions
        foreach ($request->s_questions as $sq) {
            if ($saved_questions->has($sq)) {
                // Check if the existing question needs an update
                $question = $saved_questions->get($sq);
                if ($question->saved_question !== $sq) {
                    $question->saved_question = $sq;
                    $question->save();
                }
            } else {
                // Create new question
                $squestion = new CaseRequestInformationSavedQuestion();
                $squestion->incident_type = $case->incident_type;
                $squestion->saved_question = $sq;
                $squestion->save();
            }
        }

        $activity_log = new ActivityLog();
        $activity_log->type = 'Information Request';
        $activity_log->user_id = $logged_user->id;
        $activity_log->head_office_id = $head_office->id;
        $activity_log->action = 'Information request sent to ' . $case_request_information->first_name . ' ' . $case_request_information->last_name . ' ' . '(' . $case_request_information->email . ')';
        $activity_log->save();

        $case_comment = new Comment();
        $case_comment->case_id = $case->id;
        $case_comment->type = 'request information';
        $case_comment->comment = 'from ' . $user->first_name . ' ' . $user->surname;
        $case_comment->user_id = Auth::guard('web')->user()->id;
        $case_comment->save();
        return redirect()
            ->route('case_manager.view', $case->id)
            ->with('success_message', 'Information requested');
    }
    return back()->with('error', 'Case not found');
}

public function viewAttachment($id)
{
    $caseRequestInformation = CaseRequestInformation::findOrFail($id);
    $filePath = storage_path('app/public/' . $caseRequestInformation->attachment);

    if (file_exists($filePath)) {
        return response()->file($filePath);
    }

    abort(404, 'File not found.');
}

    function request_information_update(Request $request, $id, $request_id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $logged_user = Auth::guard('web')->user();
        $case = $head_office->cases()->find($id);

        if ($case) {
            $case_request_information = CaseRequestInformation::where('case_id', $case->id)
                ->where('id', $request_id)
                ->first();

            if (!$case_request_information) {
                $case_request_information = new CaseRequestInformation();
                $case_request_information->case_id = $case->id;
            }

            $case_request_information->note = $request->note;
            $case_request_information->requested_by = Auth::guard('web')->user()->id;
            $case_request_information->is_available_to_person = $request->choice ? 1 : 0;
            if(isset($request->question_ids)){
                $case_request_information->question_ids = json_encode($request->question_ids);
            }
            $case_request_information->save();

            // Delete existing DataRadact records
            foreach ($case->link_case_with_form->data as $data) {
                DataRadact::where('data_id', $data->id)->delete();
            }

            // Add new DataRadact records
            foreach ($case->link_case_with_form->data as $data) {
                $query = 'answer_' . $data->id;
                if ($request->has($query) && $request->$query) {
                    $data_radact = new DataRadact();
                    $data_radact->data_id = $data->id;
                    $data_radact->is_radact = 1;
                    $data_radact->save();
                }
            }

            // Delete existing CaseRequestInformationQuestion records
            CaseRequestInformationQuestion::where('case_request_information_id', $case_request_information->id)->delete();

            // Add new CaseRequestInformationQuestion records
            foreach ($request->questions as $q) {
                $question = new CaseRequestInformationQuestion();
                $question->case_request_information_id = $case_request_information->id;
                $question->question = $q;
                $question->save();
            }

            $activity_log = ActivityLog::where('user_id', $logged_user->id)
                ->where('head_office_id', $head_office->id)
                ->where('type', 'Information Request')
                ->first();
            if (!$activity_log) {
                $activity_log = new ActivityLog();
                $activity_log->type = 'Information Request';
            }
            $activity_log->user_id = $logged_user->id;
            $activity_log->head_office_id = $head_office->id;
            $activity_log->action = 'Information request sent to ' . $case_request_information->first_name . ' ' . $case_request_information->last_name . ' (' . $case_request_information->email . ')';
            $activity_log->save();

            $case_comment = Comment::where('case_id', $case->id)
                ->where('type', 'request information')
                ->first();
            if (!$case_comment) {
                $case_comment = new Comment();
                $case_comment->case_id = $case->id;
                $case_comment->type = 'request information';
            }
            $case_comment->user_id = Auth::guard('web')->user()->id;
            $case_comment->save();

            return redirect()
                ->route('head_office.case.requested_informations', $case->id)
                ->with('success_message', 'Requested Information Updated');
        }

        return back()->with('error', 'Case not found');
    }

    /**
     * @By HS
     * get locations for dropdown
     */
    public function getLocationRelatedToHeadOffice()
    {
        //        $currentUserLocation =  Auth::guard('location')->user()->id;
        $currentUser = Auth::user()->toArray();
        $currentUserLocation = $currentUser['last_login_location_id'];
        $company_locations = Location::locationRelatedToHeadOffice($currentUserLocation)->toArray();
        return response()->json($company_locations);
    }

    public function edit_report_save(Request $request, $id = null)
    {
        $headOffice = Auth::guard('web')->user();
        $headOffice_log = Auth::guard('web')->user()->selected_head_office;
        //$headOffice = $location->head_office();
        $conditionsToApply = [];
        $emailsToApply = [];
        $infomationToShow = [];
        $formsToFill = [];
        $rootCauseAnalysis = [];

        $oldFormsToFill = (array) $request->to_fill;

        $record = Record::findOrFail($id);
        // if($record->created_at < Carbon::now()->sub(config('app.incident_edit_capability_time_out')))
        //         {
        //             return redirect()->route('be_spoke_forms.be_spoke_form.records',$record->form_id)->with('error','You can not edit this inicident after '.config('app.incident_edit_capability_time_out'));
        //         }
        $form = $record->form;
        $form_cards_data = [];
        $form_card_test = [];

        foreach ($form->questions as $q) {
            $question_field = 'question_' . $q->id;
            $value = $request->$question_field;
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $data = $record
                ->data()
                ->where('question_id', $q->id)
                ->first();

            if ($data && $data->question_value != $value) {
                $record_data_edited_history = new RecordDataEditedHistory();
                $record_data_edited_history->form_id = $form->id;
                $record_data_edited_history->record_id = $record->id;
                $record_data_edited_history->record_data_id = $data->id;
                $record_data_edited_history->updated_by = Auth::guard('web')->user()->id;
                $record_data_edited_history->old_value = $data->question_value;
                $record_data_edited_history->updated_value = $value;
                $record_data_edited_history->save();
            }
            if (!$data && $value) {
                $data = new RecordData();
                $data->record_id = $record->id;
                $data->question_id = $q->id;
                $data->question_value = $value;
                $data->save();

                $record_data_edited_history = new RecordDataEditedHistory();
                $record_data_edited_history->form_id = $form->id;
                $record_data_edited_history->record_id = $record->id;
                $record_data_edited_history->record_data_id = $data->id;
                $record_data_edited_history->updated_by = Auth::guard('web')->user()->id;
                $record_data_edited_history->old_value = 'No Original Value';
                $record_data_edited_history->updated_value = $value;
                $record_data_edited_history->save();
            }
            if ($data && $value) {
                $data->record_id = $record->id;
                $data->question_id = $q->id;
                $data->question_value = $value;
                $data->save();
            }

            if ($q->form_card_id) {
                $form_card = $q->formCard;
                $form_card_test[] = $form_card->id;
                $default_card_field = $q->formCardDefaultField;
                if ($q->formCardDefaultField) {
                    if (!array_key_exists($form_card->id, $form_cards_data)) {
                        $form_cards_data[$form_card->id] = ['fields' => [], 'form_card' => $form_card];
                    }
                    $form_cards_data[$form_card->id]['fields'][$default_card_field->db_field_name] = $value;
                }
            }

            //
            if ($data && $data->question_id == $form->case_description_field) {
                $case_description = $data->question_value;
            }

            # Process all actions related to condition
            $conditions = $q->conditions;
            if (count($conditions)) {
                foreach ($conditions as $condition) {
                    if ($data && $condition->checkConditionTriggers($data)) {
                        # Actions are processed individually
                        if ($condition->condition_action_type == 'send_email') {
                            $emailsToApply[] = ['condition' => $condition, 'data' => $data];
                        } elseif ($condition->condition_action_type == 'display_information_to_user') {
                            $infomationToShow[] = $condition;
                        } elseif ($condition->condition_action_type == 'trigger_another_form') {
                            $formsToFill[] = $condition->id;
                        } elseif ($condition->condition_action_type == 'trigger_root_cause_analysis') {
                            $rootCauseAnalysis[] = $condition;
                        } else {
                            $conditionsToApply[] = [
                                'condition' => $condition,
                                'data' => $data,
                            ];
                        }
                    }
                }
            }
        }
        $case = $record->recorded_case;

        //check here for data //
        $pres_c = false;
        $contact_connections = [];
        $user = Auth::guard('web')->user();
        $form_cards_data[$user->first_name]['fields'] = ['first_name' => $user->first_name, 'last_name' => $user->surname, 'registration_no' => $user->registration_no];
        $form_cards_data[$user->first_name]['form_card'] = null;
        foreach ($form_cards_data as $fcd) {
            //createing contacts
            $contact = new Contact();

            $given_address = false;
            foreach ($fcd['fields'] as $key => $field) {
                if (/*$key != 'nhs_number' && $key != 'registration_no' && */ $key != 'address') {
                    $contact->$key = $field;
                } elseif ($key == 'address') {
                    $given_address = $field;
                }

                // else if($key == 'date_of_birth')
                //     $contact->$key = $field;
            }

            $found = Contact::where([['first_name', $contact->first_name], ['last_name', $contact->last_name] /*,['date_of_birth', $contact->date_of_birth]*/])->first();
            $contact->head_office_id = $headOffice->id;
            if (!$found) {
                $contact->save();
            } else {
                $contact = $found;
            }

            // Address
            if ($given_address) {
                $address = Address::where([['head_office_id', $headOffice->id], ['address', $given_address]])->first();
                if (!$address) {
                    $address = new Address();
                    $address->address = $field;
                    $address->head_office_id = $headOffice->id;
                    $address->save();
                }
                if (ContactAddress::where('contact_id', $contact->id)->first()) {
                    $ca = ContactAddress::where('contact_id', $contact->id)->get();
                    foreach ($ca as $c) {
                        $c->is_present_address = 0;
                        $c->save();
                    }
                }
                if (!ContactAddress::where([['contact_id', $contact->id], ['address_id', $address->id]])->first()) {
                    $contact_address = new ContactAddress();
                    $contact_address->contact_id = $contact->id;
                    $contact_address->address_id = $address->id;
                    $contact_address->is_present_address = 1;
                    $contact_address->save();
                } else {
                    $contact_address = ContactAddress::where([['contact_id', $contact->id], ['address_id', $address->id]])->first();
                    $contact_address->contact_id = $contact->id;
                    $contact_address->address_id = $address->id;
                    $contact_address->is_present_address = 1;
                    $contact_address->save();
                }
            }

            //case contact
            $case_contact = CaseContact::where([['case_id', $case->id], ['contact_id', $contact->id]])->first();
            if (!$case_contact) {
                $case_contact = new CaseContact();
                $case_contact->case_id = $case->id;
                $case_contact->contact_id = $contact->id;
                $case_contact->type = $fcd['form_card'] ? $fcd['form_card']->name : 'Reporter';
                $case_contact->save();
            }

            if ($fcd['form_card'] && $fcd['form_card']->connected_form_card) {
                $contact_connections[$fcd['form_card']->connected_form_card->group_id][] = ['contact_id' => $contact->id, 'form_card' => $fcd['form_card']->connected_form_card];
            }
        }
        // create contact connections ///
        $connection_fill_data = [];
        foreach ($contact_connections as $contact_connection) {
            for ($i = 0; $i < count($contact_connection); $i++) {
                for ($j = 0; $j < count($contact_connection); $j++) {
                    if ($i == $j) {
                        continue;
                    }
                    if (!ContactConnection::where([['contact_id', $contact_connection[$i]['contact_id']], ['connected_with_id', $contact_connection[$j]['contact_id']]])->first()) {
                        $connection_fill_data[] = [
                            'contact_id' => $contact_connection[$i]['contact_id'],
                            'connected_with_id' => $contact_connection[$j]['contact_id'],
                            'relation_type' => $contact_connection[$j]['form_card']->from_card->name,
                        ];
                    }
                }
            }
        }
        //To be linked with Case Manage if HO is connected !
        if (count($connection_fill_data) > 0) {
            ContactConnection::insert($connection_fill_data);
        }

        # Merge old forms to fill with new conditions
        $formsToFill = array_merge($oldFormsToFill, $formsToFill);
        # Send All Emails
        $this->sendActionEmails($emailsToApply);

        # Process other actions.
        foreach ($conditionsToApply as $c) {
            $c['condition']->processAction($c['data']);
        }
        //$rootCauseAnalysis = $this->processRootCauseArray($rootCauseAnalysis);
        // if (count($infomationToShow) || count($formsToFill)) {
        //     $information = '';
        //     foreach ($infomationToShow as $c) {
        //         $information .= "<br>" . $c->condition_action_value;
        //     }
        //     return view('location.be_spoke_forms.display-information', compact('record', 'information', 'formsToFill', 'rootCauseAnalysis'));
        // }

        // if ($request->has('location_id'))
        //     return back()->with('success_message', 'Form submitted successfully');
        $comment = new Comment();
        $comment->case_id = $case->id;
        $comment->user_id = $user->id;
        $comment->comment = 'Report Updated by head office';
        $comment->save();

        $activity_log = new ActivityLog();
        $activity_log->type = 'Report Modified';
        $activity_log->user_id = $headOffice->id;
        $activity_log->head_office_id = $headOffice_log->id;
        $activity_log->action = 'Form modified from Company account by ' . $headOffice->first_name . ' ' . $headOffice->surname;
        $activity_log->save();
        return redirect()
            ->route('case_manager.view_report', $case->id)
            ->with('success_message', 'Report updated successfully');
    }
    public function sendActionEmails($emailsToApply = [])
    {
        if (empty($emailsToApply)) {
            return;
        }
        # Process to find To, Message, Attachment Values
        $processedEmails = [];
        foreach ($emailsToApply as $e) {
            $processedEmails[] = $e['condition']->generateEmailData($e['data']);
        }
        # Combine multiple emails into one.
        $combinedEmails = [];
        foreach ($processedEmails as $email) {
            if (empty($email['to'])) {
                continue;
            }
            $combinedEmails[$email['to']][] = [
                'message' => $email['message'],
                'attachment' => $email['attachment'],
            ];
        }

        # Send Emails using Mail::send
        foreach ($combinedEmails as $key => $collection) {
            $email = new FormEmail();
            foreach ($collection as $e) {
                $email->messageContent[] = $e['message'];
                if (!empty($e['attachment'])) {
                    $email->attach($e['attachment']);
                }
            }
            Mail::to($key)->send($email);
        }
    }
    public function single_statement($case_id, $request_id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case = $head_office->cases()->find($case_id);
        if ($case) {
            $case_request_information = $case->case_request_informations()->find($request_id);
            if ($case_request_information) {
                return view('head_office.case_manager.single_statement', compact('case', 'case_request_information'));
            }
            return back()->with('error', 'Rerquest not found');
        }

        return back()->with('error', 'Case not found');
    }
    public function single_statement_delete($case_id, $request_id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case = $head_office->cases()->find($case_id);
        if ($case) {
            $case_request_information = $case->case_request_informations()->find($request_id);
            if ($case_request_information) {
                if ($case_request_information->status == 1) {
                    return back()->with('error', 'Request already submitted. You can not delete it now.');
                }
                $case_request_information->delete();
                return back()->with('success_message', 'Request deleted successfully');
            }
            return back()->with('error', 'Rerquest not found');
        }

        return back()->with('error', 'Case not found');
    }
    public function single_statement_edit(Request $request, $case_id, $request_id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case = $head_office->cases()->find($case_id);
        if ($case) {
            $case_request_information = $case->case_request_informations()->find($request_id);
            if ($case_request_information) {
                if ($case_request_information->status == 1) {
                    return back()->with('error', 'Request already submitted. You can not delete it now.');
                }
                $case_request_information->delete();
                return back()->with('success_message', 'Request deleted successfully');
            }
            return back()->with('error', 'Rerquest not found');
        }

        return back()->with('error', 'Case not found');
    }
    public function share_case(Request $request, $id, $edit_id = null)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case = $head_office->cases()->find($id);

        if (!$case) {
            return back()->with('error', 'Case not found');
        }

        // Handle quick description questions
        if (!empty($request->s_questions)) {
            share_case_quick_description::where('form_id', $case->link_case_with_form->form_id)->delete();

            foreach ($request->s_questions as $question) {
                share_case_quick_description::create([
                    'form_id' => $case->link_case_with_form->form_id,
                    'description' => $question,
                ]);
            }
        }

        if (empty($request->share_case_emails)) {
            return redirect()->back()->with('error', 'Share Emails are required');
        }

        $emails = $request->share_case_emails;
        $userEmails = collect($emails)->unique();

        foreach ($userEmails as $email) {
            $email_verification_key = Str::random(64);
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'password' => Hash::make('123456'),
                    'first_name' => 'First Name',
                    'surname' => 'Sur Name',
                    'mobile_no' => '00000000000',
                    'email_verification_key' => $email_verification_key,
                    'position_id' => Position::where('name', 'position')->first()->id ?? Position::first()->id,
                ],
            );
            $user_ho = $head_office->users()->where('user_id', $user->id)->first();
            if(!isset($user_ho)){
                $user_ho = new HeadOfficeUser();
                $user_ho->user_id = $user->id;
                $user_ho->head_office_id = $head_office->id;
                $user_ho->is_active = 1;
                $user_ho->save();
            }

            $share_case = $edit_id ? $case->share_cases()->find($edit_id) : ShareCase::firstOrNew(['user_id' => $user->id, 'case_id' => $case->id]);

            if (!$edit_id) {
                $share_case->shared_by = Auth::guard('web')->user()->id;
                $share_case->email = $email;
                $share_case->duration_of_access = Carbon::now()->add($request->duration_of_access_number . ' ' . $request->duration_of_access_type);
            }

            $share_case->is_viewable = $request->is_viewable ? 1 : 0;
            $share_case->is_log_viewable = $request->is_log_viewable ?? false;
            $share_case->is_allow_two_way = $request->is_allow_two_way ?? false;
            $share_case->is_revoked = 0;
            $share_case->note = $request->note;
            $share_case->is_deleted = 0;
            $share_case->removed_by_user = 0;
            $share_case->question_ids = json_encode($request->question_ids);
            $share_case->save();

            $this->sendShareCaseEmail($user, $head_office);
            $this->logActivity($user, $head_office, $request, $emails);
            $this->saveComment($case, $emails);

            if ($edit_id) {
                $share_case->share_case_data_radact()->delete();
            }

            $this->handleDataRedactions($request, $case, $share_case);
            $this->handleDocuments($request, $share_case);
            $this->logShareCase($email, $share_case,$head_office->company_name);
        }

        return redirect()
            ->route('case_manager.view_sharing', $case->id)
            ->with('success_message', 'Case shared successfully');
    }

    private function sendShareCaseEmail($user, $head_office)
    {
        $ho = Auth::guard('web')->user()->selected_head_office;
        $logo = isset($ho->logo) ? $ho->logo : asset('/images/svg/logo_blue.png');
                if($user->getTable() == 'locations'){
                    $logo = $user->getBrandingAttribute()->logo;
                }
        if($user->mobile_no == '00000000000'){
            Mail::send('emails.case_share_email', ['user' => $user, 'head_office' => $head_office,'logo'=>$logo,'user_present'=>false], function ($message) use ($user,$head_office) {
                $message->to($user->email)->subject('You have received a new case from '. $head_office->company_name);
            });        
        }
        else{
            Mail::send('emails.case_share_email', ['user' => $user, 'head_office' => $head_office,'logo'=>$logo,'user_present'=>true], function ($message) use ($user,$head_office) {
                $message->to($user->email)->subject('You have received a new case from '. $head_office->company_name);
            });        
        }
    }

    private function logActivity($user, $head_office, $request, $emails)
    {
        $activity_log = new ActivityLog();
        $activity_log->type = 'Share Case Externally';
        $activity_log->user_id = $user->id;
        $activity_log->head_office_id = $head_office->id;
        $activity_log->action = 'Shared case externally with ' . implode(', ', $emails) . ' access duration: ' . $request->duration_of_access_number . ' ' . $request->duration_of_access_type;
        $activity_log->save();
    }

    private function saveComment($case, $emails)
    {
        $comment = new Comment();
        $comment->user_id = Auth::guard('web')->user()->id;
        $comment->case_id = $case->id;
        $comment->type = 'shared case';
        $comment->comment = 'with ' . implode(', ', $emails);
        $comment->save();
    }

    private function handleDataRedactions($request, $case, $share_case)
    {
        if ($request->is_viewable) {
            foreach ($case->link_case_with_form->data as $data) {
                $query = 'answer_' . $data->id;
                if ($request->has($query) && $request->$query) {
                    ShareCaseDataRadact::create([
                        'data_id' => $data->id,
                        'is_radact' => 1,
                        'share_case_id' => $share_case->id,
                    ]);
                }
            }
        }
    }

    private function handleDocuments($request, $share_case)
    {
        if ($request->documents) {
            $share_case->documents()->delete();

            foreach ($request->documents as $document) {
                $doc = Document::where('unique_id', $document)->first();
                if ($doc) {
                    ShareCaseDocument::create([
                        'share_case_id' => $share_case->id,
                        'document_id' => $doc->id,
                        'type' => $doc->isImage() ? 'image' : 'document',
                    ]);
                }
            }
        }
    }

    private function logShareCase($email, $share_case, $company_name)
    {
        $share_case_log = new ShareCaseLog();
        $share_case_log->log = "Case has been shared with " . $company_name;
        $share_case_log->share_case_id = $share_case->id;
        $share_case_log->save();
    }

    public function view_sharing($case_id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case = $head_office->cases()->find($case_id);
        if (!$case) {
            return back()->with('error', 'Case not found');
        }
        $share_cases = $case->share_cases()->where('is_deleted', 0)->orderBy('id', 'desc')->get();
        return view('head_office.case_manager.notes.view_share_cases', compact('share_cases', 'case'));
    }
    public function share_case_delete($case_id, $share_id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case = $head_office->cases()->find($case_id);
        if (!$case) {
            return back()->with('error', 'Case not found');
        }
        $share_case = $case->share_cases()->find($share_id);
        if (!$share_case) {
            return back()->with('error', 'User not found');
        }
        $share_case->is_deleted = 1;
        $share_case->save();

        $share_case_log = new ShareCaseLog();
        $share_case_log->log = 'shared case deleted';
        $share_case_log->share_case_id = $share_case->id;
        $share_case_log->save();

        $comment = new Comment();
        $comment->user_id = Auth::guard('web')->user()->id;
        $comment->case_id = $case->id;
        $comment->type = 'deleted share case';
        $comment->comment = 'share case deleted';
        $comment->save();

        return back()->with('success_message', 'Case delete successfulluy');
    }
    public function share_case_edit_duration(Request $request, $case_id, $share_id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $case = $head_office->cases()->find($case_id);
        if (!$case) {
            return back()->with('error', 'Case not found');
        }
        $share_case = $case->share_cases()->find($share_id);
        if (!$share_case) {
            return back()->with('error', 'User not found');
        }

        $share_case->duration_of_access = Carbon::parse($request->duration_date);
        $share_case->is_revoked = 0;
        $share_case->save();

        $comment = new Comment();
        $comment->user_id = Auth::guard('web')->user()->id;
        $comment->case_id = $case->id;

        $comment->type = 'edited case access duration.';
        $comment->comment = $share_case->user->email . ' access extended to ' . $request->duration_of_access;
        $comment->save();

        $share_case_log = new ShareCaseLog();
        $share_case_log->log = "shared case duration extended till $share_case->duration_of_access";
        $share_case_log->share_case_id = $share_case->id;
        $share_case_log->save();

        $activity_log = new ActivityLog();
        $activity_log->type = 'Share Case Externally';
        $activity_log->user_id = $user->id;
        $activity_log->head_office_id = $head_office->id;
        $activity_log->action = 'Shared case access extended by  ' . $request->duration_of_access_number . ' ' . $request->duration_of_access_type;
        $activity_log->save();

        return back()->with('success_message', 'Case share duration extended.');
    }

    public function revoke_access($case_id, $share_id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $case = $head_office->cases()->find($case_id);
        if (!$case) {
            return back()->with('error', 'Case not found');
        }
        $share_case = $case->share_cases()->find($share_id);
        if (!$share_case) {
            return back()->with('error', 'User not found');
        }

        $share_case->is_revoked = 1;
        $share_case->revoke_by = Auth::guard('web')->user()->id;
        $share_case->save();

        $comment = new Comment();
        $comment->user_id = Auth::guard('web')->user()->id;
        $comment->case_id = $case->id;

        $comment->type = 'revoked access';
        $comment->comment = $share_case->user->email . ' access revoked by ' . Auth::guard('web')->user()->name;
        $comment->save();

        $share_case_log = new ShareCaseLog();
        $share_case_log->log = 'shared case access revoked for ' . $share_case->user->email;
        $share_case_log->share_case_id = $share_case->id;
        $share_case_log->save();

        $activity_log = new ActivityLog();
        $activity_log->type = 'Share Case Externally';
        $activity_log->user_id = $user->id;
        $activity_log->head_office_id = $head_office->id;
        $activity_log->action = 'Shared case access revoked from  ' . $share_case->email;
        $activity_log->save();

        Mail::send('emails.request_information', ['heading' => 'Extension Rejected', 'msg' => "Your access for case $case->id has been revoked", 'case' => $share_case->case], function ($message) use ($share_case) {
            $message->to($share_case->email);
            $message->subject(env('APP_NAME') . ' - case access revoked');
        });

        return back()->with('success_message', 'Access revoked successfulluy');
    }
    public function share_case_reject(Request $request, $case_id, $share_id, $extension_id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case = $head_office->cases()->find($case_id);
        if (!$case) {
            return back()->with('error', 'Case not found');
        }
        $share_case = $case->share_cases()->find($share_id);
        if (!$share_case) {
            return back()->with('error', 'User not found');
        }

        $share_case->is_revoked = 1;
        $share_case->save();

        $ext = $share_case->extension->find($extension_id);
        if (!$ext) {
            return back()->with('error', 'Extension not found');
        }
        $ext->status = 2;
        $ext->user_id = Auth::guard('web')->user()->id;
        $ext->head_office_notes = $request->head_office_notes;
        $ext->save();

        $comment = new Comment();
        $comment->user_id = Auth::guard('web')->user()->id;
        $comment->case_id = $case->id;

        $comment->type = 'reject extension request';
        // $comment->comment = $share_case->user->email . ' extension request denied by ' . Auth::guard('web')->user()->first_name . ' ' . Auth::guard('web')->user()->surname . ' ' . 'Notes : ' . $request->head_office_notes;
        $comment->comment = 'from ' . $share_case->user->email .  ' ' . 'Notes : ' . $request->head_office_notes;
        $comment->save();

        $share_case_log = new ShareCaseLog();
        $share_case_log->log = $share_case->user->email . ' extension request denied by ' . Auth::guard('web')->user()->first_name . ' ' . Auth::guard('web')->user()->surname . ' ' . 'Notes : ' . $request->head_office_notes;
        $share_case_log->share_case_id = $share_case->id;
        $share_case_log->save();

        ActivityLog::create([
            'user_id' => Auth::guard('web')->user()->id,
            'head_office_id' => $share_case->case->head_office_id,
            'action' => 'Shared case access extension denied',
            'type' => 'Share Case Externally',
            'timestamp' => now(),
        ]);

        Mail::send('emails.request_information', ['heading' => 'Extension Rejected', 'msg' => $request->head_office_notes, 'case' => $share_case->case], function ($message) use ($ext) {
            $message->to($ext->requested_by_user->email);
            $message->subject(env('APP_NAME') . ' - case extension rejected');
        });

        return back()->with('success_message', 'Extension rejected');
    }
    public function share_case_accept(Request $request, $case_id, $share_id, $extension_id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case = $head_office->cases()->find($case_id);
        if (!$case) {
            return back()->with('error', 'Case not found');
        }
        $share_case = $case->share_cases()->find($share_id);
        if (!$share_case) {
            return back()->with('error', 'User not found');
        }

        $share_case->duration_of_access = Carbon::parse($request->duration_of_access);
        $share_case->is_revoked = 0;
        $share_case->save();

        $ext = $share_case->extension->find($extension_id);
        $ext->status = 1;
        $ext->user_id = Auth::guard('web')->user()->id;
        $ext->head_office_notes = $request->head_office_notes;
        $ext->save();

        $comment = new Comment();
        $comment->user_id = Auth::guard('web')->user()->id;
        $comment->case_id = $case->id;
        $comment->type = 'accept extension request';
        // $comment->comment = $share_case->user->email . ' extension request accepted by ' . Auth::guard('web')->user()->first_name . ' ' . Auth::guard('web')->user()->surname . '<br>' . 'Notes : ' . $request->head_office_notes;
        $comment->comment = 'from ' .$share_case->user->email.  '<br>' . 'Notes : ' . $request->head_office_notes;

        $comment->save();

        ActivityLog::create([
            'user_id' => Auth::guard('web')->user()->id,
            'head_office_id' => $share_case->case->head_office_id,
            'action' => 'Shared case access extension request approved ',
            'type' => 'Share Case Externally',
            'timestamp' => now(),
        ]);

        $share_case_log = new ShareCaseLog();
        $share_case_log->log = $share_case->user->email . ' extension request accepted by ' . Auth::guard('web')->user()->first_name . ' ' . Auth::guard('web')->user()->surname . '\n' . 'Notes : ' . $request->head_office_notes;
        $share_case_log->share_case_id = $share_case->id;
        $share_case_log->save();

        Mail::send('emails.request_information', ['heading' => 'Extension Accepted', 'msg' => $request->head_office_notes, 'case' => $share_case->case], function ($message) use ($ext) {
            $message->to($ext->requested_by_user->email);
            $message->subject(env('APP_NAME') . ' - case extension accepted');
        });
        return back()->with('success_message', 'Extension accepted');
    }
    public function default_request_information_text(Request $request, $id = null)
    {
        $user = Auth::guard('web')->user(); // bolna ja hun.
        $value = $request->value;
        $default_request = $user->defualt_requests_text()->find($id);
        if ($user->defualt_requests_text()->where('value', $value)->first()) {
            $data['result'] = false;
            $data['msg'] = 'Same text value already exsist';
            return response($data);
        }

        if (!$default_request) {
            $default_request = new DefaultRequestInformation();
        }

        $default_request->value = $value;
        $default_request->user_id = $user->id;
        $default_request->save();

        $data = [];
        $data['result'] = true;
        $data['values'] = $user->defualt_requests_text;
        $data['route'] = route('head_office.case.default_request_information_text.delete', ['id'=>$default_request->id,'_token', csrf_token()]);
        return response($data);
    }
    public function requested_informations($id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case = $head_office->cases()->find($id);
        if (!$case) {
            return back()->with('error', 'Case not found');
        }
        return view('head_office.case_manager.notes.view_requested_informations', compact('case'));
    }
    public function requested_information($case_id, $id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case = $head_office->cases()->find($case_id);
        if (!$case) {
            return back()->with('error', 'Case not found');
        }

        $requested_information = $case->case_request_informations()->find($id);
        if ($requested_information) {
            return view('head_office.case_manager.notes.view_requested_information', compact('case', 'requested_information'));
        } else {
            return back()->with('error', 'Request informaiton not found');
        }
    }

    public function requested_information_delete($case_id, $id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case = $head_office->cases()->find($case_id);
        if (!$case) {
            return back()->with('error', 'Case not found');
        }

        $requested_information = $case->case_request_informations()->find($id);

        if ($requested_information) {
            ActivityLog::create([
                'user_id' => Auth::guard('web')->user()->id,
                'head_office_id' => Auth::guard('web')->user()->selected_head_office->id,
                'action' => 'Case: #' . $case->id . 'Information request cancelled for ' . $requested_information->first_name . '(' . $requested_information->email . ')',
                'type' => 'Information Request',
                'timestamp' => now(),
            ]);
            $comment = new Comment();
            $comment->case_id = $case->id;
            $comment->user_id = Auth::guard('web')->user()->id;
            $comment->comment = 'Information request cancelled for ' . $requested_information->first_name . '(' . $requested_information->email . ')';
            $comment->type = 'Information Request';
            $comment->save();
            $requested_information->delete();
            return back()->with('success', 'Requested information deleted successfully');
        } else {
            return back()->with('error', 'Requested information not found');
        }
    }

    public function requested_information_edit($case_id, $id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case = $head_office->cases()->find($case_id);
        if (!$case) {
            return back()->with('error', 'Case not found');
        }

        $requested_information = $case->case_request_informations()->find($id);

        if (!$requested_information) {
            return back()->with('error', 'Requested information not found');
        }

        $default_texts = Auth::guard('web')->user()->defualt_requests_text;
        $requested_question = CaseRequestInformationQuestion::where('case_request_information_id', $requested_information->id)
            ->get()
            ->toArray();

        return view('head_office.case_manager.notes.edit_request', compact('case', 'requested_information', 'default_texts', 'requested_question'));
    }

    public function default_request_information_text_delete($id)
    {
        $user = Auth::guard('web')->user(); // bolna ja hun.
        $default_request = $user->defualt_requests_text()->find($id);
        if ($default_request) {
            $default_request->delete();
            $array = [];
            $data = [];
            $data['result'] = true;
            $data['values'] = $user->defualt_requests_text;
            return response($data);
        }
        $data['result'] = false;
        $data['msg'] = 'Some error occured';
        return $data;
    }
    public function share_case_view($case_id, $edit_id = null)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case = $head_office->cases()->find($case_id);
        $gdprs = $head_office->gdprs;
        if (!$case) {
            return back()->with('error', 'Case not found');
        }
        $formId = $case->link_case_with_form->form_id;
        $all_qustions = share_case_quick_description::where('form_id', $formId)->get();
        $share_case = $case->share_cases()->find($edit_id);

        if ($share_case) {
            return view('head_office.case_manager.notes.share_case', compact('case', 'share_case', 'all_qustions', 'gdprs'));
        }

        return view('head_office.case_manager.notes.share_case', compact('case', 'all_qustions', 'gdprs'));
    }
    public function add_interested_parties(Request $request, $case_id)
    {
        
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case = $head_office->cases()->find($case_id);
        if (!$case) {
            return back()->with('error', 'Case not found');
        }
        foreach($request->head_office_user_ids as $user_id){
            $head_office_user = $head_office->users->where('user_id', $user_id)->first();
            if (!$head_office_user) {
                continue;
            }

            if (
                $case
                    ->case_interested_parties()
                    ->where('head_office_user_id', $head_office_user->id)
                    ->first()
            ) {
                continue;
            }

            $party = new CaseInterestedParty();
            $party->head_office_user_id = $head_office_user->id;
            $party->case_id = $case->id;
            $party->note = $request->note ?? '';
            $party->save();
    
            $case_comment = new Comment();
            $case_comment->case_id = $case->id;
            $case_comment->type = ' added ' . $party->case_head_office_user->user->name . ' as a case investigator';
            $case_comment->comment = $request->note ?? '' ;
            $case_comment->user_id = Auth::guard('web')->user()->id;
            $case_comment->save();

        }
        

        return back()->with('success_message', 'Party added');
        

    }
    public function delete_interested_party($case_id, $party_id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case = $head_office->cases()->find($case_id);
        if (!$case) {
            return back()->with('error', 'Case not found');
        }

        $party = $case->case_interested_parties()->find($party_id);
        if (!$party) {
            return back()->with('error', 'Party not found');
        }

        $case_comment = new Comment();
        $case_comment->case_id = $case->id;
        $case_comment->type = ' removed ' . $party->case_head_office_user->user->name . ' interested party';
        $case_comment->comment = Auth::guard('web')->user()->name . ' removed ' . $party->first_name . ' ' . $party->last_name . 'interested party';
        $case_comment->user_id = Auth::guard('web')->user()->id;
        $case_comment->save();
        $party->delete();
        return redirect()
            ->route('case_manager.view', [$case->id, '#cm_case_interested_parties'])
            ->with('success_message', 'Party deleted successfully');
    }
    public function edit_interested_parties(Request $request, $case_id, $party_id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case = $head_office->cases()->find($case_id);
        if (!$case) {
            return back()->with('error', 'Case not found');
        }

        $party = $case->case_interested_parties()->find($party_id);

        if (!$party) {
            return back()->with('error', 'Party not found');
        }

        $party->first_name = $request->first_name;
        $party->last_name = $request->last_name;
        $party->email = $request->email;
        $party->save();
        return redirect()
            ->route('case_manager.view', [$case->id, '#cm_case_interested_parties'])
            ->with('success_message', 'Party updated successfully');
    }
    public function share_case_comment(Request $request, $case_id, $share_id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case = $head_office->cases()->find($case_id);
        if (!$case) {
            return back()->with('error', 'Case not found');
        }

        $share_case = $case->share_cases()->find($share_id);

        if ($share_case) {
            $communicatoin = new ShareCaseCommunication();
            // $communicatoin->is_user = 1;
            $communicatoin->user_id = Auth::guard('web')->user()->id;
            $communicatoin->share_case_id = $share_case->id;
            $communicatoin->message = $request->comment;
            $communicatoin->save();

            $case_comment = new Comment();
            $case_comment->case_id = $share_case->case->id;
            $case_comment->type = ' messaged ' . $share_case->user->name . ' regarding shared case ';
            $case_comment->comment = $request->comment;
            $case_comment->user_id = Auth::guard('web')->user()->id;
            $case_comment->save();

            return redirect()->route('case_manager.view_sharing', [$case->id, '#communication_' . $share_id]);
        }
        return back()->with('error', 'Shared case not found');
    }
    public function share_case_responsibity(Request $request, $case_handler_id, $case_id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $logged_user = Auth::guard('web')->user();
        $case = $head_office->cases()->find($case_id);
        $caseHandalers = $request->caseHandalers; // Array of user IDs

        if(!isset($caseHandalers) ||count($caseHandalers) == 0){
            return back()->with('error', 'Please select at least one user to share case responsibility.');
        }
    
        if (!$case) {
            return back()->with('error', 'Case not found');
        }
    
        $errors = [];
        $success = [];
    
        foreach ($caseHandalers as $head_office_user_id) {
            $user = $head_office->users()->where('user_id', $head_office_user_id)->first();
    
            if (!$user) {
                $errors[] = "User with ID $head_office_user_id does not exist or is not part of this head office.";
                continue;
            }
    
            if ($case->case_handlers()->where('head_office_user_id', $user->id)->exists()) {
                $errors[] = "Case is already shared with user {$user->name}.";
                continue;
            }
    
            // Create new case handler
            $case_handler_new = new CaseHandlerUser();
            $case_handler_new->head_office_user_id = $user->id;
            $case_handler_new->case_id = $case->id;
            $case_handler_new->note = $request->note;
            $case_handler_new->save();
    
            // Log activity
            $activity_log = new ActivityLog();
            $activity_log->type = 'Share Case Responsibility';
            $activity_log->user_id = $logged_user->id;
            $activity_log->head_office_id = $head_office->id;
            $activity_log->action = 'Company User ' . $user->name . ' added as case handler';
            $activity_log->save();
    
            // Add a comment
            $case_comment = new Comment();
            $case_comment->case_id = $case->id;
            $case_comment->type = $logged_user->name . ' shared case responsibility with ' . $user->name;
            $case_comment->comment = $request->note ? $request->note : '';
            $case_comment->user_id = $logged_user->id;
            $case_comment->save();
    
            $success[] = "Case has been transferred to {$user->name} successfully.";
        }
    
        // Prepare final response
        $message = '';
        if ($success) {
            $message .= implode('<br>', $success) . '<br>';
        }
        if ($errors) {
            // $message .= implode('<br>', $errors);
        }
    
        return back()->with('success', "Case shared!");
    }
    

    public function share_case_responsibity_bulk(Request $request)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $logged_user = Auth::guard('web')->user();
        if (empty($request->case_ids) && !isset($request->case_ids[0])) {
            return back()->with('error', 'please provide valid cases!');
        }
        if (empty($request->caseHandalers) && !isset($$request->caseHandalers)) {
            return back()->with('error', 'please provide valid Users!');
        }
        $case_ids = explode(',', $request->case_ids[0]);

        $user_ids = $request->caseHandalers;

        
        foreach ($case_ids as $case_id) {
            $case = $head_office->cases()->find($case_id);
            foreach($user_ids as $head_office_user_id){
                    $user = $head_office
                    ->users()
                    ->where('user_id', $head_office_user_id)
                    ->first();
                    $case_handler = $case->case_handlers()->first();
                    if ($case_handler) {
                        if (
                            $case
                                ->case_handlers()
                                ->where('head_office_user_id', $user->id)
                                ->first()
                        ) {
                            continue;
                        }
                        $case_handler_new = new CaseHandlerUser();
                        $case_handler_new->head_office_user_id = $user->id;
                        $case_handler_new->case_id = $case->id;
                        $case_handler_new->note = $request->note;
                        $case_handler_new->save();
        
                        $case_comment = new Comment();
                        $case_comment->case_id = $case->id;
                        $case_comment->type = Auth::guard('web')->user()->name . ' shared case responsibility with ' . $case_handler_new->case_head_office_user->user->name;
                        $case_comment->comment = $request->note ? $request->note : '';
                        $case_comment->user_id = Auth::guard('web')->user()->id;
                        $case_comment->save();
                    }
                }
                $activity_log = new ActivityLog();
                $activity_log->type = 'Share Case Responsibility';
                $activity_log->user_id = $logged_user->id;
                $activity_log->head_office_id = $head_office->id;
                $activity_log->action = 'Company User ' . $user->name . ' added as case handler to many cases';
                $activity_log->save();
            }
        return back()->with('success', 'Case shared successfully!');
    }

    public function open_case_bulk(Request $request)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $logged_user = Auth::guard('web')->user();
        $head_office_user = $head_office
            ->users()
            ->where('user_id', $logged_user->id)
            ->first();
        $case_ids = $request->case_ids;

        foreach ($case_ids as $case_id) {
            $case = $head_office->cases()->find($case_id);
            $case_handlers = $case->case_handlers()->get();
            $self_case_handler = $case
                ->case_handlers()
                ->where('head_office_user_id', $head_office_user->id)
                ->get();
            $case->status = 'open';
            $case->save();
            if (count($case_handlers) != 0 && count($self_case_handler) != 0) {
                $case_comment = new Comment();
                $case_comment->case_id = $case->id;
                $case_comment->type = ' Case re-opened and case handler ' . $logged_user->name;
                $case_comment->comment = $request->note ? $request->note : ''; //Auth::guard('web')->user()->name.' transfered case responsibility to '.$case_handler_new->case_head_office_user->user->name;
                $case_comment->user_id = Auth::guard('web')->user()->id;
                $case_comment->save();
            } else {
                $case_handler_new = new CaseHandlerUser();
                $case_handler_new->head_office_user_id = $head_office_user->id;
                $case_handler_new->case_id = $case->id;
                $case_handler_new->note = $request->note;
                $case_handler_new->save();

                $case_comment = new Comment();
                $case_comment->case_id = $case->id;
                $case_comment->type = ' Case re-opened and new Case handler added ' . $case_handler_new->case_head_office_user->user->name;
                $case_comment->comment = $request->note ? $request->note : ''; //Auth::guard('web')->user()->name.' transfered case responsibility to '.$case_handler_new->case_head_office_user->user->name;
                $case_comment->user_id = Auth::guard('web')->user()->id;
                $case_comment->save();
            }
        }
        $activity_log = new ActivityLog();
        $activity_log->type = 'Case Closed';
        $activity_log->user_id = $logged_user->id;
        $activity_log->head_office_id = $head_office->id;
        $activity_log->action = 'Bulk Case Opened';
        $activity_log->save();
        return back()->with('success', 'Cases opened successfully!');
    }
    public function transfer_case_responsibity(Request $request, $case_handler_id, $case_id)
{
    $head_office = Auth::guard('web')->user()->selected_head_office;
    $logged_user = Auth::guard('web')->user();
    $case = $head_office->cases()->find($case_id);
    $case_handler = $case->case_handlers()->find($case_handler_id);
    if (!$case) {
        return back()->with('error', 'Case not found.');
    }

    $caseHandalers = $request->caseHandalers; // Array of user IDs
    if(!isset($caseHandalers) ||count($caseHandalers) == 0){
        return back()->with('error', 'Please select at least one user to share case responsibility.');
    }
    $errors = [];
    $success = [];

    foreach ($caseHandalers as $head_office_user_id) {
        $user = $head_office
            ->users()
            ->where('user_id', $head_office_user_id)
            ->first();
        if (!$user) {
            $errors[] = "User with ID $head_office_user_id does not exist or is not part of this head office.";
            continue;
        }
        if (
            $case->case_handlers()->where('head_office_user_id', $user->id)->exists()
        ) {
            $errors[] = "User {$user->name} already owns this case or is a Super User.";
            continue;
        }

        // Transfer case responsibility
        $case_handler_new = new CaseHandlerUser();
        $case_handler_new->head_office_user_id = $user->id;
        $case_handler_new->case_id = $case->id;
        $case_handler_new->note = $request->note;
        $case_handler_new->save();

        // Log activity
        $activity_log = new ActivityLog();
        $activity_log->type = 'Transfer Case Responsibility';
        $activity_log->user_id = $logged_user->id;
        $activity_log->head_office_id = $head_office->id;
        $activity_log->action = 'Company User ' . $logged_user->name . ' transferred case responsibility to ' . $user->name;
        $activity_log->save();

        // Add comment
        $case_comment = new Comment();
        $case_comment->case_id = $case->id;
        $case_comment->type = 'Transferred case responsibility to ' . $user->name;
        $case_comment->comment = $request->note ? $request->note : '';
        $case_comment->user_id = $logged_user->id;
        $case_comment->save();

        $success[] = "Case has been transferred to {$user->name} successfully.";
    }

    DB::beginTransaction();
    try{
        // Delete the previous case handler
        if ($case_handler) {
            $case_handler->delete();
        } else {
            $errors[] = "Original case handler not found for case ID $case_id.";
        }

        $checkCaseHandlers = $case_handler = $case->case_handlers();
        if (!isset($checkCaseHandlers) || $checkCaseHandlers->count() == 0) {
            DB::rollBack();
        }else{
            DB::commit();
        }


    }catch (Exception $e) {
        DB::rollBack();
    }

    // Prepare final response
    $message = '';
    if ($success) {
        $message .= implode('<br>', $success) . '<br>';
    }
    if ($errors) {
        $message .= implode('<br>', $errors);
    }

    return back()->with('success', 'case transfered!');
}


    public function remove_case_handler(Request $request, $case_id)
    {
        if (empty($request->other_head_office_user_id)) {
            return redirect()->back()->wiht('error', 'Please assign new case handler first!');
        }
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $logged_user = Auth::guard('web')->user();
        $case = $head_office->cases()->find($case_id);
        $case_handler = $case->case_handlers()->find($request->case_handler_id);
        $user = $head_office
            ->users()
            ->where('user_id', $case_handler->head_office_user_id)
            ->first();
        $new_case_handler = $head_office
            ->users()
            ->where('user_id', $request->other_head_office_user_id)
            ->first();
        if (!isset($new_case_handler)) {
            return back()->with('error', 'case handler not found');
        }
        if ($case_handler || ($user->user_profile_assign && $user->user_profile_assign->profile->profile_name == 'Super User')) {
            if (
                $case
                    ->case_handlers()
                    ->where('head_office_user_id', $new_case_handler->id)
                    ->first()
            ) {
                return back()->with('error_owner', 'This user already own this case');
            }
            $case_handler_new = new CaseHandlerUser();
            $case_handler_new->head_office_user_id = $new_case_handler->id;
            $case_handler_new->case_id = $case->id;
            $case_handler_new->note = $request->note ?? '';
            $case_handler_new->save();

            $activity_log = new ActivityLog();
            $activity_log->type = 'Share Case Responsibility';
            $activity_log->user_id = $logged_user->id;
            $activity_log->head_office_id = $head_office->id;
            $activity_log->action = 'Company User ' . $logged_user->name . 'Removed case handler ' . $case_handler->case_head_office_user->user->name . ' and assigned to' . $new_case_handler->user->name;
            $activity_log->save();

            $case_comment = new Comment();
            $case_comment->case_id = $case->id;
            $case_comment->type = ' Removed case handler ' . $case_handler->case_head_office_user->user->name . '  and assigned to ' . $case_handler_new->case_head_office_user->user->name;
            $case_comment->comment = $request->note ? $request->note : ''; //Auth::guard('web')->user()->name.' transfered case responsibility to '.$case_handler_new->case_head_office_user->user->name;
            $case_comment->user_id = Auth::guard('web')->user()->id;
            $case_comment->save();

            $case_handler->delete();
            return back()->with('success_message', 'Case has been trasfered to ' . $case_handler_new->case_head_office_user->user->name . ' successfully');
        }
        return back()->with('error', 'case handler not found');
    }

    public function remove_any_case_handler(Request $request, $case_id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $logged_user = Auth::guard('web')->user();
        $case = $head_office->cases()->find($case_id);
        
        $users_to_remove = isset($request->users) ? json_decode($request->users, true) : [];
    
        if (empty($users_to_remove)) {
            return back()->with('error', 'No users selected to remove.');
        }
    
        foreach ($users_to_remove as $user_to_remove_id) {
            // Find case handler
            $case_handler = $case->case_handlers()
                                 ->whereHas('case_head_office_user', function($query) use ($user_to_remove_id) {
                                     $query->where('user_id', $user_to_remove_id);
                                 })
                                 ->first();
            
            // Find case investigator (interested party)
            $party_remove = $case->case_interested_parties()
                                 ->whereHas('case_head_office_user', function($query) use ($user_to_remove_id) {
                                     $query->where('user_id', $user_to_remove_id);
                                 })
                                 ->first();
            
            // Error if neither case handler nor case investigator is found
            if (!$case_handler && !$party_remove) {
                return back()->with('error', 'User with ID ' . $user_to_remove_id . ' not found in either case handlers or interested parties.');
            }
    
            // If it's a case handler
            if ($case_handler) {
                if(count($case->case_handlers) == 1 && empty($request->other_head_office_user_id)){
                    return redirect()->back()->with('error', 'Please assign new case handler first!');
                }
                // Validate if a new case handler is provided when required
                if (isset($case_handler) && count($case->case_handlers) == 1) {
                    $new_case_handler = $head_office->users()->where('user_id', $request->other_head_office_user_id)->first();
                    if(!isset($new_case_handler)){
                        return redirect()->back()->with('error', 'Please assign new case handler first!');
                    }
                    
                    if ($case_handler->case_head_office_user->id == $new_case_handler->id) {
                        return redirect()->back()->with('error', 'Please assign to different case handler!');
                    }
    
                    // Add the new case handler
                    $case_handler_new = new CaseHandlerUser();
                    $case_handler_new->head_office_user_id = $new_case_handler->id;
                    $case_handler_new->case_id = $case->id;
                    $case_handler_new->note = $request->note ?? '';
                    $case_handler_new->save();

    
                    // Log activity for the new case handler
                    $activity_log = new ActivityLog();
                    $activity_log->type = 'Share Case Responsibility';
                    $activity_log->user_id = $logged_user->id;
                    $activity_log->head_office_id = $head_office->id;
                    $activity_log->action = 'Company User ' . $logged_user->name . ' removed case handler ' . $case_handler->case_head_office_user->user->name . ' and assigned to ' . $new_case_handler->user->name;
                    $activity_log->save();
    
                    // Create comment for the case update
                    $case_comment = new Comment();
                    $case_comment->case_id = $case->id;
                    $case_comment->type = 'Removed case handler ' . $case_handler->case_head_office_user->user->name . ' and assigned to ' . $case_handler_new->case_head_office_user->user->name;
                    $case_comment->comment = $request->note ?? '';
                    $case_comment->user_id = $logged_user->id;
                    $case_comment->save();
                }
    
                // Remove the case handler
                $case_handler->delete();
    
                // Log removal activity
                $activity_log = new ActivityLog();
                $activity_log->type = 'Remove Case Handler';
                $activity_log->user_id = $logged_user->id;
                $activity_log->head_office_id = $head_office->id;
                $activity_log->action = 'Company User ' . $logged_user->name . ' removed case handler ' . $case_handler->case_head_office_user->user->name;
                $activity_log->save();
    
                // Create comment for the removal
                $case_comment = new Comment();
                $case_comment->case_id = $case->id;
                $case_comment->type = 'Removed case handler ' . $case_handler->case_head_office_user->user->name;
                $case_comment->comment = $request->note ?? '';
                $case_comment->user_id = $logged_user->id;
                $case_comment->save();
            }
    
            // If it's a case investigator (interested party)
            if ($party_remove) {
                // Remove the case investigator
                $party_remove->delete();
    
                // Log removal activity
                $activity_log = new ActivityLog();
                $activity_log->type = 'Remove Case Investigator';
                $activity_log->user_id = $logged_user->id;
                $activity_log->head_office_id = $head_office->id;
                $activity_log->action = 'Company User ' . $logged_user->name . ' removed Case Investigator ' . $party_remove->case_head_office_user->user->name;
                $activity_log->save();
    
                // Create comment for the removal
                $case_comment = new Comment();
                $case_comment->case_id = $case->id;
                $case_comment->type = 'Removed Case Investigator ' . $party_remove->case_head_office_user->user->name;
                $case_comment->comment = $request->note ?? '';
                $case_comment->user_id = $logged_user->id;
                $case_comment->save();
            }
        }
    
        return back()->with('success', 'User Removed successfully.');
    }
    

    public function transfer_case_responsibity_bulk(Request $request)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $logged_user = Auth::guard('web')->user();
        if (empty($request->case_ids) && !isset($request->case_ids[0])) {
            return back()->with('error', 'please provide valid cases!');
        }
        $case_ids = explode(',', $request->case_ids[0]);

        $user = $head_office
            ->users()
            ->where('user_id', $request->head_office_user_id)
            ->first();
        foreach ($case_ids as $case_id) {
            $case = $head_office->cases()->find($case_id);
            $case_handler = $case->case_handlers()->first();
            if ($case_handler || ($user->user_profile_assign && $user->user_profile_assign->profile->profile_name == 'Super User')) {
                if (
                    $case
                        ->case_handlers()
                        ->where('head_office_user_id', $user->id)
                        ->first()
                ) {
                    continue;
                }
                $case_handler_new = new CaseHandlerUser();
                $case_handler_new->head_office_user_id = $user->id;
                $case_handler_new->case_id = $case->id;
                $case_handler_new->note = $request->note;
                $case_handler_new->save();

                $case_comment = new Comment();
                $case_comment->case_id = $case->id;
                $case_comment->type = ' transfered case responsibility to ' . $case_handler_new->case_head_office_user->user->name;
                $case_comment->comment = $request->note ? $request->note : ''; //Auth::guard('web')->user()->name.' transfered case responsibility to '.$case_handler_new->case_head_office_user->user->name;
                $case_comment->user_id = Auth::guard('web')->user()->id;
                $case_comment->save();

                if (isset($case_handler)) {
                    $case_handler->delete();
                }
            }
        }
        $activity_log = new ActivityLog();
        $activity_log->type = ' Bulk Transfer Case Responsiblity';
        $activity_log->user_id = $logged_user->id;
        $activity_log->head_office_id = $head_office->id;
        $activity_log->action = 'Company User ' . $logged_user->name . 'transfered case responsibilty to ' . $user->name;
        $activity_log->save();
        return back()->with('success', 'Case transfered successfully!');
    }

    public function remove_owner($id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')
            ->user()
            ->getHeadOfficeUser($head_office->id);
        $case = $head_office->cases()->find($id);
        $owner = $case->case_handlers->where('head_office_user_id', $user->id)->first();
        $owner_handler = CaseHandlerUser::find($owner->id);
        $owner_handler->delete();
        return back()->with('success_message', 'You are removed from this case as case handler!');
    }
    public function reject_case_close_request(Request $request, $case_id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $head_office_user = $head_office
            ->users()
            ->where('user_id', Auth::guard('web')->user()->id)
            ->first();
        $case = $head_office->cases()->find($case_id);
        $case_intereseted_party = $case
            ->case_interested_parties()
            ->where('head_office_user_id', $head_office_user->id)
            ->first();
        if ($case_intereseted_party && $case_intereseted_party->tag == 'final_clouser_approval') {
            if ($case->status == 'waiting') {
                $case->status = 'open';
                $case->save();
                $case_comment = new Comment();
                $case_comment->case_id = $case->id;
                $case_comment->type = ' rejected case approval request.';
                $case_comment->comment = $request->close_comment;
                $case_comment->user_id = Auth::guard('web')->user()->id;
                $case_comment->save();

                $activity_log = new ActivityLog();
                $activity_log->type = 'Approve Close';
                $activity_log->user_id = $user->id;
                $activity_log->head_office_id = $head_office->id;
                $activity_log->action = 'Closure not approved - Referred back to ';
                $activity_log->comment_id = $case_comment->id;
                $activity_log->save();

                $documents = (array) $request->documents;
                CommentDocument::where('comment_id', $case_comment->id)->delete();

                foreach ($documents as $value) {
                    $doc = new CommentDocument();
                    $doc->comment_id = $case_comment->id;
                    $value = Document::where('unique_id', $value)->first();
                    if (!$value) {
                        continue;
                    }
                    $doc->document_id = $value->id;
                    $doc->type = $value->isImage() ? 'image' : 'document';
                    $doc->save();
                }
                return back()->with('success_message', 'case approval rejected');
            }
            return back()->with('error', 'case can not be closed.');
        }

        return back()->with('error', 'case not found');
    }
    public function accept_case_close_request(Request $request, $case_id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $case = $head_office->cases()->find($case_id);
        if ($case->status == 'waiting') {
            $case->status = 'closed';
            $case->save();
            $case_comment = new Comment();
            $case_comment->case_id = $case->id;
            $case_comment->type = ' accepted case clouser approval request.';
            $case_comment->comment = $request->close_comment;
            $case_comment->user_id = Auth::guard('web')->user()->id;
            $case_comment->save();

            $activity_log = new ActivityLog();
            $activity_log->type = 'Approve Close';
            $activity_log->user_id = $user->id;
            $activity_log->head_office_id = $head_office->id;
            $activity_log->action = 'Closure approved ';
            $activity_log->save();

            $documents = (array) $request->documents;
            CommentDocument::where('comment_id', $case_comment->id)->delete();

            foreach ($documents as $value) {
                $doc = new CommentDocument();
                $doc->comment_id = $case_comment->id;
                $value = Document::where('unique_id', $value)->first();
                if (!$value) {
                    continue;
                }
                $doc->document_id = $value->id;
                $doc->type = $value->isImage() ? 'image' : 'document';
                $doc->save();
            }
            return back()->with('success_message', 'case approval accepted');
        }

        return back()->with('error', 'case not found');
    }

    public function case_approval_bulk(Request $request)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $head_office_user = $head_office
            ->users()
            ->where('user_id', Auth::guard('web')->user()->id)
            ->first();
        foreach ($request->case_ids as $case_id) {
            $case = $head_office->cases()->find($case_id);
            if ($case->status == 'waiting') {
                $case_comment = new Comment();
                $case_comment->case_id = $case->id;
                if ($request->reason == 'accept') {
                    $case->status = 'closed';
                    $case_comment->type = ' accepted case clouser approval request.';
                } elseif ($request->reason == 'reject') {
                    $case->status = 'open';
                    $case_comment->type = ' rejected case approval request.';
                }
                $case->save();
                $case_comment->comment = $request->close_comment;
                $case_comment->user_id = Auth::guard('web')->user()->id;
                $case_comment->save();

                $documents = (array) $request->documents;
                CommentDocument::where('comment_id', $case_comment->id)->delete();

                foreach ($documents as $value) {
                    $doc = new CommentDocument();
                    $doc->comment_id = $case_comment->id;
                    $value = Document::where('unique_id', $value)->first();
                    if (!$value) {
                        continue;
                    }
                    $doc->document_id = $value->id;
                    $doc->type = $value->isImage() ? 'image' : 'document';
                    $doc->save();
                }
            }
        }
        $activity_log = new ActivityLog();
        $activity_log->type = 'Approve Close Bulk';
        $activity_log->user_id = $user->id;
        $activity_log->head_office_id = $head_office->id;
        $activity_log->action = 'Closure approved ';
        $activity_log->save();
        return back()->with('success', 'Case Status updated!');
    }

    public function archive_bulk(Request $request)
    {
        if (!isset($request->password)) {
            return response()->json(['error' => ''], 422);
        }
        if (!isset($request->reason)) {
            return response()->json(['error' => 'Please enter reason!'], 422);
        }
        if (!Hash::check($request->get('password'), Auth::user()->password)) {
            return response()->json(['error' => 'Your current password does not matches with the password you provided. Please try again!'], 422);
        }
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if ($request->case_ids) {
            foreach ($request->case_ids as $case_id) {
                $case = $head_office->cases()->find($case_id);
                $case->isArchived = true;
                $case_comment = new Comment();
                $case_comment->case_id = $case->id;
                if ($case->isArchived == true) {
                    $case_comment->type = 'Case Archived';
                    $case_comment->comment = 'Bulk Case Archived';
                }
                $case_comment->user_id = Auth::guard('web')->user()->id;
                $case_comment->save();
                $case->save();
            }
        }
        return response()->json([
            'message' => 'Success!',
            'redirect_url' => route('case_manager.index'),
        ]);
    }

    public function unarchive_bulk(Request $request)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if ($request->case_ids) {
            foreach ($request->case_ids as $case_id) {
                $case = $head_office->cases()->find($case_id);
                if ($case->isArchived == true) {
                    $case_comment = new Comment();
                    $case_comment->case_id = $case->id;
                    $case_comment->type = 'Case Unarchived';
                    $case_comment->comment = 'Case removed from Archive';
                    $case_comment->user_id = Auth::guard('web')->user()->id;
                    $case_comment->save();
                }
                $case->isArchived = false;
                $case->save();
            }
        }
        return response()->json([
            'message' => 'Success!',
            'redirect_url' => route('case_manager.index'),
        ]);
    }

    public function archive_case(Request $request, $id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;

        if (isset($id)) {
            $case = $case = $head_office->cases()->find($id);
            if (!isset($case)) {
                return redirect()->back()->with('error', 'Case not found!');
            }
            if ($case->isArchived==1) {
                if (!isset($request->password)) {
                    return redirect()->back()->with('error', 'Please enter your current password!');
                }
                if (!isset($request->reason)) {
                    return redirect()->back()->with('error', 'Please enter reason!');
                }
                if (!Hash::check($request->get('password'), Auth::user()->password)) {
                    return redirect()->back()->with('error', 'Your current password does not matches with the password you provided. Please try again.');
                }
                
            }

            $case->isArchived = !$case->isArchived;

            $case_comment = new Comment();
            $case_comment->case_id = $case->id;
            if ($case->isArchived == 0) {
                $case_comment->type = 'Case Archived';
                $case_comment->comment = isset($request->reason)? $request->reason: "Case Archived";
            } else {
                $case_comment->type = 'Case Archived';
                $case_comment->comment = (isset($request->reason) ? $request->reason : '');
            }
            $case_comment->user_id = Auth::guard('web')->user()->id;
            $case_comment->save();
            $case->save();
            return redirect()->back()->with('success', 'Archive status updated!');
        } else {
            return redirect()->back()->with('error', 'Option Validation Error!');
        }
    }

    public function export_cases_bulk(Request $request)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $cases = $head_office->cases()->get();
        $links = case_transfer_links::where('head_office_id', $head_office->id)->get();
        if (isset($request->case_ids)) {
            $case_ids = $request->case_ids;
            return view('head_office.case_manager.export_case', compact('case_ids', 'cases', 'links', 'head_office'));
        }
        $case_ids = null;
        return view('head_office.case_manager.export_case', compact('case_ids', 'cases', 'links', 'head_office'));
    }

    public function generate_transfer_links(Request $request)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case_ids = explode(',', $request->case_ids[0]);
        if ($case_ids) {
            $newLink = new case_transfer_links();
            $newLink->head_office_id = $head_office->id;
            $newLink->case_ids = json_encode($case_ids);
            $newLink->link_token = config('app.url') . $head_office->company_name . '/' . Str::random(32);
            $newLink->link_expiry = Carbon::now()->addHour();
            $newLink->save();
        }
        return redirect()->back()->with('success', 'Link Generated!');
    }

    public function import_cases(Request $request)
    {
        $transfer_link = case_transfer_links::where('link_token', $request->link)->first();
        if (!isset($transfer_link)) {
            return response()->json(['error' => 'Transfer link not found.'], 400);
        }
        $diffTime = $transfer_link->created_at->diffInSeconds(now());
        if ($diffTime > 3600) {
            return response()->json(['error1' => 'Link Expired!'], 404);
        }
        $case_ids = json_decode($transfer_link->case_ids, true);
        $cases = HeadOfficeCase::whereIn('id', $case_ids)->get();
        $company = $cases[0]->case_head_office->company_name;
        return response()->json([$cases, $company]);
        // foreach($case_ids as $case_id){
        //     $record = Record::find($case->last_linked_incident_id);
        //     $recordData = $record->data;

        //     // creating copies of data

        // }
    }

    public function add_cases(Request $request)
    {
        if (!isset($request->case_ids[0])) {
            return redirect()->back()->with('no case selected!');
        }
        $case_ids = explode(',', $request->case_ids[0]);
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $location = Location::find($request->location);
        $cases = HeadOfficeCase::whereIn('id', $case_ids)->get();
        foreach ($cases as $case) {
            // Clone related $record
            $record = Record::find($case->last_linked_incident_id);

            if ($record) {
                // Clone $record
                $newRecord = $record->replicate();
                $newRecord->location_id = $location->id;
                $newRecord->save();

                // Clone related $recordData
                $recordData = $record->data()->get(); // Assuming relation is defined properly
                foreach ($recordData as $data) {
                    // Clone $recordData
                    $newRecordData = $data->replicate();
                    $newRecordData->record_id = $newRecord->id;
                    $newRecordData->save();
                }
            }

            // Clone $case with its relationships
            $newCase = $case->replicate();
            $newCase->head_office_id = $head_office->id;
            $newCase->location_name = $location->trading_name;
            $newCase->location_email = $location->email;
            $newCase->location_phone = $location->telephone_no;
            $newCase->location_id = $location->id;
            if (isset($newRecord)) {
                $newCase->last_linked_incident_id = $newRecord->id;
            }
            $newCase->location_full_address = $location->getFullAddressAttribute();
            $newCase->save();
        }
        return redirect()->back()->with('success', 'Incidents Cloned Successfully!');
    }
    // Related to NHS Lfpse

    public function delete_nhs_lfpse($id, Request $request)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $case = $head_office->cases()->findOrFail($id);
        $api_version = config('lfpse.api_endpoint');
        $api_ver_num = '-6';

        $record = $case->link_case_with_form;
        $existing = $record->LfpseSubmissions;
        if (count($existing) != 0) {
            $id = $existing[0]->lfpse_id;
            if (!isset($id)) {
                return redirect()->back()->with('error', 'No submission found!');
            }

            try {
                $url = $api_version . '/adverse-event/fhir/AdverseEvent/' . $id;
                $response = Http::withHeaders([
                    'Content-type' => 'application/json',
                    'Ocp-Apim-Subscription-Key' => config('lfpse.ocp_apim_subscription_key'),
                ])
                    ->withOptions(['verify' => false])
                    ->timeout(config('lfpse.request_timeout_seconds'))
                    ->delete($url);

                $status = $response->status();
                $result_json = $response->body();
                $outcome = json_decode($result_json, true);
                if ($status == 200) {
                    $del_info = new lfpse_delete();
                    $del_info->message = $request->msg;
                    $del_info->record_id = $record->id;
                    $del_info->save();
                    return redirect()->back()->with('error', 'NHS Record Deleted!. ');
                }
                if (isset($outcome['issue'][0]['diagnostics'])) {
                    $diagnostics = $outcome['issue'][0]['diagnostics'];
                    // Save the diagnostics for further investigation or logging
                    $error_save = new lfpse_errors();
                    $error_save->status = $status;
                    $error_save->severity = $outcome['issue'][0]['severity'];
                    $error_save->message = $outcome['issue'][0]['diagnostics'];
                    $error_save->record_id = $record->id;
                    $error_save->save();
                    return redirect()
                        ->back()
                        ->with('error', 'Submission Failed!. ' . $diagnostics);
                }
            } catch (Exception $e) {
                dd($e);
                return redirect()->back()->with('error', 'Submission Failed! Please try again.');
            }
        } else {
            return redirect()->back()->with('error', 'No submission found!');
        }
    }
    public function submit_nhs_lfpse($id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;

        $case = $head_office->cases()->findOrFail($id);

        $record = $case->link_case_with_form;
        $existing = $record->LfpseSubmissions;
        $result = false;
        if (count($existing) == 0) {
            $form_request = $record->get_filled_form();
            $form_request_array = json_decode(json_encode($form_request), true);

            $request_obj = LfpseSubmission::prepare_request($form_request_array);
            $request_json = json_encode($request_obj);

            // Storage::put('request.json', $request_json);

            // Optionally, dump the file path
            // dd(Storage::path('request.json'));
            // dd($request_json);
            // This should be submitted via Job. Taking longer time !

            $result = LfpseSubmission::submit_request($record, $request_json);
        } else {
            return back()->with('error', 'Data is already submitted');
        }
        if (is_bool($result)) {
            if ($result) {
                // Assuming `true` indicates a successful operation
                return back()->with('success_message', 'Data Submitted to NHS portal successfully');
            } else {
                // Assuming `false` indicates a failure
                return back()->with('error', 'Error occurred while submitting the form');
            }
        } elseif (method_exists($result, 'getStatusCode')) {
            // Check if result has a getStatusCode method and handle accordingly
            if ($result->getStatusCode() == 200) {
                return back()->with('success_message', 'Data Submitted to NHS portal successfully');
            } else {
                return back()->with('error', 'Error occurred while submitting the form');
            }
        } else {
            // Handle unexpected $result types
            return back()->with('error', 'Unexpected result type');
        }
    }
    public function submit_nhs_lfpse_bulk(Request $request)
    {
        $cases = $request->case_ids;
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $failed_cases = [];

        foreach ($cases as $case_id) {
            try {
                $case = $head_office->cases()->findOrFail($case_id);
                $record = $case->link_case_with_form;
                $existing = $record->LfpseSubmissions;

                if (count($existing) == 0) {
                    $form_request = $record->get_filled_form();
                    $form_request_array = json_decode(json_encode($form_request), true);

                    $request_obj = LfpseSubmission::prepare_request($form_request_array);
                    $request_json = json_encode($request_obj);
                    $result = LfpseSubmission::submit_request_bulk($record, $request_json);

                    if (!$result) {
                        $failed_cases[] = $case_id;
                    }
                }
            } catch (Exception $e) {
                $failed_cases[] = $case_id;
            }
        }

        if (!empty($failed_cases)) {
            return response()->json(
                [
                    'message' => 'Some cases failed to submit.',
                    'failed_case_ids' => $failed_cases,
                ],
                422,
            ); // Unprocessable Entity
        }

        return response()->json([
            'message' => 'Success!',
            'redirect_url' => route('case_manager.index'),
        ]);
    }

    public function link_cases(Request $request)
{
    $head_office = Auth::guard('web')->user()->selected_head_office;
    $all_linked = linked_cases::where('head_office_id', $head_office->id)->get();

    $user = Auth::guard('web')->user()->getHeadOfficeUser($head_office->user_id);
    if ($request->has('link_cases') && count($request->link_cases) != 0) {
        foreach ($request->link_cases as $case_id) {
            $case = HeadOfficeCase::find($case_id);
            if (isset($case)) {
                $already_linked = $all_linked
                    ->where('case_id_2', $case->id)
                    ->where('case_id_1', $request->case_id)
                    ->first();
                if (isset($already_linked)) {
                    continue;
                }
                $new_link_case = new linked_cases();
                $new_link_case->head_office_id = $head_office->id;
                $new_link_case->case_id_1 = $request->case_id;
                $new_link_case->case_id_2 = $case->id;
                $new_link_case->message = $request->message;
                $new_link_case->linked_manually = 1;
                if (isset($user)) {
                    $new_link_case->user_id = $user->user_id;
                }
                $new_link_case->save();

                $activity_log = new ActivityLog();
                $activity_log->type = 'Link Case';
                $activity_log->user_id = Auth::guard('web')->user()->id;
                $activity_log->head_office_id = Auth::guard('web')->user()->selected_head_office->id;
                $activity_log->action = 'Link Case' . '# ' . $case->id . (isset($new_link_case->message) ? "\nReason: " . $new_link_case->message : '');
                $activity_log->timestamp = now();
                $activity_log->save();

                $case_comment = new Comment();
                $case_comment->case_id = $request->case_id;
                $case_comment->type = 'Link Case' . ' ' . '#' . $case_id;
                $case_comment->comment = isset($new_link_case->message) ? 'Reason: ' . $new_link_case->message : '';
                $case_comment->user_id = Auth::guard('web')->user()->id;
                $case_comment->save();
            }
        }
    }
    return redirect()->back()->with('success', 'Cases linked successfully!');
}

    public function unlink_cases(Request $request)
{
    $head_office = Auth::guard('web')->user()->selected_head_office;
    $linked_case = linked_cases::where([
        ['case_id_1', '=', $request->case_id],
        ['case_id_2', '=', $request->other_case_id],
    ])
    ->orWhere([
        ['case_id_1', '=', $request->other_case_id],
        ['case_id_2', '=', $request->case_id],
    ])
    ->first();
    
    if (isset($linked_case)) {
        $linked_case->delete();
        
        $case = HeadOfficeCase::find($request->other_case_id);
        if (isset($case)) {
            $activity_log = new ActivityLog();
            $activity_log->type = 'Unlink Case';
            $activity_log->user_id = Auth::guard('web')->user()->id;
            $activity_log->head_office_id = Auth::guard('web')->user()->selected_head_office->id;
            $activity_log->action = 'Unlink case' . '# ' . $case->id . (isset($request->message) ? "\nReason: " . $request->message : '');
            $activity_log->timestamp = now();
            $activity_log->save();

            $case_comment = new Comment();
            $case_comment->case_id = $request->case_id;
            $case_comment->type = 'Unlink Case' .' '. '#' . $request->other_case_id;
            $case_comment->comment = isset($request->message) ? 'Reason: ' . $request->message : '';
            $case_comment->user_id = Auth::guard('web')->user()->id;
            $case_comment->save();
        }
        return redirect()->back()->with('success', 'Cases unlinked successfully!');
    }

    return redirect()->back()->with('error', 'No linked case found to unlink.');
}
}
