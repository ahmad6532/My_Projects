<?php

namespace App\Http\Middleware;

use App\Models\otp;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class OtpMiddleware
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
        $user = Auth::guard('user')->user() ?? Auth::guard('web')->user();
        if(!isset($user)){
            $user = Auth::guard('location')->user();
        }
        if($user){
            $otpRecord = $user->otp;
            if($otpRecord){
                if($otpRecord->isEnabled == false){
                    return $next($request);
                }
                if($otpRecord->isVerified == true && is_null($otpRecord->otp_code) && $request->hasCookie('device_check') ){
                    $cookie = $request->cookie('device_check');
                    $decryptedValue = $cookie ?? decrypt($cookie);
                    if($decryptedValue === 'otp_verified'){
                        return $next($request);
                    }
                }
                else{
                    Session::put('user_url', $request->fullUrl());
                    return redirect()->route('verify.otp.resend');
                }
                if($otpRecord->isVerified == true && is_null($otpRecord->otp_code) ){
                    return $next($request);
                }
                Session::put('user_url', $request->fullUrl());
                return redirect()->route('verify.otp.resend');
            }
            Session::put('user_url', $request->fullUrl());
            return redirect()->route('verify.otp.resend');
        }
        return redirect('/app.html#!/login');
    }
}
