<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class account_suspended
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
            'web',
            'location',
            'user'
        ];

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                if($user->is_suspended)
                {
                    Auth::guard($guard)->logout();
                    return redirect('/app.html#!/login?error=-4');
                }
            }
        }
        //  logic for the HeadOfficeUser block
        $user = Auth::guard('web')->user();
        if(isset($user)){
            $headOffice = $user->selected_head_office;
            $headOfficeUser = $user->getHeadOfficeUser($headOffice->id);
            if(isset($headOfficeUser) && $headOfficeUser->is_blocked == true){
                $user->logout();
                return redirect('/app.html#!/login?error=-4');
            }
        }

        return $next($request);
    }
}
