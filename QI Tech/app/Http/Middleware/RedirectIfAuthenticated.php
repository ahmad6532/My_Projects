<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        // overriding //
        $guards = [
            'web' => route('user.view_profile'),
            'location' => route('location.user_login_view'),
            /// HO later //
            'admin' => route('admin.dashboard')
        ];

        if(Auth::guard('web')->check() && Auth::guard('location')->check())
            return redirect()->route('location.dashboard');

        foreach ($guards as $guard => $redirect) {
            if (Auth::guard($guard)->check()) {
                return redirect($redirect);
            }
        }

        return $next($request);
    }
}
