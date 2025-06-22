<?php

namespace App\Http\Controllers\Location;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;

use App\Models\CalenderEvent;
use App\Models\LocationDetailUpdateRequest;
use App\Models\LocationOpeningHours;
use App\Jobs\NearMissDailyCheckEmail;
use App\Models\LocationReceivedAlert;
use App\Models\LocationUserNotification;
use App\Models\near_miss_settings;
use App\Models\PatientSafetyAlertAction;
use App\Models\PsaActionComment;
use App\Models\PsaActionStaff;
use App\Models\reminders;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\LocationQuickLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Forms\Form;
use App\Models\Location;
use App\Models\NearMiss;
use App\Models\Position;
use App\Models\ServiceMessage;
use App\Models\User;
use Carbon\Carbon;
use App\Models\PsaAction;
use App\Models\be_spoke_form_record_drafts;
class LocationController extends Controller
{
    public static $limitOfTimeLineRecords = 3;
    public $perPage = 25;
    public function user_login_view()
    {
    if (Auth::guard('location')->check()) {
        $location = Auth::guard('location')->user();

         // Check if the password has been changed
        if (!$location->password_changed) {
            // Redirect to change password if not changed
            return redirect()->route('locations.changePasswordForm');
        }

        $head_office = $location->head_office();
        $head_office_timing = $head_office->head_office_timings;
        session()->forget('remote_access');

        $passwordSuccessMessage = session('password_success');   
        return view('location.user_login', compact('location', 'head_office', 'head_office_timing', 'passwordSuccessMessage'));
    }
    }

    

    
    public function showChangePasswordForm()
    {
        return view('location.change_password');
    }
    
    public function changePassword(Request $request)
    {
        // Validate the new password
    $request->validate([
        'new_password' => 'required|min:8|confirmed', 
    ]);

    // Get the currently authenticated user
    $user = Auth::guard('location')->user();

    $user->password = Hash::make($request->input('new_password'));
    $user->password_changed = true; // Set a flag if necessary
    $user->save();

    session()->flash('password_success', 'Password changed successfully.');

    return redirect()->route('location.user_login_view');
    }





    public function create_pin()
    {

        $location = Auth::guard('location')->user();

        return view('location.create_pin', compact('location'));
    }

    public function update_pin(Request $request)
    {
        // $request->validate([
        //     'new_pin' => 'required|numeric|digits_between:4,4|same:pin'
        // ]);

        $user = Auth::guard('user')->user();
        $location = Auth::guard('location')->user();
        $lc = LocationQuickLogin::where([['location_id', '=', $location?->id],['user_id', '=', $user?->id]])->first();
        if(!$lc)
        {
            $lc = new LocationQuickLogin();
            $lc->user_id = $user->id;
            $lc->location_id = $location->id;
        }
        $lc->pin = (int)$request->new_pin;
        $lc->save();

        return redirect()->route('be_spoke_forms.be_spoke_form.records')->with('success', "Your Pin is updated successfully!");
    }

    public function remove_pin(Request $request){
        if(isset($request->id)){
            LocationQuickLogin::where('id',$request->id)->delete();
            return redirect()->route('location.dashboard')->with('success_message', "User removed from quick login!");
        }
        return redirect()->route('location.dashboard')->withError('error', "Failed to remove the user from quick login!");
    }

    public function pinned_user(Request $request){
        if(isset($request->id)){
            $user = LocationQuickLogin::where('id',$request->id)->first();
            $user->isPinned = !$user->isPinned;
            $user->save();
            return redirect()->route('location.dashboard')->with('success_message', "User removed from Pinned!");
        }
        return redirect()->route('location.dashboard')->withError('error', "Failed to remove the user from Pin!");
    }

    

    public function dashboard()
    {
        return redirect()->route('be_spoke_forms.be_spoke_form.records');
//        dd(Auth::guard('location')->user()->branding);
//        $LocationServiceMessage=Helper::ServiceMessage('Location');
        $location = Auth::guard('location')->user();
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
        $misseds = $this->checkMissedReminders($form_data);
        foreach($misseds as $miss){
            $day = $miss['day'];
            $time = $miss['time'];
            $formId = $miss['form_id'];

            // Get the current date
            $today = Carbon::now()->toDateString();

            // Check if a similar record exists in the database for today
            if($miss['type'] == 'by_date'){
                $existingRecord = Reminders::where('day', $day)
                ->where('form_id', $formId)
                ->whereDate('created_at', $today)
                ->where(function ($query) use ($time) {
                    $query->where('time', '!=', $time)
                        ->orWhereNull('time');
                })
                ->first();
            }else{
                $existingRecord = Reminders::where('day', $day)
                    ->where('time', $time)
                    ->where('form_id', $formId)
                    ->whereDate('created_at', $today)
                    ->first();
            }
                if (!$existingRecord) {
                    $reminder = new Reminders();
                    $reminder->type = $miss['type'];
                    $reminder->day = $day;
                    $reminder->location_id = $location->id;
                    $reminder->time = $time;
                    $reminder->form_id = $formId;
                    $reminder->save();
                }
        }
        $reminders = reminders::where('location_id',$location->id)->get();
        $openingHoursSet = LocationOpeningHours::where('location_id', $location->id)->get();

        return view('location.dashboard',compact('location','reminders'));
    }

    static public function checkMissedReminders($data) {
        $missedReminders = [];
        if (empty($data)) {
            return $missedReminders;
        }
    
        foreach ($data['by_day'] ?? [] as $days) {
            if (isset($days['times']) && !empty($days['times'])) {
                $lastUpdate = Carbon::parse($days['updated_at']);
                foreach ($days['times'] as $key => $day) {
                    if ($day['active']) {
                        $dayOfWeek = Carbon::parse($key)->dayOfWeek;
                        foreach ($day['times'] as $daytime) {
                            $reminderTime = Carbon::parse($daytime);
                            if (now() > $lastUpdate && now()->dayOfWeek == $dayOfWeek && now()->format('H:i') >= $reminderTime->format('H:i')) {
                                $status = LocationController::getReminderStatus($reminderTime);
    
                                $missedReminders[] = [
                                    'type' => 'by_day',
                                    "day" => $key,
                                    "time" => $daytime,
                                    "form_id" => $days['form_id'],
                                    "status" => $status
                                ];
                            }
                        }
                    }
                }
            }
        }
    
        if (isset($data['by_date'][0])) {
            foreach ($data['by_date'][0] as $date) {
                if ($date->repeat_state == 'month' && $date->created_at->isSameDay(now())) {
                    if ($date->active) {
                        foreach (json_decode($date['times'], true) as $datetime) {
                            $reminderTime = Carbon::parse($datetime);
                            if (now()->format('H:i') >= $reminderTime->format('H:i')) {
                                $status = LocationController::getReminderStatus($reminderTime);
    
                                $missedReminders[] = [
                                    'type' => 'by_date',
                                    "day" => $date->created_at->format('l'),
                                    "time" => $datetime,
                                    "form_id" => $date->form_id,
                                    "status" => $status
                                ];
                            }
                        }
                    }
                } elseif ($date->repeat_state != 'off') {
                    if ($date->active && $date->created_at->isSameAs('d-m', now())) {
                        foreach (json_decode($date['times'], true) as $datetime) {
                            $reminderTime = Carbon::parse($datetime);
                            if (now()->format('H:i') >= $reminderTime->format('H:i')) {
                                $status = LocationController::getReminderStatus($reminderTime);
    
                                $missedReminders[] = [
                                    'type' => 'by_date',
                                    "date" => $date->created_at->format('l'),
                                    "time" => $datetime,
                                    "form_id" => $date->form_id,
                                    "status" => $status
                                ];
                            }
                        }
                    }
                }
            }
        }
    
        return $missedReminders;
    }
    
    /**
     * Get the status of the reminder based on the time difference.
     */
    static public function getReminderStatus($reminderTime) {
        $now = now();
        $diffInMinutes = $reminderTime->diffInMinutes($now);
        $isOverdue = $reminderTime->isPast();
    
        if ($reminderTime->isToday()) {
            if ($isOverdue) {
                return "Overdue by {$diffInMinutes} minutes";
            } elseif ($diffInMinutes <= 60) {
                return "Within {$diffInMinutes} minutes";
            }
            return "Today";
        } elseif ($isOverdue) {
            $diffInHours = $reminderTime->diffInHours($now);
            $diffInDays = $reminderTime->diffInDays($now);
            return $diffInDays > 0
                ? "Overdue by {$diffInDays} days"
                : "Overdue by {$diffInHours} hours";
        }
    
        return "Upcoming in {$diffInMinutes} minutes";
    }
    

    public function dispensing_incidents()
    {
        return view('location.report_dispensing_incidents');
    }

    public function near_miss(Request $request, $id = null)
    {
        
        $location = Auth::guard('location')->user();
        $headOffice = $location->head_office();
        $setting = near_miss_settings::where('head_office_id',$headOffice->id)->where('location_id',$location->id)->first();
        $data = isset($setting->settings) ? json_decode($setting->settings,true) : null;
        if(isset($setting->near_miss) && $setting->is_active == false && !$setting->near_miss->isActive){
            $data = null;
        }
        $where = Location::locationRelatedToHeadOffice($location->id);
        $who = User::relatedToLocation($location->id);
        $positions = Position::all();
        $nearmiss = NearMiss::find($id);
        return view('location.report_near_miss',compact('location','positions','where','who','nearmiss','data'));
    }
    public function near_miss_standalone(Request $request, $id = null){
        if($request->query('location_id')){
            $location = Location::find($request->query('location_id'));
        }
        if(!isset($location)){
            return redirect('login');
        }
        $headOffice = $location->head_office();
        $setting = near_miss_settings::where('head_office_id',$headOffice->id)->where('location_id',$location->id)->first();
        $data = isset($setting->settings) ? json_decode($setting->settings,true) : null;
        if(isset($setting->near_miss) && !$setting->near_miss->isActive){
            $data = null;
        }
        $where = Location::locationRelatedToHeadOffice($location->id);
        $who = User::relatedToLocation($location->id);
        $positions = Position::all();
        $nearmiss = NearMiss::find($id);
        return view('location.report_near_miss',compact('location','positions','where','who','nearmiss','data'))->with('standalone',true);
    }

    public function near_miss_standalone_save(Request $request){
        $this->nearMissSave($request);
        $location_id = $request->location_id;
        $location = Location::find($location_id);
            if(!$location){
                return redirect('/');
        }

        return view('location.near_miss_thank_you_standalone',compact('location'));
    }

    public function nearMissSave(Request $request){
        $nearMisses = NearMiss::MakeNearMissesFromForm($request);
        if(!count($nearMisses)){
            return redirect()->route('location.view_near_miss',['error'=>'No error type was selected. Near Miss is not saved.']);
        }
        $i = 0;
        foreach ($nearMisses as $key => $errorTypes) {
            foreach ($errorTypes as $errorType) {
                
                if ($i == 0 && NearMiss::find($request->id)) {
                    $nearmiss = NearMiss::find($request->id);
                } else {
                    $nearmiss = new NearMiss();
                }
                $i++;

                if ($request->save_as_draft) {
                    $nearmiss->status = 'draft';
                } else {
                    $nearmiss->status = 'active';
                }
                $nearmiss->location_id = (int) $request->location_id;
                $nearmiss->time = $request->time;
                $date = Carbon::createFromFormat('d/m/Y', $request->date);
                $nearmiss->date = date('Y-m-d', $date->getTimestamp());

                $nearmiss->dispensed_at_hub = $request->dispensed_at_hub;
                $nearmiss->error_by = $request->error_by == 'Please select a value' ? 'hidden' : $request->error_by;;

                $nearmiss->error_by_other = $request->error_by_other;
                $nearmiss->error_detected_by = $request->error_detected_by == 'Please select a value' ? 'hidden' : $request->error_detected_by;
                $nearmiss->error_detected_by_other = $request->error_detected_by_other == 'Please select a value' ? 'hidden' : $request->error_detected_by_other;
                $nearmiss->point_of_detection = $request->point_of_detection;

                $nearmiss->what_was_error = $key;
                $user =  Auth::guard('web')->user() ?? Auth::guard('user')->user();
                if($user){
                    $nearmiss->user_id = $user->id;
                }
                $nearmiss->$errorType = 1;

                
                // Unselect All Other Nearmiss error types.As editing a record can cause multiple errors to be selected in one nearmiss.
                foreach(NearMiss::allErrorTypes() as $field=>$type){
                    if($field == $errorType){
                        # Set error.
                        continue;
                    }
                    $nearmiss->$field = 0;
                }
                
                # Prescription has a extra field, of yes and no
                $nearmiss->prescription_expired_involve_drug = ($request->prescription_expired_involve_drug == 'Yes') ? 1 : 0;
                # Save Drugs if any field associated - 3rd Column
                $drugs = isset(NearMiss::$DrugsBasedOnErrorType[$errorType])?NearMiss::$DrugsBasedOnErrorType[$errorType]:array();
                foreach($drugs as $field=>$title){
                    $nearmiss->$field = $request->$field; ;
                }
                # Empty drug fields that are not associated. Sometimes text box are hidden but not empty.
                $tempFields =NearMiss::$DrugsBasedOnErrorType;
                if(isset($tempFields[$errorType])){
                    # Unset drugs fields that are saved.
                    unset($tempFields[$errorType]);
                }
                foreach($tempFields as $fields){
                    foreach($fields as $field=>$title){
                        $nearmiss->$field = '';
                    }
                }
                
                # Save  Reasons
                $reasons = NearMiss::FindResonsBasedOnErrorType($errorType);
                foreach($reasons as $field=>$reason){
                    if(Str::contains($field,'other_field')){
                        $nearmiss->$field = $request->$field;
                    }else{
                        $nearmiss->$field = (int)$request->$field;
                    }
                }

                # Save Contributing Factors
                foreach(NearMiss::$contributingFactors as $factors){
                    foreach($factors as $field=>$factor){
                        if(Str::contains($field,'other_field')){
                            $nearmiss->$field = $request->$field;
                        }else{
                            $nearmiss->$field = (int)$request->$field;
                        }
                    }
                }
                # Save Actions 
                $nearmiss->action_notes = $request->action_notes;
                $nearmiss->save();
                # For searching the nearmiss based on error type extra field is inserted.
                # Nearmiss error was dynamically generated.
                $nearmiss->error = $nearmiss->error();
                $nearmiss->save();
            }
        }
        return redirect()->route('be_spoke_forms.be_spoke_form.records',['success'=>' Near Miss Saved Successfully.']);

    }
    public function nearMissSaved(Request $request){
        return view('location.near_miss_thank_you');
    }
    public function settingsNearMisses(Request $request){
        $location = Auth::guard('location')->user();
        if($request->isMethod('post')){
            $location->near_miss_ask_for_who = (int)$request->near_miss_ask_for_who;
            $location->near_miss_ask_for_user_detail = ($request->near_miss_ask_for_user_detail)?$request->near_miss_ask_for_user_detail:'name';
            $location->near_miss_robot_in_use = (int)$request->near_miss_robot_in_use;
            $location->near_miss_robot_name = $request->near_miss_robot_name;
            $location->near_miss_reporting_less_than_week = (int)$request->near_miss_reporting_less_than_week;
            # Yes no as 1 and 0.
            $location->near_miss_prescirption_dispensed_at_hub = $request->near_miss_prescirption_dispensed_at_hub == 'Yes' ? 1 : 0;
            $location->save();

            

            // return redirect()->route('location.settings.nearmisses',['success'=>'Settings Saved Successfully.']);

        }

        return view('location.setting_near_misses',compact('location'));
    }
    
    public function view_dispensing_incidents()
    {
        return view('location.view_dispensing_incidents');
    }

    public function view_near_miss(Request $request)
    {
        $location = Auth::guard('location')->user();
        # Add status in future.
        //$status = $request->query('status') ? 'draft' : 'active';
        $near_misses = NearMiss::query();

        $raw_start_date = $request->query('start_date');
       
        if( $raw_start_date && Carbon::createFromFormat('d/m/Y', $raw_start_date)){
            $raw_start_date = Carbon::createFromFormat('d/m/Y', $raw_start_date);
            $start_date = date('Y-m-d',$raw_start_date->getTimestamp());
        }else{
            $start_date = date('Y-m-d', strtotime('-1 week'));
        }
        
        $raw_end_date = $request->query('end_date');
        if( $raw_end_date && Carbon::createFromFormat('d/m/Y', $raw_end_date)){
            $raw_end_date = Carbon::createFromFormat('d/m/Y', $raw_end_date);
            $end_date = date('Y-m-d',$raw_end_date->getTimestamp());
        }else{
            $end_date = date('Y-m-d');
        }
        $counter = 0;
        if($request->query('ajax')){
            $counter = (int) $request->query('count');
        }

        if($request->query('search')){
            $search = $request->query('search');
            # Had to use clousure, so conditions are executed as where( drugs = this or drug = this)
            # Its important to group all orWhere in a single where function, search is working as exptected.
            $near_misses->where(function ($near_misses) use ($search) {
                $near_misses->orWhere('error_by', 'LIKE', '%' . $search . '%');
                $near_misses->orWhere('error_detected_by', 'LIKE', '%' . $search . '%');
                $near_misses->orWhere('point_of_detection', 'LIKE', '%' . $search . '%');
                $near_misses->orWhere('what_was_error', 'LIKE', '%' . $search . '%');
                $near_misses->orWhere('error', 'LIKE', '%' . $search . '%');

                $queries = explode(' ', (string)$search);
                $drugFields = NearMiss::$DrugsBasedOnErrorType;
                foreach($drugFields as $fields)
                {
                    foreach($fields as $field=>$title){
                        if(count($queries)){
                            foreach($queries as $query){
                                $near_misses = $near_misses->orWhere($field, 'LIKE', '%' . $query . '%');
                            }
                        }
                        //$near_misses = $near_misses->orWhere($field, 'LIKE', '%' . $search . '%');
                    }
                }
            });
            
        }
        
        # All Where Conditions, grouping is not required here.
        # New changes, start and end dates are only selected when user submit them.
        if($request->query('start_date')){
            $near_misses = $near_misses->where('date', '>=', $start_date);
            $near_misses = $near_misses->where('date', '<=', $end_date);
        }
        
        if($request->query('hide')== 'deleted'){
            $near_misses->where('status', '!=','deleted');
        }
        $near_misses = $near_misses->where('location_id', $location->id);
        $limit_of_records = self::$limitOfTimeLineRecords;
        if ($request->query('ajax')) {
            $near_misses = $near_misses->skip($counter);
        }
        $near_misses = $near_misses->orderBy('date','desc')->orderBy('time','desc')->take($limit_of_records);
        
        $near_misses = $near_misses->get();
        $realCount = count($near_misses);
        $near_misses = $near_misses->groupBy('date');
        
        if($request->query('ajax')){
            if($realCount == 0){
                return 'exit';
            }
            if(request()->query('format') == 'table'){
                return view('location.view_near_miss_table_record')->with(compact('near_misses','location','counter'));
            }else{
                return view('location.view_near_miss_timeline_record')->with(compact('near_misses','location','counter'));
            }
            
        }

        return view('location.view_near_miss')->with(compact('near_misses','location','counter'));
    }

    public function view_drafts(Request $request){
        $location = Auth::guard('location')->user();
        $user = Auth::guard('user')->user() ?? Auth::guard('user')->user();
        if($location){
            $drafts = be_spoke_form_record_drafts::where('location_id',$location->id)->where('user_id',$user->id)->orderBy('created_at')->get();
            return view('location.view_drafts',compact('drafts', 'location'));
        }
    }
    public function delete_drafts($id){
        if($id){
            $draft = be_spoke_form_record_drafts::find($id);
            if(isset($draft)){
                $draft->delete();
                return redirect()->back();
            }
        }
    }
    public function nearMissAnalysis()
    {
        
       
    }
    public function nearMissQrCode(Request $request){
        $location = Auth::guard('location')->user();
        $form = Form::find($request->query('form_id'));
        return view('location.standalone_qr_code',compact('location','form'));
    }
    public function delete(Request $request, $id = null){
        
        $location = Auth::guard('location')->user();
        $nearmiss = NearMiss::where('location_id', $location->id)->where('id',$id)->first();
        $user = Auth::guard('user')->user();
        # Only delete permission records
        if(!$nearmiss || !$nearmiss->canDelete()){
            return  redirect()->route('location.view_near_miss',['error'=>'Invalid data submitted.']);  
        }
        
        $nearmiss->delete_reason = $request->delete_reason;
        $nearmiss->status = 'deleted';
        $nearmiss->deleted_by = $user->id;
        $nearmiss->deleted_timestamp = date('Y-m-d H:i:s');
        $nearmiss->save();
        return  redirect()->route('location.view_near_miss',['success'=>'Near Miss deleted successfully.']);  
    }
    public function deleteNearMiss( $id = null){
        
        $location = Auth::guard('location')->user();
        $nearmiss = NearMiss::where('location_id', $location->id)->where('id',$id)->first();
        $user = Auth::guard('user')->user();
        # Only delete permission records
        
        $nearmiss->status = 'deleted';
        $nearmiss->deleted_by = $user->id;
        $nearmiss->deleted_timestamp = date('Y-m-d H:i:s');
        $nearmiss->save();
        return  redirect()->back()->with(['success'=>'Near Miss deleted successfully.']);  
    }
    public function view_patient_safety_alerts(Request $request)
    {   
        $location = Auth::guard('location')->user();
        $user = Auth::guard('user')->user();

        # Receive new alerts.
        LocationReceivedAlert::receive($location);
        LocationReceivedAlert::createNotificationForUser($user);
        $query = LocationReceivedAlert::query();
        $query =  $query->where('location_id', $location->id);
        $status = $request->query('status')?$request->query('status'):'all';
        if($status != 'all'){
            $query =  $query->where('status', $status);
        }
        $counter = 0;
        if($request->query('ajax')){
            $counter = (int) $request->query('count');
        }
        $limit_of_records = self::$limitOfTimeLineRecords;
        if ($request->query('ajax')) {
            $query = $query->skip($counter);
        }

        if ($request->query('search')) {
            $search = $request->query('search');
            # Had to use clousure, so conditions with "and" whereHas clause
            # Its important to group all orWhere in a single where function, search is working as exptected.
            $query->whereHas('national_alert', function ($q) use ($search) {
                # here its and AND condition, 
                $q->where(function ($q1) use ($search) {
                    # here conditions are "OR" so they can be searched. 
                    $q1->orWhere('title', 'LIKE', '%' . $search . '%');
                    $q1->orWhere('type', 'LIKE', '%' . $search . '%');
                    $q1->orWhere('custom_type', 'LIKE', '%' . $search . '%');
                    $q1->orWhere('custom_originator', 'LIKE', '%' . $search . '%');
                    $q1->orWhere('class', 'LIKE', '%' . $search . '%');
                    $q1->orWhere('summary', 'LIKE', '%' . $search . '%');
                    $q1->orWhere('suggested_actions', 'LIKE', '%' . $search . '%');
                });
            });
        }
        $query =  $query->orderBy('alert_date_time', 'desc')->take($limit_of_records);
        $received_alerts = $query->get();
        $realCount = count($received_alerts);
        $received_alerts = $received_alerts->groupBy('alert_year');

        if($request->query('ajax')){
            if($realCount == 0){
                return 'exit';
            }
            if(request()->query('format') == 'table'){
                return view('location.view_psa_table_record')->with(compact('received_alerts','location','counter'));
            }else{
                return view('location.view_psa_timeline_record')->with(compact('received_alerts','location','counter'));
            }
            
        }

        return view('location.view_patient_safety_alerts',compact('received_alerts','location','counter'));
    }
    public function view_patient_safety_alert(Request $request, $id){
        $location = Auth::guard('location')->user();
        $alert = LocationReceivedAlert::where('location_id',$location->id)->where('id',$id)->first();
        if(!$alert){
            abort(404);
        }

        if($request->query('ajax') &&  $request->query('make_document_read')){
            $alert->document_is_read = true;
            $alert->save();
            return true;
        }

        $user = Auth::guard('user')->user();
        # Skip current user.
        $quickLogins = LocationQuickLogin::where('user_id','!=',$user->id)->where('location_id',$location->id)->get();

        if($request->query('ajax')){
            return view('location.patient_safety_alerts.action_form',compact('alert','quickLogins'));
        }
        return view('location.patient_safety_alerts.view',compact('alert','quickLogins'));
    }
    public function save_safety_alert_action(Request $request,$id){
        $location = Auth::guard('location')->user();
        $user = Auth::guard('user')->user();
        $alert = LocationReceivedAlert::where('location_id',$location->id)->where('id',$id)->first();
        if(!$alert){
            abort(404);
        }
        $action = PsaAction::find($request->action_id);
        if(!$action){
            $action = new PsaAction();
        }
        $action->user_id = $user->id;
        $action->received_alert_id = $id;
        $action->action_type = $request->action_type;
        $action->shared_this_alert = $request->shared_this_alert;
        $action->shared_with_team = ($request->shared_this_alert == 'yes')?$request->shared_with_team:null;
        
        if($action->action_type == 'read_and_taken_action'){
            $action->have_defective_stock = $request->have_defective_stock;
            $action->defective_quantity = (float)$request->defective_quantity;
            $action->stock_been_quarantined = $request->stock_been_quarantined;
            $action->stock_been_quarantined_location = $request->stock_been_quarantined_location;
            $action->stock_been_quarantined_reason = $request->stock_been_quarantined_reason;
            $action->stock_been_returned = $request->stock_been_returned;
            $action->stock_been_returned_reason = $request->stock_been_returned_reason;
            $action->recall_awaiting_collection = $request->recall_awaiting_collection;
            $action->patients_contacted = $request->patients_contacted;
            $action->addtional_comments = $request->addtional_comments;
        }else{
            $action->have_defective_stock = $action->defective_quantity = $action->stock_been_quarantined = $action->stock_been_quarantined_location =
            $action->stock_been_quarantined_reason = $action->stock_been_returned = $action->stock_been_returned_reason =
                $action->recall_awaiting_collection = $action->patients_contacted = $action->addtional_comments = null;
        }
        
       
        $action->save();

        PsaActionStaff::where('received_alert_id',$id)->where('action_id',$action->id)->delete();
        if($action->shared_this_alert == 'yes' && $action->shared_with_team == 'selected_staff'){
            $staff = (array)$request->shared_with_selected_staff;
            foreach($staff as $user_id){
                $s = new PsaActionStaff();
                $s->action_id = $action->id;
                $s->user_id  = $user_id;
                $s->received_alert_id   = $id;
                $s->save();
            }
        }
        $receivedAlert = LocationReceivedAlert::find($action->received_alert_id);
        $receivedAlert->updateStatus();
        $receivedAlert->save();

        return redirect()->route('location.view_patient_safety_alert',['id'=> $id,'success'=>'Action saved successfully.']); 
    }

    public function remove_action_patient_safety_alert(Request $request, $id){
        $user = Auth::guard('user')->user();
        $psaAction = PsaAction::where('id','=',$id)->where('user_id',$user->id)->first();
        if(!$psaAction){
            abort(404);
        }
        $alert_id = $psaAction->received_alert_id;
        PsaActionStaff::where('action_id',$psaAction->id)->delete();
        $psaAction->delete();

        $receivedAlert = LocationReceivedAlert::find($alert_id);
        $receivedAlert->updateStatus();
        $receivedAlert->save();
        return redirect()->route('location.view_patient_safety_alert',['id'=> $alert_id,'success'=>'Action deleted successfully.']);
       
    } 

    public function patient_safety_alert_add_comment(Request $request, $id){
        $user = Auth::guard('user')->user();
        $psaAction = PsaAction::where('id','=',$id)->first();
        if(!$psaAction){
            abort(404);
        }
        $alert_id = $psaAction->received_alert_id;
        $comment = PsaActionComment::find($request->comment_id);
        $message = "Comment is edited successfully.";
        if($comment && $comment->canEditAndDelete() == false){
            return redirect()->route('location.view_patient_safety_alert', ['id' => $alert_id, 'error' => 'You can not edit comments after 24 hours of submission.']);
        }
        if(!$comment){
            $comment = new PsaActionComment();
            $message = "Comment added successfully.";
        }
       
        $comment->user_id = $user->id;
        $comment->received_alert_id = $alert_id;
        $comment->action_id =  $id;
        $comment->comment = $request->comment;
        $comment->save();
        return redirect()->route('location.view_patient_safety_alert', ['id' => $alert_id, 'success' => $message]);
    }
    public function patient_safety_alert_delete_comment(Request $request, $id)
    {
        $comment = PsaActionComment::find( $id); 
        if(!$comment){
            abort(404);
        }
        $alert_id = $comment->received_alert_id;
        if($comment && $comment->canEditAndDelete() == false){
            return redirect()->route('location.view_patient_safety_alert', ['id' => $alert_id, 'error' => 'You can not delete comments after 24 hours of submission.']);
        }
       
        $comment->delete();
        return redirect()->route('location.view_patient_safety_alert', ['id' => $alert_id, 'success' => 'Comment deleted successfully.']);
    }
    public function edit_location_details()
    {
        $location = Auth::guard('location')->user();
        $user = Auth::guard('user')->user();
        return view('location.edit_location_details', compact('location','user'));
    }

    public function update_password_view()
    {
        return view('location.password_security.update_password');
    }
    public function verified_devices()
    {
        $user = Auth::guard('user')->user();

        $sessions = $user->userLoginSessions->where('is_active', 1)->where('is_head_office', 0);
        return view('location.password_security.verified_devices', compact('sessions'));


    }
    public function subscription()
    {
        return view('location.subscription_view');

    } public function blocked_users()
    {
        return view('location.blocked_users_view');

    } public function export_incidents()
    {
        return view('location.export_incidents_view');

    }
    public function color_branding()
    {
        $location = Auth::guard('location')->user();
        $user = Auth::guard('user')->user();
        return view('location.personalize_account.color_branding', compact('location','user'));
    }
    public function reporting()
    {
        return view('location.personalize_account.reporting');
    }
    // dynamic css
    public function color_css(Request $request)
    {
        $location = Auth::guard('location')->user();
        $branding=$location->branding;
        if($request->has('p')){
            $branding=$location->preview;
        }
        return response(view('styles.location_color', compact('branding')))->header('Content-Type', 'text/css');

    }

    public function testEmailNotificationDaily(){
        echo "Testing";
        //$job = new NearMissDailyCheckEmail();
        //$job->handle();
    }

    public function process_notifcation_url(Request $request, $id = null){
        $notification = LocationUserNotification::find($id);
        if($notification){
            $notification->status = LocationUserNotification::$statusRead;
            $notification->save();
            if(!empty($notification->url)){
                return redirect($notification->url);
            }
        }
        return redirect()->route('location.dashboard');
    }

    public function view_notifications(Request $request){
        $current_location = Auth::guard('location')->user();
        $current_user = Auth::guard('user')->user();
        $notifications = LocationUserNotification::where('location_id',$current_location->id)->where('user_id', $current_user->id)->orderBy('status','desc')->orderBy('created_at','desc')->paginate($this->perPage);
        $unread_count =LocationUserNotification::where('location_id',$current_location->id)->where('user_id', $current_user->id)->where('status','unread')->count(); 
        return view('location.notifications',compact('notifications','unread_count'));
    }
    public function mark_read_all_notifications(){
        $current_location = Auth::guard('location')->user();
        $current_user = Auth::guard('user')->user();
        LocationUserNotification::where('location_id',$current_location->id)->where('user_id', $current_user->id)->update(['status'=>'read']);
        return response()->json(['success'=>'All notications are marked as read.']);
    }
    public function end_user_session($id)
    {
        $user = Auth::guard('user')->user();

        $session = $user->userLoginSessions->where('user_session', $id)->where('is_head_office', 0)->first();
        if ($session && $session->user_session !== session('user_session')) {
            $session->delete();
            return back()->with('success_message', 'Session end successfully');
        }
        return back()->with('error', 'You cannot end your current session');

    }
}
