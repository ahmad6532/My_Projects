<?php

namespace App\Http\Controllers\HeadOffice;

use App\Exports\nearMissExport;
use App\Helpers\Helper;
use App\Http\base64Convert;
use App\Http\Controllers\Controller;
use App\Http\Requests\HeadOfficeBrandUpdateRequestsFormRequest;
use App\Http\Requests\HeadOfficeDetailUpdateRequestsFormRequest;
use App\Imports\LocationsImport;
use App\Models\ActivityLog;
use App\Models\Address;
use App\Models\ApprovedLocationGroupUser;
use App\Models\ApprovedLocationLocationUser;
use App\Models\CaseContact;
use App\Models\Contact;
use App\Models\ContactAddress;
use App\Models\ContactConnection;
use App\Models\DefaultFishBoneQuestion;
use App\Models\DefaultFiveWhysQuestion;
use App\Models\Document;
use App\Models\Forms\Form;
use App\Models\Forms\Record;
use App\Models\head_office_timings;
use App\Models\HeadOffice;
use App\Models\HeadOfficeLocation;
use App\Models\Headoffices\CaseManager\HeadOfficeCase;
use App\Models\Headoffices\CaseManager\HeadOfficeLinkedCase;
use App\Models\Headoffices\HeadofficeUserNotification;
use App\Models\Headoffices\Organisation\Group;
use App\Models\Headoffices\Organisation\Level;
use App\Models\Headoffices\Organisation\LocationGroup;
use App\Models\Headoffices\ReceivedNationalAlert;
use App\Models\HeadOfficeUserArea;
use App\Models\HeadOfficeUserBankHolidaySelection;
use App\Models\HeadOfficeUserContactDetail;
use App\Models\HeadOfficeUserHoliday;
use App\Models\HeadOfficeUserIncidentSetting;
use App\Models\HeadOfficeUserReviewSetting;
use App\Models\HeadOfficeUserTiming;
use App\Models\Location;
use App\Models\location_comment_documents;
use App\Models\location_comments;
use App\Models\LocationPharmacyType;
use App\Models\LocationRegulatoryBody;
use App\Models\LocationType;
use App\Models\new_contact_documents;
use App\Models\new_contact_links;
use App\Models\otp;
use App\Models\Position;
use App\Models\ShareCase;
use App\Models\User;
use App\Models\UserLoginSession;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Response;

class HeadOfficeController extends Controller
{
    public $perPage = 25;
    public static $limitOfTimeLineRecords = 20;
    public function board($id=null): View
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $ho_locations = $headOffice->locations;
        if(!isset($id)){
            if(count($ho_locations) == 0){
                $loc_id = null;
            }else{
                $loc_id = $ho_locations->first()->location->id;
            }
        }else{
            $loc_id = $id;
        }
        return view('head_office.board',compact('ho_locations','loc_id'));
    }
    public function board_data_export(Request $request)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $location = Location::find($request->id);
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        if(isset($location)){
            return Excel::download(new nearMissExport($location->id,$startDate,$endDate),'near_miss.xlsx');
        }else{
            return redirect()->back()->with('error','Location Not found!');
        }
    }
    public function company_info(Request $request, $parent_id = null)
    {
        $user = User::find(Auth::guard('web')->user()->id);
        $head_office = $user->selected_head_office;
        $head_office_user = $user->selected_head_office_user;
        $user_login_sessions = $user->userLoginSessions->where('head_office_id',$head_office->id);
        $users = $user->head_office_admins;
        $head_office_timing = $head_office->head_office_timings;
        $org_settings = $head_office->organisationSettings()->get();
        // delete locations
        $del_locations = $head_office->locations;

        foreach($del_locations as $del_location) {
            $location = $del_location->location;
        
            if ($location->is_deleted == 1 && $location->deleted_at != null) {
                $thirtyDaysAgo = Carbon::now()->subDays(30);
        
                if ($location->deleted_at < $thirtyDaysAgo) {
                    $del_location->delete();
                    $location->delete();
                }
            }
        }
        if(!isset($head_office_timing)){
            $head_office_timing = new head_office_timings();
            $head_office_timing->head_office_id = $head_office->id;
            $head_office_timing->save();
        }
        $arr = [];
        $l_u_s = collect();
        foreach ($users as $u) {
            $arr[] = $u->user_id;
        }
        $counter = 0;
        $test = 0;

        foreach ($user_login_sessions as $us) {
            if (in_array($us->user_id, $arr)) {
                $test++;
                $l_u_s->push($us);
            }
        }

        if ($request->query('ajax') && $request->query('verified_devices')) {

            $counter = (int) $request->query('count');
            $l_u_s = $l_u_s->slice($counter, 10)->values();
            $realCount = count($l_u_s);
            if ($realCount == 0) {
                return 'exit';
            }
            return view('head_office.verified_devices', compact('l_u_s'));
        }

        $l_u_s = $l_u_s->take(15);
        /*$locations = $head_office->locations;
        if ($request->query('ajax') && $request->query('locations')) {

            $counter = (int) $request->query('count');
            $locations = $locations->slice($counter, 10)->values();
            $realCount = count($locations);
            if ($realCount == 0) {
                return 'exit';
            }
            return view('head_office.my_organisation.loc', compact('locations'));
        }
        $locations = $locations->take(10);*/
        $perPage = 10;
        $locations = $head_office->locations;

        $totalLocations = count($locations);
        $assignedToGroups = LocationGroup::where('head_office_id', $head_office->id)->distinct('head_office_location_id')->count();
        $notAssigned = $totalLocations - $assignedToGroups;

        //organisation structure
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $allGroups = Group::where('head_office_id', $headOffice->id)->where('parent_id', null)->get();
        $parents = Group::generateParentsArray($parent_id);
        $parentGroup = Group::where('head_office_id', $headOffice->id)->where('id', $parent_id)->first();
        $groupWithChilds = Group::where('head_office_id', $headOffice->id)->get();

        if ($request->query('ajax') && $request->query('action')) {
            $id = $request->query('id');
            $action = $request->query('action');
            $group = Group::where('head_office_id', $headOffice->id)->where('id', $id)->first();
            if ($action != 'add_top_level_element' && !$group) {
                abort(404);
            }
            return view('head_office.my_organisation.organisation_structure_actions', compact('group', 'action', 'allGroups', 'groupWithChilds'));
        }

        $groups = Group::where('head_office_id', $headOffice->id)->where('parent_id', $parent_id)->get();
        $maximumDepth = Group::maximumDepthOfLevels();
        $levels = Level::generateLevels($maximumDepth, $headOffice);

        $link_token = $head_office->link_token;
        $url = 'https://'.$link_token.'.qi-tech.co.uk';

        if ($request->query('ajax')) {
            return view('head_office.my_organisation.loc', compact('locations','allGroups', 'headOffice'))->render();
        }

        return view('head_office.company_info', compact(
            'head_office',
            'l_u_s',
            'assignedToGroups',
            'notAssigned',
            'locations',
            'groups',
            'allGroups',
            'parents',
            'parentGroup',
            'groupWithChilds',
            'maximumDepth',
            'levels',
            'url',
            'head_office_timing',
            'org_settings',
            'head_office_user'
        )
        );
    }


    public function contacts_merge(Request $request, $parent_id = null)
    {
        $user = User::find(Auth::guard('web')->user()->id);
        $head_office = $user->selected_head_office;
        $head_office_user = $user->selected_head_office_user;
        $user_login_sessions = $user->userLoginSessions->where('head_office_id',$head_office->id);
        $users = $user->head_office_admins;
        $head_office_timing = $head_office->head_office_timings;
        $org_settings = $head_office->organisationSettings()->get();
        // delete locations
        $del_locations = $head_office->locations;

        foreach($del_locations as $del_location) {
            $location = $del_location->location;
        
            if ($location->is_deleted == 1 && $location->deleted_at != null) {
                $thirtyDaysAgo = Carbon::now()->subDays(30);
        
                if ($location->deleted_at < $thirtyDaysAgo) {
                    $del_location->delete();
                    $location->delete();
                }
            }
        }
        if(!isset($head_office_timing)){
            $head_office_timing = new head_office_timings();
            $head_office_timing->head_office_id = $head_office->id;
            $head_office_timing->save();
        }
        $arr = [];
        $l_u_s = collect();
        foreach ($users as $u) {
            $arr[] = $u->user_id;
        }
        $counter = 0;
        $test = 0;

        foreach ($user_login_sessions as $us) {
            if (in_array($us->user_id, $arr)) {
                $test++;
                $l_u_s->push($us);
            }
        }

        if ($request->query('ajax') && $request->query('verified_devices')) {

            $counter = (int) $request->query('count');
            $l_u_s = $l_u_s->slice($counter, 10)->values();
            $realCount = count($l_u_s);
            if ($realCount == 0) {
                return 'exit';
            }
            return view('head_office.verified_devices', compact('l_u_s'));
        }

        $l_u_s = $l_u_s->take(15);
        /*$locations = $head_office->locations;
        if ($request->query('ajax') && $request->query('locations')) {

            $counter = (int) $request->query('count');
            $locations = $locations->slice($counter, 10)->values();
            $realCount = count($locations);
            if ($realCount == 0) {
                return 'exit';
            }
            return view('head_office.my_organisation.loc', compact('locations'));
        }
        $locations = $locations->take(10);*/
        $perPage = 10;
        $locations = $head_office->locations;

        $totalLocations = count($locations);
        $assignedToGroups = LocationGroup::where('head_office_id', $head_office->id)->distinct('head_office_location_id')->count();
        $notAssigned = $totalLocations - $assignedToGroups;

        //organisation structure
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $allGroups = Group::where('head_office_id', $headOffice->id)->where('parent_id', null)->get();
        $parents = Group::generateParentsArray($parent_id);
        $parentGroup = Group::where('head_office_id', $headOffice->id)->where('id', $parent_id)->first();
        $groupWithChilds = Group::where('head_office_id', $headOffice->id)->get();

        if ($request->query('ajax') && $request->query('action')) {
            $id = $request->query('id');
            $action = $request->query('action');
            $group = Group::where('head_office_id', $headOffice->id)->where('id', $id)->first();
            if ($action != 'add_top_level_element' && !$group) {
                abort(404);
            }
            return view('head_office.my_organisation.organisation_structure_actions', compact('group', 'action', 'allGroups', 'groupWithChilds'));
        }

        $groups = Group::where('head_office_id', $headOffice->id)->where('parent_id', $parent_id)->get();
        $maximumDepth = Group::maximumDepthOfLevels();
        $levels = Level::generateLevels($maximumDepth, $headOffice);

        $link_token = $head_office->link_token;
        $url = 'https://'.$link_token.'.qi-tech.co.uk';

        if ($request->query('ajax')) {
            return view('head_office.my_organisation.loc', compact('locations','allGroups', 'headOffice'))->render();
        }

        return view('head_office.Contacts_Merge', compact(
            'head_office',
            'l_u_s',
            'assignedToGroups',
            'notAssigned',
            'locations',
            'groups',
            'allGroups',
            'parents',
            'parentGroup',
            'groupWithChilds',
            'maximumDepth',
            'levels',
            'url',
            'head_office_timing',
            'org_settings',
            'head_office_user'
        )
        );
    }


    public function template_download(){
        $filePath = public_path('Location Account Creation Template.xlsx');

        if (file_exists($filePath)) {
            return response()->download($filePath, 'Location Account Creation Template.xlsx', ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
        } else {
            abort(404);
        }
    }

    public function template_submit(Request $request)
{
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt|max:10240',
    ], [
        'csv_file.mimes' => 'The uploaded file must be a CSV file.',
    ]);

    if ($request->file('csv_file')) {
        $import = new LocationsImport();
        Excel::import($import, $request->file('csv_file'));

        // Get errors from the import process
        $errors = $import->getErrors();

        if (!empty($errors)) {
            // If there are errors, redirect back with them
            return redirect()->back()->with('error', 'Some rows could not be imported. Please check the errors below.')
                                    ->with('importErrors', $errors);
        }

        return redirect()->back()->with('success', 'Accounts Created!');
    }

    return redirect()->back()->with('error', 'Wrong file format!');
}

    public function dashboard()
    {
        return redirect()->route('case_manager.index');
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
        $locations = $ho->locations->whereBetween('created_at', [Carbon::now()->subDays(env('last_days')), Carbon::now()]);
        if (session()->has('token')) {
            $token = session()->get('token');
            return view('head_office.dashboard', compact('locations', 'to_be_removed_links', 'ho'));
        } else {
            return view('head_office.dashboard', compact('locations', 'to_be_removed_links'));
        }
    }

    public function preview_list()
    {

        $hos = Auth::guard('web')->user()->head_office_admins;

        return view('head_office.preview_list', compact('hos'));
    }

    public function select_head_office($id)
    {

        $user = Auth::guard('web')->user();
        $hou = $user->head_office_admins->where('head_office_id', $id)->first();
        if (!$hou) {
            return redirect()->route('head_office.preview_list',['_token' => csrf_token()])->withErrors(['error' => 'Invalid Head office selected. please try again'])
            ;
        }
        if ($hou->head_office->is_suspended) {
            return redirect()->route('head_office.preview_list',['_token' => csrf_token()])->withErrors(['error' => 'Selected Head office is Suspended'])
            ;
        }

        $user->selected_head_office_id = $id;
        $user->save();

        # Please also receive alerts and make notifications.
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        ReceivedNationalAlert::receiveNationalAlerts($headOffice->id);
        ReceivedNationalAlert::createNotificationForHeadOffice($headOffice);
        return redirect()->route('case_manager.index');
    }

    public function admin_login()
    {
        $ho = Auth::guard('web')->user()->selected_head_office;
        return view('head_office.admin_login', compact('ho'));
    }
    public function settings()
    {

        $user = Auth::guard('web')->user();
        $headOffice = $user->selected_head_office;
        $users = $user->head_office_admins;
        $arr = [];
        foreach ($users as $u) {
            $arr[] = $u->user_id;
        }
        $user_login_sessions = $user->userLoginSessions;

        $l_u_s = [];
        foreach ($user_login_sessions as $us) {
            if (in_array($us->user_id, $arr)) {
                $l_u_s[] = $us;
            }

        }
        $l_u_s;
        return view('head_office.settings', compact('headOffice', 'l_u_s'));
    }

    public function update_head_office_details(HeadOfficeDetailUpdateRequestsFormRequest $request)
    {
        $ho = Auth::guard('web')->user()->selected_head_office;
        $ho->company_name = $request->company_name;
        $ho->address = $request->address;
        $ho->telephone_no = $request->telephone_no;
        $ho->save();

        return redirect()->route('head_office.edit_head_office_details')
            ->with('success_message', 'Head office details updated.');
    }

    public function ho_view_profile()
    {
        $user = User::find(Auth::guard('web')->user()->id);
        $head_office = $user->selected_head_office;
        $head_office_user = $user->selected_head_office_user;
        $head_office_timing = $user->selected_head_office_timing;
        if (!$head_office_timing) {
            $head_office_timing = new HeadOfficeUserTiming();
            $head_office_timing->id = $user->selected_head_office_admin->id;
            $head_office_timing->save();
            $head_office_timing = HeadOfficeUserTiming::find($head_office_timing->id);
        }
        $list_references_id = [];
        foreach ($user->selected_head_office_user_bank_holiday_selections as $holidays) {
            $list_references_id[] = $holidays->reference_id;
        }
        $bank_holidays = Helper::bankHolidays();
        $head_office_user_holidays = $user->selected_head_office_user_holidays;

        $users = $user->head_office_admins;
        $arr = [];
        foreach ($users as $u) {
            $arr[] = $u->user_id;
        }
        $user_login_sessions = $user->userLoginSessions->where('head_office_id', $head_office->id);

        $l_u_s = [];
        foreach ($user_login_sessions as $us) {
            if (in_array($us->user_id, $arr)) {
                $l_u_s[] = $us;
            }

        }
        $l_u_s = collect($l_u_s)->sortByDesc('updated_at')->values()->all();
        $contacts = $head_office_user->head_office_user_contact_details;
        return view('head_office.view_profile', compact('head_office', 'contacts', 'l_u_s', 'user', 'head_office_timing', 'head_office_user_holidays', 'bank_holidays', 'list_references_id', 'head_office_user'));
    }
    public function ho_view_profile_logs()
    {
        $user = User::find(Auth::guard('web')->user()->id);
        $head_office = $user->selected_head_office;
        $head_office_user = $user->selected_head_office_user;
        
        return view('head_office.view_profile_activity', compact('head_office',  'head_office_user'));
    }

    public function update_password_view()
    {
        return view('head_office.password_security.update_password');
    }

    public function update_password(Request $request)
    {
        // get old, new and confirm password. use validations. and update in ho auth object. just like details update function.
        // remove this comment when you finish.

        $request->validate([
            'old_password' => 'required|max:80',
            'new_password' => 'required|min:8|max:80',
            'confirm_password' => 'same:new_password',
        ]);

        $ho = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();

        if (Hash::check($request->old_password, $ho->password)) {
            $ho->password = hash::make($request->new_password);
            $ho->password_updated_at = Carbon::now();
            $ho->password_updated_by_user_id = $user->id;
            $ho->save();
            return redirect()->back()->with('success_message', 'Password Updated Successfully!');
        }
        return back()->withInput()->withErrors(['old_password' => 'Entered password is incorrect']);

    }

    public function subscription()
    {
        return view('head_office.subscription_view');

    }

    public function color_branding()
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        return view('head_office.personalize_account.color_branding', compact('headOffice'));
    }

    public function update_color_branding(HeadOfficeBrandUpdateRequestsFormRequest $request)
    {
        /// update these in Ho object get from Auth //
        // remove comment when you finish //

        try {

            $data = $request->getData();

            $ho = Auth::guard('web')->user()->selected_head_office;
            $user = Auth::guard('web')->user();

            if ($request->preview_btn == 'preview') {
                session(['bg_color_code' => $data['bg_color_code']]);
                session(['font' => $data['font']]);
                if ($request->hasFile('logo_file')) {
                    $request->file('logo_file')->move(public_path('data_images/ho_brand_files/temp/logo'), $ho->id . '.png');
                }
                // if($request->hasFile('bg_file'))
                // {
                //     $request->file('bg_file')->move(public_path('data_images/ho_brand_files/temp/bg'), $ho->id .'.png');
                // }

                //                dd(Session::get('bg_color_code'),Session::get('font'));
                return view('head_office.preview_branding', compact('ho'));

            }

            $ho->bg_color_code = $data['bg_color_code'];
            $ho->font = $data['font'];
            $ho->save();

            if ($request->hasFile('logo_file')) {
                $request->file('logo_file')->move(public_path('data_images/ho_brand_files/logo'), $ho->id . '.png');
            }
            // if($request->hasFile('bg_file'))
            // {
            //     $request->file('bg_file')->move(public_path('data_images/ho_brand_files/bg'), $ho->id .'.png');
            // }

            return redirect()->back()
                ->with('success_message', 'Head Office Branding Details Updated successfully');

        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }

    }

    // dynamic css
    public function color_css(Request $request)
    {
        $ho = Auth::guard('web')->user()->selected_head_office;
        $branding = $ho->branding;
        if ($request->has('p')) {
            $branding = $ho->preview;
        }
        return response(view('styles.location_color', compact('branding')))->header('Content-Type', 'text/css');

    }

    public function view_notifications(Request $request)
    {
        $current_headoffice = Auth::guard('web')->user()->selected_head_office;
        //$current_user = Auth::guard('web')->user();
        $notifications = HeadofficeUserNotification::where('head_office_id', $current_headoffice->id)->orderBy('status', 'desc')->orderBy('created_at', 'desc')->paginate($this->perPage);
        $unread_count = HeadofficeUserNotification::where('head_office_id', $current_headoffice->id)->where('status', 'unread')->count();
        return view('head_office.notifications', compact('notifications', 'unread_count'));
    }
    public function process_notifcation_url(Request $request, $id = null)
    {
        $notification = HeadofficeUserNotification::find($id);
        if ($notification) {
            $notification->status = HeadofficeUserNotification::$statusRead;
            $notification->save();
            if (!empty($notification->url)) {
                return redirect($notification->url);
            }
        }
        return redirect()->route('head_office.dashboard');
    }
    public function mark_read_all_notifications(Request $request)
    {
        $current_headoffice = Auth::guard('web')->user()->selected_head_office;
        HeadofficeUserNotification::where('head_office_id', $current_headoffice->id)->update(['status' => 'read']);
        return response()->json(['success' => 'All notications are marked as read.']);
    }
    public function update_ho_timing(Request $request)
    {
        $user = Auth::guard('web')->user();
        $head_office_timing = $user->selected_head_office_timing;
        if (is_null($head_office_timing)) {
            $head_office_timing = new HeadOfficeUserTiming();
            $head_office_timing->id = $user->selected_head_office_admin->id;
        }
        // $name = $request->name;
        // $value = $request->value;
        // $head_office_timing->$name = $value;
        // $head_office_timing->save();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        $head_office_timing->monday_start_time = $request->start_time[0];
        $head_office_timing->monday_end_time = $request->end_time[0];
        $head_office_timing->is_open_monday = $request->has('is_open_monday');
        if ($this->timeIsGreater($request->start_time[0], $request->end_time[0]) == false) {
            return redirect()->route('head_office.view_profile', '#working_status')->with('error', 'Monday start time is greater than end time.');
        }
        $head_office_timing->tuesday_start_time = $request->start_time[1];
        $head_office_timing->tuesday_end_time = $request->end_time[1];

        if ($this->timeIsGreater($request->start_time[1], $request->end_time[1]) == false) {
            return redirect()->route('head_office.view_profile', '#working_status')->with('error', 'Tuesday start time is greater than end time.');
        }
        $head_office_timing->wednesday_start_time = $request->start_time[2];
        $head_office_timing->wednesday_end_time = $request->end_time[2];

        if ($this->timeIsGreater($request->start_time[2], $request->end_time[2]) == false) {
            return redirect()->route('head_office.view_profile', '#working_status')->with('error', 'Wednesday start time is greater than end time.');
        }
        $head_office_timing->thursday_start_time = $request->start_time[3];
        $head_office_timing->thursday_end_time = $request->end_time[3];

        if ($this->timeIsGreater($request->start_time[3], $request->end_time[3]) == false) {
            return redirect()->route('head_office.view_profile', '#working_status')->with('error', 'Thursday start time is greater than end time.');
        }
        $head_office_timing->friday_start_time = $request->start_time[4];
        $head_office_timing->friday_end_time = $request->end_time[4];

        if ($this->timeIsGreater($request->start_time[4], $request->end_time[4]) == false) {
            return redirect()->route('head_office.view_profile', '#working_status')->with('error', 'Friday start time is greater than end time.');
        }

        $head_office_timing->saturday_start_time = $request->start_time[5];
        $head_office_timing->saturday_end_time = $request->end_time[5];

        if ($this->timeIsGreater($request->start_time[5], $request->end_time[5]) == false) {
            return redirect()->route('head_office.view_profile', '#working_status')->with('error', 'Saturday start time is greater than end time.');
        }

        $head_office_timing->sunday_start_time = $request->start_time[6];
        $head_office_timing->sunday_end_time = $request->end_time[6];

        if ($this->timeIsGreater($request->start_time[6], $request->end_time[6]) == false) {
            return redirect()->route('head_office.view_profile', '#working_status')->with('error', 'Sunday start time is greater than end time.');
        }

        foreach ($days as $day) {
            $var = 'is_open_' . $day;
            $head_office_timing->$var = $request->has('is_open_' . $day);
        }

        $head_office_timing->save();
        return redirect()->route('head_office.view_profile', '#working_status')->with('success_message', 'Time updated successfully');
        // return response(['msg'=>' updated successfuly'],200);
    }
    public function update_company_timing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_time' => ['array', 'size:7', function ($attribute, $value, $fail) {
                foreach ($value as $time) {
                    if (!is_null($time) && !preg_match('/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/', $time)) {
                        $fail("The {$attribute} contains an invalid time format.");
                    }
                }
            }],
            'end_time' => ['array', 'size:7', function ($attribute, $value, $fail) {
                foreach ($value as $time) {
                    if (!is_null($time) && !preg_match('/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/', $time)) {
                        $fail("The {$attribute} contains an invalid time format.");
                    }
                }
            }]
        ]);

        if($validator->fails()){
            return redirect()->back()->with('error','Invalid Values!');
        }
        
        $user = Auth::guard('web')->user()->selected_head_office;
        $head_office_timing = $user->head_office_timings;
        // dd($head_office_timing,$user);
        if (is_null($head_office_timing)) {
            $head_office_timing = new head_office_timings();
            $head_office_timing->id = $user->selected_head_office_admin->id;
        }
        // $name = $request->name;
        // $value = $request->value;
        // $head_office_timing->$name = $value;
        // $head_office_timing->save();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        $head_office_timing->monday_start_time = $request->start_time[0];
        $head_office_timing->monday_end_time = $request->end_time[0];
        $head_office_timing->is_open_monday = $request->has('is_open_monday');
        if ($this->timeIsGreater($request->start_time[0], $request->end_time[0]) == false) {
            return redirect()->back()->with('error', 'Monday start time is greater than end time.');
        }
        $head_office_timing->tuesday_start_time = $request->start_time[1];
        $head_office_timing->tuesday_end_time = $request->end_time[1];

        if ($this->timeIsGreater($request->start_time[1], $request->end_time[1]) == false) {
            return redirect()->back()->with('error', 'Tuesday start time is greater than end time.');
        }
        $head_office_timing->wednesday_start_time = $request->start_time[2];
        $head_office_timing->wednesday_end_time = $request->end_time[2];

        if ($this->timeIsGreater($request->start_time[2], $request->end_time[2]) == false) {
            return redirect()->back()->with('error', 'Wednesday start time is greater than end time.');
        }
        $head_office_timing->thursday_start_time = $request->start_time[3];
        $head_office_timing->thursday_end_time = $request->end_time[3];

        if ($this->timeIsGreater($request->start_time[3], $request->end_time[3]) == false) {
            return redirect()->back()->with('error', 'Thursday start time is greater than end time.');
        }
        $head_office_timing->friday_start_time = $request->start_time[4];
        $head_office_timing->friday_end_time = $request->end_time[4];

        if ($this->timeIsGreater($request->start_time[4], $request->end_time[4]) == false) {
            return redirect()->back()->with('error', 'Friday start time is greater than end time.');
        }

        $head_office_timing->saturday_start_time = $request->start_time[5];
        $head_office_timing->saturday_end_time = $request->end_time[5];

        if ($this->timeIsGreater($request->start_time[5], $request->end_time[5]) == false) {
            return redirect()->back()->with('error', 'Saturday start time is greater than end time.');
        }

        $head_office_timing->sunday_start_time = $request->start_time[6];
        $head_office_timing->sunday_end_time = $request->end_time[6];

        if ($this->timeIsGreater($request->start_time[6], $request->end_time[6]) == false) {
            return redirect()->back()->with('error', 'Sunday start time is greater than end time.');
        }

        foreach ($days as $day) {
            $var = 'is_open_' . $day;
            $head_office_timing->$var = $request->has('is_open_' . $day);
        }

        $head_office_timing->save();
        return redirect()->back()->with('success_message', 'Time updated successfully');
        // return response(['msg'=>' updated successfuly'],200);
    }
    public function timeIsGreater($starttime, $endtime)
    {
        if (empty($starttime) || empty($endtime)) {
            return true;
        }
        if (strtotime($starttime) >= strtotime($endtime)) {
            return false;
        }
        return true;
    }
    public function update_head_office_user_holidays(Request $request)
    {

        $request->validate([
            'return_on' => 'required',
            'away_from' => 'required',
            'type' => 'required',

        ]);
        $user = Auth::guard('web')->user();
        $away_from = $request->away_from;
        $return_on = Carbon::createFromFormat('Y-m-d', $request->return_on);
        $away_from = Carbon::createFromFormat('Y-m-d', $away_from);
        $diff_in_days = $away_from->diffInDays($return_on);

        $head_office_user_holiday = new HeadOfficeUserHoliday();
        $head_office_user_holiday->head_office_user_id = $user->selected_head_office_admin->id;
        $head_office_user_holiday->away_from = $away_from;
        $head_office_user_holiday->return_on = $return_on;
        $head_office_user_holiday->type = $request->type;
        $head_office_user_holiday->total_days = $diff_in_days;
        $head_office_user_holiday->save();

        return back()->with('success', 'Holiday set successfully');
    }
    public function update_head_office_user_bank_holiday_selection(Request $request)
    {

        $user = Auth::guard('web')->user();
        $date = Carbon::createFromFormat('d-m-Y', $request->date);
        $head_office_user_bank_holiday_selection = $user->selected_head_office_user_bank_holiday_selections;

        if (!is_null($head_office_user_bank_holiday_selection)) {
            $selected_holiday = $head_office_user_bank_holiday_selection->where('reference_id', $request->reference_id)->first();
            if (!$selected_holiday) {
                $head_office_user_bank_holiday_selection = new HeadOfficeUserBankHolidaySelection();
                $head_office_user_bank_holiday_selection->head_office_user_id = $user->selected_head_office_admin->id;
                $head_office_user_bank_holiday_selection->date = $date;
                $head_office_user_bank_holiday_selection->name = $request->title;
                $head_office_user_bank_holiday_selection->reference_id = $request->reference_id;
                $head_office_user_bank_holiday_selection->is_working = $request->is_working;
                $head_office_user_bank_holiday_selection->save();
                if ($request->is_working == 0) {
                    $head_office_user_holiday = new HeadOfficeUserHoliday();
                    $head_office_user_holiday->head_office_user_id = $user->selected_head_office_admin->id;
                    $head_office_user_holiday->away_from = $date;
                    $head_office_user_holiday->return_on = $date->addDay();
                    $head_office_user_holiday->type = $request->title;
                    $head_office_user_holiday->total_days = 1;
                    $head_office_user_holiday->linked_api_holiday_id = $head_office_user_bank_holiday_selection->id;
                    $head_office_user_holiday->save();
                }
            } else {
                if ($selected_holiday->is_working == $request->is_working) {
                    return redirect()->route('head_office.view_profile', '#working_status')->with('error', 'Holiday selection already made.');
                } elseif ($request->is_working == 0) {
                    $check = $user->selected_head_office_user_holidays->where('linked_api_holiday_id', $selected_holiday->id)->first();
                    if (!$check) {
                        $head_office_user_holiday = new HeadOfficeUserHoliday();
                        $head_office_user_holiday->head_office_user_id = $user->selected_head_office_admin->id;
                        $head_office_user_holiday->away_from = $date;
                        $head_office_user_holiday->return_on = $date->addDay();
                        $head_office_user_holiday->type = $request->title;
                        $head_office_user_holiday->total_days = 1;
                        $head_office_user_holiday->linked_api_holiday_id = $selected_holiday->id;
                        $head_office_user_holiday->save();
                    }
                    $selected_holiday->is_working = $request->is_working;
                    $selected_holiday->save();

                } else {
                    $selected_head_office_user_holidays = $user->selected_head_office_user_holidays;
                    $check = $selected_head_office_user_holidays->where('linked_api_holiday_id', $selected_holiday->id)->first();
                    if ($check) {
                        $check->delete();
                    }
                    $selected_holiday->is_working = $request->is_working;
                    $selected_holiday->save();
                }
            }
        } else {
            $head_office_user_bank_holiday_selection = new HeadOfficeUserBankHolidaySelection();
            $head_office_user_bank_holiday_selection->head_office_user_id = $user->selected_head_office_admin->id;
            $head_office_user_bank_holiday_selection->date = $date;
            $head_office_user_bank_holiday_selection->name = $request->title;
            $head_office_user_bank_holiday_selection->reference_id = $request->reference_id;
            $head_office_user_bank_holiday_selection->is_working = $request->is_working;
            $head_office_user_bank_holiday_selection->save();
            if ($request->is_working == 0) {
                $head_office_user_holiday = new HeadOfficeUserHoliday();
                $head_office_user_holiday->head_office_user_id = $user->selected_head_office_admin->id;
                $head_office_user_holiday->away_from = $date;
                $head_office_user_holiday->return_on = $date->addDay();
                $head_office_user_holiday->type = $request->title;
                $head_office_user_holiday->total_days = 1;
                $head_office_user_holiday->linked_api_holiday_id = $head_office_user_bank_holiday_selection->id;
                $head_office_user_holiday->save();
            }
        }

        return redirect()->route('head_office.view_profile', '#working_status')->with('success', 'Holiday updated successfully!');

    }
    public function delete_head_office_user_holiday($id)
    {
        $holiday = HeadOfficeUserHoliday::findOrFail($id);
        if ($holiday->linked_api_holiday_id) {
            $bank_holiday = HeadOfficeUserBankHolidaySelection::findOrFail($holiday->linked_api_holiday_id);
            $bank_holiday->delete();
        }
        $holiday->delete();
        return redirect()->route('head_office.view_profile', '#working_status')->with('success', 'Holiday deleted successfully!');
    }
    public function head_office_user_login_session()
    {
        $user = Auth::guard('web')->user();
        $users = $user->head_office_admins;
        $arr = [];
        foreach ($users as $u) {
            $arr[] = $u->user_id;
        }
        $user_login_sessions = $user->userLoginSessions->where('is_head_office', 1);

        $l_u_s = [];
        foreach ($user_login_sessions as $us) {
            if (in_array($us->user_id, $arr)) {
                $l_u_s[] = $us;
            }

        }
    }

    public function end_head_office_user_session(Request $request,$id)
    {
        if (!$request->has('_token') && $request->_token != csrf_token()) {
            return back()->with('error','Invalid data submitted.');
        }
        $user = Auth::guard('web')->user();

        $session = $user->userLoginSessions->where('user_session', $id)->where('is_head_office', 1)->first();
        if ($session && $session->user_session !== session('user_session')) {
            $session->delete();
            return redirect()->route('head_office.view_profile', '#session_history')->with('success', 'Session ended successfully!');
        }
        return redirect()->route('head_office.view_profile', '#session_history')->with('error', 'You cannot end your current session');

    }

    public function end_head_office_user_session_all(Request $request)
    {
        $user = Auth::guard('web')->user();
        $ids = explode(',', $request->sessionIds[0]);
    
        // Check for invalid parameters
        if (is_null($ids) || empty($ids)) {
            return redirect()->back()->with('error', 'Invalid parameters');
        }
    
        $currentSessionId = session('user_session');
        $deletedAnySession = false;
    
        foreach ($ids as $id) {
            $session = $user->userLoginSessions->where('user_session', $id)->where('is_head_office', 1)->first();
            if ($session && $session->user_session !== $currentSessionId) {
                $session->delete();
                $deletedAnySession = true;
            }
        }
    
        if ($deletedAnySession) {
            return redirect()->back()->with('success', 'Session(s) ended successfully!');
        }
    
        return redirect()->back()->with('error', 'You cannot end your current session');
    }
    
    public function loc_access(Request $request,$id){
        if (!$request->has('_token') && $request->_token != csrf_token()) {
            return back()->with('error','Invalid data submitted.');
        }

        $location = Location::find($id);
        if(isset($location)){
            $location->is_access = !$location->is_access;
            $location->save();
            return redirect()->back()->with('success', "Location access changed successfully!");
        }
        return redirect()->back()->with('error', "Location not found!");
    }
    public function password_admin(Request $request, $id)
    {    
        if (!$request->has('_token') || $request->_token !== csrf_token()) {
            return back()->with('error', 'Invalid data submitted.');
        }
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this resource.');
        }

        $user = Auth::guard('web')->user();
        if (!$user->selected_head_office) {
            return back()->with('error', 'No head office selected.');
        }

        $headOffice = $user->selected_head_office;
        $ho_location = $headOffice->locations->find($id);
        if (!$ho_location) {
            return redirect()->back()->with('error', "Location not found.");
        }
        $location = $ho_location->location;
        if (!isset($location)) {
            return redirect()->back()->with('error', "Location data is not available.");
        }
        if (isset($location)) {
            $location->is_admin_password = !$location->is_admin_password;
            $location->save();
            return redirect()->back()->with('success', "Location property changed successfully!");
        }

        return redirect()->back()->with('error', "Invalid location access information.");
    }

    public function password_direct_update(Request $request){
        $validator = Validator::make($request->all(), [
            'loc_id' => 'required|exists:head_office_locations,id',
            'password' => 'required|min:8'
        ]);
        if($validator->fails()){
            return back()->with('error', 'Invalid data!');
        }
        $user = Auth::guard('web')->user();
        if (!$user->selected_head_office) {
            return back()->with('error', 'No head office selected.');
        }

        $headOffice = $user->selected_head_office;
        $ho_location = $headOffice->locations->find($request->loc_id);
        if (!$ho_location) {
            return redirect()->back()->with('error', "Location not found.");
        }
        $location = $ho_location->location;
        if (!isset($location)) {
            return redirect()->back()->with('error', "Location data is not available.");
        }
        if (isset($location)) {
            $location->password = Hash::make($request->password);
            $location->save();
            return redirect()->back()->with('success', "Location Passwore changed successfully!");
        }

        return redirect()->back()->with('error', "Invalid location access information.");
    }

    public function loc_access_multi(Request $request){
        if($request->has('select_id') && $request->has('loc_ids')){
            foreach($request->loc_ids as $loc_name){
                $loc = Location::where('username',$loc_name)->first();
                if($loc){
                    $loc->is_access = $request->select_id;
                    $loc->save();
                }
                else{
                    continue;
                }
            }
            return response()->json(['success'=>'Settings updated!'],200);
        }
        return response('Not Allowed', 403);
    }

    public function tags_multi(Request $request) {
        if($request->has('tag_id') && $request->has('loc_ids')){
            foreach($request->loc_ids as $loc_name){
                $loc = Location::where('username',$loc_name)->first();
                if($loc){
                    $loc->tag_id = $request->tag_id; 
                    $loc->save();
                    
                }
                else{
                    continue;
                }
            }
            return response()->json(['success'=>'Settings updated!'],200);
        }
        return response('Not Allowed', 403);
    }
    public function archive_multi(Request $request) {
        if($request->has('action_id') && $request->has('loc_ids')){
            foreach($request->loc_ids as $loc_name){
                $loc = Location::where('username',$loc_name)->first();
                if($loc && $loc->is_deleted != true){
                    $loc->is_active = false;
                    $loc->is_archived = true; 
                    $loc->save();
                    
                }
                else{
                    continue;
                }
            }
            return response()->json(['success'=>'Settings updated!'],200);
        }
        return response('Not Allowed', 403);
    }
    public function unarchive_multi(Request $request) {
        if($request->has('action_id') && $request->has('loc_ids')){
            foreach($request->loc_ids as $loc_name){
                $loc = Location::where('username',$loc_name)->first();
                if($loc){
                    $loc->is_active = true;
                    $loc->is_archived = false; 
                    $loc->save();
                }
                else{
                    continue;
                }
            }
            return response()->json(['success'=>'Settings updated!'],200);
        }
        return response('Not Allowed', 403);
    }
    public function renameMulti(Request $request) {
        $renames = $request->input('renames');
        if ($renames) {
            $lines = explode("\n", $renames);
    
            foreach ($lines as $line) {
                list($oldName, $newName) = array_map('trim', explode('->', $line));
                if ($oldName && $newName) {
                    $location = Location::where('username', $oldName)->first();
                    if ($location) {
                        $location->username = $newName;
                        $location->save();
                    }
                }
            }
    
            return response()->json(['success' => 'Locations renamed successfully!'], 200);
        }
        return response()->json(['error' => 'Invalid input.'], 400);
    }
    public function delete_multi(Request $request) {
        if($request->has('action_id') && $request->has('loc_ids')){
            foreach($request->loc_ids as $loc_name){
                $loc = Location::where('username',$loc_name)->first();
                if($loc){
                    $loc->is_archived = false; 
                    $loc->is_deleted = true; 
                    $loc->is_active = false; 
                    if(!isset($loc->deleted_at)){
                        $loc->deleted_at = now(); 
                    }
                    $loc->save();
                }
                else{
                    continue;
                }
            }
            return response()->json(['success'=>'Settings updated!'],200);
        }
        return response('Not Allowed', 403);
    }
    public function restore_multi(Request $request) {
        if($request->has('action_id') && $request->has('loc_ids')){
            foreach($request->loc_ids as $loc_name){
                $loc = Location::where('username',$loc_name)->first();
                if($loc){
                    $loc->is_archived = false; 
                    $loc->is_deleted = false; 
                    $loc->is_active = true; 
                    $loc->deleted_at = null; 
                    $loc->save();
                }
                else{
                    continue;
                }
            }
            return response()->json(['success'=>'Settings updated!'],200);
        }
        return response('Not Allowed', 403);
    }

    public function finance_department_detail_store(Request $request)
    {

        $request->validate([
            'finance_phone' => 'required|max:80',
            'finance_email' => 'min:1|required|string',
        ]);

        $phone = $request->finance_phone;
        $email = $request->finance_email;

        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $headOffice->finance_email = $email;
        $headOffice->finance_phone = $phone;
        $headOffice->save();

        return redirect()->route('head_office.settings')->with('sucess_message', 'Finance Departmen Details Updated');
    }
    public function import_location_incidents_preview(Request $request, $id)
    {
        $user = User::find(Auth::guard('web')->user()->id);
        $head_office = $user->selected_head_office;
        $location = $head_office->locations()->find($id);
        $location = Location::findOrFail($id);
        $records = $location->records();

        $counter = 0;
        $test = 0;

        if ($request->query('ajax')) {

            $counter = (int) $request->query('count');
            $records = $records->get();
            $records = $records->slice($counter, 10)->values();
            $realCount = count($records);
            if ($realCount == 0) {
                return 'exit';
            }
            return view('head_office.incidents.records', compact('records'));
        }

        $records = $records->take(20)->get();

        return view('head_office.incidents.preview', compact('location', 'records'));
    }

    public function single_record($id)
    {
        $record = Record::findOrFail($id);
        return view('head_office.incidents.preview-record', compact('record'));
    }
    public function single_record_link(Request $request)
    {
        $counter_new = 0;
        $counter_old = 0;
        foreach ($request->record_ids as $id) {
            $record = Record::findOrFail($id);
            if (!$record->head_office_linked) {

                //check if record was linked with head office in past
                $past = HeadOfficeCase::where([['last_linked_incident_id', $record->id]])->first();
                if (!$past) {
                    $counter_new++;
                    $case = new HeadOfficeCase();
                    $case->status = 'open';
                    $case->head_office_id = Auth::guard('web')->user()->id;
                    $case->description = "";
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
                    //dd($record->created_by);
                    $case->reported_by = $record->created_by ? $record->created_by->first_name : '';
                    $case->reported_by_id = $record->user_id;

                    $case->save();
                    $HeadOfficeLinkedCase = new HeadOfficeLinkedCase();
                    $HeadOfficeLinkedCase->head_office_case_id = $case->id;
                    $HeadOfficeLinkedCase->be_spoke_form_record_id = $record->id;
                    $HeadOfficeLinkedCase->save();
                } else {
                    $counter_old++;
                    $HeadOfficeLinkedCase = new HeadOfficeLinkedCase();
                    $HeadOfficeLinkedCase->head_office_case_id = $past->id;
                    $HeadOfficeLinkedCase->be_spoke_form_record_id = $record->id;
                    $HeadOfficeLinkedCase->case_status = $record->case_status ? 'Close' : 'Open';
                    $HeadOfficeLinkedCase->save();
                }

            }
        }
        return back()->with('success_message', 'Inicidents linked with Head Office');
    }
    public function root_cause_analysis_save(Request $request)
    {
        $user = Auth::guard('web')->user()->selected_head_office;
        if ($user) {
            if ($request->has('is_fish_bone')) {
                $user->is_fish_bone = $request->is_fish_bone;
            } else {
                $user->is_fish_bone = 0;
            }

            if ($request->has('is_fish_bone_compulsory')) {
                $user->is_fish_bone_compulsory = $request->is_fish_bone_compulsory;
            } else {
                $user->is_fish_bone_compulsory = 0;
            }

            if ($request->has('is_five_whys')) {
                $user->is_five_whys = $request->is_five_whys;
            } else {
                $user->is_five_whys = 0;
            }

            if ($request->has('is_five_whys_compulsory')) {
                $user->is_five_whys_compulsory = $request->is_five_whys_compulsory;
            } else {
                $user->is_five_whys_compulsory = 0;
            }

            $user->save();
            return back()->with('success_message', 'root cause analysis setting updated successfully');
        }
        return back()->with('error', 'Sorry you do not have access to this page');
    }
    public function default_fish_bone_question_save(Request $request)
    {
        $user = Auth::guard('web')->user()->selected_head_office;
        if (count($user->fish_bone_questions) < 14) {
            if ($request->has('default_fish_bone_question')) {
                $question = $user->fish_bone_questions()->find($request->default_fish_bone_question);
            } else {
                $question = new DefaultFishBoneQuestion();
            }

            $question->head_office_id = $user->id;
            $question->question = $request->question;
            $question->save();
            return redirect()->route('head_office.settings', '#root_cause_analysis')->with('success_message', 'Questions updated');
        }

        return redirect()->route('head_office.settings', '#root_cause_analysis')->with('error', 'You can only create 14 questions');
    }

    public function default_fish_bone_question_delete($id)
    {
        $user = Auth::guard('web')->user()->selected_head_office;
        $question = $user->fish_bone_questions()->find($id);
        $question->delete();
        return redirect()->route('head_office.settings', '#root_cause_analysis')->with('success_message', 'Question deleted');
    }

    public function default_five_whys_question_save(Request $request)
    {
        $user = Auth::guard('web')->user()->selected_head_office;
        if (count($user->five_whys_questions) < 5) {
            if ($request->has('default_five_whys_question')) {
                $question = $user->five_whys_questions()->find($request->default_five_whys_question);
            } else {
                $question = new DefaultFiveWhysQuestion();
            }

            $question->head_office_id = $user->id;
            $question->question = $request->question;
            $question->save();
            return redirect()->route('head_office.settings', '#root_cause_analysis')->with('success_message', 'Questions updated');
        }

        return redirect()->route('head_office.settings', '#root_cause_analysis')->with('error', 'You can only create 5 questions');
    }

    public function default_five_whys_question_delete($id)
    {
        $user = Auth::guard('web')->user()->selected_head_office;
        $question = $user->five_whys_questions()->find($id);
        $question->delete();
        return redirect()->route('head_office.settings', '#root_cause_analysis')->with('success_message', 'Question deleted');
    }

    public function contact(Request $request)
    {
        $contacts = Auth::guard('web')->user()->selected_head_office->contacts();
        $addresses = Auth::guard('web')->user()->selected_head_office->addresses();
        $counter = 0;
        $limit_of_records = self::$limitOfTimeLineRecords;
        if ($request->query('ajax') && $request->query('contacts') == 'true') {
            $counter = (int) $request->query('count');

            $contacts = $contacts->skip($counter);

            $contacts = $contacts->take($limit_of_records)->get();

            $realCount = count($contacts);
            $show_scripts = true;
            if ($realCount == 0) {
                return 'exit';
            } else {
                return view('head_office.show_contacts')->with(compact('contacts', 'show_scripts', 'counter'));
            }

        }
        if ($request->query('ajax') && $request->query('addresses') == 'true') {
            $counter = (int) $request->query('count');

            $addresses = $addresses->skip($counter);

            $addresses = $addresses->take($limit_of_records)->get();

            $realCount = count($addresses);
            if ($realCount == 0) {
                return 'exit';
            } else {
                return view('head_office.show_address')->with(compact('addresses'));
            }

        }
        $contacts = $contacts->take($limit_of_records)->get();
        $addresses = $addresses->take($limit_of_records)->get();

        return view('head_office.contact', compact('contacts', 'addresses', 'counter'));
    }
    public function view_contact($id)
    {
        $contact = Auth::guard('web')->user()->selected_head_office->contacts()->findOrFail($id);

        $case_ids = [];
        foreach ($contact->contact_cases as $case) {
            $case_ids[] = $case->case->id;
        }

        $connection_ids = [];
        foreach ($contact->contact_connections as $connection) {
            $connection_ids[] = $connection->contact->id;
        }
        $contacts = Auth::guard('web')->user()->selected_head_office->contacts;
        //$prescriber_contacts = PrescriberContact::all();
        $cases = Auth::guard('web')->user()->selected_head_office->cases;
        return view('head_office.view_contact', compact('contact', 'cases', 'case_ids', 'contacts', 'connection_ids'));
    }
    public function add_new_address(Request $request, $id)
    {
        $address = Address::where([['head_office_id', Auth::guard('web')->user()->selected_head_office->id], ['address', $request->address]])->first();
        if (!$address) {
            $address = new Address();
            $address->address = $request->address;
            $address->head_office_id = Auth::guard('web')->user()->selected_head_office->id;
            $address->save();
        }
        if (ContactAddress::where([['contact_id', $id], ['address_id', $address->id]])->first()) {
            return back()->with('error', 'Address already assigned');
        }

        foreach (ContactAddress::where('contact_id', $id)->get() as $con) {
            $con->is_present_address = 0;
            $con->save();
        }

        $contact_address = new ContactAddress();
        $contact_address->contact_id = $id;
        $contact_address->address_id = $address->id;
        $contact_address->is_present_address = 1;
        $contact_address->save();
        return back()->with('success_message', 'Contact created successfully');
    }

    public function assign_new_case(Request $request, $contact_id)
    {
        if (!$request->ids) {
            return back()->with('error', 'No case selected');
        }

        foreach ($request->ids as $id) {
            if (!CaseContact::where([['case_id', $id], ['contact_id', $contact_id]])->first()) {
                $case_contact = new CaseContact();
                $case_contact->case_id = $id;
                $case_contact->contact_id = $contact_id;
                $type = 'type_' . $id;
                $case_contact->type = $request->$type;
                $case_contact->save();
            }
        }
        return back()->with('success_message', 'Case connected with this contact');
    }

    public function delete_new_case($case_contact_id, $case_id)
    {
        $case = Auth::guard('web')->user()->selected_head_office->cases()->find($case_id);
        if ($case) {
            $case_contact = $case->case_contacts()->find($case_contact_id);
            if ($case_contact) {
                $case_contact->delete();
                return back()->with('success_message', 'Case deleted successfully');
            }
        }
        return back()->with('error', 'Case not found');
    }
    public function add_new_relation(Request $request, $contact_id = null, $connection_id = null)
    {
        $request->validate([
            'relation_type' => ['required', 'max:60'],
        ]);

        if ($connection_id) {
            if (ContactConnection::where([['connected_with_id', $request->contact_ids[0]], ['contact_id', $contact_id], ['id', '!= ', $connection_id]])->first()) {
                return back()->with('error', 'Relation already exsist');
            }
            $ContactConnection = ContactConnection::findOrFail($connection_id);
            $ContactConnection->connected_with_id = $request->contact_ids[0];
            $ContactConnection->contact_id = $contact_id;
            $ContactConnection->relation_type = $request->relation_type;
            $ContactConnection->save();

        } else {
            foreach ($request->contact_ids as $id) {
                $contact_with = ContactConnection::where([['connected_with_id', $id], ['contact_id', $contact_id]])->first();
                if (!$contact_with) {
                    $ContactConnection = new ContactConnection();
                    $ContactConnection->connected_with_id = $id;
                    $ContactConnection->contact_id = $contact_id;
                    $ContactConnection->relation_type = $request->relation_type;
                    $ContactConnection->save();
                } else {
                    $contact_with->relation_type = $request->relation_type;
                    $contact_with->save();
                }
            }
        }
        return back()->with('success_message', 'relation created successfully');
    }
    public function delete_relation($connection_id, $contact_id)
    {
        $contact = Auth::guard('web')->user()->selected_head_office->contacts()->find($contact_id);
        if ($contact) {
            $connection = $contact->contact_connections()->find($connection_id);
            $connection->delete();
            return back()->with('succes_message', 'Connection deleted');
        }
        return back()->with('error', 'Relation not found');
    }
    public function add_new_contact(Request $request, $id = null)
    {
        if ($id) {
            $contact = Auth::guard('web')->user()->selected_head_office->contacts->find($id);
        } else {
            $contact = new Contact();
        }

        $contact->first_name = $request->first_name;

        $contact->middle_name = $request->middle_name;
        $contact->last_name = $request->last_name;
        $contact->title = $request->title;
        $contact->date_of_birth = $request->date_of_birth ? Carbon::createFromFormat('Y-m-d', $request->date_of_birth) : null;

        $contact->registration_no = $request->registration_no;
        $contact->company = $request->company;
        $contact->website = $request->website;
        $contact->profession = $request->profession;

        $contact->email_address = json_encode($request->email_address);
        $contact->telephone_no = json_encode($request->telephone_no);
        $contact->gender = $request->gender;
        $contact->head_office_id = Auth::guard('web')->user()->selected_head_office->id;
        //dd($contact);
        $contact->note = $request->note;
        $contact->save();
        if ($request->has('address')) {
            $address = Address::where('address', $request->address)->first();

            if (!$address) {
                $address = new Address();
            }

            $address->address = $request->address;
            $address->head_office_id = Auth::guard('web')->user()->selected_head_office->id;
            $address->save();

            $ContactAddress = ContactAddress::where([['contact_id', $contact->id], ['address_id', $address->id]])->first();

            if (!$ContactAddress) {
                $ContactAddress = new ContactAddress();
                $ContactAddress->contact_id = $contact->id;
                $ContactAddress->address_id = $address->id;
                $ContactAddress->is_present_address = 1;
                $ContactAddress->save();
            }

            foreach (ContactAddress::where('contact_id', $contact->id)->where('id', '!=', $ContactAddress->id)->get() as $ad) {
                $ad->is_present_address = 0;
                $ad->save();
            }
        }

        return redirect()->route('head_office.contact', '#patient_contact')->with('success_message', 'Contact created successfully');
    }

    public function add_new_normal_address(Request $request, $id = null)
    {

        if ($id) {
            $address = Auth::guard('web')->user()->selected_head_office->addresses->find($id);
        } else {
            $address = new Address();
        }

        $request->validate([
            'address' => 'string|unique:addresses,address,' . $address->id,
        ]);
        $address->address = $request->address;
        $address->head_office_id = Auth::guard('web')->user()->selected_head_office->id;
        $address->save();

        return redirect()->route('head_office.contact', '#prescriber_contact')->with('success_message', 'Address Updated');
    }
    public function add_new_normal_address_delete($id)
    {
        $address = Auth::guard('web')->user()->selected_head_office->addresses()->findOrFail($id);
        $address->delete();
        return redirect()->route('head_office.contact', '#prescriber_contact')->with('success_message', 'Address deleted successfully');
    }

    public function add_new_contact_delete($id)
    {
        $contact = Auth::guard('web')->user()->selected_head_office->contacts()->findOrFail($id);
        $contact_connections = ContactConnection::where('connected_with_id', $contact)->get();
        foreach ($contact_connections as $contact_connection) {
            $contact_connection->delete();
        }
        $contact->delete();
        return redirect()->route('head_office.contact', '#patient_contact')->with('success_message', 'Contact deleted successfully');
    }
    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:0,1,2,3,4,5',
        ]);
        if($validator->fails()){
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $status = $request->status;
        $user = User::find(Auth::guard('web')->user()->id);
        $head_office = $user->selected_head_office_user;
        //0 in the office, 1 donot distrub, 2 working from home, 3 away, 4 working off site.
        $head_office->work_status = $status;
        $head_office->save();
        return response(['user' => $head_office], 200);
    }
    public function updateActive(Request $request)
    {
        $request->merge([
            'status' => filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN)
        ]);
                
        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean',
        ]);
        if($validator->fails()){
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $status = $request->status;
        $user = User::find(Auth::guard('web')->user()->id);
        $head_office = $user->selected_head_office_user;
        $head_office->is_active = (bool)$status;
        $head_office->save();
        return response(['user' => $head_office], 200);
    }
    public function updateDisturb(Request $request)
    {
        $request->merge([
            'status' => filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN)
        ]);
                
        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean',
        ]);
        if($validator->fails()){
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $status = $request->status;
        $user = User::find(Auth::guard('web')->user()->id);
        $head_office = $user->selected_head_office_user;
        $head_office->do_not_disturb = (bool)$status;
        $head_office->save();
        return response(['user' => $head_office], 200);
    }
    public function update_user_settings(Request $request, $user)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $head_office_user = Auth::guard('web')->user()->selected_head_office->users()->where('user_id', $user)->first();
        $forms = Auth::guard('web')->user()->selected_head_office->be_spoke_forms;
        foreach ($forms as $form) {
            // Initialize checkbox array
            $chkbox_array = [];
            
            // Check request parameters and populate checkbox array
            if ($request->has('allCases')) {
                $chkbox_array['1'] = [1];
            }
            if ($request->has('ownCases')) {
                $chkbox_array['2'] = 2;
            }
            if ($request->has('cases_assigned_location')) {
                $chkbox_array['5'] = 5;
            }
            if ($request->has('assigned_contacts')) {
                $chkbox_array['6'] = 6;
            }
            if ($request->has('certainTypes')) {
                $chkbox_array['3'] = $request->input('select_assigned_types', null);
            }
            if ($request->has('assignedToAnotherUser')) {
                $chkbox_array['4'] = $request->input('select_assigned_users', null);
                $chkbox_array['2'] = 2;
            }
            
            // Save user settings
            $head_office_user->user_can_view = !empty($chkbox_array) ? json_encode($chkbox_array) : '{"1":[1]}';
            $head_office_user->certain_locations = !empty($request->locations_assigned) ? json_encode($request->locations_assigned) : null;
            $head_office_user->save();
        
            $f = 'form_' . $form->id;
            
            if ($head_office_user) {
                $r = $request->input($f);
        
                // Find or create user incident setting
                $user_setting = $head_office_user->user_incident_settings()->where('be_spoke_form_id', $form->id)->first();
        
                if ($r) {
                    if ($user_setting) {
                        $user_setting->delete();
                    }
        
                    $user_setting = new HeadOfficeUserIncidentSetting();
                    $user_setting->be_spoke_form_id = $form->id;
                    $user_setting->head_office_user_id = $head_office_user->id;
        
                    $email = $form->id . '_is_email';
                    $user_setting->is_email = $request->input($email, false);
        
                    $is_share_cases = $form->id . '_is_share_cases';
                    $user_setting->is_share_cases = $request->input($is_share_cases, false);
        
                    $is_close_cases = $form->id . '_is_close_cases';
                    $user_setting->is_close_cases = $request->input($is_close_cases, false);
        
                    $is_statement_request = $form->id . '_is_statement_request';
                    $user_setting->is_statement_request = $request->input($is_statement_request, false);
        
                    $is_rca_request = $form->id . '_is_rca_request';
                    $user_setting->is_rca_request = $request->input($is_rca_request, false);
        
                    $is_read_only = $form->id . '_is_read_only';
                    $user_setting->is_read_only = $request->input($is_read_only, false);
        
                    $min_prority = $form->id . '_min_prority';
                    $user_setting->min_prority = $request->input($min_prority, null);
        
                    $max_prority = $form->id . '_max_prority';
                    $user_setting->max_prority = $request->input($max_prority, null);
        
                    $user_setting->is_active = 1;
                    
                    $loc = $form->id . '_all_location';
                    $loc_ids = [];
                    
                    if ($request->input($loc)) {
                        foreach ($head_office->locations as $location) {
                            $loc_ids[] = $location->id;
                        }
                        $user_setting->location_id = json_encode($loc_ids);
                    } else {
                        $loc = $form->id . '_location';
                        if ($request->input($loc)) {
                            $id = $form->id . '_manage_location_ids';
                            $user_setting->location_id = json_encode($request->input($id, []));
                        }
                    }
        
                    $user_setting->save();
                } else {
                    if ($user_setting) {
                        $user_setting->is_active = 0;
                        $user_setting->save();
                    }
                }
        
                // Review form settings
                $f = 'review_form_' . $form->id;
                $r = $request->input($f);
                $user_setting_review = $head_office_user->user_review_settings()->where('be_spoke_form_id', $form->id)->first();
        
                if ($r) {
                    if ($user_setting_review) {
                        $user_setting_review->delete();
                    }
        
                    $user_setting_review = new HeadOfficeUserReviewSetting();
                    $user_setting_review->be_spoke_form_id = $form->id;
                    $user_setting_review->head_office_user_id = $head_office_user->id;
        
                    $user_setting_review->is_active = 1;
        
                    $loc = 'review_' . $form->id . '_all_location';
                    $loc_ids = [];
                    
                    if ($request->input($loc)) {
                        foreach ($head_office->locations as $location) {
                            $loc_ids[] = $location->id;
                        }
                        $user_setting_review->location_id = json_encode($loc_ids);
                    } else {
                        $loc = 'review_' . $form->id . '_location';
                        if ($request->input($loc)) {
                            $id = 'review_' . $form->id . '_location_ids';
                            $user_setting_review->location_id = json_encode($request->input($id, []));
                        }
                    }
        
                    $user_setting_review->save();
                } else {
                    if ($user_setting_review) {
                        $user_setting_review->is_active = 0;
                        $user_setting_review->save();
                    }
                }
            }
            
        }
        return back()->with('success_message', 'Settings updated');
        
        return back()->with('error', 'User not found');
    }
    public function update_user_assigned_locations(Request $request, $user)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $head_office_user = Auth::guard('web')->user()->selected_head_office->users()->where('user_id', $user)->first();
        $profile = $head_office_user->get_permissions();
        $accessable_locs = $head_office_user->get_permissions()->locations ?? [];
        if(empty($accessable_locs) || !isset($accessable_locs) || $accessable_locs == 'null') {
            return back()->with('error','You dont have access to these locations!');
        }
        if (!empty($request->assigned_locations)) {
            $filtered_locations = array_filter($request->assigned_locations, function ($loc) use ($accessable_locs) {
                return in_array($loc, json_decode($accessable_locs,true));
            });
            
            $head_office_user->assigned_locations = !empty($filtered_locations) ? json_encode($filtered_locations) : null;
        } else {
            $head_office_user->assigned_locations = Null;
        }
        $head_office_user->save();
            return back()->with('success_message', 'Locations assigned successfully');
    }
    public function add_contact_detail(Request $request)
    {
        $user = User::find(Auth::guard('web')->user()->id);
        $head_office_user = $user->selected_head_office_user;
        $check = $head_office_user->head_office_user_contact_details()->where([['contact', $request->contact], ['type', $request->type]])->first();
        if (!$check) {
            $head_office_user_contact_detail = new HeadOfficeUserContactDetail();
            $head_office_user_contact_detail->head_office_user_id = $head_office_user->id;
            $head_office_user_contact_detail->contact = $request->contact;
            $head_office_user_contact_detail->type = $request->type;
            $head_office_user_contact_detail->save();
            return redirect()->route('head_office.view_profile', '#profile')->with('success_message', 'Contact details updated successfully');
        }
        return redirect()->route('head_office.view_profile', '#profile')->with('error', 'Contact details already exsist');

    }
    public function update_phone(Request $request)
    {
        $id = $request->id;
        $value = $request->value;
        $user = User::find(Auth::guard('web')->user()->id);
        $head_office_user = $user->selected_head_office_user;
        $check = $head_office_user->head_office_user_contact_details->find($id);
        if ($check) {
            $check->contact = $value;
            $check->save();
            return response(['result' => true, 'msg' => 'Contact updated successfully']);
        }
        return response(['result' => false, 'msg' => 'Contact not found']);
    }
    public function update_email(Request $request)
    {
        $value = $request->value;
            $user = User::find(Auth::guard('web')->user()->id);
            $head_office_user = $user->selected_head_office_user;
        $check = $head_office_user->head_office_user_contact_details->find($request->id);

        if (!$check) {
            $check = $head_office_user->head_office_user_contact_details()->where('contact', $value)->first();
            //return  response(['result'=>$check]);
            if (!$check) {
                $check = new HeadOfficeUserContactDetail();
                $check->head_office_user_id = $head_office_user->id;
                $check->type = $request->type;
            } else {

                return response(['result' => false, 'msg' => 'Contact already exsist', 'data' => $check]);
            }
        }
        $check->contact = $value;
        $check->save();
        return response(['result' => true, 'msg' => 'Contact updated successfully', 'data' => $check]);
    }

    public function update_email_user_settings(Request $request)
    {
        $value = $request->value;
        
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if(isset($request->id)){
            $head_office_user = $head_office->users()->find($request->id);
        }else{
            $user = User::find(Auth::guard('web')->user()->id);
            $head_office_user = $user->selected_head_office_user;
        }
        $logged_ho_u = Auth::guard('web')->user()->getHeadOfficeUser();
        
        if(!$logged_ho_u->user_profile_assign->profile->super_access == true && $head_office_user->id != $logged_ho_u->id){
            return response()->json(['error' => 'Access denied!'], 403);
        }
        $check = $head_office_user->head_office_user_contact_details->find($request->contact_id);

        if (!$check) {
            $check = $head_office_user->head_office_user_contact_details()->where('contact', $value)->first();
            //return  response(['result'=>$check]);
            if (!$check) {
                $check = new HeadOfficeUserContactDetail();
                $check->head_office_user_id = $head_office_user->id;
                $check->type = $request->type;
            } else {

                return response(['result' => false, 'msg' => 'Contact already exsist', 'data' => $check]);
            }
        }
        $check->contact = $value;
        $check->save();
        return response(['result' => true, 'msg' => 'Contact updated successfully', 'data' => $check]);
    }

    public function update_position(Request $request){
        $value = $request->value;
        $user = User::find(Auth::guard('web')->user()->id);
        $head_office_user = $user->selected_head_office_user;
        if($head_office_user){
            $head_office_user->position = $value;
            $head_office_user->save();
        }
        
        
        return response(['result' => true, 'msg' => 'Contact updated successfully', 'data' => $head_office_user->position]);
    }

    public function remove_session_records(Request $request){
        if($request->sessionIds){
            $ids = explode(',',$request->sessionIds[0]);
            foreach ($ids as $sessionId) {
                UserLoginSession::where('id', $sessionId)->delete();
            }
            return redirect()->back()->with(['success'=>'records deleted!']);
        }
        return redirect()->back()->with(['error'=>'ids not found']);
    }
    public function log_session_records(Request $request){
        if($request->sessionIds){
            $ids = explode(',',$request->sessionIds[0]);
            foreach ($ids as $sessionId) {
                UserLoginSession::where('id', $sessionId)->delete();
            }
            return redirect()->back()->with(['success'=>'Users Session logged Out!']);
        }
        return redirect()->back()->with(['error'=>'ids not found']);
    }
    public function update_about(Request $request){
        $value = $request->value;
        $user = User::find(Auth::guard('web')->user()->id);
        $head_office_user = $user->selected_head_office_user;
        if($head_office_user){
            $head_office_user->about_me = $value;
            $head_office_user->save();
        }
        
        
        return response(['result' => true, 'msg' => 'Contact updated successfully', 'data' => $head_office_user->position]);
    }
    public function delete_contact($id)
    {
        $user = User::find(Auth::guard('web')->user()->id);
        $head_office_user = $user->selected_head_office_user;
        $check = $head_office_user->head_office_user_contact_details->find($id);
        if ($check) {
            $check->delete();
            return redirect()->back()->with('success_message', 'Contact deleted successfully');
        }
        return redirect()->route('head_office.view_profile', '#profile')->with('error', 'Contact not found');
    }
    public function delete_contact_users_settings($id,$hou_id)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $head_office_user = $headOffice->users()->find($hou_id);
        $check = $head_office_user->head_office_user_contact_details->find($id);
        if ($check) {
            $check->delete();
            return redirect()->back()->with('success_message', 'Contact deleted successfully');
        }
        return redirect()->route('head_office.view_profile', '#profile')->with('error', 'Contact not found');
    }
    public function update_location(Request $request)
    {
        $user = User::find(Auth::guard('web')->user()->id);
        $head_office_user = $user->selected_head_office_user;
        $head_office_user->location = $request->location;
        $head_office_user->save();
        return response(['result' => true, 'msg' => 'location updated successfully']);
    }
    public function update_area(Request $request)
    {
        $user = User::find(Auth::guard('web')->user()->id);
        $head_office_user = $user->selected_head_office_user;

        $area = new HeadOfficeUserArea();
        $area->head_office_user_id = $head_office_user->id;
        $area->area = $request->area;
        $area->level = $request->level;
        $area->save();
        return response(['result' => true, 'data' => $area], 200);
    }
    public function update_area_user_settings(Request $request)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $head_office_user = $headOffice->users()->find($request->id);
        $logged_ho_u = Auth::guard('web')->user()->getHeadOfficeUser();
        if(!$logged_ho_u->user_profile_assign->profile->super_access && $head_office_user->id != $logged_ho_u->id){
            return response()->json(['error' => 'Access denied!'], 403);
        }

        $area = new HeadOfficeUserArea();
        $area->head_office_user_id = $head_office_user->id;
        $area->area = $request->area;
        $area->level = $request->level;
        $area->save();
        return response(['result' => true, 'data' => $area], 200);
    }
    public function delete_area($id)
    {
        $user = User::find(Auth::guard('web')->user()->id);
        $head_office_user = $user->selected_head_office_user;
        $check = $head_office_user->head_office_user_area->find($id);
        if ($check) {
            $check->delete();
            return redirect()->back()->with('success_message', 'area deleted successfully');
        }
        return redirect()->route('head_office.view_profile', '#profile')->with('error', 'area not found');
    }
    public function delete_area_user_settings($id,$hou_id)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $head_office_user = $headOffice->users()->find($hou_id);
        $check = $head_office_user->head_office_user_area->find($id);
        if ($check) {
            $check->delete();
            return redirect()->back()->with('success_message', 'area deleted successfully');
        }
        return redirect()->route('head_office.view_profile', '#profile')->with('error', 'area not found');
    }
    public function update_head_office_contact_details(Request $request)
    {
        $user = User::find(Auth::guard('web')->user()->id);
        $head_office = $user->selected_head_office;
        $column = $request->column;
        $value = $request->value;
    
        // Adjust value for link_token: lowercase and remove spaces
        if ($column === 'link_token') {
            $value = strtolower(str_replace(' ', '', $value));
        }
    
        $rules = [
            'value' => in_array($column, ['email', 'technical_email', 'finance_email']) 
                       ? 'required|email' 
                       : 'required|string',
        ];
    
        if ($column === 'link_token') {
            $rules['value'] .= '|unique:head_offices,link_token'   // Ensure uniqueness
                            . '|not_in:127'                       // Disallow the value '127'
                            . '|regex:/^\S*$/'                    // Ensure no spaces
                            . '|string';                          // Ensure it's a string
        }
        
    
        $messages = [
            'value.required' => 'The value field is required.',
            'value.email' => 'The value must be a valid email address.',
            'value.unique' => 'The company name must be unique.',
        ];
    
        // Validator
            $validator = \Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                $errorMessage = $validator->errors()->first();

                // Return JSON response if it's an AJAX request
                if ($request->expectsJson()) {
                    return response()->json(['result' => false, 'message' => $errorMessage], 400);
                }

                // Otherwise, redirect back with errors
                return back()->withErrors($errorMessage);
            }
    
        $head_office->$column = $value;
        $head_office->save();
        if ($request->expectsJson()) {
            return response()->json(['result' => true, 'message' => 'Head office contact details updated successfully', 'value' => $value], 200);
        }
        return back()->with('success', 'Head office contact details updated successfully.');
    }
    
    public function update_form_details(request $request)
    {
        $user = User::find(Auth::guard('web')->user()->id);
        $head_office = $user->selected_head_office;
        if(!isset($head_office)){
            return redirect()->route('login');
        }
        $form = Form::find($request->form_id);
        $column = $request->column;
        $form->$column = $request->value;
        $form->save();
        return response(['result' => true, 'value' => $form->$column]);
    }

    public function update_head_office_location_details(request $request)
    {
        $user = User::find(Auth::guard('web')->user()->id);
        if($user){
            $location = Location::where('id',$request->location_id)->first();
            $column = $request->column;
            $location->$column = $request->value;
            $location->save();
            return response(['result' => true, 'value' => $location->$column]);
        }
        return response(['result' => false, 'value' => 'Error!'],404);
    }
    public function head_office_approved_location_users()
    {
        $user = User::find(Auth::guard('web')->user()->id);
        $head_office = $user->selected_head_office;
        $approved_groups = $head_office->approved_groups;
        $approved_locations = $head_office->approved_locations;
        $array = collect();
        foreach ($approved_groups as $group) {
            $array->push($group);
        }
        foreach ($approved_locations as $locations) {
            $array->push($locations);
        }
        $array = $array->groupBy('user_id');

        $locations = $head_office->locations;
        $groups = $head_office->head_office_organisation_groups;
        return view('head_office.my_organisation.approved_location_users', compact('array', 'head_office', 'groups', 'locations'));
    }
    public function head_office_approved_location_delete(Request $request, $id)
    {
        if (!$request->has('_token') && $request->_token != csrf_token()) {
            return back()->with('error','Invalid data submitted.');
        }
        $user = $user = User::find(Auth::guard('web')->user()->id);
        $head_office = $user->selected_head_office;
        $locations = ApprovedLocationLocationUser::where('user_id', $id);
        $locations->delete();

        $groups = ApprovedLocationGroupUser::where('user_id', $id);
        $groups->delete();

        return redirect()->route('head_office.approved_location.users', '#approved')->with('success_message', 'Deleted successfully');
    }
    public function store_location_user(Request $request, $id = null)
    {
        $group_ids = $request->groups;
        $location_ids = $request->locations;
        $user = $user = User::find(Auth::guard('web')->user()->id);
        $group_name = User::where('email', $request->group_name)->first();
        $msg = "Approved Locations and groups assigned.";
        if (!$group_name) {
            $position = Position::where('name', 'Normal User')->first();
            if (!$position) {
                $position = new Position();
                $position->name = "Normal User";
                $position->save();
            }
            $group_name = new User();
            $group_name->email = $request->group_name;
            $group_name->first_name = "null";
            $group_name->surname = "null";
            $group_name->position_id = $position->id;
            $group_name->mobile_no = "0000000000";
            $group_name->password = Hash::make('123456');
            $group_name->email_verification_key = Str::random(64);
            $group_name->save();
            $msg = '';
            Mail::send('emails.emailVerification', ['type' => 2, 'token' => $group_name->email_verification_key], function ($message) use ($group_name) {
                $message->to($group_name->email);
                $message->subject(env('APP_NAME') . ' - Verify your email');
            });
            $msg = "New user created and Email Varification link send to user.";
        }
        $head_office = $user->selected_head_office;

        $groups = $head_office->approved_groups()->where('user_id', $group_name->id);
        if ($groups->count()) {
            $groups->delete();
        }

        $locations = $head_office->approved_locations()->where('user_id', $group_name->id);
        if ($locations->count()) {
            $locations->delete();
        }

        if ($group_ids) {
            foreach ($group_ids as $group_id) {
                $groups = new ApprovedLocationGroupUser();
                $groups->head_office_id = $head_office->id;
                $groups->head_office_organisation_group_id = $group_id;
                $groups->user_id = $group_name->id;
                $groups->save();
            }
        }
        if ($location_ids) {
            foreach ($location_ids as $location_id) {
                $groups = new ApprovedLocationLocationUser();
                $groups->head_office_id = $head_office->id;
                $groups->location_id = $location_id;
                $groups->user_id = $group_name->id;
                $groups->save();
            }
        }
        return back()->with('success_message', $msg);
    }
    public function search_email(Request $request)
    {
        $user = User::find(Auth::guard('web')->user()->id);
        $head_office = $user->selected_head_office;
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $groups = $head_office->approved_groups->where('user_id', $user->id);
            $locations = $head_office->approved_locations->where('user_id', $user->id);
            $group_ids = [];
            $location_ids = [];
            foreach ($groups as $group) {
                $group_ids[] = $group->head_office_organisation_group_id;
            }
            foreach ($locations as $location) {
                $location_ids[] = $location->location_id;
            }
            return response(['result' => true, 'locations' => $location_ids, 'groups' => $group_ids], 200);
        }
        return response(['result' => false], 404);

    }
    public function update_profile(Request $request)
    {
        $file = $request->file;
        $head_office = Auth::guard('web')->user()->selected_head_office;
        // $contents = file_get_contents($image);
        // $name = time().'.png';
        try {
            if (!is_dir('v2/head_office_profile')) {
                mkdir('v2/head_office_profile', 0777, true);
            }

            $name = $head_office->id . '.jpg';
            $destinationPath = 'v2/head_office_profile/' . $name;
            base64Convert::save_base64($file, $destinationPath);
        } catch (Exception $e) {
            return $e->getMessage();
        }

        // $extension = $cover->getClientOriginalExtension();
        // Storage::disk('public')->put($cover->getFilename().'.'.$extension,File::get($cover));

        // try
        // {
        //     $account = new Account;
        //     // .... etc. do something with the model
        //     $account->logo= $image->get();
        //     $account->save();
        // }
        // catch(\Illuminate\Database\QueryException $e)
        // {
        //     // DEBUG IN CASE OF ERROR
        //     dd($e);
        // }
    }
    public function dynamic_info(Request $request)
    {
        $token = request()->query('token');

        $head_office = HeadOffice::where('link_token', $token)->first();
        if ($head_office) {
            return redirect('/app.html#!/login');
        } else {
            return redirect('/app.html#!/login');
        }

    }

    public function apply_theme(Request $request)
{
    $user = Auth::guard('web')->user();
    $head_office = $user->selected_head_office;

    if ($head_office) {
        // Filter out unwanted keys and null values from the request
        $filtered_request = array_diff_key($request->all(), array_flip(['_token', 'company_logo', 'background_image', 'portal_logo','company_profile']));
        $filtered_request = array_filter($filtered_request, fn($value) => $value !== null);

        // Determine the link token
        $token = $head_office->link_token ?: $head_office->company_name;
        $filtered_request['link_token'] = $token;

        // Handle file uploads
        $files = ['company_logo', 'background_image', 'portal_logo'];
        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                $imagePath = $request->file($file)->store('public/images');
                $head_office->{$file} = basename($imagePath);
            }
        }

        if ($request->hasFile('company_profile')) {
            try {
                // Ensure the directory exists
                $directoryPath = 'v2/head_office_profile';
                if (!is_dir($directoryPath)) {
                    mkdir($directoryPath, 0777, true);
                }
        
                // Define the file name and destination path
                $name = $head_office->id . '.jpg';
                $destinationPath = $directoryPath . '/' . $name;
        
                // Move the uploaded file to the specified path
                $file = $request->file('company_profile');
                $file->move($directoryPath, $name);
                
            } catch (Exception $e) {
                // Handle any exceptions and return an error message
                return back()->with('error', 'Failed to upload the company profile: ' . $e->getMessage());
            }
        }
        


        // Save file changes and update other fields
        $head_office->update($filtered_request);

        // Redirect to the company info page
        return redirect()->route('head_office.company_info')->with('success', 'Theme applied successfully.');
    }

    return back()->with('error', 'Failed to apply changes.');
}

    
    public function change_percentage_merge(Request $request){
        $validator = Validator::make($request->all(), [
            'value' => 'required|numeric|digits_between:1,3|max:100|min:0'
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        $head_office = Auth::guard('web')->user()->selected_head_office;
        $head_office->percentage_merge = (int) $request->value;
        $head_office->save();
        return response()->json(['success' => true], 200);

    }
    public function theme_data(Request $request)
    {
        if (isset($request->token)) {
            $token = $request->token;
            $head_office = HeadOffice::where('link_token', $token)->first();
            $logo  = $head_office->logo;
            if (isset($head_office)) {
                $data = [
                    'primary_color' => $head_office->primary_color,
                    'icon_color' => $head_office->icon_color,
                    'highlight_color' => $head_office->highlight_color,
                    'company_logo' => $head_office->company_logo,
                    'background_image' => $head_office->background_image,
                    'title_text' => $head_office->title_text,
                    'sign_button_color' => $head_office->sign_button_color,
                    'login_highlight_color' => $head_office->login_highlight_color,
                    'portal_logo' => $head_office->portal_logo,
                    'portal_text' => $head_office->portal_text,
                    'logo' => $logo,
                    'sign_btn_text_color' => $head_office->sign_btn_text_color,
                ];
                return response(['result' => true, 'data' => $data]);
            } else {
                return response(['result' => false], 404);
            }
        }
    }

    public function otp_loc_email(Request $request)
{
    if (!$request->has('_token') && $request->_token != csrf_token()) {
        return back()->with('error','Invalid data submitted.');
    }
    $user = Auth::user();
    if ($user) {
        if (isset($request->id)) {
            $head_office = HeadOffice::where('id', $user->selected_head_office_id)->first();
            $head_office_location = HeadOfficeLocation::where('head_office_id', $head_office->id)->where('location_id',$request->id)->first();
                if(isset($head_office_location)){
                $loc = Location::where('id',$request->id)->first();
                if($loc && $request->action){
                    $loc->is_email = $request->action == 'enable' ? false : true;
                    $loc->save();
                    if ($request->action === 'disable') {
                        return redirect()->back()->with(['success'=> 'Email notification has been updated. ']);;
                    } else {
                        if ($loc->is_email) {
                            Mail::to($loc->email)->send(new otp($loc));
                        }
                        return redirect()->back()->with('success', 'Two-factor authentication has been enabled for this location.');
                    }
                }
                
            }
        }
        return redirect()->back()->withErrors(['error' => 404]);
    }
    return redirect()->route('login');
}

    public function otp_loc(Request $request)
    {
        if (!$request->has('_token') && $request->_token != csrf_token()) {
            return back()->with('error','Invalid data submitted.');
        }
        $user = Auth::user();
        if ($user) {
            if (isset($request->id)) {
                $head_office = HeadOffice::where('id', $user->selected_head_office_id)->first();
                $head_office_location = HeadOfficeLocation::where('head_office_id', $head_office->id)->where('location_id',$request->id)->get();
                if(isset($head_office_location)){
                    $loc = Location::where('id',$request->id)->first();
                    $loc_otp = $loc->otp;
                    if(isset($loc_otp)){
                        $loc_otp->update([
                            'isEnabled' => !$loc_otp->isEnabled
                        ]);
                        $msg = 'OTP '. ($loc_otp->isEnabled ? 'enabled' : 'disabled') .' successfully!';
                        return redirect()->back()->with(['success'=> $msg]);;
                    }
                    else{
                        try{
                            DB::beginTransaction();
                            $newOtp = new otp();
                            $newOtp->generate_code();
                            $loc->otp()->save($newOtp);
                            DB::commit();
                            $msg = 'OTP '. ($newOtp->isEnabled ? 'enabled' : 'disabled') .' successfully!';
                            return redirect()->back()->with(['success'=>$msg]);;
                        }
                        catch(Exception $e){
                            DB::rollBack();
                            return response()->json(['error' => 'An error occurred. Please try again.'], 500);
                        }
                    }
                }
            }
            return redirect()->back()->withErrors(['error' => 404]);
        }
        return redirect()->route('login');
    }

    public function otp_loc_all(Request $request){
        $user = Auth::user();
        if($user){
            if($request->usernames){
                $head_office = HeadOffice::where('id', $user->selected_head_office_id)->first();
                $head_office_location = HeadOfficeLocation::where('head_office_id', $head_office->id)->get();
                if(isset($head_office_location)){
                    $locations = Location::whereIn('id',$head_office_location->pluck('location_id'))->whereIn('username',$request->usernames)->get();
                    foreach($locations as $location){
                        $otp = $location -> otp;
                        if(isset($otp)){
                            $otp->update([
                                'isEnabled' => $request->action == 'enable' ? true : false
                            ]);     
                        }
                        else{
                            try{
                                DB::beginTransaction();
                                $newOtp = new otp();
                                $newOtp->isEnabled = $request->action == 'enable' ? true : false;
                                $newOtp->generate_code();
                                $location->otp()->save($newOtp);
                                DB::commit();
                            }
                            catch(Exception $e){
                                DB::rollBack();
                                return response()->json(['error' => 'An error occurred. Please try again.'], 500);
                            }
                        }
                    };
                    $locations = $head_office->locations;
                    return response()->json(['success'=>'success'],200  );
                    return view('head_office.my_organisation.loc', compact('locations'));
                }
            }
            return response()->json(['error'=>'Invalid Parameters'],404);
        }
        return response()->json(['Unauthorized!'],401);
    }

    function hide_email(Request $request,$type) {
        $contact_id = $request->contact_id;
        $user = Auth::guard('web')->user();
        $contact = HeadOfficeUserContactDetail::find($contact_id);
        if($contact){
            if($type){
                $contact->is_email_hidden = true;
                $contact->save();
                return back()->with('success_message','contact hide successfully');
            }else{
                $contact->is_email_hidden = false;
                $contact->save();
                return back()->with('success_message','contact un-hide successfully');
            }
        }
    }
    function hide_phone(Request $request,$type) {
        $contact_id = $request->contact_id;
        $user = Auth::guard('web')->user();
        $contact = HeadOfficeUserContactDetail::find($contact_id);
        if($contact){
            if($type){
                $contact->is_phone_hidden = true;
                $contact->save();
                return back()->with('success_message','contact hide successfully');
            }else{
                $contact->is_phone_hidden = false;
                $contact->save();
                return back()->with('success_message','contact un-hide successfully');
            }
        }
    }


    function shared_case_signup($email){
        $shared_case = ShareCase::where('email',$email)->first();
        if (!$shared_case) {
            return redirect('/app.html#!/signup/user?email=' . $email);
        }
    
        if ($shared_case->is_revoked) {
            $message = 'Sorry, Access revoked!';
            return view('error_message',compact('message'));
        }
    
        if ($shared_case->duration_of_access <= now()) {
            $message = 'Sorry, Link Expired';
            return view('error_message',compact('message'));
        }
    
        return redirect('/app.html#!/signup/user?email=' . $email);
    }

    function notifyAdmin(Request $request){
        $location = Auth::guard('location')->user();
        if(isset($location)){
            $headOffice = $location->head_office();

        }
        if(isset($request->form_id)){
            $form = Form::find($request->form_id);
            $headOffice = $form->form_owner;

            if($form->submitable_to_nhs_lfpse == false){
                return response()->json(['error'=>'Invalid Form!'],404  );
            }
        }


        if(!isset($headOffice)){
            return response()->json(['error'=>'Invalid Company account!'],404  );
        }

        $headOffice = $location->head_office();
        $logo = asset('images/svg/logo_blue.png');
        if(isset($headOffice)){
            $logo = $headOffice->logo;
        }
        $super_users = $headOffice->super_users();
        foreach ($super_users as $user) {
            Mail::send('emails.head_office.api-error', [
                'errorData' => $request->input('error'),
                'errorStatus' => $request->input('status'),
                'logo' => $logo,
            ], function ($message) use ($user,$logo) {
                $message->to($user->email)
                    ->subject(env('APP_NAME') . ' - API Error Notification');
            });
        }
        return response()->json(['success'=>'success'],200  );
    }

    public function add_new_case_investigator(Request $request){
        $form = Form::find($request->form_id);
        if(!isset($form)) return redirect()->back()->with('error','Form not found!');
        $case_investor_users = $request->case_investor_users;
        $user = Auth::guard('web')->user();
        $headOffice = $user->selected_head_office;
        foreach($case_investor_users as $case_investor_user){
            $headOfficeUser = $headOffice->users()->where('id',$case_investor_user)->first();
            $user_setting = $headOfficeUser->user_incident_settings()->where('be_spoke_form_id', $form->id)->first();
            if(!isset($user_setting)){
                $new_setting = new HeadOfficeUserIncidentSetting();
                $new_setting->head_office_user_id = $headOfficeUser->id;
                $new_setting->be_spoke_form_id = $form->id;
                $new_setting->location_id = json_encode($request->case_investor_locations);
                $new_setting->min_prority = $request->min_prority;
                $new_setting->max_prority = $request->max_prority;
                $new_setting->is_active = true;
                $new_setting->save();
            }else{
                $user_setting->location_id = json_encode($request->case_investor_locations);
                $user_setting->min_prority = $request->min_prority;
                $user_setting->max_prority = $request->max_prority;
                $user_setting->is_active = true;
                $user_setting->save();
            }
        }
        return redirect()->back()->with('success','Case Investigators updated!');

    }
    public function delete_case_investigator($id){
        $setting = HeadOfficeUserIncidentSetting::find($id);
        if(!isset($setting)) return redirect()->back()->with('error','setting not found!');
        $user = Auth::guard('web')->user();
        $headOffice = $user->selected_head_office;
        $setting->delete();
        return redirect()->back()->with('success','Case Investigators removed!');

    }

    public function locations_page(){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $ho_u = $user->getHeadOfficeUser();
        $profile = $ho_u->get_permissions();
        $locations = $ho_u->assigned_locations();
        if(isset($profile) && $profile->super_access == true){
            $locations = $headOffice->locations;
        }
        $allGroups = Group::where('head_office_id', $headOffice->id)->where('parent_id', null)->get();
        return view('head_office.company_location_page',compact('ho_u','locations','headOffice','allGroups'));
    }
    public function location_page_view($id){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $ho_location = $headOffice->locations()->where('id', $id)->first();
        if(!isset($ho_location)){
            return back()->with('error','Location not found!');
        }
        $location = $ho_location->location;
        $emails = isset($location->emails) ? json_decode($location->emails) : [];
        $email_notes = isset($location->email_notes) ? json_decode($location->email_notes) : [];
        $phones = isset($location->phones) ? json_decode($location->phones) : [];
        $phone_notes = isset($location->phone_notes) ? json_decode($location->phone_notes) : [];
        $comments = $ho_location->comments;
        $location_types = LocationType::all();
        $pharm_types = LocationPharmacyType::all();
        $bodies = LocationRegulatoryBody::all();
        $allGroups = Group::where('head_office_id', $headOffice->id)->where('parent_id', null)->get();
        return view('head_office.company_location_view_page',compact('allGroups','ho_location','comments','user','location_types','pharm_types','bodies','emails','phones','email_notes','phone_notes'));
    }


   public function toggleLocationStatus($id)
{
    $headOffice = Auth::guard('web')->user()->selected_head_office;
    $ho_location = $headOffice->locations()->where('location_id', $id)->first();

    if (!$ho_location) {
        return response()->json(['success' => false, 'message' => 'Location not found!']);
    }

    $location = $ho_location->location;
    $location->is_active = !$location->is_active;

    if ($location->save()) {
        return response()->json(['success' => true, 'message' => 'Status changed successfully!']);
    }

    return response()->json(['success' => false, 'message' => 'Failed to update location status!']);
}

    
    
    public function location_page_view_timeline($id, Request $request){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $ho_location = $headOffice->locations()->where('id', $id)->first();
        $startDate = $request->startDate ? Carbon::parse($request->startDate)->startOfDay() : null;
        $endDate = $request->endDate ? Carbon::parse($request->endDate)->endOfDay() : null;
        
        $recordsQuery = $ho_location->location->records();
        
        if (isset($startDate)) {
            $recordsQuery->where('created_at', '>=', $startDate);
        }
        
        if (isset($endDate)) {
            $recordsQuery->where('created_at', '<=', $endDate);
        }
        
        $records = $recordsQuery
            ->orderByDesc('created_at')
            ->get()
            ->groupBy(function($record) {
                return Carbon::parse($record->created_at)->format('Y-m-d');
            });
        // Track record counts for each form name
        $forms = $records->flatMap(function ($dailyRecords) {
            return $dailyRecords->filter(function ($record) {
                return $record->form; // Ensure form relationship exists
            });
        })->groupBy(function ($record) {
            return $record->form->name; // Group by form name
        })->map(function ($records, $formName) {
            return [
                'form_name' => $formName,
                'record_count' => $records->count(), // Count the records for each form
            ];
        })->values();

        
        
        return view('head_office.company_location_view_page_timeline',compact('ho_location','records','forms'));
    }

    public function location_comment_save(Request $request){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $ho_locations = $headOffice->locations;
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
                'ho_location_id' => 'required|min:1',
                'comment' => 'nullable|string',
                'reminder_links' => 'nullable|array',
                'documents' => 'nullable|array',
                'audios' => 'nullable|array',
            ], [
                'ho_location_id.required' => 'The contact ID field is required.',
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

        $ho_location = $ho_locations->where('id', $request->ho_location_id)->first();
        if (!$ho_location) {
            abort(403, 'Data access denied');
        }
        $comment = location_comments::where('user_id', $user->id)
            ->where('id', $request->id)
            ->first();
        $editing = true;
        if (!$comment) {
            $comment = new location_comments();
            $editing = false;
        }
        $comment->ho_location_id = $request->ho_location_id;
        $comment->user_id = $user->id;
        $comment->comment = '';
        $comment->save();

        if (!$editing) {
            $comment->parent_id = $request->parent_id ? $request->parent_id : null;
        }

        $comment->comment = $request->comment ?? ' ';

        $activity_log = new ActivityLog();
        $activity_log->type = 'location comment';
        $activity_log->user_id = $user->id;
        $activity_log->head_office_id = $headOffice->id;
        $activity_log->action = 'Comment added by ' . $user->first_name . ' ' . $user->surname;
        // $activity_log->comment_id = $comment->id;
        $activity_log->save();
        $comment->save();

        if ($request->reminder_links) {
            foreach ($request->reminder_links as $link) {
                if ($link) {
                    $data = json_decode($link);
                    $link = new new_contact_links();
                    $link->title = $data->title;
                    $link->link = $data->url;
                    $link->description = $data->comment;
                    $link->user_id = Auth::guard('web')->user()->id;
                    $link->save();
                }
            }
        }

        $documents = (array) $request->documents;
        location_comment_documents::where('comment_id', $comment->id)->delete();
        foreach ($documents as $value) {
            $doc = new location_comment_documents();
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
            location_comment_documents::where('comment_id', $comment->id)->delete();
            foreach ($request->audios as $audio) {
                $doc = new location_comment_documents();
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
            ->back()
            ->with('success', 'Comment saved successfully.');
    }

    public function goto_user_profile(){
        $user = Auth::guard('web')->user();
        Auth::guard('user')->login($user);
        return redirect()->route('user.view_profile');
    }

    public function getActivityLogs(Request $request)
{
    $head_office = Auth::guard('web')->user()->selected_head_office;
    $head_office_id = $head_office->id;

    // Pagination parameters
    $start = $request->start;
    $length = $request->length;

    // Sort parameters
    $orderColumn = $request->columns[$request->order[0]['column']]['data'];
    $orderDir = $request->order[0]['dir'];

    // Search parameter
    $searchValue = $request->search['value'];

    // Query to fetch activity logs with search, sorting, and pagination
    $query = ActivityLog::where('head_office_id', $head_office_id)
                        ->when($searchValue, function($query, $searchValue) {
                            return $query->where('type', 'like', "%{$searchValue}%")
                                         ->orWhere('action', 'like', "%{$searchValue}%");
                        });

    $totalRecords = $query->count();

    $logs = $query->orderBy($orderColumn, $orderDir)
                  ->offset($start)
                  ->limit($length)
                  ->get();

    // Format data to return to DataTables
    $data = [];
    foreach ($logs as $log) {
        $data[] = [
            'created_at' => $log->created_at->format('d/m/y (D) h:i'),
            'type' => $log->type,
            'action' => $log->action
        ];
    }

    return response()->json([
        'draw' => intval($request->draw),
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $totalRecords,
        'data' => $data,
    ]);
}


public function dismissToast(){
    $headOffice = Auth::guard('web')->user()->selected_head_office;
    if(!isset($headOffice)){
        return response()->json(['status' => 'error']);
    }
    session()->forget('show_toast');
    return response()->json(['status' => 'success']);
}
}
