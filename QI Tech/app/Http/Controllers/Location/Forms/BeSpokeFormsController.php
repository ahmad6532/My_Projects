<?php

namespace App\Http\Controllers\Location\Forms;

use App\Helpers\Nhs_LFPSE\Contained;
use App\Helpers\Nhs_LFPSE\Extension;
use App\Helpers\Nhs_LFPSE\ExtensionInner;
use App\Helpers\Nhs_LFPSE\Lfpse_General_Helper;
use App\Helpers\Nhs_LFPSE\Location as Nhs_LFPSELocation;
use App\Helpers\Nhs_LFPSE\Recorder;
use App\Helpers\Nhs_LFPSE\Root;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Location\LocationController;
use App\Mail\FormEmail;
use App\Models\ActivityLog;
use App\Models\Address;
use App\Models\AssignedBespokeForm;
use App\Models\BeSpokeFormCategory;
use App\Models\CalenderEvent;
use App\Models\CaseContact;
use App\Models\CaseHandlerUser;
use App\Models\CaseInterestedParty;
use App\Models\CaseManagerCaseDocument;
use App\Models\CaseManagerCaseDocumentDocument;
use App\Models\CaseStage;
use App\Models\CaseStageTask;
use App\Models\CaseStageTaskAssign;
use App\Models\CaseStageTaskDocument;
use App\Models\ConnectedFormCard;
use App\Models\Contact;
use App\Models\ContactAddress;
use App\Models\ContactConnection;
use App\Models\deadlineCaseTask;
use App\Models\DefaultCard;
use App\Models\DefaultCaseStage;
use App\Models\DefaultCaseStageTask;
use App\Models\DefaultCaseStageTaskDocument;
use App\Models\DefaultField;
use App\Models\DefaultTask;
use App\Models\DefaultTaskDocument;
use App\Models\DMD;
use App\Models\dmd_vmp;
use App\Models\Document;
use App\Models\form_modification_logs;
use App\Models\Forms\LfpseSubmission;
use App\Models\FishBoneRootCauseAnalysis;
use App\Models\FishBoneRootCauseAnalysisAnswer;
use App\Models\FiveWhysRootCauseAnalysis;
use App\Models\FiveWhysRootCauseAnalysisAnswer;
use App\Models\FormRecordUpdate;
use App\Models\FormRecordUpdateDocument;
use App\Models\Forms\ActionCondition;
use App\Models\Forms\ActionEmail;
use App\Models\Forms\Form;
use App\Models\Forms\FormCard;
use App\Models\Forms\FormStage;
use App\Models\Forms\QuestionGroup;
use App\Models\Forms\Record;
use App\Models\be_spoke_form_record_drafts;
use App\Models\Forms\RecordData;
use App\Models\Forms\StageQuestion;
use App\Models\GdprFormField;
use App\Models\HeadOfficeLocation;
use App\Models\Headoffices\CaseManager\Comment;
use App\Models\Headoffices\CaseManager\HeadOfficeCase;
use App\Models\Headoffices\CaseManager\HeadOfficeLinkedCase;
use App\Models\Headoffices\Organisation\Group;
use App\Models\Headoffices\Organisation\LocationGroup;
use App\Models\LfpseOption;
use App\Models\linked_cases;
use App\Models\Location;
use App\Models\matching_contacts;
use App\Models\near_miss_manager;
use App\Models\NearMiss;
use App\Models\new_contacts;
use App\Models\new_contacts_relations;
use App\Models\RecordDataEditedHistory;
use App\Models\reminders;
use App\Models\RootCauseAnalysis;
use App\Models\task_deadline_records;
use App\Models\temp_forms;
use App\Models\contact_to_case;
use App\Models\temp_task_forms;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Session;
use Str;

class BeSpokeFormsController extends Controller
{

    public $test = false;
    public $ids = [];

    public $record_datas;
    public $rdts;

    public function form_test(){
        return view('form_creator');
    }
    public function form_nhs(){
        return view('nhs_form');
    }
    public function form_submit(){
        return view('form_submit');
    }
    public function index()
    {
        // get this location's bespoke forms only ! update db accordingly !
        $forms = Form::all();
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        $headOfficeUser = $user->getHeadOfficeUser();
        $profile = $headOfficeUser->get_permissions();
        // dd($user);
        //$beSpokeForms = $forms->form_owner();


           
        $beSpokeForms = $user->selected_head_office->be_spoke_forms()->where([['is_deleted', 1], ['deleted_at', '<', Carbon::now()->subMonth(1)]])->delete();
        $head_office = $user->selected_head_office;
        $allGroups = Group::where('head_office_id', $head_office->id)->where('parent_id', null)->get();
        Lfpse_General_Helper::create_form_if_not_exists($head_office);

        $beSpokeForms = $user->selected_head_office->be_spoke_forms;
        foreach($beSpokeForms as $form){
            if ($form->is_deleted) {
                if ($form->deleted_at) {
                    $deletedAt = Carbon::parse($form->deleted_at);
                    if ($deletedAt->diffInDays(Carbon::now()) >= 30) {
                        $form->soft_deleted = 1;
                        $form->is_deleted = 0;  
                        $form->deleted_at = now(); 
                        $form->is_active = false; 
                        $form->is_archived = 0;   
                        $form->save();
                    }
                }
            }
        }
        $near_miss = $user->selected_head_office->near_miss;
        if(!isset($near_miss)){
            $near_miss = new near_miss_manager();
            $near_miss->head_office_id = $user->selected_head_office->id;
            $near_miss->name = 'Near Miss';
            $near_miss->save();
        }
        return view('head_office.be_spoke_forms.index', compact('beSpokeForms', 'head_office','near_miss','allGroups','profile'));
    }

    /**
     * Combines SQL and its bindings
     *
     * @param \Eloquent $query
     * @return string
     */
    public static function getEloquentSqlWithBindings($query)
    {
        return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
    }


    public function records(Request $request, $form_id = null)
    {
        $location = Auth::guard('location')->user();
        $counter = 0;        
        if ($request->query('ajax')) {
            $counter = (int) $request->query('count');
        }
        if ($location) {
            // For Location //
            if (!$location->userCanUpdateSettings()) {
                $near_misses = NearMiss::where('location_id', $location->id)->orderBy('created_at','asc')->get();
                $records = $location->records()->where('hide', 0)->whereNull('record_id')->orderBy('created_at', 'asc')->get();
                $records = $records->concat($near_misses)->sortBy('created_at');
                // This will give location forms for sure !
                if (!$records) {
                    return redirect()->back()->with('error', 'Invalid data submitted.');
                }

                // $records = $form->records();

                if (request()->query('status') && request()->query('status') != 'all' && request()->query('status') != 'near_miss' ) {
                    $records = $location->records()->where('hide', 0)->where('record_id',null)->where('form_id', request()->query('status')); // This will give location forms for sure !
                    if (!$records->count()) {
                        return redirect()->back()->with('error', 'Invalid data submitted.');
                    }
                }
                if ($request->has('ad_search')) {
                    $near_misses = NearMiss::where('location_id', $location->id)->where(function ($query) use ($request) {
                        $columns = [
                            'error_by',
                            'point_of_detection',
                            'error_detected_by_other',
                            'what_was_error',
                            'error',
                            'prescription_missing_signature_cause_other_field',
                            'prescription_tampered_cause_other_field',
                            'prescription_old_treatment_cause_other_field',
                            'deleted_by',
                        ];

                        foreach ($columns as $column) {
                            $query->orWhereRaw("LOWER($column) LIKE ?", ["%$request->ad_search%"]);
                        }
                    })->orderByDesc('created_at')->get();
                    $records = $location->records()->where('hide', 0)->where('record_id',null)->orderByDesc('created_at')->get();
                    $questions = StageQuestion::where('question_name', 'like', 'Name')->get();
                    $recordData = RecordData::whereIn('question_id', $questions->pluck('id')->toArray())->whereIn('record_id', $records->pluck('id')->toArray())->where('question_value', 'like', '%' . $request->ad_search . '%')
                        ->orderByDesc('created_at')
                        ->get();

                    $records = $records->whereIn('id', $recordData->pluck("record_id")->toArray());

                    $records = $records->concat($near_misses)->sortBy('created_at');

                }

                if ($request->has('ad_search') && $request->has('input_values')) {
                    $inputValuesArray = json_decode($request->input('input_values'));

                    if (!empty($inputValuesArray->near_miss) && in_array('parent_near_miss_chk', $inputValuesArray->near_miss)) {
                        if(count($inputValuesArray->near_miss) == 1 && in_array('parent_near_miss_chk', $inputValuesArray->near_miss)){
                            $near_misses = NearMiss::where('location_id', $location->id)->orderByDesc('created_at')->get();
                        }else{
                            $near_misses = NearMiss::where('location_id', $location->id)->where(function ($query) use ($inputValuesArray, $request) {
                                $columns = $inputValuesArray->near_miss = array_filter($inputValuesArray->near_miss, function($value) {
                                    return $value !== 'parent_near_miss_chk';
                                });
                                foreach ($columns as $column) {
                                    $query->orWhereRaw("LOWER($column) LIKE ?", ["%$request->ad_search%"]);
                                }
                            })->orderByDesc('created_at')->get();
                        }
                    } else{
                        $records = $location->records()->where('hide', 0)->where('record_id',null)->orderByDesc('created_at')->get();
                        $near_misses = collect();
                    }

                    if($request->has('type_of_error_multi') && count($request->type_of_error_multi) !=0){
                        $near_misses = $near_misses->filter(function ($item) use ($request) {
                            foreach ($request->type_of_error_multi as $column) {
                                if (!$item->$column) {
                                    return false;
                                }
                            }
                            return true;
                        });
                    }

                    if($request->has('contribution_chk_multi') && count($request->contribution_chk_multi) !=0){
                        $near_misses = $near_misses->filter(function ($item) use ($request) {
                            foreach ($request->contribution_chk_multi as $column) {
                                if (!$item->$column) {
                                    return false;
                                }
                            }
                            return true;
                        });
                    }

                    if($request->has('point_of_detection_multi') && count($request->point_of_detection_multi) !=0){
                        $near_misses = $near_misses->filter(function ($item) use ($request) {
                            return isset($item->point_of_detection) && in_array($item->point_of_detection, $request->point_of_detection_multi);
                        });
                    }
                    
                    if (!empty($inputValuesArray->incident) && in_array('parent_incident_chk', $inputValuesArray->incident)) {
                        $records = $location->records()->where('hide', 0)->where('record_id',null)->orderByDesc('created_at')->get();
                        $questions = StageQuestion::where(function ($query) use ($inputValuesArray) {

                            $incidents = array_filter($inputValuesArray->incident, function($value) {
                                return $value !== 'parent_incident_chk';
                            });
                            foreach ($incidents as $incident) {
                                $query->orWhere('question_name', 'like', $incident);
                            }
                        })->get();
                        $recordData = RecordData::whereIn('question_id', $questions->pluck('id')->toArray())->whereIn('record_id', $records->pluck('id')->toArray())->where('question_value', 'like', '%' . $request->ad_search . '%')
                            ->orderByDesc('created_at')
                            ->get();
                        $records = $records->whereIn('id', $recordData->pluck("record_id")->toArray());
                        $records = $records->concat($near_misses)->sortBy('created_at');
                    }else{
                        if(count($inputValuesArray->near_miss) == 1 && in_array('parent_near_miss_chk', $inputValuesArray->near_miss)){
                            $records = collect();
                            $records = $records->concat($near_misses)->sortBy('created_at');
                        }else{
                            $records = $near_misses;
                        }
                    }



                    if(isset($request->start_date)){
                        $start_date = carbon::parse($request->start_date);
                        $records = $records->where('created_at','>=',$start_date);
                    }
                    if(isset($request->end_date)){
                        $end_date = carbon::parse($request->end_date);
                        $records = $records->where('created_at','<=',$end_date);
                    }


                }


                if ($request->query('ajax')) {

                    $records = $records->skip($counter);
                    $records = $records->take(10);
                    $records = $records->groupBy('date');
                    $realCount = count($records);
                    if ($realCount == 0) {
                        return 'exit';
                    }

                    if (request()->query('format') == 'table') {
                        return view('location.be_spoke_forms.record_data')->with(compact('location', 'counter', 'records'));
                    } else {
                        return view('location.be_spoke_forms.record_data')->with(compact('location', 'counter', 'records'));
                    }

                }

                $forms = [];
        $form_data =[];
        if($location->organization_setting_assignment)
        {
            foreach($location->group_forms() as $form)
            {
                if($form->is_active && ($form->expiry_state == 'never_expire' || $form->expiry_time > now())){
                    $forms[] = $form;
                    if($form->schedule_state == 'day'){
                        $form_data['by_day'][] = [
                            'form_id' => $form->id,
                            'times' => json_decode($form->schedule_by_day,true),
                            'updated_at' => $form->updated_at
                        ];
                    }elseif($form->schedule_state == 'date'){
                        $form_data['by_date'][] = CalenderEvent::where('form_id',$form->id)->get();
                    }
                }
            }
            $forms = collect($forms);
        }
        $misseds = LocationController::checkMissedReminders($form_data);
        foreach($misseds as $miss){
            $day = $miss['day'];
            $time = $miss['time'];
            $formId = $miss['form_id'];

            // Get the current date
            $today = Carbon::now()->toDateString();

            // Check if a similar record exists in the database for today
            if($miss['type'] == 'by_date'){
                $existingRecord = reminders::where('day', $day)
                ->where('form_id', $formId)
                ->whereDate('created_at', $today)
                ->where(function ($query) use ($time) {
                    $query->where('time', '!=', $time)
                        ->orWhereNull('time');
                })
                ->first();
            }else{
                $existingRecord = reminders::where('day', $day)
                    ->where('time', $time)
                    ->where('form_id', $formId)
                    ->whereDate('created_at', $today)
                    ->first();
            }
                if (!$existingRecord) {
                    $reminder = new reminders();
                    $reminder->type = $miss['type'];
                    $reminder->day = $day;
                    $reminder->location_id = $location->id;
                    $reminder->time = $time;
                    $reminder->form_id = $formId;
                    $reminder->save();
                }
        }
        $reminders = reminders::where('location_id',$location->id)->get();

                
                if ($records) {
                    $groupedRecords = $records->sortByDesc('created_at')
                        ->groupBy(function($item) {
                            return $item->created_at->format('Y-m-d'); // Adjust format if necessary
                        });
                    $records = $groupedRecords;
                }
                if (request()->query('status') == 'near_miss') {
                    $records = NearMiss::where('location_id', $location->id)->orderByDesc('created_at')->get();
                    $records = $records->groupBy('date');
                }
                // dd($records);
                return view('location.be_spoke_forms.records', compact('location', 'counter', 'records','reminders'));
            } else {
                $records = $location->records;
                $realCount = count($records);
                if ($request->query('ajax')) {

                    $records = $location->records()->skip($counter);
                    $records = $location->records()->take(10);
                    $records = $records->groupBy('created_at');
                    if ($realCount == 0) {
                        return 'exit';
                    }
                    if (request()->query('format') == 'table') {
                        return view('location.be_spoke_forms.record_data')->with(compact('location', 'counter', 'records','reminders'));
                    } else {
                        return view('location.be_spoke_forms.record_data')->with(compact('location', 'counter', 'records','reminders'));
                    }

                }
            }

            return view('location.be_spoke_forms.records', compact('location', 'records'));
        } else { // For Head Office
            $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
            $form = $user->selected_head_office->be_spoke_forms()->find($form_id); // This will give head office forms for sure !
            if (!$form) {
                return redirect()->back()->with('error', 'Invalid data submitted.');
            }
            $records = $form->records()->where('record_id',null);
            if (!$records) {
                return redirect()->back()->with('error', 'Invalid data submitted.');
            }
            if (request()->query('start_date') && request()->query('end_date')) {
                $records = $records->whereBetween('created_at', [carbon::createFromFormat('d/m/Y', request()->query('start_date')), carbon::createFromFormat('d/m/Y', request()->query('end_date'))]);
            } elseif (request()->query('end_date')) {
                $records = $records->where('created_at', '<=', request()->query('end_date'));
            } elseif (request()->query('')) {
                $records = $records->where('created_at', '>=', request()->query('end_date'));
            }
            if (request()->query('search')) {
                $search = $request->query('search');
                $records = $records->whereHas('data', function ($query) use ($search) {
                    $query->where('question_value', 'LIKE', '%' . $search . '%');
                });
            }
            if ($request->query('ajax')) {

                $records = $records->skip($counter);
                $records = $records->take(10)->get();
                $records = $records->groupBy('date');
                $realCount = count($records);
                if ($realCount == 0) {
                    return 'exit';
                }

                if (request()->query('format') == 'table') {
                    return view('head_office.be_spoke_forms.record_data')->with(compact('location', 'counter', 'records'));
                } else {
                    return view('head_office.be_spoke_forms.record_data')->with(compact('location', 'counter', 'records'));
                }

            }

            if ($records) {
                //dd($records->get());
                $records = $records->take(10)->get();
                $records = $records->groupBy('date');
            }
            return view('head_office.be_spoke_forms.records', compact('records', 'form'));
        }

    }

    public function record_detail_view($id)
    {
        $location = Auth::guard('location')->user();
        $record = $location->records->find($id);

        if(!isset($record)){
            abort('404');
        }

        return view('location.be_spoke_forms.records_details_view', compact('record', 'location'));
    }

    public function preview($id, $record_id = null) 
    {
        $user = Auth::guard('location')->user();
        
        if ($user) { // For Location //
            if ($user->userCanUpdateSettings()) {
                $form = $user->be_spoke_forms()->findOrFail($id);
                return view('location.be_spoke_forms.preview', compact('form'));
            }
            $form = $user->organization_setting_assignment->organization_setting->organisationSettingBespokeForms()->where('be_spoke_form_id', $id)->first();
            if ($form && $form->form) {
                $form = $form->form;
                $record = null;
                if ($record_id) {
                    $record = $form->records()->find($record_id);
                    if ($record->created_at < Carbon::now()->sub(config('app.incident_edit_capability_time_out'))) {
                        return redirect()->route('be_spoke_forms.be_spoke_form.records', $record->form_id)->with('error', 'You can not edit this inicident after ' . config('app.incident_edit_capability_time_out'));
                    }

                }
                return view('location.be_spoke_forms.preview', compact('form', 'record'));
            }
            return redirect()->route('be_spoke_forms.be_spoke_form.index')->with('error', "You don't have access to this page");
        } else { // For Head Office
            $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
            $form = $user->selected_head_office->be_spoke_forms()->find($id); // This will give head office forms for sure !
            return view('head_office.be_spoke_forms.preview', compact('form'));
        }

    }

    public function recordPreview(Request $request, $record_id)
    {
        $record = Record::find($record_id);
        if (!$record) {
            return redirect()->back()->with('error', 'Invalid data submitted.');
        }
        $form = $record->form;
        return view('location.be_spoke_forms.preview-record', compact('record', 'form'));
    }
    public function archive($id)
    {
        // $user = Auth::guard('location')->user();
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        $bespoke = $user->selected_head_office->be_spoke_forms()->find($id); // This will give head office forms for sure !
        if ($bespoke) {
            $bespoke->is_archived = 1 - $bespoke->is_archived;
            if ($bespoke->is_archived) {
                $message = "Form is Archived.";
                $bespoke->is_active = false;
            } else {
                $message = "Form is Unarchived.";
            }
            $bespoke->save();
            return redirect()->back()->with(['success' => $message]);
        }
        return redirect()->back()->with(['error' => 'Form not found']);
    }
    public function active(Request $request,$id)
    {
        if (!$request->has('_token') && $request->_token != csrf_token()) {
            return back()->with('error','Invalid data submitted.');
        }
        // $user = Auth::guard('location')->user();

        // if ($user) { // For Location //
        //     if (!$user->userCanUpdateSettings()) {
        //         return redirect()->back()->with('error', "you don't have access to update the form");
        //     }
        //     $form = $user->be_spoke_forms()->find($id); // This will give location forms for sure !

        //     $form->is_active = 1 - $form->is_active;
        //     if ($form->is_active) {
        //         $message = "Form is Activated.";
        //     } else {
        //         $message = "Form is Deactivated.";
        //     }
        //     $form->save();
        //     return redirect()->route('be_spoke_forms.be_spoke_form.index', ['success' => $message]);
        // } else { // For Head Office
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        $bespoke = $user->selected_head_office->be_spoke_forms()->find($id); // This will give head office forms for sure !
        if ($bespoke) {
            if(!isset($bespoke->be_spoke_form_category_id)){
                return redirect()->back()->with(['error' => 'Assign category first to proceed.']);
            }
            $bespoke->is_active = 1 - $bespoke->is_active;
            if ($bespoke->is_active) {
                $message = "Form is Activated.";
                $bespoke->is_archived = false;
            } else {
                $message = "Form is Deactivated.";
            }
            $bespoke->save();
            return redirect()->back()->with(['success' => $message]);
        }
        return redirect()->back()->with(['error' => 'Form not found']);
        // }

    }


    /*
     *   New template of form starts from here.
     */

    public function form_view($id)
    {
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        $form = $user->selected_head_office->be_spoke_forms()->find($id);
        $cards = DefaultCard::all();
        $head_office = $user->selected_head_office;
        return view('head_office.be_spoke_forms.form_view', compact('cards', 'head_office','user'))->with('form', $form)->with('id', $id);
    }
    public function formTemplate(Request $request, $id = null)
    {   
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        $head_office = $user->selected_head_office;
        if (!isset($head_office)) {
            
            return redirect()->route('be_spoke_forms.be_spoke_form.index')->with('error', 'You do not have access to this page');
        }

        $form = $user->selected_head_office->be_spoke_forms()->find($id);
        $cards = DefaultCard::all();
        $head_office = $user->selected_head_office;
        $ho_user = $user->getHeadOfficeUser($head_office->id);
        $form_json = empty($form->form_json) ? null : json_decode($form->form_json,true);
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

        
        if(isset($form)){
            $url = 'https://' . str_replace(' ', '_', $head_office->company_name) . '.qi-tech.co.uk/external/' . $form->external_link;
        }else{
            $url = '';
        }
        $default_category = BeSpokeFormCategory::where('reference_id', $head_office->id)->first();
        // if(!isset($default_category)){
        //     $default_category = new BeSpokeFormCategory();
        //     $default_category->reference_id = $head_office->id;
        //     $default_category->reference_type = 'head_office';
        //     $default_category->name = 'New Category';
        //     $default_category->save();
        // }
        $form_stage = optional($form)->default_stages;
            if(isset($form_stage) && $form_stage->count() == 0){
                $newStage =new DefaultCaseStage();
            $newStage->be_spoke_form_id = $form->id;
            $newStage->name = 'Enter Stage Name';
            $newStage->save();
            }
        return view('head_office.be_spoke_forms.form_template', compact('default_category','cards', 'head_office','ho_user','form_json','url','incident_date_items'))->with('form', $form)->with('id', $id);

    }

    public function stageUsers($stage_id)
    {
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        $user = $user ?? Auth::guard('location')->user();
        if (!$user) {
            return response()->json(['error' => 'User not logged in'], 401);
        }
        $stage = DefaultCaseStage::find($stage_id);
        $form =  $stage->form;
        $data =  isset($stage->stage_rules) ? json_decode($stage->stage_rules, true) : null;
        $startedData = isset($data['started']) && !empty($data['started']) ? $data['started'] : null;
        $completedData = isset($data['completed']) && !empty($data['completed']) ? $data['completed'] : null;

        $matchingRecords = [];

        if (isset($startedData)) {
            foreach ($startedData as $singleRule) {
                if ($singleRule['condition_type'] == 1) {
                    
                    if(isset($completedData)){
                        $filteredArray = array_filter($completedData, function($item) {
                            return isset($item['condition_type']) && $item['condition_type'] == 1;
                        });
                        
                    }
                    $unique_profiles = [];
                    if(isset($filteredArray) && !empty($filteredArray)){
                    foreach ($filteredArray as $item) {
                        $unique_profiles = array_merge($unique_profiles, array_diff( $singleRule['user_profiles'],$item['user_profiles']));
                    }
                    
                    $unique_profiles = array_unique($unique_profiles);
                }else{
                    $unique_profiles = $singleRule['user_profiles'];
                }
                    // Retrieve matching records for condition type 1
                    $matchedProfiles = $form->form_owner
                        ->head_office_user_profiles()
                        ->whereIn(
                            'id',
                            $unique_profiles,
                        )
                        ->get();

                    foreach ($matchedProfiles as $profile) {
                        foreach (
                            $profile->user_profile_assign as $assign
                        ) {
                            foreach (
                                $assign->head_office_user->get() as $user
                            ) {
                                $matchingRecords[] = [
                                    'condition_type' =>
                                    $singleRule[
                                        'condition_type'
                                    ],
                                    'data' => $user->user,
                                    'logo' => $user->user->logo
                                ];
                            }
                        }
                    }
                } elseif ($singleRule['condition_type'] == 2) {
                    if(isset($completedData)){
                        $filteredArrayUsers = array_filter($completedData, function($item) {
                            return isset($item['condition_type']) && $item['condition_type'] == 2;
                        });
                    }
                        
                    $unique_users = [];
                    if(isset($filteredArrayUsers) && !empty($filteredArrayUsers)){
                    foreach ($filteredArrayUsers as $item) {
                        $unique_users = array_merge($unique_users, array_diff( $singleRule['users'],$item['users']));
                    }
                    
                    $unique_users = array_unique($unique_users);
                }else{
                    $unique_users = $singleRule['users'];
                }
                    
                    // Retrieve matching users for condition type 2
                    $matchedUsers = $form
                        ->usersToDisplay()
                        ->whereIn('id', $unique_users);

                    foreach ($matchedUsers as $user) {
                        $matchingRecords[] = [
                            'condition_type' =>
                            $singleRule['condition_type'],
                            'data' => $user,
                            'logo' => $user->logo
                        ];
                    }
                } elseif (
                    $singleRule['condition_type'] == 3 &&
                    $singleRule['email_user_type'] == 2
                ) {
                    // Retrieve matching records for condition type 3 and email_user_type 2
                    $matchedProfiles = $form->form_owner
                        ->head_office_user_profiles()
                        ->whereIn(
                            'id',
                            $singleRule['user_profiles'],
                        )
                        ->get();

                    foreach ($matchedProfiles as $profile) {
                        foreach (
                            $profile->user_profile_assign as $assign
                        ) {
                            foreach (
                                $assign->head_office_user->get() as $user
                            ) {
                                $matchingRecords[] = [
                                    'condition_type' =>
                                    $singleRule[
                                        'condition_type'
                                    ],
                                    'data' => $user->user,
                                    'logo' => $user->user->logo,
                                    'pro' => true
                                ];
                            }
                        }
                    }
                } elseif (
                    $singleRule['condition_type'] == 3 &&
                    $singleRule['email_user_type'] == 1
                ) {
                    // Retrieve matching users for condition type 3 and email_user_type 1
                    $matchedUsers = $form
                        ->usersToDisplay()
                        ->whereIn('id', $singleRule['users']);

                    foreach ($matchedUsers as $user) {
                        $matchingRecords[] = [
                            'condition_type' =>
                            $singleRule['condition_type'],
                            'data' => $user,
                            'logo' => $user->logo
                        ];
                    }
                }
            }
        }

        usort($matchingRecords, function ($a, $b) {
            return $a['condition_type'] <=> $b['condition_type'];
        });

        return response()->json(['success' => $matchingRecords], 200);
    }



    // Function to edit event in Bespoke Form 😭😭😭😭😭
    public function updateEvent(Request $request, $id) {
        $event = CalenderEvent::findOrFail($id); // Use the appropriate model for your events
        $event->times = json_encode($request->input('times'));
        $event->repeat_state = $request->input('repeat_state');
        $event->save();
    
        return response()->json(['success' => true]);
    }
    
    





    public function formEventDelete($id){
        $calender_event = CalenderEvent::find($id);
        if(isset($calender_event)){
            $calender_event->delete();
            return redirect()->back()->with('success','record deleted!');
        }else{
            return redirect()->back()->with('error','record not found!');
        }
    }

    public function formTemplateDuplicate($id){
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $form = $head_office->be_spoke_forms()->find($id);
        if(!isset($form)){
            return redirect()->back()->with('error','Form not found!');
        }

        try {
            DB::beginTransaction();
    
            $baseName = $form->name . ' Copy';

            // Check for existing copies with the same base name
            $existingCopies = $head_office->be_spoke_forms()
                ->where('name', 'LIKE', "$baseName%")
                ->get();

            // Extract existing numbering from similar names
            $numbers = $existingCopies->map(function ($existingForm) use ($baseName) {
                $suffix = trim(str_replace($baseName, '', $existingForm->name));
                return is_numeric($suffix) ? (int)$suffix : 0;
            });

            // Determine the next available number
            $nextNumber = $numbers->isEmpty() ? 1 : $numbers->max() + 1;

            // Assign a unique name
            $new_form = $form->replicate();
            $new_form->name = "$baseName $nextNumber";
    
            // Save the replicated form
            $new_form->is_draft = true;
            $new_form->save();
    
            // Duplicate Calender Events 
            if(isset($form->calenderEvent)){
                foreach($form->calenderEvent as $event){
                    $new_event = $event->replicate();
                    $new_event->form_id = $new_form->id;
                    $new_event->save();
                }
            }

            // Duplicate Default Stages

            if(isset($form->default_stages)){
                foreach($form->default_stages as $stage){
                    $new_stage = $stage->replicate();
                    $new_stage->be_spoke_form_id = $new_form->id;
                    $new_stage->save();

                    // Duplicate Tasks
                    if(isset($stage->default_tasks)){
                        foreach($stage->default_tasks as $task){
                            $new_task = $task->replicate();
                            $new_task->default_case_stage_id = $new_stage->id;
                            $new_task->save();

                            // Duplicate Task Documents
                            if(isset($task->documents)){
                                foreach($task->documents as $document){
                                    $new_document = $document->replicate();
                                    $new_document->case_stage_task_id = $new_task->id;
                                    $new_document->save();
                                }
                            }

                            // Duplicate Dead line records
                            if(isset($task->deadline_records)){
                                foreach($task->deadline_records as $deadline_record){
                                    $new_deadline_record = $deadline_record->replicate();
                                    $new_deadline_record->default_case_stage_tasks_id = $new_task->id;
                                    $new_deadline_record->save();
                                }
                                
                            }
                        }

                    }
                }
            }
            
            // Duplicate Default Documents
            if(isset($form->defaultDocuments)){
                foreach($form->defaultDocuments as $def_document){
                    $new_def_document = $def_document->replicate();
                    $new_def_document->be_spoke_form_id = $new_form->id;
                    $new_def_document->save();

                    if(isset($def_document->documents)){
                        foreach($def_document->documents as $document_assign){
                            $new_def_doc_assign = $document_assign->replicate();
                            $new_def_doc_assign->default_document_id = $new_def_document->id;
                            $new_def_doc_assign->save();
                        }
                    }
                }
            }

            // Duplicate Default Links
            if(isset($form->defaultLinks)){
                foreach($form->defaultLinks as $def_link){
                    $new_link = $def_link->replicate();
                    $new_link->form_id = $new_form->id;
                    $new_link->save();
                }
            }
            // Duplicate Shared_case Approved Emails
            if(isset($form->shared_case_approved_emails)){
                foreach($form->shared_case_approved_emails as $approved_email){
                    $new_approved_email = $approved_email->replicate();
                    $new_approved_email->be_spoke_form_id = $new_form->id;
                    $new_approved_email->save();
                }
            }

            // Duplicate investigators
            if(isset($form->form_settings)){
                foreach($form->form_settings as $def_settings){
                    $new_settings = $def_settings->replicate();
                    $new_settings->be_spoke_form_id = $new_form->id;
                    $new_settings->save();
                }
            }


            DB::commit();
            return redirect()->route('head_office.be_spoke_forms_templates.form_template', $new_form->id)->with('success', 'Form duplicated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while duplicating the form: ' . $e->getMessage());
        }


    }

    public function formTemplateDuplicateBulk(Request $request){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $logged_ho_u = Auth::guard('web')->user()->getHeadOfficeUser();
        $headOfficeFormIds = $headOffice->be_spoke_forms->pluck('id')->toArray();
        
        $selectedForms = explode(',', $request->input('form_ids'));
    
        $request->merge(['form_ids' => $selectedForms]);
    
        // Validate the request
        $request->validate([
            'form_ids' => [
                'required',
                'array',
                function ($attribute, $value, $fail) use ($headOfficeFormIds) {
                    foreach ($value as $userId) {
                        if (!in_array($userId, $headOfficeFormIds)) {
                        return back()->with('error', 'Unknown Form!');
                        }
                    }
                },
            ],
            'form_ids.*' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            foreach ($selectedForms as $form_id){
                $form = $headOffice->be_spoke_forms()->find($form_id);
                if(!isset($form)){
                    continue;
                }

                $baseName = $form->name . ' Copy';

            // Check for existing copies with the same base name
            $existingCopies = $headOffice->be_spoke_forms()
                ->where('name', 'LIKE', "$baseName%")
                ->get();

            // Extract existing numbering from similar names
            $numbers = $existingCopies->map(function ($existingForm) use ($baseName) {
                $suffix = trim(str_replace($baseName, '', $existingForm->name));
                return is_numeric($suffix) ? (int)$suffix : 0;
            });

            // Determine the next available number
            $nextNumber = $numbers->isEmpty() ? 1 : $numbers->max() + 1;

            // Assign a unique name
            $new_form = $form->replicate();
            $new_form->name = "$baseName $nextNumber";
    
            // Save the replicated form
            $new_form->is_draft = true;
            $new_form->save();
    
            // Duplicate Calender Events 
            if(isset($form->calenderEvent)){
                foreach($form->calenderEvent as $event){
                    $new_event = $event->replicate();
                    $new_event->form_id = $new_form->id;
                    $new_event->save();
                }
            }

            // Duplicate Default Stages

            if(isset($form->default_stages)){
                foreach($form->default_stages as $stage){
                    $new_stage = $stage->replicate();
                    $new_stage->be_spoke_form_id = $new_form->id;
                    $new_stage->save();

                    // Duplicate Tasks
                    if(isset($stage->default_tasks)){
                        foreach($stage->default_tasks as $task){
                            $new_task = $task->replicate();
                            $new_task->default_case_stage_id = $new_stage->id;
                            $new_task->save();

                            // Duplicate Task Documents
                            if(isset($task->documents)){
                                foreach($task->documents as $document){
                                    $new_document = $document->replicate();
                                    $new_document->case_stage_task_id = $new_task->id;
                                    $new_document->save();
                                }
                            }

                            // Duplicate Dead line records
                            if(isset($task->deadline_records)){
                                foreach($task->deadline_records as $deadline_record){
                                    $new_deadline_record = $deadline_record->replicate();
                                    $new_deadline_record->default_case_stage_tasks_id = $new_task->id;
                                    $new_deadline_record->save();
                                }
                                
                            }
                        }

                    }
                }
            }
            
            // Duplicate Default Documents
            if(isset($form->defaultDocuments)){
                foreach($form->defaultDocuments as $def_document){
                    $new_def_document = $def_document->replicate();
                    $new_def_document->be_spoke_form_id = $new_form->id;
                    $new_def_document->save();

                    if(isset($def_document->documents)){
                        foreach($def_document->documents as $document_assign){
                            $new_def_doc_assign = $document_assign->replicate();
                            $new_def_doc_assign->default_document_id = $new_def_document->id;
                            $new_def_doc_assign->save();
                        }
                    }
                }
            }

            // Duplicate Default Links
            if(isset($form->defaultLinks)){
                foreach($form->defaultLinks as $def_link){
                    $new_link = $def_link->replicate();
                    $new_link->form_id = $new_form->id;
                    $new_link->save();
                }
            }
            // Duplicate Shared_case Approved Emails
            if(isset($form->shared_case_approved_emails)){
                foreach($form->shared_case_approved_emails as $approved_email){
                    $new_approved_email = $approved_email->replicate();
                    $new_approved_email->be_spoke_form_id = $new_form->id;
                    $new_approved_email->save();
                }
            }

            // Duplicate investigators
            if(isset($form->form_settings)){
                foreach($form->form_settings as $def_settings){
                    $new_settings = $def_settings->replicate();
                    $new_settings->be_spoke_form_id = $new_form->id;
                    $new_settings->save();
                }
            }


            }
            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success','Forms duplicated successfully!');

    }
    public function formTemplateSave(Request $request, $id = null)
    {
        $form = null;
        $u = Auth::guard('web')->user()  ?? Auth::guard('user')->user();
        $ho = $u->selected_head_office;
            $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
            $form = $user->selected_head_office->be_spoke_forms()->find($id); // This will give head office forms for sure !
        // There should be a condition like only this logged in locatino/head office's bespoke forms should only be findable.
        if (!$form) {

            // $check = Form::where([['name', $request->form_name], ['reference_type', Auth::guard('location')->user() ? 'location' : 'head_office'], ['reference_id', $user->id]])->first();
            // if ($check) {
            //     return redirect()->back()->with('error', "you have already a from with same name and cannot create new one with same name");
            // }
            // Leaving it for now. assume that a head office can create multiple forms with same name !
            $form = new Form();
            $form->created_by_id = $u->getHeadOfficeUser($ho->id)->id;
            
        }

        if (empty($request->form_name)) {
            return redirect()->back()->with('error', 'Invalid data submitted.');
        }
        $form->purpose = $request->form_purpose;
        if (isset($request->allow_editing) && isset($request->allow_editing_time_always)) {
            $form->allow_editing_state = 'always';
        } elseif (!isset($request->allow_editing)) {
            $form->allow_editing_state = 'disable';
        } else {
            if($request->allow_editing_select == 1){
                $form->allow_editing_state = 'minutes';
            }else if($request->allow_editing_select == 2){
                $form->allow_editing_state = 'hour';
            }else if ($request->allow_editing_select == 3){
                $form->allow_editing_state = 'day';
            }
            else if ($request->allow_editing_select == 4){
                $form->allow_editing_state = 'month';
            }
        }
        if (isset($request->provide_update) && isset($request->allow_update_time_always)) {
            $form->allow_update_state = 'always';
        } elseif (isset($request->allow_update) && !isset($request->allow_update_time_open)) {
            $form->allow_update_state = 'disable';
        }elseif(isset($request->allow_update_time_open)){
            $form->allow_update_state = 'open';
        }
         else {
            if($request->allow_update_select == 1){
                $form->allow_update_state = 'minutes';
            }else if($request->allow_update_select == 2){
                $form->allow_update_state = 'hour';
            }else if ($request->allow_update_select == 3){
                $form->allow_update_state = 'day';
            }
            else if ($request->allow_update_select == 4){
                $form->allow_update_state = 'month';
            }
        }
        $form->allow_editing_time = isset($request->allow_editing_time) ? $request->allow_editing_time : null;
        $form->allow_update_time = isset($request->allow_update_time) ? $request->allow_update_time : null;
        $form->limits = $request->limits ?? 0;
        $form->active_limit_by_amount = $request->active_limit_by_amount == 'on' ? true : false;
        $form->allow_responder_update = $request->provide_update == 'on' ? true : false;
        $form->amount_total_max_res = $request->amount_total_max_res == 'on' ? true : false;
        $form->limit_to_one_user = $request->limit_to_one_user == 'on' ? true : false;
        $form->limit_to_one_location = $request->limit_to_one_location == 'on' ? true : false;
        $form->active_limit_by_period = $request->active_limit_by_period == 'on' ? true : false;
        if (!isset($request->limit_by_period_max_check) && $request->limit_by_period_max_check != 'on') {
            $form->limit_by_period_max_state = 'off';
        } else if ($request->limit_by_period_max_select == 1) {
            $form->limit_by_period_max_state = 'day';
        } else if ($request->limit_by_period_max_select == 2) {
            $form->limit_by_period_max_state = 'week';
        } else {
            $form->limit_by_period_max_state = 'month';
        }
        $form->limit_by_period_max_value = $request->limit_by_period_max_value;
        $form->limit_by_per_user_value = $request->limit_by_per_user_value;
        $form->limit_by_per_location_value = $request->limit_by_per_location_value;
        if (!isset($request->limit_by_period_min_check) && $request->limit_by_period_min_check != 'on') {
            $form->limit_by_period_min_state = 'off';
        } else if ($request->limit_by_period_min_select == 1) {
            $form->limit_by_period_min_state = 'day';
        } else if ($request->limit_by_period_min_select == 2) {
            $form->limit_by_period_min_state = 'week';
        } else {
            $form->limit_by_period_min_state = 'month';
        }
        $form->limit_by_period_min_value = $request->limit_by_period_min_value;
        if (isset($request->never_expire_check) && $request->never_expire_check == 'on') {
            $form->expiry_state = 'never_expire';
        } else {
            $form->expiry_state = 'expiry_time';
        }
        $form->expiry_time = $request->expiry;


        if (isset($request->submission_text)) {
            $form->submission_text = $request->submission_text;
        } else {
            $form->submission_text = null;
        }
    
        $form->show_to_responder = isset($request->show_to_responder) && $request->show_to_responder == 'on'; // Save checkbox state
    
        if (!isset($request->schedule_check) && $request->schedule_check != 'on') {
            $form->schedule_state = 'optional';
        } else if ($request->schedule_radio == '1') {
            $form->schedule_state = 'day';
        } else {
            $form->schedule_state = 'date';
        }
        if (isset($request->submission_loc) && $request->submission_loc == 'on') {
            $form->show_submission_loc = true;
        } else {
            $form->show_submission_loc = false;
        }
        if (isset($request->quick_report) && $request->quick_report == 'on') {
            $form->is_quick_report = true;
        } else {
            $form->is_quick_report = false;
        }
        if (isset($request->qr) && $request->qr == 'on') {
            $form->is_qr_code = true;
        } else {
            $form->is_qr_code = false;
        }
        if (isset($request->allow_draft) && $request->allow_draft == 'on') {
            $form->allow_drafts_off_site = true;
        } else {
            $form->allow_drafts_off_site = false;
        }
        $form->color_code = $request->color_code;
        $form->name = $request->form_name;
        $form->note = $request->note ?? '';
        $form->add_to_case_manager = $request->has('add_to_case_manager'); // from location side, it will always be 0. from head office side, it will ask while creating.
        $form->fields_updated_at = Carbon::now();
        $form->be_spoke_form_category_id = $request->be_spoke_form_category_id;
        $form->is_external_link = isset($request->is_external_link) && $request->is_external_link == 'on' ? false : true;
        if ($form->is_external_link) {
            if ($request->external_link_input !== $form->external_link) {
                $form->external_link = $request->external_link_input;
            }
            if ($form->external_link == null || $request->external_link_input === '') {
                $form->external_link = Str::random(10);
            }
            if (strlen($form->external_link) < 10) {
                return redirect()->route('head_office.be_spoke_forms_templates.form_template', $form->id)
                                 ->with('error', 'Custom External link must be at least 10 characters!');
            }
            if (strlen($form->external_link) > 15) {
                return redirect()->route('head_office.be_spoke_forms_templates.form_template', $form->id)
                                 ->with('error', 'Custom External link must be less than 15 characters!');
            }
            $forms_check = Form::where('external_link', $form->external_link)->get();
            if ($forms_check->isNotEmpty()) {
                return redirect()->route('head_office.be_spoke_forms_templates.form_template', $form->id)
                                 ->with('error', 'Please use unique external link characters!');
            }
        }
        
        if(empty($form->schedule_by_day) || $form->schedule_by_day == null){
            $form->schedule_by_day = json_encode([
                "Monday" => ["active" => false, "times" => ["09:00"],'cutoff'=>"09:00",'do_not_allow_submissions'=>true,'mandatory'=>false],
                "Tuesday" => ["active" => false, "times" => ["09:00"],'cutoff'=>"09:00",'do_not_allow_submissions'=>true,'mandatory'=>false],
                "Wednesday" => ["active" => false, "times" => ["09:00"],'cutoff'=>"09:00",'do_not_allow_submissions'=>true,'mandatory'=>false],
                "Thursday" => ["active" => false, "times" => ["09:00"],'cutoff'=>"09:00",'do_not_allow_submissions'=>true,'mandatory'=>false],
                "Friday" => ["active" => false, "times" => ["09:00"],'cutoff'=>"09:00",'do_not_allow_submissions'=>true,'mandatory'=>false],
                "Saturday" => ["active" => false, "times" => ["09:00"],'cutoff'=>"09:00",'do_not_allow_submissions'=>true,'mandatory'=>false],
                "Sunday" => ["active" => false, "times" => ["09:00"],'cutoff'=>"09:00",'do_not_allow_submissions'=>true,'mandatory'=>false]
            ]);
        }
        if(empty(json_decode($form->form_json,true))){
            $form_json = '{"id":-4,"tracker":4,"title":"Dispensing and Supply","pages":[{"id":-108,"tracker":35,"name":"New Page","items":[],"order":6,"type":"page","draging":false,"has_over":false,"is_required":false,"allow_only_one":false,"is_nhs_hidden":false}],"show_progress":false,"fill_bar_color":"#68bb55","next_btn_text":"Next","next_btn_color":"#72C4BA","submit_btn_text":"Submit","submit_btn_color":"#72C4BA","font_size":34,"text_color":"#000000","bg_color":"#ffffff","involvements":[]}';
            $form_data = json_decode($form_json, true);
            $form_data['title'] = $form->name;
            $form->form_json = json_encode($form_data);
        }
        $form->updated_by_id = Auth::guard('web')->user()->getHeadOfficeUser($ho->id)->id;
         
         
        
            $form->reference_type = 'head_office';
            $form->reference_id = Auth::guard('web')->user()->selected_head_office->id;
            $form->is_draft = false;
            $form->save(); 

            $form_stage = optional($form)->default_stages;
            if(isset($form_stage) && $form_stage->count() == 0){
                $newStage =new DefaultCaseStage();
            $newStage->be_spoke_form_id = $form->id;
            $newStage->name = 'Enter Stage Name';
            $newStage->save();
            }
            $href = "/bespoke_form_v3/#!/form/".($form->id).'?ho='.$ho->id; 
            if(!isset($id)){
                return redirect($href)->with('success_message', 'Form Saved Successfully.');
            }
            return redirect()->route('head_office.be_spoke_forms_templates.form_template', $form->id)
                ->with('success_message', 'Form Saved Successfully.');
        

    }
    public function formTemplateStagesSave(Request $request)
    {
        if (isset($request->form_id)) {

            if (!empty($request->stage_name)) {
                $newStage = new FormStage();
                $newStage->form_id = $request->form_id;
                $newStage->stage_name = $request->stage_name;
                $newStage->save();
            }

            if (!empty($request->stages)) {
                foreach ($request->stages as $id => $stage_name) {
                    $stage = FormStage::find($id);
                    $stage->stage_name = $stage_name;
                    $stage->save();
                }
            }
            return redirect()->back() //->route('be_spoke_forms_templates.form_template',$request->form_id)
                ->with('success_message', 'Stages Saved Successfully.');

        } else {
            return redirect()->back()->with('error', 'Invalid stage submitted.');
        }
    }

    public function deleteStage(Request $request, $id = null)
    {
        // If have permissions to delete
        $stage = FormStage::find($id);
        if (!$stage) {
            return redirect()->back()->with('error', 'Invalid data submitted.');
        }
        $form_id = $stage->form_id;

        $groups = $stage->groups;
        if (count($groups)) {
            foreach ($groups as $g) {
                $questions = $g->questions;
                if (count($questions)) {
                    foreach ($questions as $q) {
                        $condtions = $q->conditions;
                        if (count($condtions)) {
                            foreach ($condtions as $c) {
                                # Delete conditions
                                $c->deleteAllAssociatedActions();
                                $c->delete();
                            }
                        }
                        # Question delete
                        if ($q->data) {
                            $q->data->delete();
                        }
                        $q->delete();
                    }
                }
                # Group Delete
                $g->delete();
            }
        }
        $stage->delete();

        return redirect()->back()
            ->with('success_message', 'Stage Deleted Successfully.');
    }

    public function stageGroups(Request $request, $stage_id = null)
    {
        $stage = FormStage::find($stage_id);
        if (!$stage) {
            return redirect()->back()->with('error', 'Invalid data submitted.');
        }
        $user = Auth::guard('location')->user();

        if ($user) { // For Location //
            if (!$user->userCanUpdateSettings()) {
                return redirect()->back()->with('error', "you don't have access to update the form");
            }
            return view('location.be_spoke_forms.stages_groups')->with('stage', $stage)->with('stage_id', $stage_id);
        }
        return view('head_office.be_spoke_forms.stages_groups')->with('stage', $stage)
            ->with('stage_id', $stage_id);

    }
    public function stageGroupSave(Request $request)
    {

        if (isset($request->stage_id)) {

            if (!empty($request->group_name)) {
                $newGroup = new QuestionGroup();
                $newGroup->stage_id = $request->stage_id;
                $newGroup->group_name = $request->group_name;
                $newGroup->save();
            }

            if (!empty($request->groups)) {
                foreach ($request->groups as $id => $group_name) {
                    $group = QuestionGroup::find($id);
                    $group->group_name = $group_name;
                    $group->save();
                }
            }
            $user = Auth::guard('location')->user();
            if ($user) { // For Location //
                if (!$user->userCanUpdateSettings()) {
                    return redirect()->back()->with('error', "you don't have access to update the form");
                }
                return redirect()->route('be_spoke_forms_templates.form_template', $request->form_id)
                    ->with('success_message', 'Stages Saved Successfully.');
            }
            return redirect()->route('head_office.be_spoke_forms_templates.form_template', $request->form_id)
                ->with('success_message', 'Stages Saved Successfully.');

        } else {
            return redirect()->back()->with('error', 'Invalid stage submitted.');
        }
    }

    public function deleteGroup(Request $request, $id = null)
    {
        // If have permissions to delete
        // Delete All Questions With IT
        $group = QuestionGroup::find($id);

        if (!$group) {
            return redirect()->back()->with('error', 'Invalid data submitted.');
        }

        $questions = $group->questions;
        if (count($questions)) {
            foreach ($questions as $q) {
                $condtions = $q->conditions;
                if (count($condtions)) {
                    foreach ($condtions as $c) {
                        # Delete conditions
                        $c->deleteAllAssociatedActions();
                        $c->delete();
                    }
                }
                # Question delete
                if ($q->data) {
                    $q->data->delete();
                }
                $q->delete();
            }
        }
        # Group Delete
        $group->delete();
        return redirect()->back()->with('success_message', 'Group Deleted Successfully.');
    }

    public function stageQuestionsIndex(Request $request, $stage_id, $group_id)
    {
        $stage = FormStage::find($stage_id);
        if (!$stage) {
            return redirect()->back()->with('error', 'Invalid data submitted.');
        }
        $group = QuestionGroup::find($group_id);
        $default_fields = DefaultField::all();
        if (Auth::guard('location')->user()) {
            return view('location.be_spoke_forms.group_questions', compact('stage', 'group_id', 'group', 'default_fields'));
        }

        return view('head_office.be_spoke_forms.group_questions', compact('stage', 'group_id', 'group', 'default_fields'));
    }

    public function stageQuestionEdit(Request $request, $question_id)
    {
        if (!$question = StageQuestion::find($question_id)) {
            return redirect()->back()->with('error', 'Invalid data submitted.');
        }
        $stage = $question->stage;

        $default_fields = DefaultField::all();
        if (Auth::guard('location')->user()) {
            return view('location.be_spoke_forms.question_edit', compact('stage', 'default_fields'))->with('question', $question);
        }

        $head_office = Auth::guard('web')->user()->selected_head_office;
        $gdpr_tags = $head_office->gdprs;
        return view('head_office.be_spoke_forms.question_edit', compact('stage', 'default_fields', 'gdpr_tags'))->with('question', $question);
    }
    public function stageQuestionsSave(Request $request, $stage_id, $group_id)
    {
        $stage = FormStage::find($stage_id);
        if (!$stage) {
            return redirect()->back()->with('error', 'Invalid data submitted.');
        }
        if (empty($request->field_type) || empty($request->question_name) || empty($request->question_label)) {
            return redirect()->back()->with('error', 'Invalid data submitted.');
        }
        if (!$question = StageQuestion::find($request->question_id)) {
            $question = new StageQuestion();
        }

        $question->form_id = $stage->form_id;
        $question->stage_id = $stage->id;
        $question->group_id = $group_id;
        $question->question_type = $request->field_type;
        $question->question_name = $request->question_name;
        $question->question_title = $request->question_label;
        $question->question_required = (int) $request->question_required;
        $question->question_min = $request->field_minimum;
        $question->question_max = $request->field_maximum;

        if ($request->has('form_card_id') && $request->form_card_id > 0) {
            $question->form_card_id = $request->form_card_id;
        }

        if ($request->has('default_field_id')) {
            $question->default_field_id = $request->default_field_id;
        }

        # Unset empty values.
        $fieldValues = (array) $request->field_options;
        foreach ($fieldValues as $key => $field) {
            if (empty($fieldValues[$key])) {
                unset($fieldValues[$key]);
            }
        }
        $question->question_values = json_encode($fieldValues);

        $question->question_select_multiple = (int) $request->field_select_multiple;
        $question->question_select_loggedin_user = (int) $request->field_select_loggedin_user;

        $question->question_extra_value = $request->question_extra_value;
        $question->question_extra_value_1 = $request->question_extra_value_1;

        if ($question->question_type == 'user') {
            $question->question_extra_value = $request->field_allow_change;
        }

        $question->save();
        //Form updates with default case description //
        if ($request->has('case_description_field')) {
            $form = $stage->form;
            $form->case_description_field = $question->id;
            $form->save();
        }
        if ($question->gdpr_form_field) {
            $question->gdpr_form_field->delete();
        }

        if ($request->gdpr_tag) {
            $question_tag = new GdprFormField();
            $question_tag->gdpr_tag_id = $request->gdpr_tag;
            $question_tag->be_spoke_form_question_id = $question->id;
            $question_tag->save();

        }

        return redirect()->back()->with('success_message', 'Question Saved Successfully.');
    }

    public function stageQuestionDelete(Request $request, $id = null)
    {
        // If have permissions to delete
        // Delete All action associated With IT
        $question = StageQuestion::find($id);
        if (!$question) {
            return redirect()->back()->with('error', 'Invalid data submitted.');
        }
        $condtions = $question->conditions;
        if (count($condtions)) {
            foreach ($condtions as $c) {
                # Delete conditions
                $c->deleteAllAssociatedActions();
                $c->delete();
            }
        }
        # Question delete
        if ($question->data) {
            $question->data->delete();
        }

        $question->delete();

        return redirect()->back()->with('success_message', 'Question Deleted Successfully.');
    }

    public function questionActionIndex(Request $request, $question_id)
    {
        $question = StageQuestion::find($question_id);
        if (!$question) {
            return redirect()->back()->with('error', 'Invalid data submitted.');
        }
        $stage = FormStage::find($question->stage_id);
        if (Auth::guard('location')->user()) {
            return view('location.be_spoke_forms.actions')->with('question', $question)
                ->with('stage', $stage);
        }

        return view('head_office.be_spoke_forms.actions')->with('question', $question)
            ->with('stage', $stage);
    }

    public function questionActionEdit(Request $request, $question_id, $condition_id)
    {
        $question = StageQuestion::find($question_id);
        if (!$question) {
            return redirect()->back()->with('error', 'Invalid data submitted.');
        }
        $stage = FormStage::find($question->stage_id);
        $condition = ActionCondition::find($condition_id);
        if (!$condition) {
            return redirect()->back()->with('error', 'Invalid data submitted.');
        }

        # Add action details such as send email.
        if ($condition->condition_action_type == 'send_email') {
            $actionEmail = ActionEmail::where('condition_id', $condition->id)->first();
            View::share('actionEmail', $actionEmail);
        }

        if (Auth::guard('location')->user()) {
            return view('location.be_spoke_forms.actions')->with('question', $question)
                ->with('stage', $stage)
                ->with('condition', $condition);
        }

        return view('head_office.be_spoke_forms.actions')->with('question', $question)
            ->with('stage', $stage)
            ->with('condition', $condition);
    }

    public function questionActionTypeDetail(Request $request)
    {
        $question = StageQuestion::find($request->question_id);
        if (!$question) {
            return 0;
        }
        $type = $request->type;
        $condition = ActionCondition::find($request->condition_id);
        if (Auth::guard('location')->user()) {
            return view('location.be_spoke_forms.action_type')
                ->with('question', $question)
                ->with('type', $type)
                ->with('condition', $condition);
        }

        return view('head_office.be_spoke_forms.action_type')
            ->with('question', $question)
            ->with('type', $type)
            ->with('condition', $condition);

    }
    public function questionActionSave(Request $request)
    {
        $question = StageQuestion::find($request->question_id);
        if (!$question) {
            return redirect()->back()->with('error', 'Invalid data submitted.');
        }
        $condition = ActionCondition::find($request->condition_id);
        if (!$condition) {
            $condition = new ActionCondition();
        }

        # If is array condition values, fix empty value and store as json
        # for select, checkbox and radio buttons.
        if (is_array($request->condition_value)) {
            $fieldValues = (array) $request->condition_value;
            foreach ($fieldValues as $key => $field) {
                if (empty($fieldValues[$key])) {
                    unset($fieldValues[$key]);
                }
            }
            $condition->condition_value = json_encode($fieldValues);
        } else {
            $condition->condition_value = $request->condition_value;
        }
        $condition->question_id = $request->question_id;
        $condition->condition_if_value = $request->if_value;
        $condition->condition_value_2 = $request->condition_value_2;
        $condition->condition_action_type = $request->action_type;

        if ($request->action_type == 'trigger_root_cause_analysis') {
            $five_whys = isset($request->condition_action_value_5_whys) ? $request->condition_action_value_5_whys : false;
            $five_whys_required = isset($request->condition_action_value_5_whys_required) ? $request->condition_action_value_5_whys_required : false;
            $fish_bone = isset($request->condition_action_value_fish_bone) ? $request->condition_action_value_fish_bone : false;
            $fish_bone_required = isset($request->condition_action_value_fish_bone_required) ? $request->condition_action_value_fish_bone_required : false;
            $both = $request->condition_action_value_1;
            $action_value = array(
                'five_whys' => $five_whys,
                'five_whys_required' => $five_whys_required,
                'fish_bone' => $fish_bone,
                'fish_bone_required' => $fish_bone_required,
            );
            $condition->condition_action_value = json_encode($action_value);
            $condition->condition_action_value_1 = $both;
        } elseif ($request->action_type == 'create_custom_task_in_case_manager') {
            $data = (array) $request->condition_action_value;
            $files = $request->file('files');
            if ($files) {
                $data['documents'] = array();
                if (count($files)) {
                    foreach ($files as $file) {
                        $path = $file->store('/forms/attachments');
                        $data['documents'][] = $path;
                    }
                }
            } else {
                # Old Documents clone if new files are not uploaded
                $customTask = (array) json_decode($condition->condition_action_value, true);
                $data['documents'] = isset($customTask['documents']) ? $customTask['documents'] : array();
            }

            //$path = $request->file('file')->store('/forms/attachments');

            $condition->condition_action_value = !empty($request->condition_action_value) ? json_encode($data) : '';
            $condition->condition_action_value_1 = !empty($request->condition_action_value_1) ? $request->condition_action_value_1 : '';
        } else {
            # Some actions require just two fields, we adjust them here instead of new table for each action
            $condition->condition_action_value = !empty($request->condition_action_value) ? $request->condition_action_value : '';
            $condition->condition_action_value_1 = !empty($request->condition_action_value_1) ? $request->condition_action_value_1 : '';
        }

        $condition->save();

        # If action email is selected save data, otherwise delete rows
        ActionEmail::processSave($request, $condition);
        return redirect()->back()
            ->with('success_message', 'Condition Saved Successfully.');

    }

    public function actionConditionDelete(Request $request, $condition_id = null)
    {
        // If have permissions to delete
        // Delete All actions associated With IT
        $condition = ActionCondition::find($condition_id);
        if (!$condition) {
            return redirect()->back()->with('error', 'Invalid data submitted.');
        }
        # Deleting emails
        $condition->deleteAllAssociatedActions();
        $question_id = $condition->question_id;
        $condition->delete();

        if (Auth::guard('location')->user()) {
            return redirect()->route('be_spoke_forms_templates.form_stage_questions.action', $question_id)
                ->with('success_message', 'Action Deleted Successfully.');
        }

        return redirect()->route('head_office.be_spoke_forms_templates.form_stage_questions.action', $question_id)
            ->with('success_message', 'Action Deleted Successfully.');
    }

    public function viewEmailAttachment(Request $request, $action_id)
    {
        $email = ActionEmail::find($action_id);
        if (!$email) {
            return redirect()->back()->with('error', 'Invalid data submitted.');
        }
        return response()->download(storage_path($email->uploadPath . $email->email_attachment));

    }
    public function deleteEmailAttachment(Request $request, $action_id)
    {
        $email = ActionEmail::find($action_id);
        if (!$email) {
            return redirect()->back()->with('error', 'Invalid data submitted.');
        }
        @unlink(storage_path($email->uploadPath . $email->email_attachment));
        $email->email_attachment = '';
        $email->save();
        return response()->json(array('message' => true));
    }

    # TinyMce related functions.
    public function uploadGeneralAttachment(Request $request)
    {
        $path = $request->file('file')->store('/forms/attachments');
        return response()->json(['location' => url('/' . $path)]);
    }
    public function displayGeneralAttachment($filename = '')
    {
        return response()->download(storage_path('app/forms/attachments/' . $filename));
    }

    public function saveModifications(Request $request, $id = null)
    {
        $location = Auth::guard('location')->user();
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        if ($id) {
            $record = Record::find($id);
            if ($record->form->allow_editing_state === 'time' && is_null($record->form->allow_editing_time) && $record->form->allow_editing_time < Carbon::now()) {
                return redirect()->route('be_spoke_forms.be_spoke_form.records_view', ['id' => $record->id])
                    ->withErrors(['error' => 'You can not edit this incident after ' . ($record->form->allow_editing_time ? $record->form->allow_editing_time->format('d-M-Y H:i:s') : 'No Time')]);
            }
            $record_data = $record->data;
            foreach ($request->all() as $key => $data) {
                if ($key !== '_token') {
                    foreach ($record_data as $question) {
                        if ($question->question_id === $key && $question->question_value != $data) {
                            $record_data_edited_history = new RecordDataEditedHistory();
                            $record_data_edited_history->form_id = $record->form->id;
                            $record_data_edited_history->record_id = $record->id;
                            $record_data_edited_history->record_data_id = $question->id;
                            $record_data_edited_history->updated_by = $user->id;
                            $record_data_edited_history->old_value = $question->question_value;
                            $record_data_edited_history->updated_value = $data;
                            $record_data_edited_history->save();
                        }
                    }
                }
            }
        }
        $activity_log = new ActivityLog();
            $activity_log->type = "Report Modified";
            $activity_log->user_id = $user->id;
            $activity_log->head_office_id = $location->head_office()->id;
            $activity_log->action = 'Form modified from Location by ' . $user->first_name;
            $activity_log->save();
        return redirect()->back();
    }

    // Delete this function after completing other new function !!!!
    public function saveRecord(Request $request, $id = null)
    {
        $location = Auth::guard('location')->user();
        $headOffice = $location->head_office();
        $conditionsToApply = array();
        $emailsToApply = array();
        $infomationToShow = array();
        $formsToFill = array();
        $rootCauseAnalysis = array();
        $prority = 0;
        $requires_final_approval = 0;

        $oldFormsToFill = (array) $request->to_fill;
        if ($id) {
            $record = Record::findOrFail($id);
            if ($record->form->allow_editing_state === 'time' && is_null($record->form->allow_editing_time) && $record->form->allow_editing_time < Carbon::now()) {
                return redirect()->route('be_spoke_forms.be_spoke_form.records_view', ['id' => $record->id])
                    ->withErrors(['error' => 'You can not edit this incident after ' . ($record->form->allow_editing_time ? $record->form->allow_editing_time->format('d-M-Y H:i:s') : 'No Time')]);
            }

            $form = $record->form;
            $form_cards_data = [];
            $form_card_test = [];

            $requires_final_approval = $form->requires_final_approval == 1;
            $case = $record->recorded_case;
        } else {
            $form = Form::find($request->form_id);
            $requires_final_approval = $form->requires_final_approval == 1;
            if (!$form) {
                return redirect()->back()->with('error', 'Invalid data submitted.');
            }
            if (!$form->is_active) {
                return redirect()->back()->with('error', 'Form is inactive.');
            }

            $location = Auth::guard('location')->user();

            $record = new Record();
            $record->form_id = $form->id;
            if ($form->is_external_link && $request->has('location_id')) {

                $record->location_id = $request->location_id;
            } else {

                $record->location_id = $location->id;
            }
            $u = Auth::guard('web')->user() ?? Auth::guard('user')->user();
            $record->user_id = $u ? $u->id : ''; // In case if posted without login
            $record->priority = 1;
            $record->status = 'active';
            $record->hide = !$form->show_submission_loc;
            $record->save();

            $oldFormsToFill = (array) $request->to_fill;
            $conditionsToApply = array();
            $emailsToApply = array();
            $infomationToShow = array();
            $formsToFill = array();
            $rootCauseAnalysis = array();

            $case_description = "N/A";

            if ($location->head_office()) {
                DB::beginTransaction();
                $headOffice = $location->head_office();

                $description = $case_description;

                // On Head Office Case //
                $case = new HeadOfficeCase();
                $case->status = 'open';
                $case->head_office_id = $headOffice->id;
                $case->description = $description;
                $case->case_closed = 0;
                $case->last_accessed = Carbon::now();
                $case->last_action = Carbon::now();
                $case->last_linked_incident_id = $record->id;
                $case->incident_type = $record->location->location_type->name;
                $case->location_name = $record->location->trading_name;
                $case->location_id = $record->location->id;
                $case->location_email = $record->location->email;
                $case->location_phone = $record->location->telephone_no;
                $case->location_full_address = $record->location->name();
                $case->reported_by = $record->created_by->first_name;
                $case->reported_by_id = $record->user_id;
                $case->save();

                $root_cause_analysis = new RootCauseAnalysis();
                $root_cause_analysis->name = '';
                $root_cause_analysis->is_editable = 1;
                $root_cause_analysis->case_id = $case->id;

                if ($headOffice->is_fish_bone) {

                    $root_cause_analysis->type = 'fish_bone';

                    $root_cause_analysis->save();
                    foreach ($headOffice->fish_bone_questions as $question) {
                        $q = new FishBoneRootCauseAnalysis();
                        $q->root_cause_analysis_id = $root_cause_analysis->id;
                        $q->question = $question->question;
                        $q->save();
                    }
                }

                if ($headOffice->is_five_whys) {
                    $root_cause_analysis->type = 'five_whys';
                    $root_cause_analysis->save();
                    foreach ($headOffice->fish_bone_questions as $question) {
                        $q = new FIveWhysRootCauseAnalysis();
                        $q->root_cause_analysis_id = $root_cause_analysis->id;
                        $q->question = $question->question;
                        $q->save();
                    }
                }

                // Link to Location Incident through 1-1
                $linked_case = new HeadOfficeLinkedCase();
                $linked_case->head_office_case_id = $case->id;
                $linked_case->be_spoke_form_record_id = $record->id;
                $linked_case->save();
                

                // ================== Saving Default Documents ==================
                if ($form->defaultDocuments) {
                    foreach ($form->defaultDocuments as $d) {

                        $task = new CaseManagerCaseDocument();

                        $task->case_id = $case->id;
                        $task->title = $d->title;
                        $task->is_default_document = 1;
                        $task->description = $d->description;
                        $task->save();

                        $documents = $d->documents;
                        foreach ($documents as $value) {
                            $doc = new CaseManagerCaseDocumentDocument();
                            $doc->c_m_c_d_id = $task->id;
                            $value = Document::where('unique_id', $value->document->unique_id)->first();
                            if (!$value) {
                                continue;
                            }
                            $doc->document_id = $value->id;
                            $doc->type = ($value->isImage()) ? 'image' : 'document';
                            $doc->save();
                        }
                    }
                }
                
                // ================== Saving Default Stages and Tasks and Task Deadlines ==================
                $form_default_stages = $form->default_stages()->orderBy('label')->get();
                if (!count($form_default_stages)) {
                    $case_stage = new CaseStage();
                    $case_stage->case_id = $case->id;
                    $case_stage->name = 'Stage 1';
                    $case_stage->is_default = 1;
                    $case_stage->label = 0;
                    $case_stage->is_current_stage = true;
                    $case_stage->save();
                }

                foreach ($form_default_stages as $form_default_stage) {
                    $current_stage = $case->stages()->where('is_current_stage', 1)->first();
                    $case_stage = new CaseStage();
                    $case_stage->case_id = $case->id;
                    $case_stage->name = $form_default_stage->name;
                    $case_stage->is_default = 1;
                    if (!$current_stage) {
                        $case_stage->is_current_stage = 1;
                    }

                    $case_stage->label = $form_default_stage->label;
                    $case_stage->save();
                    foreach ($form_default_stage->default_tasks()->orderBy('label')->get() as $form_default_task) {
                        $head_office_task = $case_stage->my_tasks()->where('case_stage_id', $case_stage->id)->first();
                        if (!$head_office_task) {

                            $case_manager_task = new CaseStageTask();
                            $case_manager_task->user_id = $u->id;
                            $case_manager_task->case_stage_id = $case_stage->id;
                            $case_manager_task->title = $form_default_task->title;
                            $case_manager_task->description = $form_default_task->description;
                            $case_manager_task->mandatory = $form_default_task->mandatory;
                            $case_manager_task->status = 'in_progress';
                            $case_manager_task->is_default_task = 1;
                            $case_manager_task->form_json = $form_default_task->form_json;
                            $case_manager_task->save();

                            $deadline_records = $form_default_task->deadline_records;

                            if(!empty($deadline_records)){
                                foreach ($deadline_records as $deadline_record) {
                                    $new_deadline_record = $deadline_record->replicate();
                                    $new_deadline_record->default_case_stage_tasks_id = null;
                                    $new_deadline_record->save();
                                    // new deadline association with case manager task 
                                    $new_deadline_link = new deadlineCaseTask();
                                    $new_deadline_link->case_task_id = $case_manager_task->id;
                                    $new_deadline_link->default_task_id = $form_default_task->id;
                                    $new_deadline_link->deadline_id = $deadline_record->id;
                                    $new_deadline_link->save();
                                }
                            }

                            foreach ($form_default_task->documents as $document) {
                                $doc = $document->document;
                                if (!$case_manager_task->documents()->where('document_id', $doc->id)->first()) {
                                    $task_document = new CaseStageTaskDocument();
                                    $task_document->case_stage_task_id = $case_manager_task->id;
                                    $task_document->document_id = $doc->id;
                                    $task_document->type = $document->type;
                                    $task_document->save();
                                }
                            }
                            if (json_decode($form_default_task->type_ids)) {
                                foreach (json_decode($form_default_task->type_ids) as $id) {
                                    if ($form_default_task->type) {
                                        $profile = $headOffice->head_office_user_profiles()->find($id);
                                        if ($profile) {
                                            foreach ($profile->user_profile_assign as $user_profile_assign) {
                                                $user = $user_profile_assign->head_office_user;
                                                $task_assign = new CaseStageTaskAssign();
                                                $task_assign->head_office_user_id = $user->id;
                                                $task_assign->task_id = $case_manager_task->id;
                                                $task_assign->save();
                                            }
                                        }

                                    } else {
                                        $user = $headOffice->users()->where('user_id', $id)->first();
                                        if ($user) {
                                            $task_assign = new CaseStageTaskAssign();
                                            $task_assign->head_office_user_id = $user->id;
                                            $task_assign->task_id = $case_manager_task->id;
                                            $task_assign->save();
                                        }
                                    }

                                }
                            }

                            
                        }
                    }
                }

            }

            $fields = [];

            $form_cards_data = [];
            $form_card_test = [];

        }

        $case->requires_final_approval = $requires_final_approval;
        $case->prority = $prority;
        $case->save();
        if ($requires_final_approval) {
            //match form name, location, in where clause //
            // we dont need to filter head offices. because one form definitely lies to one head office.
            $case_interested_party = new CaseInterestedParty();
            $case_interested_party->case_id = $case->id;
            $case_interested_party->tag = 'final_clouser_approval';

            $setting = $form->form_review_settings()->where('location_id', 'like', '%"' . $location->id . '"%')->get();

            if ($setting->count()) {
                $randi = rand(0, $setting->count());
                $user_id = $setting[$randi]->head_office_user_id;
                // use this user id to assign this person as a case closure person in interested party
                $case_interested_party->head_office_user_id = $user_id;
            } else {
                foreach ($headOffice->users as $user) {
                    if ($user->user_profile_assign && $user->user_profile_assign->profile->profile_name == 'Super User') {
                        $case_interested_party->head_office_user_id = $user->id;
                    }
                }
            }
            $case_interested_party->save();
        }
        $users = [];
        $head_office_users = $headOffice->users;
        $designated_user = false;
        $designated_user_orders_count = 100; //some higher number //

        if ($prority > 0) {
            foreach ($head_office_users as $head_office_user) {
                $setting = $head_office_user->user_incident_settings()->where('be_spoke_form_id', $form->id)->first();
                $total_open_cases_for_this_setting = $head_office_user->head_office_user_cases()->count(); 
                if ($setting && ($prority >= $setting->min_prority) && ($prority <= $setting->max_prority) && $designated_user_orders_count > $total_open_cases_for_this_setting) {
                    $designated_user_orders_count = $total_open_cases_for_this_setting;
                    $designated_user = $setting->head_office_user;
                }
            }
        }
        $case_handler_user = new CaseHandlerUser();
        $case_handler_user->case_id = $case->id;
        if ($designated_user) {
            $case_handler_user->head_office_user_id = $designated_user->id;
        } else {
            $found = false;
            foreach ($headOffice->users as $user) {
                if ($user->user_profile_assign && $user->user_profile_assign->profile->super_access == 'Super User') {
                    $case_handler_user->head_office_user_id = $user->id;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $case_handler_user->head_office_user_id = $headOffice->users[0]->id;
            }

        }
        $case_handler_user->save();
        // if(count($users) > 0)
        // {
        //     $max = null;
        //     foreach ($users as $item) {
        //       $max = $max === null ? $item['cases'] : max($max, $item['cases']);
        //     }
        // }

        //check here for data //
        if ($headOffice) {
            $pres_c = false;
            $contact_connections = [];
            $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
            $form_cards_data[$user->first_name]['fields'] = ['first_name' => $user->first_name, 'last_name' => $user->surname, 'registration_no' => $user->registration_no];
            $form_cards_data[$user->first_name]['form_card'] = null;
            foreach ($form_cards_data as $fcd) {
                //createing contacts
                $contact = new Contact();

                $given_address = false;
                foreach ($fcd['fields'] as $key => $field) {
                    if ( /*$key != 'nhs_number' && $key != 'registration_no' && */$key != 'address') {
                        $contact->$key = $field;
                    } else if ($key == 'address') {
                        $given_address = $field;
                    }

                    // else if($key == 'date_of_birth')
                    //     $contact->$key = $field;

                }

                $found = Contact::where([['first_name', $contact->first_name], ['last_name', $contact->last_name]/*,['date_of_birth', $contact->date_of_birth]*/])->first();

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
                    if ($case_contact->type == 'Reporter') {
                        $contact->user_id = $user->id;
                        $contact->save();
                    }
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

        }
        # Merge old forms to fill with new conditions
        $formsToFill = array_merge($oldFormsToFill, $formsToFill);
        DB::commit();

        $rootCauseAnalysis = $this->processRootCauseArray($rootCauseAnalysis);
        if (count($infomationToShow) || count($formsToFill)) {
            $information = '';
            foreach ($infomationToShow as $c) {
                $information .= "<br>" . $c->condition_action_value;
            }
            return view('location.be_spoke_forms.display-information', compact('record', 'information', 'formsToFill', 'rootCauseAnalysis'));
        }

        if ($request->has('location_id')) {
            return back()->with('success_message', 'Form submitted successfully');
        }

        return redirect()->route('be_spoke_forms.be_spoke_form.index');
    }
    public function processRootCauseArray($rootCauseAnalysis)
    {
        $processed = array(
            'five_whys' => false,
            'five_whys_required' => false,
            'fish_bone' => false,
            'fish_bone_required' => false,
            'both' => false,
        );
        if (!count($rootCauseAnalysis)) {
            return $processed;
        }

        foreach ($rootCauseAnalysis as $condition) {
            $value = json_decode($condition->condition_action_value, true) ? json_decode($condition->condition_action_value, true) : array();
            $both = $condition->condition_action_value_1;

            if ($value['five_whys'] != false) {
                $processed['five_whys'] = true;
            }
            if ($value['five_whys_required'] != false) {
                $processed['five_whys_required'] = true;
            }
            if ($value['fish_bone'] != false) {
                $processed['fish_bone'] = true;
            }
            if ($value['fish_bone_required'] != false) {
                $processed['fish_bone_required'] = true;
            }
            # If both are required.
            if ($value['five_whys_required'] && $value['fish_bone_required']) {
                if ($both == 'yes') {
                    $processed['both'] = true;
                }
            }

        }
        return $processed;

    }
    public function sendActionEmails($emailsToApply = array())
    {
        if (empty($emailsToApply)) {
            return;
        }
        # Process to find To, Message, Attachment Values
        $processedEmails = array();
        foreach ($emailsToApply as $e) {
            $processedEmails[] = $e['condition']->generateEmailData($e['data']);
        }
        # Combine multiple emails into one.
        $combinedEmails = array();
        foreach ($processedEmails as $email) {
            if (empty($email['to'])) {
                continue;
            }
            $combinedEmails[$email['to']][] = array(
                'message' => $email['message'],
                'attachment' => $email['attachment'],
            );
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
    public function displayInformation(Request $request, $condition_id)
    {
        // dd($condition_id);
    }
    public function drugsDmd(Request $request)
    {
        $query = $request->get('query');
        if (empty($query)) {
            return response()->json(array());
        }

        $queries = explode(' ', (string) $query);
        $data = dmd_vmp::query();
        if (count($queries)) {
            foreach ($queries as $q) {
                $data->whereRaw('LOWER(NM) LIKE ?', ['%' . strtolower($q) . '%']);
            }
        }
        # Limit to 30 records
        $data = $data->limit(30)->get('NM');
        if (empty($data)) {
            return response()->json(array());
        }
        return response()->json($data);
    }

    public function deleteForm(Request $request, $id)
    {
        $form = Form::find($id);
        if (!$form || count($form->records) > 0) {
            return redirect()->route('be_spoke_forms.be_spoke_form.index', ['error' => 'Form is not deleted.']);
        }
        // $stages = $form->stages;
        // foreach ($stages as $stage) {
        //     $groups = $stage->groups;
        //     if (count($groups)) {
        //         foreach ($groups as $g) {
        //             $questions = $g->questions;
        //             if (count($questions)) {
        //                 foreach ($questions as $q) {
        //                     $condtions = $q->conditions;
        //                     if (count($condtions)) {
        //                         foreach ($condtions as $c) {
        //                             # Delete conditions
        //                             $c->deleteAllAssociatedActions();
        //                             $c->delete();
        //                         }
        //                     }
        //                     # Question delete
        //                     if ($q->data) {
        //                         $q->data->delete();
        //                     }
        //                     $q->delete();
        //                 }
        //             }
        //             # Group Delete
        //             $g->delete();
        //         }
        //     }
        //     $stage->delete();
        // }

        $form->is_deleted = 1 - $form->is_deleted;
        $form->deleted_at = Carbon::now();
        $form->is_active = false;
        $form->is_archived = 0;
        if(empty($form->form_json)){
            $form->form_json = json_encode('[]');
        }
        $form->save();
        if($form->is_draft == true){
            return redirect()->route('head_office.be_spoke_form.index', ['tab' => 'AllFormBespoke'])
                 ->with('success', 'Form Duplication Cancelled.');
        }
        return redirect()->back()->with('success', 'Form Deleted Successfully.');
    }
    public function restoreForm(Request $request, $id)
    {
        $form = Form::find($id);
        if (!$form || count($form->records) > 0) {
            return redirect()->route('be_spoke_forms.be_spoke_form.index', ['error' => 'Form is not deleted.']);
        }

        $form->is_deleted = 1 - $form->is_deleted;
        $form->deleted_at = Carbon::now();
        $form->is_active = false;
        $form->is_archived = 0;
        if(empty($form->form_json)){
            $form->form_json = json_encode('[]');
        }
        $form->save();
        return redirect()->back()->with('success', 'Form restored Successfully.');
    }

    /**
     *  NHS Data from XML is stored into database.
     *  Please note the medicines are called Actual Medicial Products AMPS
     *  Name and Desc will be used for Querying DMDS.
     *  Latest Version  19 December 2022
     *  URL: https://isd.digital.nhs.uk/trud/user/guest/group/0/home
     *  @put file f_amp2_3151222.xml into storage/dmd
     * @return void
     */
    // public function SaveDmdsToDatabase(){
    //     return 'This function is disabled.';
    //     $xmlString = file_get_contents(storage_path('/dmd/f_amp2_3151222.xml'));
    //     $xmlObject = simplexml_load_string($xmlString);
    //     set_time_limit(0);
    //    foreach($xmlObject->AMPS->AMP as $amp){
    //         $dmd = new \App\Models\DMD();
    //         $dmd->APID = $amp->APID;
    //         $dmd->VPID =  $amp->VPID;
    //         $dmd->name = $amp->NM;
    //         $dmd->description = $amp->DESC;
    //         $dmd->SUPPCD = $amp->SUPPCD;
    //         $dmd->LIC_AUTHCD = $amp->LIC_AUTHCD;
    //         $dmd->AVAIL_RESTRICTCD = $amp->AVAIL_RESTRICTCD;
    //        // $dmd->save();
    //         dd( $dmd );
    //    }

    // }


    public function default_task_delete($id)
    {
        $default_task = DefaultTask::findOrFail($id);
        $task_documents = $default_task->documents;
        foreach ($task_documents as $task_document) {
            $document = $task_document->document;
            $path = storage_path('app/' . $document->path($document->folder) . '/' . $document->file_name);
            if (File::exists($path)) {
                unlink($path);
            }

            $document->delete();
            $task_document->delete();
        }
        $default_task->delete();
        return back()->with('success_message', 'task deleted successfully');
    }
    public function default_task_save(Request $request)
    {
        $type = $request->select_user_type;
        if ($type) {
            $profiles = $request->profiles;
            $type_ids = json_encode($profiles);
        } else {
            $users = $request->users;
            $type_ids = json_encode($users);
        }

        if ($request->has('form_id')) {
            $form = Form::findOrFail($request->form_id);
        }
        if ($request->has('default_task_id')) {
            $task = $form->defaultTasks()->find($request->default_task_id);
        } else {
            $task = new DefaultTask();
        }

        $task->be_spoke_form_id = $request->form_id;
        if(!isset($request->title) || $request->title == ''){
            return back()->with('error_message', 'Please enter the title.');
        } 
        $task->title = $request->title;
        $task->description = $request->description ?? '';


        $task->type = $type;
        $task->type_ids = $type_ids;
        if ($type == 2) {
            $task->type_ids = null;
        }

        //saving over due data
        if ($request->is_dead_line) {

            $task->is_dead_line = $request->is_dead_line;
            $task->dead_line_option = $request->dead_line_option;

            $task->dead_line_duration = Carbon::now()->add($request->dead_line_duration . " " . $request->dead_line_unit);

            $task->dead_line_duration = $request->dead_line_duration;
            $task->dead_line_unit = $request->dead_line_unit;
            $task->dead_line_start_from = $request->dead_line_start_from;
            $task->is_dead_line = $request->is_dead_line;
            if ($request->dead_line_option == 'move_user' || $request->dead_line_option == 'mail_user') {

                $data = json_encode($request->dead_line_user);
                $task->dead_line_user_id = $data;
                $task->dead_line_profile_id = null;
                $task->dead_line_over_due_email = null;

            } elseif ($request->dead_line_option == 'move_profile' || $request->dead_line_option == 'mail_profile') {
                $task->dead_line_user_id = null;
                $task->dead_line_profile_id = json_encode($request->dead_line_profile);
                $task->dead_line_over_due_email = null;
            } else {
                $task->dead_line_user_id = null;
                $task->dead_line_profile_id = null;
                $task->dead_line_over_due_email = $request->dead_line_email;
            }
        }

        if ($request->is_task_over_due) {
            $task->is_task_over_due = $request->is_task_over_due;
            $task->task_over_due_option = $request->task_over_due_option;

            $task->task_over_due_duration = $request->over_due_duration;
            $task->task_over_due_unit = $request->over_due_unit;

            if ($request->task_over_due_option == 'move_user' || $request->task_over_due_option == 'mail_user') {

                $task->task_over_due_user_id = json_encode($request->task_over_due_users);
                $task->task_over_due_profile_id = null;
                $task->task_over_due_email = null;

            } elseif ($request->task_over_due_option == 'move_profile' || $request->task_over_due_option == 'mail_profile') {
                $task->task_over_due_user_id = null;
                $task->task_over_due_profile_id = json_encode($request->task_over_due_profiles);
                $task->task_over_due_email = null;
            } else {
                $task->task_over_due_user_id = null;
                $task->task_over_due_email = null;
                $task->task_over_due_profile_id = null;
            }

        }

        $task->save();

        $documents = (array) $request->documents;
        DefaultTaskDocument::where('default_task_id', $task->id)->delete();
        foreach ($documents as $value) {
            $doc = new DefaultTaskDocument();
            $doc->default_task_id = $task->id;
            $value = Document::where('unique_id', $value)->first();
            if (!$value) {
                continue;
            }
            $doc->document_id = $value->id;
            $doc->type = ($value->isImage()) ? 'image' : 'document';
            $doc->save();
        }

        return back()->with('success_message', 'Default Task created successfully');

    }

    public function stage_default_task_save(Request $request)
    {
        $type = $request->select_user_type;
        if ($type) {
            $profiles = $request->profiles;
            $type_ids = json_encode($profiles);
        } else {
            $users = $request->users;
            $type_ids = json_encode($users);
        }

        try{
            DB::beginTransaction();
        if ($request->has('stage_id')) {
            $stage = DefaultCaseStage::findOrFail($request->stage_id);
            $l = $stage->default_tasks()->get()->last();
        }
        if ($request->has('default_task_id')) {
            $task = $stage->default_tasks()->find($request->default_task_id);
            $l = $stage->default_tasks()->get()->last();
        } else {
            $task = new DefaultCaseStageTask();
        }

        if ($l) {
            $label = $l->label + 1;
        } else {
            $label = 0;
        }

        $task->label = $label;

        $task->default_case_stage_id = $request->stage_id;
        if(!isset($request->title) || $request->title == ''){
            return back()->with('error','Please enter the title.');
        }
        $task->title = $request->title;
        $task->description = $request->description ?? '';
        $task->mandatory = $request->mandatory == 'on' ? 1 : 0;

        $task->type = $type;
        $task->type_ids = $type_ids;
        if ($type == 2) {
            $task->type_ids = null;
        }
        $task->save();

        if($request->is_dead_line){
            task_deadline_records::create(
                [
                    'default_case_stage_tasks_id' => $task->id,
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
        }
        if($request->is_task_over_due){
            task_deadline_records::create(
                [
                    'default_case_stage_tasks_id' => $task->id,
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
        }
        

        $documents = (array) $request->documents;
        DefaultCaseStageTaskDocument::where('default_case_stage_task_id', $task->id)->delete();
        foreach ($documents as $value) {
            $doc = new DefaultCaseStageTaskDocument();
            $doc->default_case_stage_task_id = $task->id;
            $value = Document::where('unique_id', $value)->first();
            if (!$value) {
                continue;
            }
            $doc->document_id = $value->id;
            $doc->type = ($value->isImage()) ? 'image' : 'document';
            $doc->save();
        }
        DB::commit();

        return back()->with('success_message', 'Default Task created successfully');
        }catch(\Exception $e){
            DB::rollBack();
            return back()->with('error', "Error occured while saving the Task " . $e->getMessage());
        }
        

    }

    public function stage_default_task_update(Request $request){
        $record = task_deadline_records::find($request->task_rec_id);
        if(!isset($record)){
            return redirect()->back()->with('error','Deadline task record not found!');
        }
            $record->update(
                [
                    'duration' => $request->is_dead_line ,
                    'unit' =>  $request->dead_line_unit ,
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
        
            return redirect()->back()->with('success','Deadline task updated!');
    }

    public function stage_default_task_delete($id){
        $record = task_deadline_records::find($id);
        if(isset($record)){
            $record->delete();
            return redirect()->back()->with('success','Deadline task record deleted!');
        }
        return redirect()->back()->with('error','Deadline task record not found!');

    }

    public function when_case_closed(Request $request, $id)
    {
        $u = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        $user = Auth::guard('web')->user()->selected_head_office;
        $form = $user->be_spoke_forms()->find($id);
        if ($form) {
            $form->is_case_close_priority = $request->is_case_close_priority ? 1 : 0;
            $form->case_close_priority_rule = $request->case_close_priority_rule;
            $form->case_close_priority_value = $request->case_close_priority_value;
            $form->case_close_priority_comment = $request->case_close_priority_comment;
            $form->requires_final_approval = $request->requires_final_approval ? 1 : 0;
            $form->save();
            return back()->with('success_message', 'Form updated successfully');
        }
        return back()->with('error', 'Form not found');
    }

    public function case_must_review(Request $request, $id)
    {
        $user = Auth::guard('web')->user()->selected_head_office;
        $form = $user->be_spoke_forms()->find($id);
        if ($form) {
            $form->requires_final_approval = $request->case_must_review ? 1 : 0;
            $form->save();
            return back()->with('success_message', 'From updated successfully');
        }
        return back()->with('error', 'Form not found');
    }
    public function rule_remove($form_id, $page_id,$item_id,$id)
    {
        $user = Auth::guard('web')->user()->selected_head_office;
        $form = $user->be_spoke_forms()->find($form_id);
        if ($form) {
            $form_json = empty($form->form_json) ? null : json_decode($form->form_json,true);
            if(!isset($form_json)){
                return back()->with('error', 'Form not found');
            }
            
            if(isset($form_json['pages'][$page_id]['items'][$item_id]['input']['conditions'][$id])) {
                unset($form_json['pages'][$page_id]['items'][$item_id]['input']['conditions'][$id]);
            } else {
                return back()->with('error', 'record not found!');
            }
            $form->form_json = json_encode($form_json);
            $form->save();
            return back()->with('success_message', 'Rule deleted successfully');
        }
        return back()->with('error', 'Form not found');
    }
    public function rule_edit($form_id, $page_id,$item_id,$id)
    {
        $user = Auth::guard('web')->user()->selected_head_office;
        $form = $user->be_spoke_forms()->find($form_id);
        if ($form) {
            $form_json = empty($form->form_json) ? null : json_decode($form->form_json,true);
            if(!isset($form_json)){
                return back()->with('error', 'Form not found');
            }
            
            if(isset($form_json['pages'][$page_id]['items'][$item_id]['input']['conditions'][$id])) {
                $queryParam = urlencode("$page_id,$item_id,$id");
                return redirect('/bespoke_form_v3/#!/form/'.$form_id.'?rule_edit='.$queryParam);
            }
            else if(isset($form_json['pages'][$page_id]['items'][$item_id])) {
                $queryParam = urlencode("$page_id,$item_id");
                return redirect('/bespoke_form_v3/#!/form/'.$form_id.'?rule_edit='.$queryParam);
            }
            
            else {
                return back()->with('error', 'record not found!');
            }
            return back()->with('success_message', 'Rule deleted successfully');
        }
        return back()->with('error', 'Form not found');
    }
    
    public function root_cause_analysis_requests()
    {

        $user = Auth::guard('location')->user();
        $requests = $user->root_cause_analysis;
        $type = null;
        if ($type == 'fish_bone') {
            return view('location.be_spoke_forms.fish_bone', compact('request'));
        }

        return view('location.be_spoke_forms.five_whys', compact('requests'));
    }
    public function root_cause_analysis_request($id, $type)
    {
        $user = Auth::guard('location')->user();
        $records = $user->records;
        $request = "";
        // foreach($records as $record)
        // {
        //     if($record->recorded_case)
        //     {
        //         $request = $record->recorded_case->root_cause_analysis()->find($id);
        //     }
        // }
        $request = RootCauseAnalysis::find($id);
        if (!$request) {
            return back()->with('error', 'Root Cause analysis not found');
        }

        $form_id = $request->root_cause_analysis_case->linked_location_incident->incident->form->id;
        if ($request->status == 0) {
            if (!$request || $request->type != $type) {
                return back()->with('error', 'Root Cause analysis not found');
            }

            //$request = $user->root_cause_analysis_requests;
            if ($type == 'fish_bone') {
                return view('location.be_spoke_forms.fish_bone', compact('request', 'id'));
            }

            return view('location.be_spoke_forms.five_whys', compact('request', 'id'));
        }
        return redirect()->route('head_office.be_spoke_forms.be_spoke_form.records', $form_id);
    }

    public function root_cause_analysis($id, $type)
    {
        $user = Auth::guard('location')->user();
        $records = $user->records;
        $head_office = $user->head_office_location;
        foreach ($records as $record) {
            if ($record->recorded_case) {
                $request = $record->recorded_case->root_cause_analysis()->where('type', $type)->first();
                continue;
            }
        }

        if ($type == 'five_whys') {
            $is_five_whys = 1;
            return view('location.be_spoke_forms.five_whys', compact('is_five_whys', 'request'));
        }

        if ($type == 'fish_bone') {
            $is_fish_bone = 1;
            return view('location.be_spoke_forms.fish_bone', compact('is_fish_bone', 'request'));
        }
        // if($head_office->head_office->is_five_whys && $head_office->head_office->is_fish_bone)
        // {
        //     $is_five_whys = 1;
        //     $is_fish_bone = 1;
        //     return view('location.be_spoke_forms.models',compact('is_fish_bone','is_five_whys','record'));
        // }
        return back()->with('error', 'No model requested for this record');
    }
    public function fish_bone($id)
    {
        $user = Auth::guard('location')->user();
        $record = $user->records()->find($id);
        $head_office = $user->head_office_location;
        if ($head_office->head_office->is_fish_bone) {
            $questions = $head_office->head_office->fish_bone_questions;
            return view('location.be_spoke_forms.fish_bone', compact('record', 'questions'));
        }
        return back()->with('error', 'You do not have access to this page');
    }
    public function form_card_save(Request $request)
    {
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        $form = $user->selected_head_office->be_spoke_forms()->findOrFail($request->form_id);
        if (FormCard::where([['be_spoke_form_id', $form->id]/*,['default_card_id',$request->default_card_id]*/, ['name', $request->name]])->first()) {
            return back()->with('error', 'Card with same name already assigned');
        }

        if ($request->link_ids) {
            /// form_card->id ==
            $link_ids = $request->link_ids;
            $exist_count = 1;
            if ($request->has('default_card_id')) // card is being edited
            {
                $form_card = $form->formCards()->findOrFail($request->default_card_id);
                //dd($form_card);
                //$link_ids[] = $form_card->group_id;
                if (!$form_card->connected_form_card) {
                    $exist_count = 0;
                }

            } else // card is new
            {
                $exist_count = 0;
            }

            $existing = ConnectedFormCard::whereIn('form_card_id', $link_ids)->whereNotNull('group_id')->groupBy('group_id')->get('group_id')->count();
            // dd($existing, $exist_count);
            if ($existing > $exist_count) {
                return back()->with('error', 'At least one of the selected Links are already in another group !');
            }
        }

        if ($request->has('default_card_id')) {
            $form_card = $form->formCards()->find($request->default_card_id);
        } else {
            $form_card = new FormCard();
        }

        // $form_card->default_card_id = $request->card_id;
        $form_card->name = $request->card_name;
        $form_card->be_spoke_form_id = $form->id;
        $form_card->save();

        //delete old links //
        $form_card->group_del()->delete();

        if ($request->link_ids) {
            $linked_form_card = new ConnectedFormCard();
            $linked_form_card->form_card_id = $form_card->id;
            $linked_form_card->group_id = $form_card->id;
            $linked_form_card->save();
            foreach ($request->link_ids as $id) {
                $linked_form_card = new ConnectedFormCard();
                $linked_form_card->form_card_id = $id;
                $linked_form_card->group_id = $form_card->id;
                $linked_form_card->save();
            }
        }

        return back()->with('success_message', 'Card assigned');
    }
    public function form_card_delete($id)
    {
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        $forms = $user->selected_head_office->be_spoke_forms;
        foreach ($forms as $form) {
            $f = $form->formCards()->find($id);
            if ($f) {

                if (ConnectedFormCard::where('group_id', $f->id)) {
                    ConnectedFormCard::where('group_id', $f->id)->delete();
                }

                $f->delete();
                return back()->with('success_message', 'Card Delated');
            }
        }
        return back()->with('error', 'Card not found');
    }
    public function form_card_fields(Request $request): Response
    {
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        $forms = $user->selected_head_office->be_spoke_forms;
        foreach ($forms as $form) {
            $f = $form->formCards()->find($request->id);
            if ($f) {
                $data['result'] = true;
                $data['fields'] = $f->default_card->fields;
                return response($data);
            }

        }
        $data['result'] = false;
        return response($data);
    }
    public function store_question_answer(Request $request): Response
    {
        $data['result'] = false;
        // $record_id = (int)$request->record_id;
        $question_id = (int) $request->question_id;
        $root_cause_analysis_id = (int) $request->root_cause_analysis_id;

        $a = $request->answer;
        $answer = null;
        $user = Auth::guard('location')->user(); //
        // $records = $user->records;
        $data['msg'] = 'record not found';

        // foreach($records as $record)
        // {
        //     if($record->recorded_case)
        //     {
        //         $root_cuse_analysis = $record->recorded_case->root_cause_analysis()->find($root_cause_analysis_id);
        //     }
        // }

        $root_cuse_analysis = RootCauseAnalysis::find($root_cause_analysis_id);
        $data['msg'] = 'root_cause_analysis not found';
        if (!$root_cuse_analysis) {
            return response($data);
        }

        $question = $root_cuse_analysis->type == 'fish_bone' ? $root_cuse_analysis->fish_bone_questions()->find($question_id) : $root_cuse_analysis->five_whys_questions()->find($question_id);
        $data['msg'] = 'root_cause_analysis -> fish_bone_questions not found';
        if (!$question) {
            return response($data);
        }

        if ($request->has('answer_id')) {
            if ($question->answers()->find($request->answer_id)) {
                $answer = $question->answers()->find($request->answer_id);
            }
        }
        if (!$answer) {
            if ($root_cuse_analysis->type == 'fish_bone') {
                if ((count($question->answers) >= 4) && !$request->has('answer_id')) {
                    $data['msg'] = 'already have 4 answers';
                    return response($data);
                }
            }
            $answer = $root_cuse_analysis->type == 'fish_bone' ? new FishBoneRootCauseAnalysisAnswer() : new FiveWhysRootCauseAnalysisAnswer;
        }
        $data['result'] = true;
        $root_cuse_analysis->type == 'fish_bone' ? $answer->fish_bone_root_cause_analysis_id = $question_id : $answer->five_whys_root_cause_analysis_id = $question_id;
        $answer->answer = $request->answer;
        $answer->save();
        $data['answer'] = $a;

        return response($data);
    }
    public function root_cause_analysis_save(Request $request, $id)
    {
        $problem = $request->problem;

        $user = Auth::guard('location')->user(); //
        $u = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        $records = $user->records;

        foreach ($records as $record) {
            if ($record->recorded_case) {
                $root_cause_analysis = $record->recorded_case->root_cause_analysis()->find($id);
                if ($root_cause_analysis) {
                    $root_cause_analysis->status = 1;
                    $root_cause_analysis->completed_by = $u->id;
                    if ($request->has('problem')) {
                        $root_cause_analysis->name = $problem;
                    }

                    $root_cause_analysis->save();
                    return back()->with('success_message', 'Saved successfully');
                }
            }

        }
        return back()->with('success_message', 'Not found');

    }
    public function root_cause_analysis_answer_delete(Request $request): Response
    {
        $user = Auth::guard('location')->user(); //
        $records = $user->records;

        foreach ($records as $record) {
            if ($record->recorded_case && $record->recorded_case->root_cause_analysis) {
                foreach ($record->recorded_case->root_cause_analysis as $analysis) {
                    foreach ($analysis->fish_bone_questions as $question) {
                        $answer = $question->answers()->find($request->id);
                        if ($answer) {
                            $a = FishBoneRootCauseAnalysisAnswer::find($answer->id);
                            if ($a) {
                                $a->delete();
                                $data['result'] = true;
                                $data['answer'] = $a;
                                return response($data);
                            }
                        }
                    }

                }
            }
        }
        $data['result'] = false;
        return response($data);
    }

    public function is_allow_non_approved_emails_route(Request $request)
    {
        $form_id = $request->form_id;
        $is_allow_non_approved_emails = $request->is_allow_non_approved_emails;

        $user = Auth::guard('web')->user()->selected_head_office;
        $form = $user->be_spoke_forms()->find($form_id);
        if ($form) {
            if ($is_allow_non_approved_emails) {
                $form->is_allow_non_approved_emails = 1;
            } else {
                $form->is_allow_non_approved_emails = 0;
            }

            $form->save();
            return response(['result' => true]);
        }
        return response(['result' => false]);
    }
    public function saveUpdate(Request $request, $id)
    {
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user(); //
        $location = Auth::guard('location')->user(); //
        $record = $location->records->find($id);
        if ($record) {
            $update = new FormRecordUpdate();
            $update->be_spoke_form_record_id = $record->id;
            $update->update = $request->update;
            $update->user_id = $user->id;
            $update->save();
            $attachedDocuments = [];
            if ($request->documents) {
                foreach ($request->documents as $document) {
                    
                    $value = Document::where('unique_id', $document)->first();
                    if (!$value) {
                        continue;
                    }
                    $attachedDocuments[] = $value->name;
                    $update_document = new FormRecordUpdateDocument();
                    $update_document->document_id = $value->id;
                    $update_document->be_spoke_form_record_update_id = $update->id;
                    $update_document->type = ($value->isImage()) ? 'image' : 'document';
                    $update_document->save();
                }
            }
            $case_id = $record->case_id ?? ($record->recorded_case->id ?? null);
    
            if ($case_id) {
                $case = HeadOfficeCase::find($case_id);
                if(isset($case)){
                    $case->status = 'Updated';
                    $case->save();

                    if (count($case->case_handlers) == 0) {
                        $ho = $case->case_head_office;
                        $users = $ho->users;
                    
                        // Check if there are any users
                        if ($users->isNotEmpty()) {
                            // Pick a random user
                            $randomUser = $users->random();
                    
                            // Create a new case handler
                            $new_case_handler = new CaseHandlerUser();
                            $new_case_handler->case_id = $case->id;
                            $new_case_handler->head_office_user_id = $randomUser->id;  // Assign the random user's ID
                            $new_case_handler->save();
                        }
                    }
                    
                }
                $comment = new Comment();
                $comment->case_id = $case_id;
                $comment->user_id = $user->id;
                $comment->comment =  $user->name . ' submitted an update from ' . ' location'.' '. $location->location_code ;
                $comment->type =  'update submited from location' ;
                $comment->record_update_id = $update->id;
                $comment->save();
    
                ActivityLog::create([
                    'user_id' => $user->id,
                    'head_office_id' => $user->selected_head_office?->id,
                    'action' => 'Record # ' . $record->id . ' updated by ' . $user->name . 
                                (empty($attachedDocuments) ? '' : ' with documents: ' . implode(', ', $attachedDocuments)),
                    'type' => 'Record Update',
                    'timestamp' => now(),
                ]);
            } else {
                dd('asdfa');
                return back()->with('error', 'Record has no associated case.');
            }
            return back()->with('success', 'Record Updated successfully');
        }
        return back()->with('error', 'Record not found');
    }
    public function default_stage_save(request $request)
    {
        $stage_id = $request->stage_id;
        $head_office = Auth::guard('web')->user()->selected_head_office; //
        $form_id = $request->form_id;
        $form = $head_office->be_spoke_forms()->find($form_id);
        $name = $request->name;
        $edit = false;

        $label = null;

        $stage = new DefaultCaseStage();
        if ($stage_id) {
            $edit = true;
            $stages = $form->default_stages()->where([['id', '!=', $stage_id], ['name', $name]]);
            if ($stages->count()) {
                return response(['result' => false, 'msg' => 'Name already assign to stage form this form']);
            }

            $stage = $form->default_stages()->find($stage_id);

        } else {
            $stages = $form->default_stages()->where('name', $name);
            if ($stages->count()) {
                if (isset($request->add)) {
                    $allExistingNames = $form->default_stages()->pluck('name')->toArray();
                    $org_name = $name;
                    $counter = 1;
                    while (in_array($name, $allExistingNames)) {
                        $name = $org_name . ' ' . $counter;
                        $counter++;
                    }
                    $l = $form->default_stages()->orderBy('label')->get()->last();
                    if ($l) {
                        $label = $l->label + 1;
                    } else {
                        $label = 0;
                    }

                    $stage->label = $label;
                } else {
                    return response(['result' => false, 'msg' => 'Name already assign to stage form this form']);
                }
            } else {
                $l = $form->default_stages()->orderBy('label')->get()->last();
                if ($l) {
                    $label = $l->label + 1;
                } else {
                    $label = 0;
                }

                $stage->label = $label;
            }
        }
        $stage->be_spoke_form_id = $form_id;
        $stage->name = $name;
        $stage->save();
        $task = null;
        $view = '' . view('head_office.be_spoke_forms.default_case_stage_task', compact('stage', 'task')) . '';
        return response(['result' => true, 'msg' => 'Stage updated successfully', 'view' => $view, 'stage' => $stage, 'edit' => $edit]);

    }
    public function default_stage_delete(request $request)
    {
        $stage_id = $request->stage_id;
        $head_office = Auth::guard('web')->user()->selected_head_office; //
        $form_id = $request->form_id;
        $form = $head_office->be_spoke_forms()->find($form_id);
        $name = $request->name;

        $stage = $form->default_stages()->find($stage_id);
        $stage->delete();

        return response(['result' => true, 'msg' => 'Stage updated successfully']);

    }
    public function swap_stage_route(Request $request): Response
    {
        $label_1 = $request->label_1;
        $label_2 = $request->label_2;
        $stage_id_1 = $request->stage_1_id;
        $stage_id_2 = $request->stage_2_id;
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $form = $head_office->be_spoke_forms()->find($request->form_id);

        $stage_1 = $form->default_stages()->find($stage_id_1);
        // return response(['result'=> $stage_1]);
        $stage_1->label = $label_1;
        $stage_1->save();

        $stage_2 = $form->default_stages()->find($stage_id_2);
        $stage_2->label = $label_2;
        $stage_2->save();

        return response(['result' => true]);
    }

    public function swap_task_route(Request $request): Response
    {
        $label_1 = $request->label_1;
        $label_2 = $request->label_2;

        $stage_1_id = $request->stage_1_id;
        $stage_2_id = $request->stage_2_id;

        $task_1_id = $request->task_1_id;
        $task_2_id = $request->task_2_id;
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $form = $head_office->be_spoke_forms()->find($request->form_id);

        $stage_1 = $form->default_stages()->find($stage_1_id);
        if ($stage_1) {
            $label_1_task = $stage_1->default_tasks()->orderBy('label');

            if (!$label_1_task) {
                $label_1 = 0;
            }

            $task_1 = DefaultCaseStageTask::find($task_1_id);

            if ($task_1) {
                $task_1->default_case_stage_id = $stage_1->id;
                $task_1->label = $label_1;
                $task_1->save();
            }
        }
        $stage_2 = $form->default_stages()->find($stage_2_id);
        //return response(['stage_2' => $stage_2,'stage1' => $stage_1]);
        if ($stage_2) {

            $label_2_task = $stage_2->default_tasks()->orderBy('label');

            if (!$label_2_task && !$label_2) {
                $label_2 = 0;
            }

            $task_2 = DefaultCaseStageTask::find($task_2_id);

            if ($task_2) {
                // return response(['task_2' => $task_2]);
                $task_2->default_case_stage_id = $stage_2->id;
                $task_2->label = $label_2;
                $task_2->save();
            }

        }

        return response(['result' => true]);
    }

    /// For Company ///
    public function getFormJson(Request $request, $id)
    {
        $user = Auth::guard('web')->user();
        $head_office = isset($user) ? $user->selected_head_office : null;
        if(!isset($head_office)){
            $form = Form::find($id);
        }else{
            $form = $head_office->be_spoke_forms->find($id);
        }
        if(!isset($form)){
            return response('not found', 404);
        }
        if(isset($head_office)){
            $forms = $head_office->be_spoke_forms->where('is_active',1)->map(function ($form) {
                return [
                    'id' => $form['id'] ?? null,
                    'name' => $form['name'] ?? null,
                ];
            })->toArray();
            $locations = $head_office->locations->map(function ($loc){
                return [
                    'id' => $loc->id,
                    'text' => $loc->location->trading_name
                ];
            });
            $users = $head_office->users->map(function ($u){
                return [
                    'id' => $u->id,
                    'name' => $u->user->name
                ];
            });
            $profiles = $head_office->head_office_user_profiles->map(function ($p){
                return [
                    'id' => $p->id,
                    'name' => $p->profile_name
                ];
            });
            $tags = $head_office->location_tags->map(function($t) {
                return [
                    'id' => $t->id,
                    'name' => $t->name
                ];
            });
        }else{
            $forms = [];
            $users = [];
            $profiles = [];
            $locations = [];
            $tags = [];
        }
        
        $json = $form->form_json;
        $default_stages = $form->default_stages->map(function ($stage) {
            return [
                'id' => $stage['id'] ?? null,
                'name' => $stage['name'] ?? null
            ];
        });
        $external = $form->is_external_link == 1 ? true : false;

        return response([
            'data' => $json,
            'form_name'=>$form->name,
            'forms'=>$forms,
            'default_stages'=>$default_stages,
            'users'=>$users,
            'profiles'=>$profiles,
            'locations'=>$locations,
            'is_external' => $external,
            'tags' => $tags
        ]);
    }

    public function getFormTaskJson(Request $request, $id)
    {
        $user = Auth::guard('web')->user();
        $head_office = isset($user) ? $user->selected_head_office : null;
        $form = DefaultCaseStageTask::find($id);
        if(!isset($form)){
            return response('not found', 404);
        }
        if(!isset($form->stage) || !isset($form->stage->form) || $form->stage->form->form_owner->id != $head_office->id){
            return response('not found', 404);
        } 
        if(isset($head_office)){
            $forms = $head_office->be_spoke_forms->where('is_active',1)->map(function ($form) {
                return [
                    'id' => $form['id'] ?? null,
                    'name' => $form['name'] ?? null,
                ];
            })->toArray();
            $locations = $head_office->locations->map(function ($loc){
                return [
                    'id' => $loc->id,
                    'text' => $loc->location->trading_name
                ];
            });
            $users = $head_office->users->map(function ($u){
                return [
                    'id' => $u->id,
                    'name' => $u->user->name
                ];
            });
            $profiles = $head_office->head_office_user_profiles->map(function ($p){
                return [
                    'id' => $p->id,
                    'name' => $p->profile_name
                ];
            });
            $tags = $head_office->location_tags->map(function($t) {
                return [
                    'id' => $t->id,
                    'name' => $t->name
                ];
            });
        }else{
            $forms = [];
            $users = [];
            $profiles = [];
            $locations = [];
            $tags = [];
        }
        if(empty(json_decode($form->form_json,true))){
            $form_json = '{"id":-4,"tracker":4,"title":"Dispensing and Supply","pages":[{"id":-108,"tracker":35,"name":"New Page","items":[],"order":6,"type":"page","draging":false,"has_over":false,"is_required":false,"allow_only_one":false,"is_nhs_hidden":false}],"show_progress":false,"fill_bar_color":"#68bb55","next_btn_text":"Next","next_btn_color":"#72C4BA","submit_btn_text":"Submit","submit_btn_color":"#72C4BA","font_size":34,"text_color":"#000000","bg_color":"#ffffff","involvements":[]}';
            $form_data = json_decode($form_json, true);
            $form_data['title'] = $form->name;
            $form->form_json = json_encode($form_data);
        }
        
        $json = $form->form_json;
        $default_stages = $form->stage->form->default_stages->map(function ($stage) {
            return [
                'id' => $stage['id'] ?? null,
                'name' => $stage['name'] ?? null
            ];
        });
        $external = $form->is_external_link == 1 ? true : false;

        return response([
            'data' => $json,
            'form_name'=>$form->title ?? 'Task form',
            'forms'=>$forms,
            'main_form_id'=>$form->stage->form->id,
            'default_stages'=>$default_stages,
            'users'=>$users,
            'profiles'=>$profiles,
            'locations'=>$locations,
            'is_external' => $external,
            'tags' => $tags
        ]);
    }
    public function delFormTaskJson(Request $request, $id)
    {
        $user = Auth::guard('web')->user();
        $head_office = isset($user) ? $user->selected_head_office : null;
        $form = DefaultCaseStageTask::find($id);
        if(!isset($form)){
            return response('not found', 404);
        }
        if(!isset($form->stage) || !isset($form->stage->form) || $form->stage->form->form_owner->id != $head_office->id){
            return response('not found', 404);
        } 
        
        
        $form->form_json = null;
        $form->save();

        return redirect()->back();
    }
    public function getFormJsonTemp(Request $request, $id)
    {
        $user = Auth::guard('web')->user()->getHeadOfficeUser();
        $form = Form::findOrFail($id);
        $temp_form = temp_forms::where('form_id',$id)->first();
        if(!isset($temp_form)){
            $temp_form = new temp_forms();
            $temp_form->form_id = $id;
            $temp_form->head_office_user_id = $user->id;
            $temp_form->form_json = $form->form_json;
            $temp_form->save();
        }elseif(!isset($temp_form->form_json) || empty($temp_form->form_json)){
            $temp_form->form_json = $form->form_json;
            $temp_form->save();
        }

        $json = $temp_form->form_json;
        return response(['data' => $json,'form_name'=>$form->name]);
    }

    public function getFormTaskJsonTemp(Request $request, $id)
    {
        $user = Auth::guard('web')->user()->getHeadOfficeUser();
        $form = DefaultCaseStageTask::find($id);
        if(!isset($form)){
            return response('not found', 404);
        }
        if(!isset($form->stage) || !isset($form->stage->form) || $form->stage->form->form_owner->id != $user->head_office_id){
            return response('not found', 404);
        } 
        $temp_form = temp_task_forms::where('task_id',$id)->first();
        if(!isset($temp_form)){
            $temp_form = new temp_task_forms();
            $temp_form->task_id = $id;
            $temp_form->head_office_user_id = $user->id;
            $temp_form->head_office_id = $user->head_office_id;
            $temp_form->form_json = $form->form_json;
            $temp_form->save();
        }elseif(!isset($temp_form->form_json) || empty($temp_form->form_json)){
            $temp_form->form_json = $form->form_json;
            $temp_form->save();
        }

        $json = $temp_form->form_json;
        return response(['data' => $json,'form_name'=>$form->name]);
    }
    public function getFormJsonEdit(Request $request, $id)
    {
        $location = Auth::guard('location')->user();
        if(isset($location)){
            $head_office = Auth::guard('user')->user()->selected_head_office; //
        }else{
            $user = Auth::guard('web')->user();
            if(!isset($user)){
                return response('Unauthorized', 401);
            }
            $head_office = Auth::guard('web')->user()->selected_head_office; //
        }
        if(!isset($head_office)){
            return response('Unauthorized', 401);
        }
        $form = Record::find($id);   
        $json = $form->raw_form;
        return response(['data' => $json,'form_name'=>$form->name]);
    }

    public function getDraftFormJson(Request $request, $id)
    {
        $draft = be_spoke_form_record_drafts::find($id);
        $form = Form::find($draft->form_id);
        $json = $draft->json_submission;
        return response(['data' => $json,'form_id' => $draft->form_id,"form_type" => $form->is_external_link ? 'external' : 'internal']);
    }

    public function saveFormJson(Request $request, $id)
    {
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        $ho_u = $user->getHeadOfficeUser();
        $head_office = $user->selected_head_office; //
        $form = $head_office->be_spoke_forms()->findOrFail($id);
        $temp_form = temp_forms::where('form_id',$id)->first();
        if(!isset($temp_form)){
            $temp_form = new temp_forms();
            $temp_form->head_office_user_id = $ho_u->id;
            $temp_form->form_id = $id;
            $temp_form->form_json = $request->data;
            $temp_form->save();
        }else{
            $temp_form->form_json = $request->data;
            $temp_form->save();
        }
        $form->form_json = $request->data;
        $form->save();
        return response(['result' => true]);
    }
    public function saveFormTaskJson(Request $request, $id)
    {
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        $ho_u = $user->getHeadOfficeUser();
        $head_office = $user->selected_head_office; //
        $form = DefaultCaseStageTask::find($id);
        if(!isset($form)){
            return response('not found', 404);
        }
        if(!isset($form->stage) || !isset($form->stage->form) || $form->stage->form->form_owner->id != $head_office->id){
            return response('not found', 404);
        } 
        $temp_form = temp_task_forms::where('task_id',$id)->first();
        if(!isset($temp_form)){
            $temp_form = new temp_task_forms();
            $temp_form->head_office_user_id = $ho_u->id;
            $temp_form->head_office_id = $head_office->id;
            $temp_form->task_id = $id;
            $temp_form->form_json = $request->data;
            $temp_form->save();
        }else{
            $temp_form->form_json = $request->data;
            $temp_form->save();
        }
        $form->form_json = $request->data;
        $form->save();
        return response(['result' => true]);
    }
    public function saveFormJsonTemp(Request $request, $id)
    {
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        $ho_u = $user->getHeadOfficeUser();
        $head_office = $user->selected_head_office; //
        $temp_form = temp_forms::where('form_id',$id)->first();
        if(!isset($temp_form)){
            $temp_form = new temp_forms();
            $temp_form->head_office_user_id = $ho_u->id;
            $temp_form->form_id = $id;
            $temp_form->form_json = $request->data;
            $temp_form->save();
        }else{
            $temp_form->form_json = $request->data;
            $temp_form->save();
        }
        return response(['result' => true]);
    }
    public function saveFormTaskJsonTemp(Request $request, $id)
    {
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        $ho_u = $user->getHeadOfficeUser();
        $head_office = $user->selected_head_office; //
        $temp_form = temp_task_forms::where('task_id',$id)->first();
        if(!isset($temp_form)){
            $temp_form = new temp_task_forms();
            $temp_form->head_office_user_id = $ho_u->id;
            $temp_form->head_office_id = $head_office->id;
            $temp_form->task_id = $id;
            $temp_form->form_json = $request->data;
            $temp_form->save();
        }else{
            $temp_form->form_json = $request->data;
            $temp_form->save();
        }
        return response(['result' => true]);
    }

    public function testSubmitFormJson(Request $request, $id)
    {
        //we can retrive form with id and we can apply validation in real case
        $head_office = Auth::guard('web')->user()->selected_head_office; //
        $form = $head_office->be_spoke_forms()->findOrFail($id);

        $request_json = $request->data;
        $request_form = json_decode($request_json,true);

        //prepare the request for nhs lfpse
        $all_questions = [];
        foreach($request_form['pages'] as $page)
        {
            foreach($page['items'] as $item)
            {
                if($item['is_nhs_field'] && $item['type'] == "field" && array_key_exists('nhs_extension_url', $item))
                    $all_questions[$item['nhs_extension_url']] = $item['input'];
            }
        }

        //location
        $location_extensions_set = [];
        $location_extensions_set[] = new ExtensionInner("LocationKnown", valueCode: $all_questions["LocationKnown"]['value']);
        if($all_questions["LocationKnown"]['value'] != 'u') // if y or n
            $location_extensions_set[] = new ExtensionInner("Organisation", valueCode: $all_questions["Organisation"]['value']);
        //Other if not available
        $location_extensions_set[] = new ExtensionInner("LocationWithinService", valueCode: $all_questions["LocationWithinService"]['value']);
        foreach($all_questions["ServiceArea"]['value'] as $sav)
            $location_extensions_set[] = new ExtensionInner("ServiceArea", valueCode: $sav);
        $location_extensions_set[] = new ExtensionInner("ResponsibleSpecialty", valueCode: $all_questions["ResponsibleSpecialty"]['value']);
        //other if not available
        $location_extension = new Extension($location_extensions_set, "https://psims-uat.azure-api.net/taxonomy/fhir/StructureDefinition/location-details-5");
        $location = new Contained("Location", "location1",[$location_extension]);

        $practitioner_extensions_set = [];
        $practitioner_extensions_set[] = new ExtensionInner("ReporterType", valueCode: "3"); // 3 means i am member of staff. You need to collect it from questions side
        $practitioner_extensions_set[] = new ExtensionInner("ReporterOrganisation", valueCode: $all_questions["Organisation"]['value']);
        $practitioner_extension = new Extension($practitioner_extensions_set, "https://psims-uat.azure-api.net/taxonomy/fhir/StructureDefinition/practitioner-details-5");
        $practitioner = new Contained("Practitioner", "practitioner1", [$practitioner_extension]);

        //after location//
        $additional_extensions_set = [];

        // Adverse Event Agents
        $ae_agents_set = [];
        foreach($all_questions["InvolvedAgents"]['value'] as $ia){
            // additional tasks can also be collected at this point !
            $ae_agents_set[] = new ExtensionInner("InvolvedAgents", valueCode: $ia);

            if($ia == "11"){
                $ae_agents_set[] = new ExtensionInner("SabreReportNumber", valueString: $all_questions["SabreReportNumber"]['value']);
                $ae_agents_set[] = new ExtensionInner("ShotReportNumber", valueString: $all_questions["ShotReportNumber"]['value']);
            }
            if($ia == "8"){
                $ae_agents_set[] = new ExtensionInner("NhsbtReportNumber", valueString: $all_questions["NhsbtReportNumber"]['value']);
            }
        }
        $additional_extensions_set[] = new Extension($ae_agents_set, "https://psims-uat.azure-api.net/taxonomy/fhir/StructureDefinition/adverse-event-agent-5");

        // Adverse Event Safety Challenges
        $ae_safety_challenges_set = [];
        foreach($all_questions["SafetyChallenges"]['value'] as $sc){
            $ae_safety_challenges_set[] = new ExtensionInner("SafetyChallenges", valueCode: $sc);
        }
        if($all_questions["SafetyChallenges"]['value'] == "4")
            $ae_safety_challenges_set[] = new ExtensionInner("RadiotherapyIncidentCode", valueString: $all_questions["RadiotherapyIncidentCode"]['value']);
        if($all_questions["SafetyChallenges"]['value'] == "7")
            $ae_safety_challenges_set[] = new ExtensionInner("MarvinReferenceNumber", valueString: $all_questions["MarvinReferenceNumber"]['value']);
        $additional_extensions_set[] = new Extension($ae_safety_challenges_set, "https://psims-uat.azure-api.net/taxonomy/fhir/StructureDefinition/adverse-event-safety-challenges-5");

        //Adverse Event Estimated Date
        $ae_estimated_date_set = [];
        $ae_estimated_date_set[] = new ExtensionInner("IncidentOccurredToday", valueCode: $all_questions["IncidentOccurredToday"]['value']);
        if($all_questions["IncidentOccurredToday"]['value'] != 'u')
            $ae_estimated_date_set[] = new ExtensionInner("TodaysDate", valueDate: $all_questions["TodaysDate"]['value']);//need to set it from the other side as well
        if($all_questions["IncidentOccurredToday"]['value'] == 'u')
            $ae_estimated_date_set[] = new ExtensionInner("ApproximateDate", valueString: $all_questions["ApproximateDateYear"]['value'] . "-" . $all_questions["ApproximateDateMonth"]['value']);
        $ae_estimated_date_set[] = new ExtensionInner("PreciseTime", valueTime: explode(".",explode("T", $all_questions["PreciseTime"]['value'])[1])[0]); // probably need to extract as 24 hours time.
        $additional_extensions_set[] = new Extension($ae_estimated_date_set, "https://psims-uat.azure-api.net/taxonomy/fhir/StructureDefinition/adverse-event-estimated-date-5");
        //test.split('T')[1].split('.')[0]
        //Adverse Event Risk
        $patients_count = 0; // temp variable //
        if($patients_count == 0){
            $ae_risk_set = [];
            $ae_risk_set[] = new ExtensionInner("RiskImminent", valueCode: $all_questions["RiskImminent"]['value']);
            if($all_questions["RiskImminent"]['value']){
                $ae_risk_set[] = new ExtensionInner("RiskPopulation", valueString: $all_questions["RiskPopulation"]['value']);
            }
            $additional_extensions_set[] = new Extension($ae_risk_set, "https://psims-uat.azure-api.net/taxonomy/fhir/StructureDefinition/adverse-event-risk-details-5");   
        }

        // Adverse Event Classification
        $ae_concern_ext = [];
        $ae_concern_ext[] = new ExtensionInner("LevelOfConcern", valueCode: $all_questions["LevelOfConcern"]['value']);
        $additional_extensions_set[] = new Extension($ae_concern_ext, "https://psims-uat.azure-api.net/taxonomy/fhir/StructureDefinition/adverse-event-classification-5");
        
        
        $lfpse_obj = new Root([$location, $practitioner], $additional_extensions_set, "1", null, "2024-03-11", new Nhs_LFPSELocation("#location1"), new Recorder("#practitioner1"), "test description");

        $lfpse_json = json_encode($lfpse_obj);

        //before submission to LFPSE, we can save this record !
        $record = new Record();
        $record->form_id = $form->id;
        $record->location_id = 1;// temp assign !
        $record->user_id = 1;// temp assign !
        $record->priority = 1;//calculate from the form conditions !
        $record->json_submission = $request_json; // need to clean it and get values only !
        $record->save();
        
        
        
        // sending data to LFPSE
        if(config('lfpse.service_active'))
        {
            $url = config('lfpse.api_endpoint') . "/adverse-event/fhir/AdverseEvent";    
            $response = Http::withHeaders([
                'Content-type' => 'application/json',
                'Ocp-Apim-Subscription-Key' => config('lfpse.ocp_apim_subscription_key')
            ])->withOptions(['verify'=> false])->withBody($lfpse_json, 'application/json')->post($url);
            
            $status = $response->status();
            if($status > 200 && $status < 299)
            {
                $result_json = $response->body();
                $outcome = json_decode($result_json, true);
                $resource_id = $outcome['id'];
                $issues_details = [];
                if($outcome['resourceType'] == 'OperationOutcome')
                {
                    foreach($outcome['issue'] as $issue){
                        $issues_details[] = $issue['details']['text']; // though this should be tested thoroughly !
                    }
                }
                // you can save this data !
                $lfpse_sub = new LfpseSubmission();
                $lfpse_sub->lfpse_id = $resource_id;
                $lfpse_sub->be_spoke_form_records_id = $record->id;
                if($outcome['resourceType'] == "AdverseEvent")
                    $lfpse_sub->version = $outcome['meta']['versionId'];
                $lfpse_sub->outcome_type = $outcome['resourceType'];
                $lfpse_sub->remarks = implode(", ", $issues_details);
                $lfpse_sub->save();
                return response(['result' => true]);
            }
        }
        


        return response(['result' => false]);
    }

    // For Location ///
    public function getLocationFormJson(Request $request, $id)
    {
        $location = Auth::guard('location')->user() ?? Location::find($request->query('location_id'));
        if(!isset($location)){
            return response('Unauthorized', 401);
        }
        $head_office = $location->head_office();
        if($head_office)
        {
            $form = $head_office->be_spoke_forms()->findOrFail($id);
            $json = $form->form_json;
            $ods = isset($location->ods_name) ? $location->ods_name : "NHS England (Z45)";
            $ods_value = null;
            if(isset($location->ods_name)){
                $ods_value = LfpseOption::selectRaw("id, CONCAT(val, ' (', code, ')') as text, code")
                ->where('code', 'LIKE', "%" . $location->ods_name . "%")
                ->first();
            }
            $submission_text = null;
            if(isset($form->show_submission_loc) && $form->show_submission_loc == false && $form->show_to_responder == true){
                $submission_text = $form->submission_text ?? null;
            }
            if(isset($location) && isset($location->organization_setting_assignment)){
                $location_logo = $location->organization_setting_assignment->organization_setting->setting_logo();
            }else{
                $location_logo = '';
            }
            return response(['data' => $json,
            "form_type" => $form->is_external_link ? 'external' : 'internal',
            "form_name" => $form->name,
            'ods' => $ods,
            'submission_text' => $submission_text,
            'location_logo'=>$location_logo,
            'ods_value' => $ods_value
        ]);
        }
        return response(['data' => []]);
    }

    public function  submitLocationFormJson(Request $request, $id)
    {
        $form = Form::where('id',$id)->first();
        $uniq_user = Auth::guard('user')->user() ?? Auth::guard('web')->user();

        if($request->query('external') == 1){
            $location = Location::where('email','external@qitech.com')->first();
            $orglocation = Location::where('email','external@qitech.com')->first();
            $headOffice = $form->form_owner;
            $user = User::where('email','external@qitech.com')->first();
        }else{
            $location = Auth::guard('location')->user() ?? Location::find($request->query('location_id')); 
            $orglocation = Auth::guard('location')->user() ?? Location::find($request->query('location_id')); 
            $headOffice = $location->head_office();
            $user = (Auth::guard('user')->user()) ? Auth::guard('user')->user() : null;
            if($request->query('location_id') && !isset($uniq_user)){
                $user = User::where('email','external@qitech.com')->first();
            }
        }

          // =================== Form Submission Limits =====================
        if(!isset($form)){
            return response('form not found',404);
        }

        if($form->active_limit_by_amount){
            if ($form->amount_total_max_res && $form->limits  > 0 && Record::where('form_id', $form->id)->count() >= $form->limits) {
                return response('Total submission limit reached.', 403);
            }
            if(isset($user)){
                //check for user
                if ($form->limit_to_one_user && $form->limit_by_per_user_value > 0 && Record::where('user_id', $user->id)->where('form_id', $form->id)->count() >= $form->limit_by_per_user_value) {
                    return response('User submission limit reached.', 403);
                }
            }
    
                // Check per location limit
                if ($form->limit_to_one_location && $form->limit_by_per_location_value > 0 && Record::where('location_id', $location->id)->where('form_id', $form->id)->count() >= $form->limit_by_per_location_value) {
                    return response('Location submission limit reached.', 403);
                }
            }
    
            if($form->active_limit_by_period){
                $updatedAt = Carbon::parse($form->updated_at);
    
                if($form->limit_by_period_max_state != 'off'){
                    $submissionsThisPeriod = $this->countSubmissionsWithinPeriod($form->id, $form->limit_by_period_max_state, $updatedAt);
                    if ($submissionsThisPeriod >= $form->limit_by_period_max_value) {
                        return response('Period max submission limit reached.', 403);
                    }
                }
                // min submissions
                // if($form->limit_by_period_min_state != 'off'){
                //     $submissionsThisPeriod = $this->countSubmissionsWithinPeriod($form->id,$form->limit_by_period_min_state, $updatedAt);
                //     if ($submissionsThisPeriod < $form->limit_by_period_min_value) {
                //         return response('Period min submission limit not met.', 403);
                //     }
                // }
            }
    
            // =================== End of Form Submission Limits =====================
        // Check total max responses
        if($headOffice)
        {
            // init vars //
            $request_json = $request->data;
            $request_form = json_decode($request_json,true);
            if (isset($request_form['save_location']) && !empty($request_form['save_location']['selected_location'])) {
                $selectedLocation = isset($request_form['save_location']['selected_location']['id']) 
                    ? $request_form['save_location']['selected_location']['id'] 
                    : $request_form['save_location']['selected_location'][0];
            
                $headOfficeLocations = $headOffice->locations->where('location_id', $selectedLocation)->first();
            
                if (!isset($headOfficeLocations)) {
                    return response('Invalid Location', 403);
                }
            
                $location = $headOfficeLocations->location;
            }
            
            $form_priority = isset($request_form['priority_value']) ? $request_form['priority_value'] : 0;            
            $conditionsToApply = [];
            $emailsToApply = [];
            $infomationToShow = [];
            $formsToFill = [];
            $rootCauseAnalysis = array();
            $prority = $form_priority;
            $requires_final_approval = 0;

            $oldFormsToFill = (array) $request->to_fill ?? [];
            
            $form = $headOffice->be_spoke_forms()->findOrFail($id);
            $requires_final_approval = $form->requires_final_approval == 1;
            if (!$form->is_active) {
                return response(['result' => false, 'data' => "form is not active"], 503);
            }

            
            DB::beginTransaction();

            $record = new Record();
            $record->form_id = $form->id;
            /// if it is external link and no one is logged in. You can't achieve even company till this point ! Doesn't make sense.
            if ($form->is_external_link && $request->has('location_id')) {
                // you should update location over here if possible
                $record->location_id = $request->location_id;
            } else {
                $record->location_id = $location->id;
            }
            $record->user_id = $user->id; // In case if posted without login
            $record->priority = $form_priority;
            $record->status = 'active';
            $record->hide = !$form->show_submission_loc;
            $record->set_values($request_form);
            $record->raw_form = $request_json;
            $record->linked_forms = isset($request_form['forms_trigger']) ? json_encode($request_form['forms_trigger']) : [];
            $record->save();

            
            

            $description = "N/A";

            // On Head Office Case //
            $case = new HeadOfficeCase();
            $case->status = 'open';
            $case->head_office_id = $headOffice->id;
            $case->description = $description;
            $case->case_closed = 0;
            $case->last_accessed = Carbon::now();
            $case->last_action = Carbon::now();
            $case->last_linked_incident_id = $record->id;
            $case->incident_type = $record->location->location_type->name;
            $case->location_name = $record->location->trading_name;
            $case->location_id = $record->location->id;
            $case->location_email = $record->location->email;
            $case->location_phone = $record->location->telephone_no;
            $case->location_full_address = $record->location->name();
            $case->reported_by = $user->email == 'external@qitech.com' ? 'external' : $record->created_by->first_name ;
            $case->reported_by_id = $record->user_id;
            $case->submitable_to_nhs_lfpse = $record->form->submitable_to_nhs_lfpse;
            if (isset($request_form['save_location']) && !empty($request_form['save_location']['selected_location'])) {
                $selectedLocationId = isset($request_form['save_location']['selected_location']['id']) 
                    ? $request_form['save_location']['selected_location']['id'] 
                    : $request_form['save_location']['selected_location'][0];
            
                $case->show_reported_location = isset($request_form['save_location']['display_location_reported_from']) 
                    ? $request_form['save_location']['display_location_reported_from'] 
                    : false;
            
                $case->saved_location = $orglocation->id;
            }
            
            $case->save();

            


            // Link to Location Incident through 1-1
            $linked_case = new HeadOfficeLinkedCase();
            $linked_case->head_office_case_id = $case->id;
            $linked_case->be_spoke_form_record_id = $record->id;
            $linked_case->save();

            if ($form->defaultDocuments) {
                foreach ($form->defaultDocuments as $d) {

                    $task = new CaseManagerCaseDocument();

                    $task->case_id = $case->id;
                    $task->title = $d->title;
                    $task->is_default_document = 1;
                    $task->description = $d->description;
                    $task->save();

                    $documents = $d->documents;
                    //CaseManagerCaseDocumentDocument::where('c_m_c_d_id', $task->id)->delete();
                    foreach ($documents as $value) {
                        $doc = new CaseManagerCaseDocumentDocument();
                        $doc->c_m_c_d_id = $task->id;
                        $value = Document::where('unique_id', $value->document->unique_id)->first();
                        if (!$value) {
                            continue;
                        }
                        $doc->document_id = $value->id;
                        $doc->type = ($value->isImage()) ? 'image' : 'document';
                        $doc->save();
                    }
                }
            }
            

            $form_default_stages = $form->default_stages()->orderBy('label')->get();
            if (!count($form_default_stages)) {
                $case_stage = new CaseStage();
                $case_stage->case_id = $case->id;
                $case_stage->name = 'Stage 1';
                // $case_stage->user_id = Auth::guard('web')->user()->id;
                $case_stage->is_default = 1;
                $case_stage->label = 0;
                $case_stage->save();
            }
            foreach ($form_default_stages as $form_default_stage) {
                $current_stage = $case->stages()->where('is_current_stage', 1)->first();
                $case_stage = new CaseStage();
                $case_stage->case_id = $case->id;
                $case_stage->name = $form_default_stage->name;
                $case_stage->stage_rules = $form_default_stage->stage_rules;
                // $case_stage->user_id = Auth::guard('web')->user()->id;
                $case_stage->is_default = 1;
                if (!$current_stage) {
                    $case_stage->is_current_stage = 1; 
                }

                $case_stage->label = $form_default_stage->label;
                $case_stage->save();

                // =============== Custom Tasks from Forms Json ==========
                $custom_tasks_json = isset($request_form['custom_tasks']) ? $request_form['custom_tasks'] : [];
                if(count($custom_tasks_json) > 0) {
                    foreach($custom_tasks_json as $custom_task) {
                        if($custom_task['stage_id'] == $form_default_stage->id) {
                            $custom_case_task = new CaseStageTask();
                            $custom_case_task->case_stage_id = $case_stage->id;
                            $custom_case_task->user_id = $uniq_user->id ?? $user->id;
                            $custom_case_task->title = $custom_task['task_name'];
                            $custom_case_task->description = $custom_task['task_description'];
                            $custom_case_task->status = 'in_progress';
                            $custom_case_task->mandatory = $custom_task['mandatory'];
                            $custom_case_task->save();

                            if(isset($custom_case_task)){
                                // assign to users
                                if($custom_task['auto_assign_to'] == 'user'){
                                    foreach($custom_task['assign_users'] as $cu_ho_user_id) {
                                        $is_user = $case->case_head_office->users->find($cu_ho_user_id);
                                        if(isset($is_user)){
                                            $task_assign = new CaseStageTaskAssign();
                                            $task_assign->head_office_user_id = $is_user->id;
                                            $task_assign->task_id = $custom_case_task->id;
                                            $task_assign->save();
                                        }
                                    }
                                }
                                elseif($custom_task['auto_assign_to'] == 'profiles'){
                                    foreach($custom_task['assign_profiles'] as $cu_ho_profile){
                                        $is_profile = $case->case_head_office->head_office_user_profiles->find($cu_ho_profile);
                                        if(isset($is_profile)){
                                            if($custom_task['assign_profile_user'] == '1'){
                                                // single random user in the profile
                                                $random_user = $is_profile->user_profile_assign->random(); // Select a random record
                                                if ($random_user) {
                                                    $task_assign = new CaseStageTaskAssign();
                                                    $task_assign->head_office_user_id = $random_user->head_office_user_id;
                                                    $task_assign->task_id = $custom_case_task->id;
                                                    $task_assign->save();
                                                }
                                            }
                                            elseif($custom_task['assign_profile_user'] == '2'){
                                                // all users in the profile
                                                foreach($is_profile->user_profile_assign as $user_profile_ho){
                                                    if(isset($user_profile_ho)){
                                                        $task_assign = new CaseStageTaskAssign();
                                                        $task_assign->head_office_user_id = $user_profile_ho->head_office_user_id;
                                                        $task_assign->task_id = $custom_case_task->id;
                                                        $task_assign->save();
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                foreach ($form_default_stage->default_tasks()->orderBy('label')->get() as $form_default_task) {
                    $head_office_task = $case_stage->my_tasks()->where('case_stage_id', $case_stage->id)->first();
                    if (!isset($head_office_task)) {

                        $case_manager_task = new CaseStageTask();
                        $case_manager_task->user_id = $uniq_user->id ?? $user->id;
                        $case_manager_task->case_stage_id = $case_stage->id;
                        $case_manager_task->title = $form_default_task->title;
                        $case_manager_task->description = $form_default_task->description;
                        $case_manager_task->status = 'in_progress';
                        $case_manager_task->mandatory = $form_default_task->mandatory;
                        $case_manager_task->is_default_task = 1;
                        $case_manager_task->form_json = $form_default_task->form_json;
                        $case_manager_task->save();

                        $deadline_records = $form_default_task->deadline_records;

                        if(!empty($deadline_records)){
                            foreach ($deadline_records as $deadline_record) {
                                $new_deadline_record = $deadline_record->replicate();
                                $new_deadline_record->default_case_stage_tasks_id = null;
                                $new_deadline_record->save();
                                // new deadline association with case manager task 
                                $new_deadline_link = new deadlineCaseTask();
                                $new_deadline_link->case_task_id = $case_manager_task->id;
                                $new_deadline_link->default_task_id = $form_default_task->id;
                                $new_deadline_link->deadline_id = $deadline_record->id;
                                $new_deadline_link->save();
                            }
                        }
                        
                        foreach ($form_default_task->documents as $document) {
                            $doc = $document->document;
                            if (!$case_manager_task->documents()->where('document_id', $doc->id)->first()) {
                                $task_document = new CaseStageTaskDocument();
                                $task_document->case_stage_task_id = $case_manager_task->id;
                                $task_document->document_id = $doc->id;
                                $task_document->type = $document->type;
                                $task_document->save();
                            }
                        }
                        if (json_decode($form_default_task->type_ids)) {
                            foreach (json_decode($form_default_task->type_ids) as $id) {
                                if ($form_default_task->type) {
                                    $profile = $headOffice->head_office_user_profiles()->find($id);
                                    if ($profile) {
                                        foreach ($profile->user_profile_assign as $user_profile_assign) {
                                            $new_user = $user_profile_assign->head_office_user;
                                            $task_assign = new CaseStageTaskAssign();
                                            $task_assign->head_office_user_id = $new_user->id;
                                            $task_assign->task_id = $case_manager_task->id;
                                            $task_assign->save();
                                        }
                                    }

                                } else {
                                    $new_user = $headOffice->users()->where('user_id', $id)->first();
                                    if ($new_user) {
                                        $task_assign = new CaseStageTaskAssign();
                                        $task_assign->head_office_user_id = $new_user->id;
                                        $task_assign->task_id = $case_manager_task->id;
                                        $task_assign->save();
                                    }
                                }

                            }
                        }
                    }
                }
            }

            $fields = [];

            $form_cards_data = [];
            $form_card_test = [];

            $filled_form = $record->get_filled_form();
            $questions = json_decode($record->json_submission, true)['mandatory_questions'];
            
        

            $case->requires_final_approval = $requires_final_approval;
            $case->requires_final_approval = isset($request_form['approval_required']) 
                        ? $request_form['approval_required'] 
                        : (isset($request_form['approval_not_required']) ? false : $requires_final_approval);

            $case->prority = $form_priority;
            $case->save();
            if ($requires_final_approval) {
                //match form name, location, in where clause //
                // we dont need to filter head offices. because one form definitely lies to one head office.
                $case_interested_party = new CaseInterestedParty();
                $case_interested_party->case_id = $case->id;
                $case_interested_party->tag = 'final_clouser_approval';

                $setting = $form->form_review_settings()->where('location_id', 'like', '%"' . $location->id . '"%')->get();

                if ($setting->count()) {
                    $randi = rand(0, $setting->count());
                    $user_id = $setting[$randi]->head_office_user_id;
                    // use this user id to assign this person as a case closure person in interested party
                    $case_interested_party->head_office_user_id = $user_id;
                } else {
                    foreach ($headOffice->users as $ho_user) {
                        if ($ho_user->user_profile_assign && $ho_user->user_profile_assign->profile->profile_name == 'Super User') {
                            $case_interested_party->head_office_user_id = $ho_user->id;
                        }
                    }
                }
                $case_interested_party->save();
            }

            // ============================== Start of Case Handler Logic ============================== 
            $users = [];
            $head_office_users = $headOffice->users;
            $designated_user = null; // Initialize as null
            $designated_user_orders_count = PHP_INT_MAX; // Start with a very high number
            $users_task_counts = []; // Initialize array to keep track of pending tasks

            $current_date = now(); // Current date

            foreach ($head_office_users as $head_office_user) {
                $setting = $head_office_user->user_incident_settings()->where('be_spoke_form_id', $form->id)->where('is_active',true)->first();
                if(isset($setting)){
                
                $total_open_cases_for_this_setting = $head_office_user->head_office_user_cases()->count();

                // Check if the user is on a bank holiday
                $on_bank_holiday = $head_office_user->head_office_user_bank_holiday_selection
                    ->where('date', $current_date)
                    ->isNotEmpty();

                // Check if the user is on personal leave
                $on_personal_holiday = $head_office_user->head_office_user_holidays
                    ->where('away_from', '<=', $current_date)
                    ->where('return', '>=', $current_date)
                    ->isNotEmpty();

                // Count in-progress tasks
                $pending_tasks_count = CaseStageTaskAssign::where('head_office_user_id', $head_office_user->id)
                    ->whereHas('task', function ($query) {
                        $query->where('status', 'in_progress');
                    })
                    ->count();

                // Update the users_task_counts array
                $users_task_counts[$head_office_user->id] = [
                    'pending_tasks_count' => $pending_tasks_count,
                    'user' => $head_office_user
                ];

                // Check priority and holiday conditions
                if (isset($setting) && ($prority >= $setting->min_prority) && ($prority <= $setting->max_prority) 
                    && $designated_user_orders_count > $total_open_cases_for_this_setting 
                    && !$on_bank_holiday && !$on_personal_holiday) {
                    // Update the designated user if this user is more suitable
                    if ($pending_tasks_count < $designated_user_orders_count) {
                        $designated_user_orders_count = $total_open_cases_for_this_setting;
                        $designated_user = $head_office_user;
                    }
                }
            }
            }
            // Find the user with the lowest number of pending tasks
            $min_pending_tasks = PHP_INT_MAX;
            $selected_user = null;

            foreach ($users_task_counts as $user_id => $task_data) {
                if ($task_data['pending_tasks_count'] < $min_pending_tasks) {
                    $min_pending_tasks = $task_data['pending_tasks_count'];
                    $selected_user = $task_data['user'];
                }
            }

            // Assign the user with the fewest pending tasks if no suitable user was selected previously
            if ($selected_user && (!$designated_user || $min_pending_tasks < $designated_user_orders_count)) {
                $designated_user = $selected_user;
            }

            $case_handler_user = new CaseHandlerUser();
            $case_handler_user->case_id = $case->id;
            if ($designated_user) {
                $case_handler_user->head_office_user_id = $designated_user->id;
            } else {
                $found = false;
                foreach ($headOffice->users as $ho_user) {
                    if ($ho_user->user_profile_assign && $ho_user->user_profile_assign->profile && $ho_user->user_profile_assign->profile->super_access == 'Super User') {
                        $case_handler_user->head_office_user_id = $ho_user->id;
                        $found = true;
                        break;
                    }
                }
                if (!$found) { 
                    $case_handler_user->head_office_user_id = $headOffice->users[0]->id;
                }
                $ho = $headOffice;
                $logo = isset($ho->logo) ? $ho->logo : asset('/images/svg/logo_blue.png');
                Mail::send([], [], function($message) use ($case_handler_user, $case, $logo) {
                    $body = '
                        <html><body>
                        <img src="'. $logo .'" alt="Image Description" style="width:100px;max-width:100%;height:auto; padding-bottom: 20px"/>
                        <p style="line-height: ;">Hi '. $case_handler_user->case_head_office_user->user->first_name .',</p>
                        <p>Case #'. $case->id .' has been assigned to you because of unavailability of a suitable case handler.</p>
                        </body></html>';
                
                    $message->to($case_handler_user->case_head_office_user->user->email)
                            ->subject('Case assigned | Unavailability of suitable case handler')
                            ->html($body); // Set body as HTML and specify 'text/html'
                });
                

            }
                $case_handler_user->save();
                
            // ============================== End of Case Handler Logic ============================== 
            
            // ============================== Start of Contacts creation ============================== 
                $involvements = json_decode($record->json_submission,true)['involvements'] ?? null;
                if(isset($involvements)){
                    $contact_ids = [];

                    foreach($involvements as $key => $inv) {
                        $new_contact = new new_contacts();
                        
                        $firstName = isset($inv['connected_fields']['first_name']) ? $inv['connected_fields']['first_name'] : '';
                        $middleName = isset($inv['connected_fields']['middle_name']) ? $inv['connected_fields']['middle_name'] : '';
                        $surname = isset($inv['connected_fields']['sur_name']) ? $inv['connected_fields']['sur_name'] : '';
                        $new_contact->name = trim("$firstName $middleName $surname");

                        if (empty($new_contact->name)) {
                            continue;
                        }

                        if (isset($inv['connected_fields']['dob']) && !empty($inv['connected_fields']['dob'])) {
                            try {
                                $dob = Carbon::createFromFormat('Y-m-d', $inv['connected_fields']['dob']);

                                if (!$dob) {
                                    $dob = Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $inv['connected_fields']['dob']);
                                }

                                $new_contact->date_of_birth = $dob;
                            } catch (\Exception $e) {
                                $new_contact->date_of_birth = null;
                            }
                        } else {
                            $new_contact->date_of_birth = null;
                        }

                        
                        if (isset($inv['connected_fields']['gender'])) {
                            $gender = $inv['connected_fields']['gender'];                        
                            if (is_array($gender) && isset($gender[0])) {
                                $gender = strtolower(trim($gender[0]));
                            } elseif (!is_array($gender)) {
                                $gender = strtolower(trim($gender)); 
                            } else {
                                $gender = null;
                            }
                        
                            if (in_array($gender, ['male', 'female', 'other'])) {
                                $new_contact->gender = $gender;
                            } else {
                                $new_contact->gender = null;
                            }
                        } else {
                            $new_contact->gender = null;
                        }
                        
                        if (isset($inv['connected_fields']['marital_status'])) {
                            $status = $inv['connected_fields']['marital_status'];

                            if (is_array($status) && isset($status[0])) {
                                $status = strtolower(trim($status[0])); 
                            } elseif (!is_array($status)) {
                                $status = strtolower(trim($status)); 
                            } else {
                                $status = null; 
                            }                        
                            switch ($status) {
                                case 'yes':
                                case '1':
                                case 'married':
                                    $new_contact->marital_status = 'married';
                                    break;
                                case 'no':
                                case '2':
                                case 'single':
                                    $new_contact->marital_status = 'single';
                                    break;
                                case 'separated':
                                    $new_contact->marital_status = 'separated';
                                    break;
                                case 'divorced':
                                    $new_contact->marital_status = 'divorced';
                                    break;
                                default:
                                    $new_contact->marital_status = null;
                            }
                        } else {
                            $new_contact->marital_status = null;
                        }                        
                       // Handle NHS number
                        $new_contact->nhs_no = isset($inv['connected_fields']['organisation_no']) 
                        ? (isset($inv['connected_fields']['organisation_no']['val']) 
                            ? $inv['connected_fields']['organisation_no']['val'] 
                            : (is_array($inv['connected_fields']['organisation_no']) 
                                ? $inv['connected_fields']['organisation_no'][0] 
                                : $inv['connected_fields']['organisation_no'])) 
                        : null;


                            // Handle ethnicity
                            $new_contact->ethnicity = isset($inv['connected_fields']['ethnicity']) 
                                ? (isset($inv['connected_fields']['ethnicity']['val']) 
                                    ? $inv['connected_fields']['ethnicity']['val'] 
                                    : (is_array($inv['connected_fields']['ethnicity']) && !empty($inv['connected_fields']['ethnicity']) 
                                        ? $inv['connected_fields']['ethnicity'][0] 
                                        : $inv['connected_fields']['ethnicity']))
                                : null;



                            // Handle religion
                        $new_contact->religion = isset($inv['connected_fields']['religion']) 
                        ? (isset($inv['connected_fields']['religion']['val']) 
                            ? $inv['connected_fields']['religion']['val'] 
                            : (is_array($inv['connected_fields']['religion']) 
                                ? $inv['connected_fields']['religion'][0] 
                                : $inv['connected_fields']['religion'])) 
                        : null;

                        // Handle profession
                        $new_contact->profession = isset($inv['connected_fields']['profession']) 
                        ? (isset($inv['connected_fields']['profession']['val']) 
                            ? $inv['connected_fields']['profession']['val'] 
                            : (is_array($inv['connected_fields']['profession']) 
                                ? $inv['connected_fields']['profession'][0] 
                                : $inv['connected_fields']['profession'])) 
                        : null;

                        // Handle profession registration number
                        $new_contact->registration_no = isset($inv['connected_fields']['profession_registration']) 
                        ? (isset($inv['connected_fields']['profession_registration']['val']) 
                            ? $inv['connected_fields']['profession_registration']['val'] 
                            : (is_array($inv['connected_fields']['profession_registration']) 
                                ? $inv['connected_fields']['profession_registration'][0] 
                                : $inv['connected_fields']['profession_registration'])) 
                        : null;


                        // Initialize work_emails and work_phones as empty arrays
                        $work_emails = [];
                        $work_phones = [];

                        // Loop through connected fields to capture all phone and email fields
                        foreach ($inv['connected_fields'] as $field_key => $field_value) {
                            // Check if the field is an email (starts with 'email')
                            if (preg_match('/^email(\d*)$/', $field_key)) {
                                $work_emails[] = $field_value;
                            }

                            // Check if the field is a phone (starts with 'phone')
                            if (preg_match('/^phone(\d*)$/', $field_key)) {
                                $work_phones[] = $field_value;
                            }
                        }
                        // Convert the arrays to JSON and store them
                        $new_contact->work_emails = !empty($work_emails) ? json_encode($work_emails) : null;
                        $new_contact->work_mobiles = !empty($work_phones) ? json_encode($work_phones) : null;



                        // $new_contact->work_emails = isset($inv['connected_fields']['email']) ? json_encode([$inv['connected_fields']['email']]) : null;
                        // $new_contact->work_mobiles = isset($inv['connected_fields']['phone']) ? json_encode([$inv['connected_fields']['phone']]) : null;
                        $new_contact->facebook = isset($inv['connected_fields']['social']) ? json_encode($inv['connected_fields']['social']) : null;
                        $new_contact->other_link = isset($inv['connected_fields']['website']) ? json_encode($inv['connected_fields']['website']) : null;
                        
                        $new_contact->head_office_id = $headOffice->id;
                        $new_contact->save();

                        $new_contact_to_case = new contact_to_case();
                        $new_contact_to_case->contact_id = $new_contact->id;
                        $new_contact_to_case->case_id = $case->id;
                        $new_contact_to_case->save();

                        $contact_ids[$key] = $new_contact->id;
                    }

                    foreach($involvements as $key => $inv) {
                        if (isset($inv['relation_with']) && isset($inv['relation']) && isset($inv['inverse_relation'])) {
                            $source_contact_id = isset($contact_ids[$key]) ? $contact_ids[$key] : null;
                    
                            $relation_with = array_search($inv['relation_with'], array_column($involvements, 'name'));
                            $target_contact_id = isset($contact_ids[$relation_with]) ? $contact_ids[$relation_with] : null;
                    
                            if ($target_contact_id && $source_contact_id) {
                                $contact_relation = new new_contacts_relations();
                                $contact_relation->source_contact_id = $source_contact_id;
                                $contact_relation->target_contact_id = $target_contact_id;
                                $contact_relation->relation = $inv['relation']; 
                                $contact_relation->reverse_relation = $inv['inverse_relation']; 
                                $contact_relation->save();
                    
                                $contact_relation_reverse = new new_contacts_relations();
                                $contact_relation_reverse->source_contact_id = $target_contact_id;
                                $contact_relation_reverse->target_contact_id = $source_contact_id;
                                $contact_relation_reverse->relation = $inv['inverse_relation']; 
                                $contact_relation_reverse->reverse_relation = $inv['relation']; 
                                $contact_relation_reverse->save();
                            }
                        }
                    }
                    

                    
                }
            $contacts = $headOffice->new_contacts;
            $matchs = ContactsController::findPotentialMatches($contacts);
            if(!empty($matchs)){
                foreach ($matchs as $match) {
                    $contact1 = $match['contact_id'];
                    $contact2 = $match['matching_contact_id'];
            
                    $existingMatch = matching_contacts::where(['contact_1' => $contact1, 'contact_2' => $contact2])->first();
                    if(!isset($existingMatch)){
                        $existingMatch = matching_contacts::where(['contact_2' => $contact1, 'contact_1' => $contact2])->first();
                    }
                    $db_contact1 = new_contacts::find($contact1);
                    $db_contact2 = new_contacts::find($contact2);
                    if (!isset($existingMatch) && isset($db_contact1) && isset($db_contact2)) {
                        $new_match = new matching_contacts();
                        $new_match->contact_1 = $contact1;
                        $new_match->contact_2 = $contact2;
                        $new_match->match = (float) $match['similarity_percentage'];
                        $new_match->save();
                    }
                }
            }
            // ============================== End of Contacts creation ============================== 
            # Merge old forms to fill with new conditions
            $formsToFill = array_merge($oldFormsToFill, $formsToFill);

            ActivityLog::create([
                'user_id' => $user->id,
                'head_office_id' => $headOffice->id,
                'action' => 'New Case opened.',
                'type' => 'Case Opened',
                'timestamp' => now(),
            ]);

            // =============== Custom Emails from Forms Json ==========
            $custom_emails_json = isset($request_form['custom_emails']) ? $request_form['custom_emails'] : [];
            if(count($custom_emails_json) > 0){
                foreach($custom_emails_json as $custom_email){
                    
                    $email_message = $custom_email['email_text'];
                    $email_subject = $custom_email['email_subject'];

                    if($custom_email['send_email_type'] == 'free_type_email'){
                        $e_user = $custom_email['send_email_address'];
                        Mail::html($email_message, function($message) use ($e_user,$email_subject) {
                            $message->to($e_user)
                                    ->subject($email_subject ?? 'Qitech - Form Submission Email ');
                        });
                    }
                    elseif($custom_email['send_email_type'] == 'head_office_profile_type'){
                        foreach($custom_email['assign_profiles'] as $cu_ho_profile){
                            $is_profile = $case->case_head_office->head_office_user_profiles->find($cu_ho_profile);
                            if(isset($is_profile)){
                                if($custom_email['assign_profile_user'] == '1'){
                                    // single random user in the profile
                                    $random_user = $is_profile->user_profile_assign->random(); // Select a random record
                                    if ($random_user) {
                                        $e_user = $random_user->head_office_user->user->email;
                                        Mail::html($email_message, function($message) use ($e_user,$email_subject) {
                                            $message->to($e_user)
                                                    ->subject($email_subject ?? 'Qitech - Form Submission Email ');
                                        });
                                    }
                                }
                                elseif($custom_email['assign_profile_user'] == '2'){
                                    // all users in the profile
                                    foreach($is_profile->user_profile_assign as $user_profile_ho){
                                        if(isset($user_profile_ho)){
                                            $e_user = $random_user->head_office_user->user->email;
                                            Mail::html($email_message, function($message) use ($e_user,$email_subject) {
                                                $message->to($e_user)
                                                        ->subject($email_subject ?? 'Qitech - Form Submission Email ');
                                            });
                                        }
                                    }
                                }

                            }
                        }
                    }elseif($custom_email['send_email_type'] == 'reported_by'){
                        if($case->reported_by != 'external'){
                            $e_user = $case->getReporter->email;
                            Mail::html($email_message, function($message) use ($e_user,$email_subject) {
                                $message->to($e_user)
                                        ->subject($email_subject ?? 'Qitech - Form Submission Email ');
                            });
                        }
                    }elseif($custom_email['send_email_type'] == 'email_field_selected'){
                        $e_user = $custom_email['email_field']['value'];
                        Mail::html($email_message, function($message) use ($e_user,$email_subject) {
                            $message->to($e_user)
                                    ->subject($email_subject ?? 'Qitech - Form Submission Email ');
                        });
                    }
                }
            }

            DB::commit();

            $linked_record = $request->query('linked_record');
            if (isset($linked_record)) {
                // Find the linked record
                $linked_record = Record::find($linked_record);
                
                // Decode the linked_forms JSON data
                $linked_record_data = isset($linked_record->linked_forms) ? json_decode($linked_record->linked_forms, true) : null;
                if (isset($linked_record_data)) {
                    // Loop through the linked_forms to find the entry with the matching cond_id
                    foreach ($linked_record_data as $index => $linkedForm) {
                        // Check if the cond_id matches the id and the status is 'pending'
                        if (isset($linkedForm['id']) && $linkedForm['id'] == $request->cond_id && $linkedForm['status'] == 'pending') {
                            // Update the status to 'completed'
                            $linked_record_data[$index]['status'] = 'completed';
                            
                            // Encode the updated linked_forms back to a JSON string
                            $linked_record->linked_forms = json_encode($linked_record_data);
                            
                            // Save the updated record
                            $linked_record->save();
                            
                            // Optionally return a response indicating success
                        }
                    }
                }
            }
            if(isset($linked_record)){
                $linked_cases = new linked_cases();
                $linked_cases->case_id_1 = $case->id;
                $linked_cases->case_id_2 = $linked_record->recorded_case->id;
                $linked_cases->head_office_id = $case->head_office_id ;
                $linked_cases->save();
                
                $new_links = isset($linked_record->linked_forms) ? json_decode($linked_record->linked_forms,true) : null;
                if ( isset($new_links)) {
                    // Iterate through the linked_forms JSON to find the data
                    foreach ($new_links as $index => $linkedForm) {
                        // Check if the linkedForm matches your condition (if needed)
                        if (isset($linkedForm['id'], $linkedForm['form_id'], $linkedForm['message'], $linkedForm['status'])) {
                            // Check if the status is 'completed'
                            if ($linkedForm['status'] == 'completed') {
                                // If the status is 'completed', check for the next cond_id
                                $nextCondition = isset($new_links[$index + 1]) ? $new_links[$index + 1] : null;
                                
                                if ($nextCondition) {
                                    // If there is a next condition, return it
                                    return response([
                                        'id' => $nextCondition['id'],
                                        'form_id' => $nextCondition['form_id'],
                                        'message' => $nextCondition['message'],
                                        'linked_record' => $linked_record->id,
                                    ]);
                                } else {
                                    // If there are no further conditions, just return the message
                                    return response([
                                        'message' => 'All done!'
                                    ]);
                                }
                            } else {
                                // If the status is not 'completed', just return the current data
                                return response([
                                    'id' => $linkedForm['id'],
                                    'form_id' => $linkedForm['form_id'],
                                    'message' => $linkedForm['message'],
                                    'linked_record' => $linked_record->id,
                                    'currnt' => true
                                ]);
                            }
                        }
                    }
                    
                }

            }else{
                $new_links = isset($record->linked_forms) ? json_decode($record->linked_forms,true) : null;
                if ( isset($new_links)) {
                    // Iterate through the linked_forms JSON to find the data
                    foreach ($new_links as $linkedForm) {
                        // Check if the linkedForm matches your condition (if needed)
                        if (isset($linkedForm['id'], $linkedForm['form_id'], $linkedForm['message'])) {
                            // Return the desired data
                            return response([
                                'id' => $linkedForm['id'],
                                'form_id' => $linkedForm['form_id'],
                                'message' => $linkedForm['message'],
                                'linked_record' => $record->id,
                            ]);
                        }
                    }
                }
            }
            if ($request->has('location_id')) {
                return back()->with('success_message', 'Form submitted successfully');
            }

                
            return response(['result' => $case->id]);
        }

        return abort(404);
        
    }

    public function submitLocationFormJson_edit(Request $request, $id)
    {
        $user = (Auth::guard('web')->user()) ? Auth::guard('web')->user() : Auth::guard('user')->user();
        if($request->has('location_id')){
            $location = Location::where('id',$request->location_id)->first();
            if(!isset($location)){
                return response('location not found',404);
            }
        }
        // =================== Form Submission Limits =====================
        $form = Record::where('id',$id)->first();
        if(isset($form->recorded_case->id)){
            $case = $form->recorded_case;
        }else{
            $case = $form->first_lfpse_record()->recorded_case;
        }
        if(!isset($form)){
            return response('form not found',404);
        }
        
        
        DB::beginTransaction();
        // =================== End of Form Submission Limits =====================
            $form = Record::where('id',$id)->first();
            if($form->submitable_to_nhs_lfpse == 1){
                if(isset($form->LfpseSubmissions->first()->lfpse_id)){
                    $form_sub = $form->LfpseSubmissions->first()->lfpse_id;
                    $form_num = $form->LfpseSubmissions->first()->reference_id;
                }else{
                    $form_sub = $form->first_lfpse_record()->LfpseSubmissions->first()->lfpse_id;
                    $form_num = $form->first_lfpse_record()->LfpseSubmissions->first()->reference_id;
                }
            }

            $request_json = $request->data;
            $request_form = json_decode($request_json,true);
            

            $record = new Record();
            $record->form_id = $form->form_id;
            /// if it is external link and no one is logged in. You can't achieve even company till this point ! Doesn't make sense.
            $record->location_id = $form->location_id;
            $record->user_id = $form->user_id; // In case if posted without login
            $record->priority = 1;
            $record->status = 'active';
            $record->hide = 0;
            $record->set_values($request_form);
            $record->raw_form = $request_json;
            $record->record_id = $form->id;
            $record->save();


            
             // Transaction should be moved in previous sections before anything save in db
            //$headOffice = $location->head_office(); // we already have head office / company declared before

            $description = "N/A";


            $modification = new form_modification_logs();
            $modification->parent_record_id = $form->id;
            $modification->modified_record_id = $record->id;
            $modification->head_office_id = $user->selected_head_office->id;
            $modification->user_id = $user->id;
            $modification->modified_data = json_encode($record->compare_form_values($record->json_submission, $form->json_submission));
            $modification->is_company = $request->has('company_id') ? true : false;
            $modification->save();

            



            // Questions are now changed. you need to get questions from json object ! //
            ///// ************************************************* //////
            $filled_form = $record->get_filled_form();
            $questions = json_decode($record->json_submission, true)['mandatory_questions'];
            
            if ($request->has('location_id')) {
                $comment = new Comment();
                    $comment->case_id = $case->id;
                    $comment->user_id = Auth::guard('user')->user()->id;
                    $comment->comment = 'Form modified from Location ' . $location->id . ' By '.  $location->username ;
                    $comment->type = 'Report Modified';
                    $comment->save();

                    ActivityLog::create([
                        'user_id' => $user->id,
                        'head_office_id' => Auth::guard('user')->user()->selected_head_office->id,
                        'action' => 'Case: # ' . $case->id . ' Form modified from Location by ' . $location->username,
                        'type' => 'Form Report Modified',
                        'timestamp' => now(),
                    ]);
                
            }elseif($request->has('company_id')){
                $ho = Auth::guard('web')->user()->selected_head_office;
                $comment = new Comment();
                $comment->case_id = $case->id;
                $comment->user_id = Auth::guard('web')->user()->id;
                $comment->comment = 'Form modified from Company by ' . $user->name ;
                $comment->type = 'Report Modified';
                $comment->save();

                ActivityLog::create([
                    'user_id' => $user->id,
                    'head_office_id' => Auth::guard('web')->user()->selected_head_office->id,
                    'action' => 'Case: # ' . $case->id . ' Form modified by ' . $ho->company_name,
                    'type' => 'Form Report Modified',
                    'timestamp' => now(),
                ]);
            }else{
               
    
                // Storage::put('request.json', $request_json);
    
                // Optionally, dump the file path
                // dd(Storage::path('request.json'));
                            // dd($request_json);
                // This should be submitted via Job. Taking longer time !
                if($form->submitable_to_nhs_lfpse == true){
                    $form_request = $record->get_filled_form();
                    $form_request_array = json_decode(json_encode($form_request), true);
                    $request_obj = LfpseSubmission::prepare_request($form_request_array);
                    $request_json = json_encode($request_obj);
                    $result = LfpseSubmission::submit_request_update($record, $request_json,$form_sub,$form_num);
                }
            }

        
            
                
                DB::commit();

            //return redirect()->route('be_spoke_forms.be_spoke_form.index');
            return response(['result' => $record->id]);
        


        
    }

    public function submitLocationDraftFormJson(Request $request, $id)
    {
        $location = Auth::guard('location')->user(); //
        $headOffice = $location->head_office();
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        if($headOffice)
        {

            $form = $headOffice->be_spoke_forms()->findOrFail($id);
            if (!$form->is_active) {
                return response(['result' => false, 'data' => "form is not active"], 503);
            }

            $request_json = $request->data;
            // $request_form = json_decode($request_json,true);

            $newDraft = new  be_spoke_form_record_drafts();
            $newDraft->user_id = isset($user) ? $user->id : null;
            $newDraft->location_id = $location->id;
            $newDraft->form_id = $form->id;
            $newDraft->json_submission = $request_json;
            $newDraft->save();


            //return redirect()->route('be_spoke_forms.be_spoke_form.index');
                
            return response(['result' => $newDraft->created_at->format('D d/m/Y g:ia')]);
        }

        // in case location is not linked to any head office. This is an invalid case
        return abort(404);
        
    }

    private function countSubmissionsWithinPeriod($formId, $periodState, $updatedAt)
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

        // Ensure the period starts from the form's updated_at timestamp
        // if ($updatedAt > $startPeriod) {
        //     $startPeriod = $updatedAt;
        // }

        return $query->whereBetween('created_at', [$startPeriod, Carbon::now()])->count();
    }
    private function process_condition($condition)
    {
        $priority_value = 0;
        //if($condition['action_type'] == 'add_priority_value')
            //$priority_value += $condition['']
    }
    public function assign_locations(Request $request)
{
    $form_id = $request->form_id;
    $parent_id = $request->parent_id;

    if (!$form_id || !$parent_id) {
        return redirect()->back()->with('error', 'Form ID or Parent ID missing.');
    }

    $headOffice = Auth::guard('web')->user()->selected_head_office;

    $form = Form::find($form_id);

    if (!$form) {
        return redirect()->back()->with('error', 'Form not found.');
    }

    $location_groups = Group::where('head_office_id', $headOffice->id)
    ->where('id', $parent_id)
    ->get();
    $form_group_json = $form->org_groups ? json_decode($form->org_groups, true) : [];
    $parent_array = $location_groups[0]->generateParentsArray($parent_id);
    $hasCommonValues = !empty(array_intersect($form_group_json, $parent_array));
    
    if($hasCommonValues) {
        return redirect()->back()->with('error', 'Group Parent already assigned!');
    }
    if ($location_groups->isEmpty()) {
        return redirect()->back()->with('error', 'No location groups found.');
    }
    
    $group_already_assigned = in_array($parent_id, $form_group_json);
    
    if (!$group_already_assigned) {
        $form_group_json[] = $parent_id;
        $form->org_groups = json_encode($form_group_json);
    }
    
    foreach ($location_groups as $location_group) {
        $head_office_location = HeadOfficeLocation::find($location_group->head_office_location_id);
        
        if ($head_office_location) {
            $assignedForm = AssignedBespokeForm::where('location_id', $head_office_location->location->id)
            ->where('be_spoke_form_id', $form_id)
            ->first();
            
            if (!$assignedForm) {
                $newAssignedForm = new AssignedBespokeForm();
                $newAssignedForm->location_id = $head_office_location->location->id;
                $newAssignedForm->be_spoke_form_id = $form_id;
                $newAssignedForm->save();
            }
        }
    }
    
    if (!$group_already_assigned) {
        $form->save();
    }

    return redirect()->back()->with('success', 'Locations assigned successfully.');
}

    
    public function remove_locations($id, $form_id)
{
    $form = Form::find($form_id);
    $headOffice = Auth::guard('web')->user()->selected_head_office;

    if (!$form) {
        return redirect()->back()->withErrors(['form_not_found' => 'Form not found.']);
    }

    $form_group_json = $form->org_groups ? json_decode($form->org_groups, true) : null;

    if (is_null($form_group_json) || !isset($form_group_json[$id])) {
        return redirect()->back()->withErrors(['group_not_found' => 'Group not found in form.']);
    }

    $group_id = $form_group_json[$id];
    $group = Group::find($group_id);

    if (!$group) {
        return redirect()->back()->withErrors(['group_not_found' => 'Group not found.']);
    }

    $location_groups = LocationGroup::where('head_office_id', $headOffice->id)
                                    ->where('group_id', $group->id)
                                    ->get();

                                    // dd($form_group_json[$id]);
    unset($form_group_json[$id]);

    if (empty($form_group_json)) {
        $form->org_groups = null;
    } else {
        $form->org_groups = json_encode(array_values($form_group_json));
    }

    $form->save();

    foreach ($location_groups as $location_group) {
        $head_office_location = HeadOfficeLocation::find($location_group->head_office_location_id);

        if ($head_office_location) {
            $assignedForm = AssignedBespokeForm::where('location_id', $head_office_location->location->id)
                                               ->where('be_spoke_form_id', $form->id)
                                               ->first();
            if ($assignedForm) {
                $assignedForm->delete();
            }
        }
    }

    return redirect()->back()->with('status', 'Locations removed successfully.');
}


public function checkFormLimits($formId)
{
    $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
    $location = Auth::guard('location')->user();
    $form = Form::findOrFail($formId);

    if($form->is_active == false){
        return redirect()->back()->with('error','Form is not active.');
    }
    if($form->active_limit_by_amount){
        if ($form->amount_total_max_res && $form->limits > 0 && Record::where('form_id', $form->id)->count() >= $form->limits) {
            return redirect()->back()->with('error','Total submission limit reached.');
        }
        if ($form->limit_to_one_user && $form->limit_by_per_user_value > 0 && Record::where('user_id', $user->id)->where('form_id', $form->id)->count() >= $form->limit_by_per_user_value) {
            return redirect()->back()->with('error','User submission limit reached.');
        }
        if ($form->limit_to_one_location && $form->limit_by_per_location_value > 0 && Record::where('location_id', $location->id)->where('form_id', $form->id)->count() >= $form->limit_by_per_location_value) {
            return redirect()->back()->with('error','Location submission limit reached.');
        }
    }
    if($form->active_limit_by_period){
        $updatedAt = Carbon::parse($form->updated_at);
        if($form->limit_by_period_max_state != 'off'){
            $submissionsThisPeriod = $this->countSubmissionsWithinPeriod($form->id, $form->limit_by_period_max_state, $updatedAt);
            if ($submissionsThisPeriod >= $form->limit_by_period_max_value) {
                return redirect()->back()->with('error','Period max submission limit reached.')->withInput();
            }
        }
    }

    // If all checks pass, return the URL for redirection
    if(isset($location)){
        $url = url('/bespoke_form_v3/#!/submit/' . $form->id.'?location_id='.$location->id);
    }else{
        $url = url('/bespoke_form_v3/#!/submit/' . $form->id);
    }
    return redirect()->away($url);
}

public function external_link($external_link)
    {
        $form = Form::where('external_link', $external_link)->first();
        if(!isset($form)){
            return redirect()->route('login');
        }
        if($form->is_active == false){
            return redirect()->route('form-error',['error'=>1,'message'=>'This Form is not available','external'=>1]);
        }
        $headOffice = $form->form_owner;
        if(isset($headOffice)){
            $url = "/bespoke_form_v3/#!/submit/" . $form->id.'?external=1&ho='. $headOffice->id;
        }else{
            $url = "/bespoke_form_v3/#!/submit/" . $form->id.'?external=1';
        }
        return redirect()->to($url);
    }

    public function softDeleteForm(Request $request, $id)
{
    $validator = Validator::make(['id' => $id], [
        'id' => 'required|exists:be_spoke_form,id', 
    ]);

    if($validator->fails()){
        return back()->with('error',$validator->errors()->first());
    }
    $form = Form::find($id);


    if (!$form) {
        return redirect()->route('be_spoke_form.be_spoke_form.index')->with('error', 'Form not found.');
    }

    if (count($form->records) > 0) {
        return redirect()->route('be_spoke_form.be_spoke_form.index')->with('error', 'Cannot delete form with associated records.');
    }

    if ($form->is_deleted) {
        if ($form->deleted_at) {
            $deletedAt = Carbon::parse($form->deleted_at);
            if ($deletedAt->diffInDays(Carbon::now()) >= 30) {
                $form->soft_deleted = 1;
                $form->is_deleted = 0;  
                $form->deleted_at = now(); 
                $form->is_active = false; 
                $form->is_archived = 0;   
                $form->save();
                return redirect()->back()->with('success', $form->name . ' form was deleted permanently.');
            }
        }
    }
    $form->soft_deleted = 1;
    $form->is_deleted = 0;
    $form->deleted_at = now();
    $form->is_active = false;
    $form->is_archived = 0;
    $form->save();

    return redirect()->back()->with('success', 'Form deleted permanently.');
}

}
