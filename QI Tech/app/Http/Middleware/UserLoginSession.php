<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserLoginSession
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
        $guards = [
            'web'
        ];
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                if($user->id != 1000 && count($user->userLoginSessions->where('user_session',session('user_session'))->where('is_active',1)) == 0)
                {
                    Auth::guard($guard)->logout();
                    return redirect('/app.html#!/login');
                }
            }
        }

        // Check if the user is authenticated in the 'location' guard
        // if (Auth::guard('location')->check()) {
        //     $lastActivity = Session::get('location_last_activity');
        //     $timeout = 3 * 60; // 3 minutes in seconds

        //     if ($lastActivity && (time() - $lastActivity > $timeout)) {
        //         // Logout the user from the 'location' guard
        //         Auth::guard('user')->logout();

        //         // Redirect to user sign-in page
        //         return redirect()->route('location.user_login_view');
        //     }

        //     // Update last activity time
        //     Session::put('location_last_activity', time());
        // }
        // // Check if company account exists
        // if (Auth::guard('web')->check()) {
        //     $lastActivity = Session::get('company_last_activity');
        //     $timeout = 10 * 60; // 10 minutes in seconds

        //     if (isset($lastActivity) && (time() - $lastActivity > $timeout)) {
        //         // Logout the user from the 'company' guard
        //         Auth::guard('web')->logout();
        //         Auth::guard('user')->logout();

        //         // Redirect to user sign-in page
        //         return redirect()->route('login');
        //     }

        //     // Update last activity time
        //     Session::put('company_last_activity', time());
        // }
        return $next($request);
    }
}
