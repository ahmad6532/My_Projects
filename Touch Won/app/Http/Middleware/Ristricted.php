<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Ristricted
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
        if( session()->has('data') && (url('signup')==$request->url() ||
                url('signin')==$request->url() || url('drawer')==$request->url()
                || url('reminder')==$request->url()
                || url('password/forget')==$request->url()
                || url('/reset-password')==$request->url()
                || url('password-resetted')==$request->url()|| url('edit_account')==$request->url()))
        {
            return back();
        }
        return $next($request);
    }
}
