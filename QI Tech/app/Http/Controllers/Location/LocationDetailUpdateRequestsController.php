<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationDetailUpdateRequestsFormRequest;
use App\Models\LocationDetailUpdateRequest;
use App\Models\LocationOpeningHours;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Exception;

class LocationDetailUpdateRequestsController extends Controller
{

    /**
     * Display a listing of the location detail update requests.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $locationDetailUpdateRequests = LocationDetailUpdateRequest::with('user')->paginate(25);

        return view('location_detail_update_requests.index', compact('locationDetailUpdateRequests'));
    }

    /**
     * Show the form for creating a new location detail update request.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $users = User::pluck('is_registered','id')->all();
        
        return view('location_detail_update_requests.create', compact('users'));
    }

    public function update_opening_hours(Request $request){
        
        if($request->update_opening_hours){
            $location = Auth::guard('location')->user();
            $hours = $location->opening_hours;
            if(!$hours){
                $hours = new LocationOpeningHours();
                $hours->location_id = $location->id; 
            }
            $hours->open_monday = (int)$request->open_monday;
            $hours->monday_start_time = $request->monday_start_time;
            $hours->monday_end_time = $request->monday_end_time;
            if($this->timeIsGreater($hours->monday_start_time,$hours->monday_end_time) == false){
                return redirect()->route('location.settings.nearmisses')->with('error','Monday start time is greater than end time.');
            }

            $hours->open_tuesday = (int)$request->open_tuesday;
            $hours->tuesday_start_time = $request->tuesday_start_time;
            $hours->tuesday_end_time = $request->tuesday_end_time;

            if($this->timeIsGreater($hours->tuesday_start_time,$hours->tuesday_end_time) == false){
                return redirect()->route('location.settings.nearmisses')->with('error','Tuesday start time is greater than end time.');
            }

            $hours->open_wednesday = (int)$request->open_wednesday;
            $hours->wednesday_start_time = $request->wednesday_start_time;
            $hours->wednesday_end_time = $request->wednesday_end_time;
            if($this->timeIsGreater($hours->wednesday_start_time,$hours->wednesday_end_time) == false){
                return redirect()->route('location.settings.nearmisses')->with('error','Wednesday start time is greater than end time.');
            }

            $hours->open_thursday = (int)$request->open_thursday;
            $hours->thursday_start_time = $request->thursday_start_time;
            $hours->thursday_end_time = $request->thursday_end_time;
            if($this->timeIsGreater($hours->thursday_start_time,$hours->thursday_end_time) == false){
                return redirect()->route('location.settings.nearmisses')->with('error','Thursday start time is greater than end time.');
            }

            $hours->open_friday = (int)$request->open_friday;
            $hours->friday_start_time = $request->friday_start_time;
            $hours->friday_end_time = $request->friday_end_time;
            if($this->timeIsGreater($hours->friday_start_time,$hours->friday_end_time) == false){
                return redirect()->route('location.settings.nearmisses')->with('error','Friday start time is greater than end time.');
            }
            
            $hours->open_saturday = (int)$request->open_saturday;
            $hours->saturday_start_time = $request->saturday_start_time;
            $hours->saturday_end_time = $request->saturday_end_time;
            if($this->timeIsGreater($hours->saturday_start_time,$hours->saturday_end_time) == false){
                return redirect()->route('location.settings.nearmisses')->with('error','Saturday start time is greater than end time.');
            }

            $hours->open_sunday = (int)$request->open_sunday;
            $hours->sunday_start_time = $request->sunday_start_time;
            $hours->sunday_end_time = $request->sunday_end_time;
            if($this->timeIsGreater($hours->sunday_start_time,$hours->sunday_end_time) == false){
                return redirect()->route('location.settings.nearmisses')->with('error','Sunday start time is greater than end time.');
            }
            $hours->save();
            return redirect()->route('location.settings.nearmisses')->with('success_message','Opening hours saved successfully.');
        }
    }
    public function timeIsGreater($starttime, $endtime){
        if(empty($starttime) || empty($endtime)){
            return true;
        }
        if(strtotime($starttime) >= strtotime($endtime)){
            return false;
        }
        return true;
    }
    /**
     * Store a new location detail update request in the storage.
     *
     * @param App\Http\Requests\LocationDetailUpdateRequestsFormRequest $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update_location_details(LocationDetailUpdateRequestsFormRequest $request)
    {
        
        try {
            
            $location = Auth::guard('location')->user();
            $user = Auth::guard('user')->user();

            $data = $request->getData();
            $vals = [];
            foreach($data as $dk => $dv)
            {
                $vals[$dk] = $dv;
            }
            $data['location_id'] = $location->id;
            $data['user_id'] = $user->id;

            $data['token'] = Str::random(64);
            
            $lup = LocationDetailUpdateRequest::create($data);
            $link = route('location.confirm_location_details', $data['token']);

            // Time to send email to registered account email //
            Mail::send('emails.location_update', ['user' => $user, 
            'location' =>  $location,
            'vals' => $vals,
            'link' => $link,
            ]
            , function($message) use($location){
                $message->to($location->email);
                $message->subject(env('APP_NAME') . ' - Update Requested');
            });
            

            return redirect()->route('location.edit_location_details')
                ->with('success_message', 'Details Update Request successfully submitted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request. ' . $exception->getMessage()]);
        }
    }

    public function manager_update_location_details(Request $request){
        $location = Auth::guard('location')->user();
        $user = Auth::guard('user')->user();
        if($location->head_office()){
            # For now do not update
            return redirect()->route('location.edit_location_details')->withInput()->withErrors(['Access is Denied.']);
        }
        if($location->hasManagers() && !$location->userIsManager($user->id)){
            # For now do not update
            return redirect()->route('location.edit_location_details')->withInput()->withErrors(['Access is Denied.']);
        }
        if(empty($request->trading_name) || empty($request->address_line1) || empty($request->registration_no)
        || empty($request->telephone_no)){
            return redirect()->route('location.edit_location_details')->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
        
        $location->trading_name = $request->trading_name;
        $location->address_line1 = $request->address_line1;
        $location->address_line2 = $request->address_line2;
        $location->address_line3 = $request->address_line3;
        $location->registration_no = $request->registration_no;
        $location->telephone_no = $request->telephone_no;
        $location->save();

        return redirect()->route('location.edit_location_details')->with('success_message', 'Details Updated Successfully.');

    }
    public function confirm_location_details($token)
    {
        $update_request = LocationDetailUpdateRequest::where('token', $token)->first();
        if(!$update_request)
            return abort(403); // Todo:further throtle options
        if($update_request->status == 1)
            return "Request was already approved";
        
        $location = Auth::guard('location')->user();
        $location->trading_name = $update_request->trading_name;
        $location->address_line1 = $update_request->address_line1;
        $location->address_line2 = $update_request->address_line2;
        $location->address_line3 = $update_request->address_line3;
        $location->registration_no = $update_request->registration_no;
        $location->telephone_no = $update_request->telephone_no;

        $location->save();

        $update_request->status = 1;
        $update_request->save();
        
        return "Request approved";
    }

    /**
     * Display the specified location detail update request.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $locationDetailUpdateRequest = LocationDetailUpdateRequest::with('user')->findOrFail($id);

        return view('location_detail_update_requests.show', compact('locationDetailUpdateRequest'));
    }

    /**
     * Show the form for editing the specified location detail update request.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $locationDetailUpdateRequest = LocationDetailUpdateRequest::findOrFail($id);
        $users = User::pluck('is_registered','id')->all();

        return view('location_detail_update_requests.edit', compact('locationDetailUpdateRequest','users'));
    }

    /**
     * Update the specified location detail update request in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\LocationDetailUpdateRequestsFormRequest $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, LocationDetailUpdateRequestsFormRequest $request)
    {
        try {
            
            $data = $request->getData();
            
            $locationDetailUpdateRequest = LocationDetailUpdateRequest::findOrFail($id);
            $locationDetailUpdateRequest->update($data);

            return redirect()->route('location_detail_update_requests.location_detail_update_request.index')
                ->with('success_message', 'Location Detail Update Request was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }        
    }

    /**
     * Remove the specified location detail update request from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $locationDetailUpdateRequest = LocationDetailUpdateRequest::findOrFail($id);
            $locationDetailUpdateRequest->delete();

            return redirect()->route('location_detail_update_requests.location_detail_update_request.index')
                ->with('success_message', 'Location Detail Update Request was successfully deleted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }



}
