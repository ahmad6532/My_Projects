<?php

namespace App\Http\Middleware;

use App\Models\HeadOfficeLocation;
use App\Models\remote_location_tokens;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class location_access
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $location = Auth::guard('location')->user();
        $has_access = false;
        if ($location->is_access) {
            $head_office_location = HeadOfficeLocation::where('location_id', $location->id)->first();
            //$head_office_location = $location->head_office_location;
            $head_office = $head_office_location->head_office;
            $groups = $head_office->approved_groups;
            $locations = $head_office->approved_locations;
            foreach ($groups as $group) {
                if ($head_office->head_office_location_groups()->where('group_id', $group->group->id)->first()) {
                    $rt = 'location.dashboard';
                    $has_access = true;
                }
            }

            if ($head_office->approved_locations()->where('location_id', $location->id)->first()) {
                $has_access = true;
            }
            if (!$has_access)
            {
                Auth::guard('web')->logout();
                return redirect()->route('location.user_login_view')->with('error','You are not authorized to login to this location.');//->with  message. user dont have access !
            }
        }

        $ho = Auth::guard('web')->check() ? Auth::guard('web')->user()->selected_head_office : null;
        $hashedToken = session('remote_access');
        $currentIpAddress = $request->ip();
        $currentUserAgent = $request->header('User-Agent');
        $remote = false;

        if(isset($ho)){
            if (!$hashedToken) {
                $location->logout();
            }
            $locationToken = remote_location_tokens::where('token', $hashedToken)
            ->where('ip', $currentIpAddress) // Match the IP address
            ->where('user_agent', $currentUserAgent) // Match the user agent
            ->first();
            if (isset($locationToken)) {
                if($locationToken->expires_at < now()){
                    $location->logout();
                }
                $remote = true;
                session()->flash('remote', $remote);
            }else{
                $location->logout();
            }
        }
        return $next($request);
    }
}
