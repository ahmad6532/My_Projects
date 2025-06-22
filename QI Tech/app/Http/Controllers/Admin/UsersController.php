<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UsersFormRequest;
use App\Models\HeadOffice;
use App\Models\HeadOfficeUser;
use App\Models\Location;
use App\Models\LocationManager;
use App\Models\LocationRegulatoryBody;
use App\Models\LocationUser;
use App\Models\Position;
use App\Models\User;
use CrestApps\CodeGenerator\Support\Str;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{

    /**
     * Display a listing of the users.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $users = User::with('position','locationregulatorybody')->get();//->paginate(25);

        return view('admin.users.index', compact('users'));
    }

    /**
     * user toggle status.
     */
    public function toggle_active($id)
    {
        $user=User::findorFail($id);
        $user->is_active=1-$user->is_active;
        $user->email_verified_at= \Carbon\Carbon::now(env('TIMEZONE'));
        $user->save();
        return redirect()->route('users.user.index')
            ->with('success_message', 'Request Updated.');
    }


    /**
     * User Re-Send Verification Email.
     */
    public function activation_email($id){

        try{

            $user=User::findorFail($id);

            $data['email']=$user->email;
            $data['email_verification_key']=Str::random(64);

            $user->email_verification_key=$data['email_verification_key'];
            $user->save();

            Mail::send('emails.emailVerification', ['type' => 1, 'token' => $data['email_verification_key']], function($message) use($data){
                $message->to($data['email']);
                $message->subject(env('APP_NAME') . ' - Verify your email');
            });

            return redirect()->route('users.user.index')
                ->with('success_message', 'Verification Email Sent.');
        }
        catch (Exception $e){

            return redirect()->route('users.user.index')
                ->withErrors(['error'=> 'Unexpected Error caused during this request']);
        }

    }


    /**
     * user toggle status.
     */
    public function toggle_archived($id)
    {
        $user=User::findorFail($id);
        $user->is_archived=1-$user->is_archived;
        $user->save();
        return redirect()->route('users.user.index')
            ->with('success_message', 'Request Updated.');
    }
    /**
     * user toggle status.
     */
    public function toggle_suspend($id)
    {
        $user=User::findorFail($id);
        $user->is_suspended=1-$user->is_suspended;
        $user->save();
        return redirect()->route('users.user.index')
            ->with('success_message', 'Request Updated.');
    }

    /**
     * Show the form for creating a new user.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $positions = Position::pluck('name','id')->all();
$locationRegulatoryBodies = LocationRegulatoryBody::pluck('name','id')->all();
        
        return view('admin.users.create', compact('positions','locationRegulatoryBodies'));
    }

    /**
     * Store a new user in the storage.
     *
     * @param App\Http\Requests\UsersFormRequest $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(UsersFormRequest $request)
    {
        $request->validate(['password' => 'required']);
        $ho=$request->has('hid') ? HeadOffice::findorFail($request->hid) : 0;

        $location=$request->has('lid') ? Location::findorFail($request->lid) : 0;

//        dd($location);

        try {

            DB::beginTransaction();
            $data = $request->getData();
            $data['password'] = Hash::make($data['password']);
            $data['email_verified_at'] = Carbon::now();
            $data['password_updated_at'] = Carbon::now();
            
            $user=User::create($data);
            if($ho){
                $hou = new HeadOfficeUser;
                $hou->head_office_id=$ho->id;
                $hou->user_id=$user->id;
                $hou->level = 1;
                $hou->position=$request->position;
                $hou->save();

                DB::commit();
                return redirect()->route('head_offices.head_office.index')
                    ->with('success_message', 'Super Admin was  created and assigned successfully');
            }

            if($location){
                $location_manager=new LocationManager;
                $location_manager->location_id=$location->id;
                $location_manager->user_id=$user->id;
                $location_manager->save();
                
                # Also add to Location Users.
                $location_user = LocationUser::where('location_id',$location->id)->where('user_id',$user->id)->first();
                if(!$location_user){
                    $location_user = new LocationUser;
                }
                $location_user->location_id = $location->id;
                $location_user->user_id = $user->id;
                $location_user->save();

                DB::commit();
                return redirect()->route('locations.location.index')
                    ->with('success_message', 'Manager was  created and assigned successfully');
            }


            DB::commit();
            return redirect()->route('users.user.index')
                ->with('success_message', 'User was successfully added.');
        } catch (Exception $exception) {

            DB::rollBack();
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Display the specified user.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::with('position','locationregulatorybody')->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $positions = Position::pluck('name','id')->all();
$locationRegulatoryBodies = LocationRegulatoryBody::pluck('name','id')->all();

        return view('admin.users.edit', compact('user','positions','locationRegulatoryBodies'));
    }

    /**
     * Update the specified user in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\UsersFormRequest $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, UsersFormRequest $request)
    {
        try {
            
            $data = $request->getData();
            if(isset($data['password']))
            {
                $data['password'] = Hash::make($data['password']);
                $data['password_updated_at'] = Carbon::now();
            }
            $user = User::findOrFail($id);
            $user->update($data);

            return redirect()->route('users.user.index')
                ->with('success_message', 'User was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.' . $exception->getMessage()]);
        }        
    }

    /**
     * Remove the specified user from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('users.user.index')
                ->with('success_message', 'User was successfully deleted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }



}
