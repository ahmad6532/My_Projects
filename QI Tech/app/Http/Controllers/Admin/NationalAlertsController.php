<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\NationalAlertsFormRequest;
use App\Http\Controllers\Controller;
use App\Mail\Headoffice\MailAlertWhenHoldingAreaOn;
use App\Models\LocationReceivedAlert;
use App\Models\PsaAction;
use App\Models\PsaActionComment;
use App\Models\PsaActionStaff;
use Illuminate\Http\Request;
use App\Models\NationalAlert;
use App\Models\NationalAlertCountry;
use App\Models\NationalAlertDesignation;
use App\Models\NationalAlertDocument;
use App\Models\NationalAlertHeadOffice;
use App\Models\NationalAlertLocation;
use App\Models\NationalAlertOriginator;
use Illuminate\Support\Facades\Auth;
use App\Models\HeadOffice;
use App\Models\Location;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\LocationUserNotification;
use Illuminate\Support\Facades\Mail;
use Exception;

class NationalAlertsController extends Controller
{
    public $countries = [
        "England",
        "Scotland",
        "Wales",
        "Channel Islands",
        "Northern Ireland",
        "Republic of Ireland"
    ];


    /**
     * Display a listing of the national alerts.
     *
     */
    public function index(Request $request)
    {
        $query = NationalAlert::query();
        if ($request->query('search')) {
            $search = $request->query('search');
            $query->where(function($q) use ($search){
                $q->orWhere('title', 'LIKE', '%' . $search . '%');
                $q->orWhere('type', 'LIKE', '%' . $search . '%');
                $q->orWhere('custom_type', 'LIKE', '%' . $search . '%');
                $q->orWhere('custom_originator', 'LIKE', '%' . $search . '%');
                $q->orWhere('class', 'LIKE', '%' . $search . '%');
                $q->orWhere('summary', 'LIKE', '%' . $search . '%');
                $q->orWhere('suggested_actions', 'LIKE', '%' . $search . '%');
            });
        }
        $nationalAlerts = $query->where('created_by','CAS')->orderBy('created_at','desc')->orderBy('start_time','desc')->paginate(25);
        return view('admin.national_alerts.index', compact('nationalAlerts'));
    }

    /**
     * Show the form for creating a new national alert.
     *
     */
    public function create(Request $request, $id = null)
    {
        $nationalAlert = NationalAlert::find($id);
        if($nationalAlert){
                # Do not share thi outside this if condition.
                View::share('nationalAlert',$nationalAlert);
        }
        $countries = $this->countries;
        $designations = \App\Models\Position::all();
        $headOffices = HeadOffice::where('is_archived','=', 0)->where('is_suspended','=',0)->get();
        $locations = Location::where('is_active',1)->where('is_archived', 0)->where('is_suspended',0)->get();
        return view('admin.national_alerts.create',compact('countries','designations','headOffices','locations'));
    }

    /**
     * Store a new national alert in the storage.
     *
     * @param App\Http\Requests\NationalAlertsFormRequest $request
     *
     */
    public function store(NationalAlertsFormRequest $request)
    {
        try {
            $nationalAlert = NationalAlert::find($request->id);
            $message = "National Alert was successfully edited";
            $alertIsNew = false;
            if(!$nationalAlert){
                $nationalAlert = new NationalAlert();
                $message = "National Alert was successfully added.";
                $alertIsNew = true;
            }
            $nationalAlert->title = $request->title;
            $nationalAlert->type = $request->type;
            $nationalAlert->custom_type = ($request->type == 'Custom')?$request->custom_type:'';
           
            $nationalAlert->custom_originator = (in_array('Custom', $request->originator))?$request->custom_originator:'';
            $nationalAlert->class = $request->class;
            $nationalAlert->action_within = $request->action_within;
            $nationalAlert->action_within_days = ($request->action_within == 'Custom')?$request->action_within_days:'';
            $nationalAlert->summary = $request->summary;
            $nationalAlert->suggested_actions = $request->suggested_actions;
            $nationalAlert->send_to_head_offices_or_location = $request->send_to_head_offices_or_location;
            $nationalAlert->patient_level_recall = (int)$request->patient_level_recall;

            // Other hidden fields.
            $nationalAlert->status = 'active';
            $nationalAlert->created_by = 'CAS';
            $nationalAlert->admin_id = Auth::guard('admin')->user()->id;

            # Schedule Later
            if($nationalAlert->canEditScheduleDateTime()){
                $nationalAlert->schedule_later = $request->schedule_later;
            }
            
            if( $nationalAlert->canEditScheduleDateTime()&& $nationalAlert->schedule_later == 'yes'){
                $date = $request->schedule_date;
                $time = $request->schedule_time;
                $datetime = "$date $time:00";
                $nationalAlert->start_time = $datetime;
            }else{
                # if already created, store that time otherwise store current save time.
                $nationalAlert->start_time = (isset($nationalAlert->created_at))?$nationalAlert->created_at:date('Y-m-d H:i:s');
            }
            $nationalAlert->save();

            $originators = (array) $request->originator;
            NationalAlertOriginator::where('national_alert_id', $nationalAlert->id)->delete();
            foreach($originators as $value){
                $originator = new NationalAlertOriginator();
                $originator->national_alert_id = $nationalAlert->id;
                $originator->originator = $value;
                $originator->save();
            }
            $countires = (array) $request->send_to_countries;
            NationalAlertCountry::where('national_alert_id', $nationalAlert->id)->delete();
            $nationalAlert->send_to_all_countries = 0;
            $nationalAlert->save();
            foreach($countires as $value){
                if ($value == 'all') {
                    $nationalAlert->send_to_all_countries = 1;
                    $nationalAlert->save();
                   # If all is selected break; do not store other values
                    break;
                }else{
                    $country = new NationalAlertCountry();
                    $country->national_alert_id = $nationalAlert->id;
                    $country->country = $value;
                    $country->save();
                }
            }
            $designations = (array) $request->send_to_designation;
            NationalAlertDesignation::where('national_alert_id', $nationalAlert->id)->delete();
            $nationalAlert->send_to_all_designations = 0;
            $nationalAlert->save();
            foreach($designations as $value){
                if ($value == 'all') {
                    $nationalAlert->send_to_all_designations = 1;
                    $nationalAlert->save();
                    # If all is selected break; do not store other values
                    break;
                }else{
                    $designation = new NationalAlertDesignation();
                    $designation->national_alert_id = $nationalAlert->id;
                    $designation->position_id = $value;
                    $designation->save();
                }

                
            }
            NationalAlertHeadOffice::where('national_alert_id', $nationalAlert->id)->delete();
            if($nationalAlert->send_to_head_offices_or_location == 'head_offices'){
                $head_offices = (array) $request->send_to_head_offices;
                $nationalAlert->send_to_all_head_offices = 0;
                $nationalAlert->save();
                foreach($head_offices as $value){
                    if($value == 'all'){
                        $nationalAlert->send_to_all_head_offices = 1;
                        $nationalAlert->save();
                       # If all is selected break; do not store other values
                        break;
                    }else{
                        $ho = new NationalAlertHeadOffice();
                        $ho->national_alert_id = $nationalAlert->id;
                        $ho->head_office_id = $value;
                        $ho->save();
                    }
                }
            }
            NationalAlertLocation::where('national_alert_id', $nationalAlert->id)->delete();
            if ($nationalAlert->send_to_head_offices_or_location == 'locations') {
                $locations = (array) $request->send_to_locations;
                $nationalAlert->send_to_all_locations = 0;
                        $nationalAlert->save();
                foreach ($locations as $value) {
                    if($value == 'all'){
                        $nationalAlert->send_to_all_locations = 1;
                        $nationalAlert->save();
                         # If all is selected break; do not store other values
                        break;
                    } else {
                        $loc = new NationalAlertLocation();
                        $loc->national_alert_id = $nationalAlert->id;
                        $loc->location_id = $value;
                        $loc->save();
                    }
                    
                }
            }
            $documents = (array) $request->documents;
            NationalAlertDocument::where('national_alert_id', $nationalAlert->id)->delete();
            foreach($documents as $value){
                $doc = new NationalAlertDocument();
                $doc->national_alert_id = $nationalAlert->id;
                $doc->document_id = $value;
                $doc->save();
            }

            if($alertIsNew){
                $this->sendEmailToHeadOfficesWhoHasHoldingAreaOn($nationalAlert);
            }

            return redirect()->route('national_alerts.national_alert.index')
                ->with('success_message', $message);
        } catch (Exception $exception) {
            //dd($exception);
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    public function sendEmailToHeadOfficesWhoHasHoldingAreaOn(NationalAlert $alert){
        $headOffices = HeadOffice::where('holding_area_on',1)->get();
        $receiversEmails = array();
        if(count($headOffices)){
            foreach($headOffices as $ho){
                if($ho->canReceiveNationalAlert($alert)){
                    foreach($ho->users as $u){
                        $receiversEmails[$u->user->id] =$u->user;       
                    }
                    
                }
            }

        }
        # Do not send multiple emails in two headoffice of same alert.
        foreach($receiversEmails as $user){
            Mail::to($user)->queue(new MailAlertWhenHoldingAreaOn($alert));
        }
       

    }

    /**
     * Display the specified national alert.
     *
     * @param int $id
     *
     */
    public function show($id)
    {
        $nationalAlert = NationalAlert::findOrFail($id);

        return view('admin.national_alerts.show', compact('nationalAlert'));
    }

    /**
     * Show the form for editing the specified national alert.
     *
     * @param int $id
     *
     */
    public function edit($id)
    {
        $nationalAlert = NationalAlert::findOrFail($id);
        return view('admin.national_alerts.edit', compact('nationalAlert'));
    }

    /**
     * Remove the specified national alert from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $nationalAlert = NationalAlert::findOrFail($id);
            NationalAlertOriginator::where('national_alert_id', $nationalAlert->id)->delete();
            NationalAlertCountry::where('national_alert_id', $nationalAlert->id)->delete();
            NationalAlertDesignation::where('national_alert_id', $nationalAlert->id)->delete();
            NationalAlertHeadOffice::where('national_alert_id', $nationalAlert->id)->delete();
            NationalAlertLocation::where('national_alert_id', $nationalAlert->id)->delete();
            NationalAlertDocument::where('national_alert_id', $nationalAlert->id)->delete();

            # Delete All Actions associated.
            $received_alerts = LocationReceivedAlert::where('national_alert_id',$nationalAlert->id)->get();
            foreach( $received_alerts as $alert){
                PsaActionComment::where('received_alert_id',$alert->id)->delete();
                PsaActionStaff::where('received_alert_id',$alert->id)->delete();
                PsaAction::where('received_alert_id',$alert->id)->delete();
                LocationUserNotification::where('object_id', $alert->id)->where('type', LocationUserNotification::$userNoticationType)->delete();
                $alert->delete();
            }
            \App\Models\Headoffices\ReceivedNationalAlert::where('national_alert_id',$nationalAlert->id)->delete();
            $nationalAlert->delete();

            return redirect()->route('national_alerts.national_alert.index')
                ->with('success_message', 'National Alert was successfully deleted.');
        } catch (Exception $exception) {
                //dd( $exception);
            return back()->withInput()
                ->withErrors(['error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }



}
