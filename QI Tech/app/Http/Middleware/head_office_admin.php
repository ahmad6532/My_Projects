<?php

namespace App\Http\Middleware;

use App\Models\remote_location_tokens;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class head_office_admin
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
        $user = Auth::guard('web')->user();
        if(!$user->selected_head_office_id){
            Auth::guard('web')->logout();
            return redirect('/app.html#!/login');
        }

        $location = Auth::guard('location')->user();
        $hashedToken = session('remote_access');
        $currentIpAddress = $request->ip();
        $currentUserAgent = $request->header('User-Agent');

        
        if(isset($location)){
            if (!$hashedToken) {
                $location->logout();
            }
            $locationToken = remote_location_tokens::where('token', $hashedToken)
            ->where('ip', $currentIpAddress) // Match the IP address
            ->where('user_agent', $currentUserAgent) // Match the user agent
            ->first();
            if (!$locationToken || $locationToken->expires_at < now()) {
                $location->logout();
            }
        }


        if($user->selected_head_office->is_suspended)
        {
            Auth::guard('web')->logout();
            return redirect('/app.html#!/login?error=-2');
        }

        return $next($request);
    }
}
