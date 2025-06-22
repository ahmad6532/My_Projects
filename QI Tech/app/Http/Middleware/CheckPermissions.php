<?php

namespace App\Http\Middleware;

use App\Models\user_case_restrictions;
use Auth;
use Closure;
use Illuminate\Http\Request;

class CheckPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,$permissionType)
    {
        $user = Auth::guard('web')->user();
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $headOfficeUser = $user->getHeadOfficeUser();
        $profile = $headOfficeUser->get_permissions();

        if (is_null($user) || is_null($headOffice) || is_null($headOfficeUser) || is_null($profile)) {
            abort(403, 'Permission Denied');
        }


        if($permissionType == 'forms' && ($profile->super_access == true || $profile->is_manage_forms == true)){
            return $next($request);
        }
        elseif($permissionType == 'company' && ($profile->super_access == true || $profile->is_manage_company_account == true)){
            return $next($request);
        }
        elseif($permissionType == 'team' && ($profile->super_access == true || $profile->is_manage_team == true)){
            return $next($request);
        }
        elseif($permissionType == 'alerts' && ($profile->super_access == true || $profile->is_manage_alert_settings == true)){
            if ($request->headers->has('referer')) {
                return redirect()->back()->with(['error' => 'Alerts comming soon!']);
            } else {
                abort(403, 'Alerts comming soon!');
            }
            // return $next($request);
        }
        elseif($permissionType == 'location_users' && ($profile->super_access == true || $profile->is_manage_location_users == true)){
            return $next($request);
        }
        elseif($permissionType == 'contacts' && ($profile->super_access == true || $profile->is_access_contacts == true)){
            return $next($request);
        }
        elseif($permissionType == 'locations' && ($profile->super_access == true || $profile->is_access_locations == true)){
            return $next($request);
        }
        elseif($permissionType == 'case_manager'){
            $case_id = $request->route('id');
            if(!isset($case_id)){
                return $next($request);
            }
            
            $case_restriction = user_case_restrictions::where('ho_user_id',$headOfficeUser->id)->where('case_id',$case_id)->first();
            if(isset($case_restriction)){
                abort(403,'Permission Denied');
            }else{
                return $next($request);
            }
        }
        else{
            if ($request->headers->has('referer')) {
                return redirect()->back()->with(['error' => 'You do not have permission to access this page']);
            } else {
                abort(403, 'Permission Denied');
            }
        }
    }
}
