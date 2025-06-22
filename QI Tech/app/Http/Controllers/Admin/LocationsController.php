<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationsFormRequest;
use App\Models\HeadOffice;
use App\Models\HeadOfficeLocation;
use App\Models\HeadOfficeUser;
use App\Models\Location;
use App\Models\LocationManager;
use App\Models\LocationPharmacyType;
use App\Models\LocationRegulatoryBody;
use App\Models\LocationType;
use App\Models\LocationUser;
use App\Models\Position;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use CrestApps\CodeGenerator\Support\Str;
use Illuminate\Support\Facades\Auth;

class LocationsController extends Controller
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
     * Display a listing of the locations.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $locations = Location::paginate(25);

        return view('admin.locations.index', compact('locations'));
    }

    /**
     * Location Re-Send Verification Email.
     */
    public function activation_email($id){

        try{

            $location=Location::findorFail($id);


            $data['email']=$location->email;
            $data['email_verification_key']=Str::random(64);

            $location->email_verification_key=$data['email_verification_key'];
            $location->save();

            Mail::send('emails.emailVerification', ['type' => 1, 'token' => $data['email_verification_key']], function($message) use($data){
                $message->to($data['email']);
                $message->subject(env('APP_NAME') . ' - Verify your email');
            });

            return redirect()->route('locations.location.index')
                ->with('success_message', 'Verification Email Sent.');
        }
        catch (Exception $e){

            return redirect()->route('locations.location.index')
                ->withErrors(['error'=> 'Unexpected Error caused during this request']);
        }

    }
    /**
     * Location toggle status.
     */
    public function toggle_active($id)
    {
        $location=Location::findorFail($id);
        # For now its not toggle, make toggle than deactivate or activate
        $location->is_active=1;
        $location->email_verified_at=Carbon::now(env('TIMEZONE'));
        $location->save();
        return redirect()->route('locations.location.index')
            ->with('success_message', 'Request Updated.');
    }
    /**
     * Location toggle status.
     */
    public function toggle_archived($id)
    {
        $location=Location::findorFail($id);
        $location->is_archived=1-$location->is_archived;
        $location->save();
        return redirect()->route('locations.location.index')
            ->with('success_message', 'Request Updated.');
    }
    /**
     * Location toggle status.
     */
    public function toggle_suspend($id)
    {
        $location=Location::findorFail($id);
        $location->is_suspended=1-$location->is_suspended;
        $location->save();
        return redirect()->route('locations.location.index')
            ->with('success_message', 'Request Updated.');
    }


    /**
     * Assign Super admin to head office.
     *
     * 
     */
    public function assign_manager_view($id){
        $location = Location::findOrFail($id);
        $positions = Position::pluck('name','id')->all();
        $locationRegulatoryBodies = LocationRegulatoryBody::pluck('name','id')->all();

        $users = User::all();

        return view('admin.locations.assign_manager',
            compact('location','positions','locationRegulatoryBodies','users'));


    }
    /**
     * Remove Super admin from head office.
     *
     * 
     */
    public function assign_manager_remove($manager_id){

        $manager = LocationManager::findOrFail($manager_id);
        if(!$manager){
            return redirect()->route('locations.location.index')->withErrors(['error'=>'Manager is not found.']);
        }
        $manager = $manager->delete();
        return redirect()->route('locations.location.index') ->with('success_message', 'Manager was removed successfully');
        

    }
    /**
     * Store Super admin of head office to database.
     */

    public function assign_manager($id, Request $request )
    {
        $location = Location::find($id);
        if(!$location){
            return redirect()->route('locations.location.index')
            ->with('error_message', 'Invalid Data Submitted.');
        }

        $location_manager = LocationManager::where('location_id',$id)->where('user_id',$request->user_id)->first();
        if(!$location_manager){
            $location_manager = new LocationManager;
        }
        $location_manager->location_id = $id;
        $location_manager->user_id = $request->user_id;
        $location_manager->save();

        # Also add to Location Users.
        $location_user = LocationUser::where('location_id',$id)->where('user_id',$request->user_id)->first();
        if(!$location_user){
            $location_user = new LocationUser;
        }
        $location_user->location_id = $id;
        $location_user->user_id = $request->user_id;
        $location_user->save();

        return redirect()->route('locations.location.index')
            ->with('success_message', 'Manager was assigned successfully');
    }





    /**
     * Show the form for creating a new location.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $locationTypes = LocationType::pluck('name','id')->all();
        $locationPharmacyTypes = LocationPharmacyType::pluck('name','id')->all();
        $locationRegulatoryBodies = LocationRegulatoryBody::pluck('name','id')->all();
        $countries = $this->countries;
        
        return view('admin.locations.create', compact('locationTypes','locationPharmacyTypes','locationRegulatoryBodies', 'countries'));
    }

    /**
     * Store a new location in the storage.
     *
     * @param App\Http\Requests\LocationsFormRequest $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(LocationsFormRequest $request)
    {
        $request->validate(['password' => 'required']);
        try {
            
            $data = $request->getData();

            $data['password'] = Hash::make($data['password']);
            
            Location::create($data);

            return redirect()->route('locations.location.index')
                ->with('success_message', 'Location was successfully added.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Display the specified location.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $location = Location::with('location_type','pharmacy_type','regulatory_body')->findOrFail($id);

        return view('admin.locations.show', compact('location'));
    }

    /**
     * Show the form for editing the specified location.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $location = Location::findOrFail($id);
        $locationTypes = LocationType::pluck('name','id')->all();
        $locationPharmacyTypes = LocationPharmacyType::pluck('name','id')->all();
        $locationRegulatoryBodies = LocationRegulatoryBody::pluck('name','id')->all();
        $countries = $this->countries;

        return view('admin.locations.edit', compact('location','locationTypes','locationPharmacyTypes','locationRegulatoryBodies', 'countries'));
    }

    /**
     * Update the specified location in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\LocationsFormRequest $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, LocationsFormRequest $request)
    {
        try {
            
            $data = $request->getData();

            if($data['password'])
            {
                $data['password'] = Hash::make($data['password']);
            }
            else
            {
                unset($data['password']);
            }
            
            $location = Location::findOrFail($id);
            $location->update($data);

            return redirect()->route('locations.location.index')
                ->with('success_message', 'Location was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }        
    }

    /**
     * Remove the specified location from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $location = Location::findOrFail($id);
            $location->delete();

            return redirect()->route('locations.location.index')
                ->with('success_message', 'Location was successfully deleted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    public function view_assign_head_office(Request $request,$location_id){
        $location = Location::findOrFail($location_id);
        $headoffices = HeadOffice::all();
        return view('admin.locations.view_assign_head_office',compact('location','headoffices'));
    }
    public function save_assign_head_office(Request $request,$location_id){
        $location = Location::findOrFail($location_id);
        $head_office_id = (int)$request->head_office_id;
        if(empty($head_office_id)){
            return back()->withInput()->withErrors(['unexpected_error' => 'Please select a head office to continue.']);
        }
        $location_head_office = HeadOfficeLocation::where('location_id', $location->id)->first();
        if(!$location_head_office){
            $location_head_office = new HeadOfficeLocation();
        }
        $location_head_office->location_id = $location->id;
        $location_head_office->head_office_id = $head_office_id;
        $location_head_office->save();

        return redirect()->route('locations.location.index')->with('success_message', 'Location is successfully assigned to head office.');
        
    }

    public function remove_head_office(Request $request, $location_id)
    {
        $location = Location::findOrFail($location_id);
        HeadOfficeLocation::where('location_id', $location->id)->delete();
        return redirect()->route('locations.location.index')->with('success_message', 'Location is successfully de-assigned from head office.');
    }

    public function location_login(Request $request,$id)
    {
        $location  = Location::findOrFail($id);
        $user = Auth::guard('location')->loginUsingId($location->id);
        if($user)
        {
            $user = Auth::guard('web')->loginUsingId(1000);
            if($user)
                return redirect()->route('location.dashboard');
        }
        return back()->with('error','Location not found');
    }

}
