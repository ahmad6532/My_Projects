<?php

namespace App\Http\Controllers;

use App\Models\HeadOfficeRequest;
use App\Models\Headoffices\Users\HeadOfficeUserInvite;
use App\Models\Headoffices\Users\UserProfile;
use App\Models\Headoffices\Users\UserProfileAssign;
use App\Models\HeadOfficeUser;
use Illuminate\Http\Request;
use App\Models\Location;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    public function verify_email($type, $token)
    {
        $msg = "";
        switch($type)
        {
            case 1:
                $location = Location::where('email_verification_key', $token)->first();
                if(!$location)
                {
                    $msg = "Request is invalid or expired.";
                    return view('email_verified', compact('msg'));
                }
                if($location->email_verified_at)
                {
                    $msg = "This account has already been activated.";
                    return view('email_verified', compact('msg'));
                }
                /// can be placed an expiration check here ////
                $location->email_verified_at = Carbon::now();
                $location->is_active=true;
                $location->save();
                $msg = "Account activated successfully.";
                return view('email_verified', compact('msg'));

            case 2: // User
                $user = User::where('email_verification_key', $token)->first();
                if(!$user)
                {
                    $msg = "Request is invalid or expired.";
                    return view('email_verified', compact('msg'));
                }
                if($user->email_verified_at)
                {
                    $msg = "This account has already been activated.";
                    return view('email_verified', compact('msg'));
                }
                /// can be placed an expiration check here ////
                $user->email_verified_at = Carbon::now();
                $user->is_active=true;
                $user->save();

                $head_office_user_invite = HeadOfficeUserInvite::where('email', $user->email)->first();

                if($head_office_user_invite)
                {
                    $u = headOfficeUser::where('user_id', $user->id)->first();
                    if(!$u)
                    {
                        $headofficeuser = new HeadOfficeUser();
                        $headofficeuser->user_id    = $user->id;
                        $headofficeuser->head_office_id = $$head_office_user_invite->head_office_profile->head_office->id;
                        $headofficeuser->head_office_position = $head_office_user_invite->head_office_position;
                        $headofficeuser->save();

                        $head_office_user_profile = new UserProfileAssign();
                        $head_office_user_profile->head_office_user_id = $headofficeuser->id;
                        $head_office_user_profile->user_profile_id = $head_office_user_invite->head_office_user_profile_id;
                        $head_office_user_profile->save();
                        
                    }
                }
                $msg = "Account activated successfully.";
                return view('email_verified', compact('msg'));

        }
        $msg = "Invalid Request.";
        return view('email_verified', compact('msg'));
    }

    public function get_redirect_to()
    {
        $guards = [
            'web' => route('user.view_profile'),
            'location' => route('location.user_login_view'),
            /// HO later //
        ];

        if(Auth::guard('web')->check() && Auth::guard('location')->check())
            return route('location.dashboard');

        foreach ($guards as $guard => $redirect) {
            if (Auth::guard($guard)->check()) {
                return $redirect;
            }
        }

        return "";
    }
    public function create_head_office_user($token)
    {
        $user = HeadOfficeUserInvite::where("token", $token)->first();
        if(!$user)
            return redirect()->to('app.html#!/login?error=-11');
        if($user){
            return redirect()->to('app.html#!/signup/user?invite_email='.$user->email.'&token='.$token);
        }
    }
    public function create_head_office_user_request($token)
{
    $headOffice = HeadOfficeRequest::where("token", $token)->first();
    if(!isset($headOffice)){
        $user = HeadOfficeUserInvite::where("token", $token)->first();
        if(!$user){
            return redirect()->to('app.html#!/login?error=-11');
        }
        if($user){
            return redirect()->to('app.html#!/signup/user?invite_email='.$user->email.'&token='.$token);
        }
        return redirect()->to('app.html#!/login?error=-11');
    }
    if(isset($headOffice)){
        return redirect()->to('app.html#!/signup/user?invite_email='.$headOffice->email.'&ho_token='.$token.'&first_name='.$headOffice->first_name.'&surname='.$headOffice->surname.'&tel='.$headOffice->user_telephone_no);
    }
}
}
