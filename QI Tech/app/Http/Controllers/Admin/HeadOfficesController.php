<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HeadOfficesFormRequest;
use App\Mail\Admin\HeadOfficeUserAssigned;
use App\Models\HeadOfficeUser;
use App\Models\LocationRegulatoryBody;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\HeadOffice;
use App\Models\Position;
use App\Models\User;
use App\Models\UserLoginSession;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Jenssegers\Agent\Facades\Agent;
class HeadOfficesController extends Controller
{

    private $countries = ['0' => 'England',
        '1' => 'Scotland',
        '2' => 'Wales',
        '3' => 'Channel Islands',
        '4' => 'Northern Ireland',
        '5' => 'Republic of Ireland'];


    /**
     * Display a listing of the head offices.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $headOffices = HeadOffice::with('lastloginuser')->paginate(25);

        return view('admin.head_offices.index', compact('headOffices'));
    }


    /**
     * head_office toggle status.
     */
    public function toggle_archived($id)
    {
        $head_office=HeadOffice::findorFail($id);
        $head_office->is_archived=1-$head_office->is_archived;
        $head_office->save();
        return redirect()->route('head_offices.head_office.index')
            ->with('success_message', 'Request Updated.');
    }
    /**
     * head_office toggle status.
     */
    public function toggle_suspend($id)
    {
        $head_office=HeadOffice::findorFail($id);
        $head_office->is_suspended=1-$head_office->is_suspended;
        $head_office->save();
        return redirect()->route('head_offices.head_office.index')
            ->with('success_message', 'Request Updated.');
    }
    
    public function head_office_login(Request $request,$id)
    { 
        
        $head_office = HeadOffice::findOrFail($id);
        $users = $head_office->users;
        if(count($users) >= 1)
        {
            
            if(Auth::guard('web')->loginUsingId($users->first()->user->id))
            {
                
                $user = $users->first()->user;
                $user->selected_head_office_id = $id;
                $user->save();
                try {
                    $ip = $request->ip();
                    $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$ip"));
                    $browser = Agent::browser();
                    $version = Agent::version($browser);

                    //ya line check kro ya chaye hamesha user ka data ka get krny k liya
                    //Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password]);
                    
                    
                    $checking = new UserLoginSession();
                    $checking->user_id = $user->id;
                    $checking->ip = $ip;
                    $checking->browser = $browser . $version;
                    $checking->country = $geo['geoplugin_countryName'];
                    $checking->city = $geo['geoplugin_city'];
                    $checking->lat = $geo['geoplugin_latitude'];
                    $checking->long = $geo['geoplugin_longitude'];
                    
                    
                    $checking->is_head_office = 1;
                    $checking->head_office_id = $id;
                    $checking->user_session = session('user_session');
                    $checking->is_active = 1;
                    $checking->save();
                    

                } catch (Exception $e) {
                    dd($e);
                }
                return redirect()->route('head_office.dashboard');
            }
        }
        
        return back()->with('error','Atleast one head office super admin is needed in order to login to head office.');
        
    }
    
    

    /**
     * Assign Super admin to head office.
     *
     */
    public function assign_super_admin_view($id){
        $headOffice = HeadOffice::with('lastloginuser')->findOrFail($id);

        $positions = Position::pluck('name','id')->all();
        $locationRegulatoryBodies = LocationRegulatoryBody::pluck('name','id')->all();

        $head_office_users = HeadOfficeUser::where('head_office_id',$headOffice->id)->get();
        $ids = array();
        foreach($head_office_users as $u){
            $ids[] = $u->user_id;
        }
        $users  = User::whereNotIn('id',$ids)->get();

        return view('admin.head_offices.assign_super_admin',
            compact('headOffice','positions','locationRegulatoryBodies','users'));


    }
    /**
     * Remove Super admin from head office.
     *
     */
    public function assign_super_admin_remove($id){

        $headOfficeUser = HeadOfficeUser::findOrFail($id);
        $headOffice=$headOfficeUser->head_office;
        if($headOffice->has_multiple_super_admins)
        {
        $headOfficeUser =$headOfficeUser ->delete();
            return redirect()->route('head_offices.head_office.index')
                ->with('success_message', 'Super Admin was removed successfully');
        }
            return redirect()->route('head_offices.head_office.index')
                ->withErrors(['error'=>'Last Super Admin can not be removed']);

    }
    /**
     * Store Super admin of head office to database.
     */

    public function assign_super_admin($id, Request $request )
    {
                $ho = HeadOffice::findorFail($id);
                $hou =HeadOfficeUser::where('head_office_id', $id)->where('user_id',$request->user_id)->first();
                if(!$hou){
                    $hou = new HeadOfficeUser;
                    $hou->level = 1;
                    $hou->head_office_id = $id;
                    $hou->user_id = $request->user_id;
                }
                $hou->position=$request->position;
                $hou->save();
                $user = $hou->user;
                Mail::to($user)->queue(new HeadOfficeUserAssigned($ho));
                $ho->defaultUserAccessProfiles();
                $ho->defaultUserAccessProfilesNew($hou);
                // $ho->makeUserSuperUser($hou);
                $ho->makeUserSuperUserNew($hou);
                return redirect()->route('head_offices.head_office.index')
                    ->with('success_message', 'Super Admin was assigned successfully');


}

    /**
     * Show the form for creating a new head office.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $positions = Position::pluck('name','id')->all();
        $locationRegulatoryBodies = LocationRegulatoryBody::pluck('name','id')->all();

        $users=User::all();
        return view('admin.head_offices.create', compact('positions', 'locationRegulatoryBodies', 'users'));
    }

    /**
     * Store a new head office in the storage.
     *
     * @param App\Http\Requests\HeadOfficesFormRequest $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(HeadOfficesFormRequest $request)
    {
//        dd($request);
        $request->validate(['user_id'=>'required|numeric|min:-1']);

        $user=false;
        if($request->user_id==-1) {
            $request->validate([
                'position_id' => 'required|numeric|min:0|max:10',
                'is_registered' => 'boolean|nullable',
                'registration_no' => 'string|min:2|max:50|nullable',
                'location_regulatory_body_id' => 'nullable',
                'country_of_practice' => 'string|nullable|max:80',
                'first_name' => 'required|string|min:1|max:50',
                'surname' => 'required|string|min:1|max:50',
                'mobile_no' => 'required|string|min:1|max:20',
                'email' => 'required|max:150|email|unique:users,email',
                'password' => 'required|min:8|max:30',
            ]);
            $user=new User;
            $user->position_id=$request->position_id;
            $user->is_registered=$request->is_registered;
            $user->registration_no=$request->registration_no;
            $user->location_regulatory_body_id=$request->location_regulatory_body_id;
            $user->first_name=$request->first_name;
            $user->surname=$request->surname;
            $user->mobile_no=$request->mobile_no;
            $user->email=$request->email;
            $user->password=Hash::make($request->password);
            $user->email_verified_at=Carbon::now();
            $user->password_updated_at=Carbon::now();

            if($request->has('country_of_practice'))
                $user->country_of_practice = $this->countries[$request->country_of_practice];

        }
        else{
            $user=User::findorFail($request->user_id);
        }


        try {

            DB::beginTransaction();

            $user->save();

            $data = $request->getData();
            $ho = HeadOffice::create($data);
            $ho->defaultUserAccessProfiles();
            // Creating or Assigning a new Head Office Admin ///

            $hou=new HeadOfficeUser;
            $hou->head_office_id=$ho->id;
            $hou->user_id=$user->id;
            $hou->level = 1;
            $hou->position=$request->position;
            $hou->save();
            
            $ho->makeUserSuperUserNew($hou);
            DB::commit();
            

            return redirect()->route('head_offices.head_office.index')
                ->with('success_message', 'Head Office was successfully added.');
        } catch (Exception $exception) {

            dd($exception->getMessage());
            DB::rollBack();
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Display the specified head office.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $headOffice = HeadOffice::with('lastloginuser')->findOrFail($id);

        return view('admin.head_offices.show', compact('headOffice'));
    }

    /**
     * Show the form for editing the specified head office.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $headOffice = HeadOffice::findOrFail($id);
        

        return view('admin.head_offices.edit', compact('headOffice'));
    }

    /**
     * Update the specified head office in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\HeadOfficesFormRequest $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, HeadOfficesFormRequest $request)
    {
        try {
            
            $data = $request->getData();
            
            $headOffice = HeadOffice::findOrFail($id);
            $headOffice->update($data);

            return redirect()->route('head_offices.head_office.index')
                ->with('success_message', 'Head Office was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }        
    }

    /**
     * Remove the specified head office from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {

            $headOffice = HeadOffice::findOrFail($id);
            $headOffice->delete();

            return redirect()->route('head_offices.head_office.index')
                ->with('success_message', 'Head Office was successfully deleted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }



}
