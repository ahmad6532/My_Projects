<?php

namespace App\Http\Controllers\HeadOffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\HeadOfficeUserInviteFormRequest;
use App\Http\Requests\HeadOfficeUserUpdateFormRequest;
use App\Models\AssignedBespokeForm;
use App\Models\CaseHandlerUser;
use App\Models\CaseStageTaskAssign;
use App\Models\head_office_access_rights;
use App\Models\HeadOffice;
use App\Models\Headoffices\Users\AccessRight;
use App\Models\Headoffices\Users\AccessRightPermission;
use App\Models\Headoffices\Users\HeadOfficeUserInvite;
use App\Models\Headoffices\Users\Permission;
use App\Models\Headoffices\Users\ProfilePermission;
use App\Models\Headoffices\Users\UserProfile;
use App\Models\Headoffices\Users\UserProfileAssign;
use App\Models\HeadOfficeUser;
use App\Models\Position;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Validator;

class HeadOfficeUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function head_office_users()
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $logo = $headOffice->logo;
        $logged_ho_user = $user->getHeadOfficeUser();
        $headOffice->defaultUserAccessProfilesNew();
        $user_profiles  = head_office_access_rights::where('head_office_id',$headOffice->id)->get();
        $permission = Permission::class;
        $profiles = head_office_access_rights::where('head_office_id', $headOffice->id)->get();
        $head_office_invites = HeadOfficeUserInvite::where('head_office_id',$headOffice->id)->orderBy('created_at', 'desc')->get();
       return view('head_office.users.head_office_users',compact('headOffice','user_profiles','permission','profiles','head_office_invites','user','logged_ho_user'));

    }

    public function block_users($id)
{
    // Validate the $id
    $validator = Validator::make(['id' => $id], [
        'id' => 'required|numeric|exists:head_office_users,id',
    ]);

    if ($validator->fails()) {
        $firstError = $validator->errors()->first();
        return redirect()->back()->with('error', $firstError);
    }

    $user = Auth::guard('web')->user();
    $headOffice = $user->selected_head_office;
    $logged_profile = $user->getHeadOfficeUser()->get_permissions();
    
    if (!$headOffice) {
        return redirect()->back()->with('error', 'Head office not found!');
    }

    $headOfficeUser = $headOffice->users->where('id', $id)->first();

    if (!$headOfficeUser) {
        return redirect()->back()->with('error', 'User not found in this head office!');
    }

    $profile = $headOfficeUser->get_permissions();

    if ($profile->super_access == false) {
        // Toggle the is_blocked status
        $headOfficeUser->is_blocked = !$headOfficeUser->is_blocked;
        $headOfficeUser->save();

        $message = $headOfficeUser->is_blocked ? 'User blocked successfully!' : 'User unblocked successfully!';
        return redirect()->back()->with('success', $message);
    }elseif( $profile->super_access && $logged_profile->super_access==true){
        $headOfficeUser->is_blocked = !$headOfficeUser->is_blocked;
        $headOfficeUser->save();

        $message = $headOfficeUser->is_blocked ? 'User blocked successfully!' : 'User unblocked successfully!';
        return redirect()->back()->with('success', $message);
    }

    return redirect()->back()->with('error', 'Cannot block a user with super access!');
}

    public function block_users_comment_update(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:head_office_users,id',
            'value' => 'nullable|string|max:255'
        ]);
    
        if ($validator->fails()) {
            $firstError = $validator->errors()->first();
            return response()->json(['error'=>$firstError],400);
        }
        
        $user = Auth::guard('web')->user();
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $ho_users = $headOffice->users;
        $logged_profile = $user->getHeadOfficeUser()->get_permissions();
        
        if(isset($request->id)){
            $user = $ho_users->where('id',$request->id)->first();
            if(!$user) return;
            $user_profile = $user->get_permissions();
            if($user_profile->super_access == true && $logged_profile->super_access == true){
                $user->block_comment = $request->value;
            }elseif($user_profile->super_access == false){
                $user->block_comment = $request->value;
            }
            $user->save();
            return response()->json(['success'=>'comment updated!'],200);
        }
    }


    public function block_users_save(Request $request){
        $validator = Validator::make($request->all(), [
            'users' => 'required|array',
            'users.*' => 'numeric|exists:head_office_users,id',
            'comment' => 'nullable|string|max:255'
        ]);
    
        if ($validator->fails()) {
            $firstError = $validator->errors()->first();
            return redirect()->back()
                             ->with('error', $firstError);
        }
        $user = Auth::guard('web')->user();
        $ho_u = $user->getHeadOfficeUser();
        $headOffice = $user->selected_head_office;
        $headOfficeUsers = $headOffice->users;
        $profile = $ho_u->get_permissions();
        
        if($request->has('users')){
            if(in_array($user->id ,$request->users)){
                return back()->with(['error'=>'Can not block this user!']);
            }
            foreach($request->users as $user_id){
                $ho_user = $headOfficeUsers->where('id',$user_id)->first();
                if(isset($ho_user)){
                    $ho_user_profile = $ho_user->get_permissions();

                    if($ho_user_profile->super_access == false ){
                        $ho_user->is_blocked = true;
                        $ho_user->block_comment = isset($request->comment) ? $request->comment : null;
                        $ho_user->save();
                    }elseif( $ho_user_profile->super_access == true && $profile->super_access == true){
                        $ho_user->is_blocked = true;
                        $ho_user->block_comment = isset($request->comment) ? $request->comment : null;
                        $ho_user->save();
                    }
                }
            }
            return redirect()->back()->with('success','User blocked!');
        }
        return redirect()->back()->with('error','invalid request!');
    }
    /**
     * update head office user details
     */
    public function head_office_user_update($id,HeadOfficeUserUpdateFormRequest $request)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $logged_ho_u = Auth::guard('web')->user()->getHeadOfficeUser();
        $data=$request->getData();
        $hou=HeadOfficeUser::findorFail($id);
        if(!$logged_ho_u->user_profile_assign->profile->super_access && $hou->id != $logged_ho_u->id){
            return redirect()->back()->with('error','Access denied!');
        }
        $hou->position=$data['position'];
        $hou->about_me = $request->about_me;
        $hou->save();
        if($request->assigned_profile == 'Select a Profile'){
            return redirect()->back()->with('success_message', "Please select a profile to continue.");
        }
        $profile = head_office_access_rights::where('head_office_id',$headOffice->id)->where('id',$request->assigned_profile)->first();
        if(!$profile){
            abort(403,'Invalid data submitted.');
        }
        $hasSuperAccess = $headOffice->users()
        ->whereHas('user_profile_assign.profile', function ($query) {
            $query->where('super_access', true);
        })
        ->exists();
        $assignedProfile = UserProfileAssign::where('head_office_user_id',$hou->id)->first();
        if($logged_ho_u->user_profile_assign->profile->super_access == true ){

            DB::beginTransaction();
            if(!$assignedProfile){
                $assignedProfile = new UserProfileAssign();
                $assignedProfile->head_office_user_id = $hou->id;
            }
            $assignedProfile->user_profile_id = $profile->id;
            $assignedProfile->save();
            $hasSuperAccess = $headOffice->users()
            ->whereHas('user_profile_assign.profile', function ($query) {
                $query->where('super_access', true);
            })
            ->exists();
            if($hasSuperAccess){
                DB::commit();
            }else{
                DB::rollback();
                return redirect()->back()->with('error', 'At least one super user is required.');
            }
        }
        // if( $assignedProfile && $assignedProfile->profile->super_access){
        //     # Profile has super access.
        //     return redirect()->back()->with('success_message', "Unable to change profile of super user.");
        // }
        

        return redirect()->back()->with('success_message', 'User Updated successfully');

    }

    
    public function bulk_profile_assign(Request $request)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $logged_ho_u = Auth::guard('web')->user()->getHeadOfficeUser();
        $headOfficeUserIds = $headOffice->users->pluck('id')->toArray();
        
        if($logged_ho_u->user_profile_assign->profile->super_access == false ){
            return back()->with('error','Access denied!');
        }
        $selectedUsers = explode(',', $request->input('selected_users_main'));
    
        $request->merge(['selected_users_main' => $selectedUsers]);
    
        // Validate the request
        $request->validate([
            'selected_users_main' => [
                'required',
                'array',
                function ($attribute, $value, $fail) use ($headOfficeUserIds) {
                    foreach ($value as $userId) {
                        if (!in_array($userId, $headOfficeUserIds)) {
                        return back()->with('error', 'Unknown User!');
                        }
                    }
                },
            ],
            'selected_users_main.*' => 'required|integer',
        ]);

        $profile = head_office_access_rights::where('head_office_id',$headOffice->id)->where('id',$request->assigned_profile)->first();
        if(!$profile){
            abort(403,'Invalid data submitted.');
        }
        foreach ($selectedUsers as $ho_u){
            $hou = $headOffice->users->where('id', $ho_u)->first();
            $permission = $hou->get_permissions();
            $assignedProfile = UserProfileAssign::where('head_office_user_id',$hou->id)->first();
            DB::beginTransaction();
        if(isset($hou->access_right)){
                $hou->access_right->delete();
            }
            if(isset($permission) && $permission->profile_name == 'custom'){                
                $permission->delete();
            }
            if(!$assignedProfile){
                $assignedProfile = new UserProfileAssign();
                $assignedProfile->head_office_user_id = $hou->id;
            }
            $assignedProfile->user_profile_id = $profile->id;
            $assignedProfile->save();
        }
        $hasSuperAccess = $headOffice->users()
            ->whereHas('user_profile_assign.profile', function ($query) {
                $query->where('super_access', true);
            })
            ->exists();
            if($hasSuperAccess){
                DB::commit();
            }else{
                DB::rollback();
                return redirect()->back()->with('error', 'At least one super user is required.');
            }
        return redirect()->back()->with('success','Profile assigned successfully.');
    }

    public function bulk_profile_unassign(Request $request)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $logged_ho_u = Auth::guard('web')->user()->getHeadOfficeUser();
        $headOfficeUserIds = $headOffice->users->pluck('id')->toArray();
        
        if($logged_ho_u->user_profile_assign->profile->super_access == false ){
            return back()->with('error','Access denied!');
        }
        $selectedUsers = explode(',', $request->input('selected_users_main'));
    
        $request->merge(['selected_users_main' => $selectedUsers]);
    
        // Validate the request
        $request->validate([
            'selected_users_main' => [
                'required',
                'array',
                function ($attribute, $value, $fail) use ($headOfficeUserIds) {
                    foreach ($value as $userId) {
                        if (!in_array($userId, $headOfficeUserIds)) {
                        return back()->with('error', 'Unknown User!');
                        }
                    }
                },
            ],
            'selected_users_main.*' => 'required|integer',
        ]);

        foreach ($selectedUsers as $ho_u){
            $hou = $headOffice->users->where('id', $ho_u)->first();
            $permission = $hou->get_permissions();
            $assignedProfile = UserProfileAssign::where('head_office_user_id',$hou->id)->first();
            DB::beginTransaction();
        if(isset($hou->access_right)){
                $hou->access_right->delete();
            }
            if(isset($permission) && $permission->profile_name == 'custom'){                
                $permission->delete();
            }
            if($assignedProfile){
                $assignedProfile->delete();
            }
        }
        $hasSuperAccess = $headOffice->users()
            ->whereHas('user_profile_assign.profile', function ($query) {
                $query->where('super_access', true);
            })
            ->exists();
            if($hasSuperAccess){
                DB::commit();
            }else{
                DB::rollback();
                return redirect()->back()->with('error', 'At least one super user is required.');
            }
        return redirect()->back()->with('success','Profile assigned successfully.');
    }
    


    
    public function head_office_user_delete(Request $request, $id)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $hou = $headOffice->users->find($id);
        if (!$hou) {
            return redirect()->back()->with('error', 'User not found!');
        }
    
        // Get specific users from the request
        if($request->delete_action == '1'){
            $allUsers = $request->specific_users;
        }else{
            $headOfficeUserIds = $headOffice->head_office_user_profiles
            ->flatMap(function ($profile) {
                return $profile->user_profile_assign; // Collect all user_profile_assign records
            })
            ->pluck('head_office_user_id') // Extract head_office_user_id from each user_profile_assign
            ->unique() // Optional: Remove duplicates
            ->values() // Reindex the array
            ->toArray();
            $allUsers = $headOfficeUserIds;
        }
    
        DB::beginTransaction();
    
        // Delete UserProfileAssign related to the head office user
    
        // Get open case handlers for the head office user
        $caseHandlers = $hou->head_office_user_cases->filter(fn($case) => $case->case->status == 'open');
    
        // Delete existing case handlers
        $caseHandlers->each->delete();
    
        // Shuffle users for random assignment
        $randomizedUsers = collect($allUsers)->shuffle();
    
        // Assign new case handlers to users
        if($randomizedUsers->isNotEmpty()){
            foreach ($caseHandlers as $case) {
                $assignedUserId = $randomizedUsers->random(); // Pick a random user
                $user = HeadOfficeUser::where('id', $assignedUserId)
                    ->where('head_office_id', $headOffice->id)
                    ->first();
                
                $existingHandler = $case->case->case_handlers()->withTrashed()->where('head_office_user_id', $user->id)->first();
    
                if (!$existingHandler) {
                    $case->case->case_handlers()->create([
                        'head_office_user_id' => $user->id,
                        'case_id' => $case->case->id
                    ]);
                } else {
                    if ($existingHandler->trashed()) {
                        $existingHandler->restore();
                    }
                }
    
            }
        }
    
        // Handle task assignment
        if($randomizedUsers->isNotEmpty()){
            $hou->stage_task_assigns()->each(function ($assign_task) use ($randomizedUsers, $headOffice) {
                $assignedUserId = $randomizedUsers->random(); // Pick a random user
                $user = HeadOfficeUser::where('id', $assignedUserId)
                    ->where('head_office_id', $headOffice->id)
                    ->first();
        
                $task = $assign_task->task;
        
                // Delete the existing task assignment
                $assign_task->delete();
        
                // Assign the task to a new user
                $existingTaskAssign = CaseStageTaskAssign::where('task_id', $task->id)->where('head_office_user_id', $user->id)->first();
    
                if (!$existingTaskAssign) {
                    $new_task_assign = new CaseStageTaskAssign();
                    $new_task_assign->task_id = $task->id;
                    $new_task_assign->head_office_user_id = $user->id;
                    $new_task_assign->save();
                }
                
            });
        }
    
        // Delete the head office user
        if(isset($hou->user_profile_assign->profile->head_office_user_id) && $hou->user_profile_assign->profile->head_office_user_id == $hou->id){
            $hou->user_profile_assign->profile->head_office_user_id = null;
            $hou->user_profile_assign->profile->save();
        }
        $hou->delete();

        $hasSuperAccess = $headOffice->users()
        ->whereHas('user_profile_assign.profile', function ($query) {
            $query->where('super_access', true);
        })
        ->exists();
        if($hasSuperAccess){
            DB::commit();
        }else{
            DB::rollback();
            return redirect()->back()->with('error', 'At least one super user is required.');
        }
    return redirect()->back()->with('success','Profile assigned successfully.');
    }
    




    public function show_invite_user()
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $profiles = $headOffice->head_office_user_profiles;

        $positions = Position::all();
        return view('head_office.users.head_office_user_invite', compact('profiles','positions'));
    }

    public function submit_invite_user(HeadOfficeUserInviteFormRequest $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'head_office_position' => 'required|string|min:2|max:50',
        'head_office_user_profile_id' => 'required|exists:head_office_access_rights,id'
    ]);
    if($validator->fails()){
        return back()->with('error','Invalid data provided!');
    }
    $data = $request->getData();
    $user = User::where('email',$data['email'])->first();
    if ($user) {
        $head_office_user = $user->getHeadOfficeUser(Auth::guard('web')->user()->id);
        if(isset($head_office_user)){
            return back()->withErrors(['already_exists'=>'User Already exists under current Company Scope.']);
        }

        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $existing_head_office_user = HeadOfficeUser::where('user_id', $user->id)
            ->where('head_office_id', $headOffice->id)
            ->first();

        if ($existing_head_office_user) {
            return back()->withErrors(['already_exists' => 'User Already exists under current Company Scope.']);
        }
        $user->update(['selected_head_office_id' => $headOffice->id]);
        $head_office_user = new HeadOfficeUser();
        $head_office_user->user_id = $user->id;
        $head_office_user->head_office_id = $headOffice->id;
        $head_office_user->position = $request->head_office_position;
        $head_office_user->save();
        $profile_assign = new UserProfileAssign();
        $profile_assign->user_profile_id = $request->head_office_user_profile_id;
        $profile_assign->head_office_user_id = $head_office_user->id;
        $profile_assign->save();

        if ($headOffice->restricted == 1 && !empty($headOffice->link_token)) {
            $signin_link = 'https://' . $headOffice->link_token . '.qi-tech.co.uk' . '/login';
        } else {
            $signin_link = url('/login');
        }

        Mail::send('emails.added_profile_email', ['user' => $user, 'head_office_user' => $head_office_user,'request' => request(),'signin_link' => $signin_link,], function($message) use ($user, $head_office_user) {$message->to($user->email)
            ->subject('Welcome to ' . $head_office_user->headOffice->company_name );
        });

        return redirect()->route('head_office.head_office_users')->with('success','You have assign a new user');
    } else {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $data['invited_by_id'] = Auth::guard('web')->user()->getHeadOfficeUser()->id;
        $data['invited_by_type'] = 'head_office';
        $data['expires_at'] = Carbon::now()->add(config('app.head_office_user_invite_expiry'));
        $data['token'] = Str::random(20);
        $data['head_office_id'] = Auth::guard('web')->user()->selected_head_office->id;

        $HeadOfficeUserInvite = HeadOfficeUserInvite::create($data);

        Mail::send('emails.invite_email', [
            'logo' => $headOffice->logo,
            'head_office_name' => $headOffice->company_name,
            'user_profile' => $request->head_office_position,
            'request' => request(),
            'token' => $data['token']
        ], function($message) use($data) {
            $message->to($data['email']);
            $message->subject(env('APP_NAME') . ' - New Head Office User');
        });


        return redirect()->route('head_office.head_office_users',['tab'=>'UserProfileInvite'])->with('success_message','Invitation has been send to a user.');
    }
}

    public function edit_invite_user( Request $request){
        if(isset($request->invite_id)){
            $invite = HeadOfficeUserInvite::find($request->invite_id);
            $invite->email = $request->email;
            $invite->head_office_position = $request->head_office_position;
            $invite->head_office_user_profile_id = $request->head_office_user_profile_id;
            $invite->token = Str::random(20);
            $invite->head_office_id = Auth::guard('web')->user()->selected_head_office->id;
            $invite->save();

            Mail::send('emails.general_email', ['heading' => 'Head Office User', 'msg' => 'You have been added as admin of '. $invite->headOfficeUser->headOffice->company_name .' head office. Kinldy click on link to create your account.
        '.route('create_head_office_user',$invite->token)], function($message) use($invite){
            $message->to($invite->email);
            $message->subject(env('APP_NAME') . ' - New Head Office User');
        });
        return redirect()->route('head_office.head_office_users')->with('success_message','Invitation has been Resent to a user.');
        }
    }

    public function resend_invite_user(Request $request,$id){
        if (!$request->has('_token') && $request->_token != csrf_token()) {
            return back()->with('error','Invalid data submitted.');
        }
        if(isset($id)){
            $invite = HeadOfficeUserInvite::find($id);
            $invite->token = Str::random(20);
            $invite->save();

            Mail::send('emails.general_email', ['heading' => 'Head Office User', 'msg' => 'You have been added as admin of '. $invite->headOfficeUser->headOffice->company_name .' head office. Kinldy click on link to create your account.
        '.route('create_head_office_user',$invite->token)], function($message) use($invite){
            $message->to($invite->email);
            $message->subject(env('APP_NAME') . ' - New Head Office User');
        });
        Mail::send('emails.added_profile_email', ['head_office_user' => $invite->headOfficeUser ], function($message) use ($invite) {   $message->to($invite->headOfficeUser->user->email)
            ->subject('You have been added to the ' . $invite->headOfficeUser->headOffice->company_name .' as company user');
            });
        return redirect()->route('head_office.head_office_users')->with('success_message','Invitation has been Resent to a user.');
        }
    }
    public function cancel_invite_user(Request $request,$id){
        if (!$request->has('_token') && $request->_token != csrf_token()) {
            return back()->with('error','Invalid data submitted.');
        }

        if(isset($id)){
            $invite = HeadOfficeUserInvite::find($id);
            $invite->expires_at = Null;
            $invite->save();

        return redirect()->route('head_office.head_office_users')->with('success_message','Invitation has been cancelled.');
        }
    }
    // public function head_office_profile_delete(Request $request){
    //     $headOffice = Auth::guard('web')->user()->selected_head_office;
    //     $profile = UserProfile::where('head_office_id',$headOffice->id)->where('id',$request->id)->first();
    //     if(!$profile){
    //         abort(403,'Invalid data submitted.');
    //     }
    //     if($profile->super_access){
    //         abort(403,'Invalid data submitted.');
    //     }
    //     $profile->delete();
    //     return redirect()->back()->with('success_message','Profile deleted successfully.');
    // }


    // new function for delete Profile in team
    public function head_office_profile_delete(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:head_office_access_rights,id',
        ]);
        
        $profileId = $request->id;
        $newProfileId = $request->new_profile_id;
        $selectedUsers = $request->selected_users;
    
        $profile = head_office_access_rights::find($profileId);
    
        if ($profile->super_access) {
            return back()->withErrors(['error' => 'You cannot delete a Super User profile.']);
        }
    
        $users = UserProfileAssign::where('user_profile_id', $profileId)->get();
    
        if ($selectedUsers && $newProfileId) {
            foreach ($selectedUsers as $userId) {
                $userProfileAssign = UserProfileAssign::where('head_office_user_id', $userId)->first();
                if ($userProfileAssign) {
                    $userProfileAssign->user_profile_id = $newProfileId;
                    $userProfileAssign->save();
                }
            }
        }
        $profile->delete();
    
        return redirect()->route('head_office.head_office_users', ['tab' => 'UserProfileTeam'])
                         ->with('success_message', 'Profile deleted successfully.');
    }




    public function remove_user($id){
       // $headOffice = HeadOfficeUser::findOrFail($id)->delete();
        return redirect()->back()->with('success_message', 'User was removed successfully');
    }

    public function head_office_user_profiles(Request $request){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $headOffice->defaultUserAccessProfiles();
        $profiles = UserProfile::where('head_office_id', $headOffice->id)->get();
        $permission = Permission::class;
        return view('head_office.users.user_profiles',compact('headOffice','profiles','permission'));
    }
    public function head_office_profile_save(Request $request){
        $validated = $request->validate([
            'profile_name' => 'required|min:1',
        ]);
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $profile = head_office_access_rights::where('head_office_id', $headOffice->id)->where('id',$request->id)->first();
        # User is trying to edit Super User.
        if($profile && $profile->super_access){
            abort(403,'Access denied!');
        }

        if(!$profile){
            $profile = new head_office_access_rights();
            $profile->head_office_id = $headOffice->id;
            $profile->profile_name = $request->profile_name;
            $profile->is_manage_forms = isset($request->is_manage_forms);
            $profile->is_manage_company_account = isset($request->is_manage_company_account);
            $profile->is_manage_location_users = isset($request->is_manage_location_users);
            $profile->is_manage_alert_settings = isset($request->is_manage_alert_settings);
            $profile->is_access_company_activity_log = isset($request->is_access_company_activity_log);
            $profile->is_access_contacts = isset($request->is_access_contacts);
            $profile->is_access_locations = isset($request->is_access_locations);
            $profile->locations = isset($request->access_locations_options) ? json_encode($request->access_locations_options) : null;
        }
        $profile->profile_name = $request->profile_name;
        $profile->is_manage_forms = isset($request->is_manage_forms);
        $profile->is_manage_company_account = isset($request->is_manage_company_account);
        $profile->is_manage_location_users = isset($request->is_manage_location_users);
        $profile->is_manage_alert_settings = isset($request->is_manage_alert_settings);
        $profile->is_access_company_activity_log = isset($request->is_access_company_activity_log);
        $profile->is_access_contacts = isset($request->is_access_contacts);
        $profile->is_access_locations = isset($request->is_access_locations);
            $profile->locations = isset($request->access_locations_options) ? json_encode($request->access_locations_options) : null;
        $profile->save();
        

        return redirect()->route('head_office.head_office_users',['tab'=>'UserProfileTeam'])->with('success_message','Profile saved successfully.');
    }
    

    public function head_office_access_right_save(Request $request){
        $validated = $request->validate([
            'head_office_user_id' => 'required|min:1',
        ]);
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $logged_ho_u = Auth::guard('web')->user()->getHeadOfficeUser();
        if(!$logged_ho_u->user_profile_assign->profile->super_access){
            return redirect()->back()->with('error_message','Access denied!');
        }
        $hou = HeadOfficeUser::where('head_office_id',$headOffice->id)->where('id',$request->head_office_user_id)->first();
        $profile = $hou->get_permissions();

        if(!$hou){
            abort(403,'Data integrity modified.');
        }
        $accessRight = AccessRight::where('head_office_user_id', $request->head_office_user_id)->where('id',$request->id)->first();
        if(!$accessRight){
            $accessRight = new AccessRight();
            $accessRight->head_office_user_id = (int)$request->head_office_user_id;
        }
        $accessRight->save();

        $new_office_right = head_office_access_rights::where('custom_access_rights_id',$accessRight->id)->first();

        if($new_office_right){
            $new_office_right->delete();
        }
        $new_office_right = new head_office_access_rights();
        $new_office_right->profile_name = 'custom';
        $new_office_right->head_office_id = $headOffice->id;
        $new_office_right->head_office_user_id = $request->head_office_user_id;
        $new_office_right->custom_access_rights_id = $accessRight->id;
        $new_office_right->super_access = (isset($profile)) ? $profile->super_access : false;
        $new_office_right->is_manage_forms = isset($request->is_manage_forms);
        $new_office_right->is_manage_company_account = isset($request->is_manage_company_account);
        $new_office_right->is_manage_location_users = isset($request->is_manage_location_users);
        $new_office_right->is_manage_team = isset($request->is_manage_team);
        $new_office_right->is_manage_alert_settings = isset($request->is_manage_alert_settings);
        $new_office_right->is_access_company_activity_log = isset($request->is_access_company_activity_log);
        $new_office_right->is_access_contacts = isset($request->is_access_contacts);
        $new_office_right->is_access_locations = isset($request->is_access_locations);
        $new_office_right->locations = json_encode($request->access_locations_options);

        $new_office_right->save();

        

        
        // $permissions = (array)$request->permissions;
        // AccessRightPermission::where('access_rights_id',$accessRight->id)->delete();
        // foreach($permissions as $name){
        //     $p = Permission::where('name',$name)->first();
        //     if(!$p){
        //         $p = new Permission();
        //         $p->name = $name;
        //         $p->save();
        //     }
        //     if($p){
        //         $profilePerm = new AccessRightPermission();
        //         $profilePerm->access_rights_id = $accessRight->id;
        //         $profilePerm->permission_id = $p->id;
        //         $profilePerm->save();
        //     }
        // }

        return redirect()->route('head_office.head_office_users')->with('success_message','Access rights saved successfully.');
    }

    public function head_office_access_right_delete(Request $request,$id){
        if (!$request->has('_token') && $request->_token != csrf_token()) {
            return back()->with('error','Invalid data submitted.');
        }
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $hou = HeadOfficeUser::where('head_office_id',$headOffice->id)->where('id',$id)->first();
        if(!$hou){
            abort(404);
        }
        $profile = $hou->get_permissions();
        if(isset($profile) && $profile->super_access){
            return redirect()->back()->with('error','You cannot delete permissions of super users!');
        }
        if( isset($hou->access_right)){
            if(isset($hou->access_right->head_office_access_rights)){
                $hou->access_right->head_office_access_rights->delete();
            }
            $hou->access_right->delete();
        }
        return redirect()->route('head_office.head_office_users')->with('success_message','Custom access rights removed.');
    }
}
