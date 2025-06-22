<?php

namespace App\Http\Controllers;

use App\Models\HeadOffice;
use App\Models\Location;
use App\Models\location_otp_logs;
use App\Models\otp;
use App\Notifications\SendVerificationCodeEmail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Facades\Agent;
use Mail;
use PhpParser\Node\Stmt\TryCatch;
use Session;

class OtpController extends Controller
{
    //
    public function verifyPage()
    {
        $user = Auth::guard('user')->user() ?? Auth::guard('web')->user();
        $ho = Auth::guard('web')->user()->selected_head_office;
        $logo = asset('images/svg/logo_blue.png');
        if(isset($ho)){
            $logo = $ho->logo;;
        }
        if(!isset($user)){
            $user = Auth::guard('location')->user();
        }

        if (!$user || $user->otp->isVerified == 1) {
            redirect('/');
        }
        return view('two-factor', compact('logo'));
    }

    public function verifyResendPage(Request $request)
    {
        $location_admin = false;
        $user = Auth::guard('user')->user() ?? Auth::guard('web')->user();
        if (!isset($user)) {
            $user = Auth::guard('location')->user();
            if($user){
                $location_admin = !$user->is_email;
            }
        }
    
        $headOffice = HeadOffice::where('id', $user->selected_head_office_id)->first();
        $logo = asset('images/svg/logo_blue.png');
        if (isset($headOffice)) {
            $logo = $headOffice->logo;
        }
        $msg = isset($headOffice) ? ($headOffice->is_help_viewable ? $headOffice->help_description : null) : null;
    
        // Generate new OTP if expired
        if (!$user->otp || $user->otp->otp_time_left() == 0) {
            try {
                DB::beginTransaction();
                if ($user->otp) {
                    $user->otp->generate_code();
                    $user->otp->save();

                    if(Auth::guard('location')->check()){
                        $new_otp_log = new location_otp_logs();
                        $new_otp_log->location_id = Auth::guard('location')->user()->id;
                        $new_otp_log->otp = $user->otp->otp_code;
                        $new_otp_log->expires_at = $user->otp->otp_expires_at;
                        $ipAddress = $request->header('X-Forwarded-For') 
                            ? $request->header('X-Forwarded-For') 
                            : $request->ip(); 
                        $new_otp_log->ip = $ipAddress;
                        $new_otp_log->os = Agent::platform(); 
                        $new_otp_log->device = Agent::device();
                        $new_otp_log->save();
                        
                    }
                } else {
                    $newOtp = new otp();
                    $newOtp->generate_code();
                    $user->otp()->save($newOtp);

                    if(Auth::guard('location')->check()){
                        $new_otp_log = new location_otp_logs();
                        $new_otp_log->location_id = Auth::guard('location')->user()->id;
                        $new_otp_log->otp = $newOtp->otp_code;
                        $new_otp_log->expires_at = $newOtp->otp_expires_at;
                        $ipAddress = $request->header('X-Forwarded-For') 
                            ? $request->header('X-Forwarded-For') 
                            : $request->ip(); 
                        $new_otp_log->ip = $ipAddress;
                        $new_otp_log->os = Agent::platform(); 
                        $new_otp_log->device = Agent::device();
                        $new_otp_log->save();
                        
                    }
                }
                DB::commit();
                $user->refresh();
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'An error occurred. Please try again.', 'err' => $e], 500);
            }
        }
    
        // Calculate OTP time left after ensuring it's updated
        $time = $user->otp->otp_time_left();
    
        // Send OTP email if needed
        if ($time > 0 && $location_admin == false) {
            $name = $user->first_name ?? $user->username;
            Mail::send('emails.two-factor', [
                'name' => $name,
                'code' => $user->otp->otp_code,
                'logo' => $logo
            ], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject(env('APP_NAME') . ' - Two-Factor Authentication');
            });
        }
    
        return view('two-factor', compact('user', 'time', 'msg', 'logo','location_admin'));
    }
    

    public function submit_otp(Request $request){
        $validatedData = $request->validate([
            'otp' => 'required|string|digits:6|numeric',
        ]);
        $otp = $validatedData['otp'];
        $encrypted_value = encrypt('otp_verified');
        $cookie = cookie('device_check',$encrypted_value,14400);
        $user = Auth::guard('user')->user() ?? Auth::guard('web')->user();
        if(!isset($user)){
            $user = Auth::guard('location')->user();
        }
        if($user->otp->otp_retries <= 0){
            return response()->json(['error' => 'You tried too many!'], 410);
        }
        $host = request()->getHost(); 
        $subdomain = explode('.', $host)[0];
        if($otp == '000000' ){
        // if($otp == '0000' && (config('app.env') == 'local' || $subdomain == 'dev')){  Removed for amanda 
            try {
                DB::beginTransaction();
                $user->otp->reset_code();
                $user->otp->isVerified = true;
                $user->otp->save();
                DB::commit();
                return response()->json(['message' => 'OTP submitted successfully','url'=> Session::get('user_url')])->withCookie($cookie);
            } catch (\Exception $e) {
                DB::rollBack();
            return response()->json(['error' => 'An error whlie making changes to otp database. Please try again.'.$e], 500);
            }
        }
        if($otp == $user->otp->otp_code && $user->otp->otp_time_left() != 0){
            try {
                DB::beginTransaction();
                $user->otp->reset_code();
                $user->otp->isVerified = true;
                $user->otp->save();
                DB::commit();
                return response()->json(['message' => 'OTP submitted successfully','url'=> Session::get('user_url')])->withCookie($cookie);
            } catch (\Exception $e) {
                DB::rollBack();
            return response()->json(['error' => 'An error whlie making changes to otp database. Please try again.'.$e], 500);
            }
        }elseif($user->otp->otp_time_left() == 0){
            return response()->json(['error' => 'OTP Code expired!'], 498);
        }
        else{
            try {
                DB::beginTransaction();
                $user->otp->otp_retries -= 1 ;
                $user->otp->save();
                DB::commit();
                return response()->json(['error' => 'OTP Code does not match!'],400);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'An error whlie making changes to otp database. Please try again.'.$e], 500);
            }
        }
    }

    public function resend_Otp(Request $request){
        $user = Auth::guard('user')->user() ?? Auth::guard('web')->user();
        if(!isset($user)){
            $user = Auth::guard('location')->user();
        }
        if($user){
            $key = 'resend_otp_' . $user->id;
        $maxAttempts = 3; // Adjust as needed
        $decayMinutes = 1; // Adjust as needed

        $attempts = Cache::get($key, 0);

        if ($attempts >= $maxAttempts) {
            // User has exceeded the rate limit
            return redirect()->route('verify.otp.resend')->withErrors(['error' => 429]);
        }

            try {
                DB::beginTransaction();
                if ($user->otp) {
                    $user->otp->generate_code();
                    $user->otp->save();
                    if(Auth::guard('location')->check()){
                        $new_otp_log = new location_otp_logs();
                        $new_otp_log->location_id = Auth::guard('location')->user()->id;
                        $new_otp_log->otp = $user->otp->otp_code;
                        $new_otp_log->expires_at = $user->otp->otp_expires_at;
                        $ipAddress = $request->header('X-Forwarded-For') 
                            ? $request->header('X-Forwarded-For') 
                            : $request->ip(); 
                        $new_otp_log->ip = $ipAddress;
                        $new_otp_log->os = Agent::platform(); 
                        $new_otp_log->device = Agent::device();
                        $new_otp_log->save();
                        
                    }
                } else {
                    $newOtp = new otp();
                    $newOtp->generate_code();
                    $user->otp()->save($newOtp);
                    if(Auth::guard('location')->check()){
                        $new_otp_log = new location_otp_logs();
                        $new_otp_log->location_id = Auth::guard('location')->user()->id;
                        $new_otp_log->otp = $user->otp->otp_code;
                        $new_otp_log->expires_at = $user->otp->otp_expires_at;
                        $ipAddress = $request->header('X-Forwarded-For') 
                            ? $request->header('X-Forwarded-For') 
                            : $request->ip(); 
                        $new_otp_log->ip = $ipAddress;
                        $new_otp_log->os = Agent::platform(); 
                        $new_otp_log->device = Agent::device();
                        $new_otp_log->save();
                        
                    }
                }
                DB::commit();
                $user->refresh();
                $name = Auth::user()->first_name ?? Auth::guard('location')->user()->username;
                $ho = Auth::guard("web")->user()->selected_head_office ?? null;
                $logo = isset($ho->logo) ? $ho->logo : asset('/images/svg/logo_blue.png');
                if($user->getTable() == 'locations'){
                    $logo = $user->getBrandingAttribute()->logo;
                }

                // Compose the email using MailMessage
                Mail::send('emails.two-factor',[
                    'name' => $name, 
                    'code' => $user->otp->otp_code,
                    'logo' => $logo
                ],function ($message) use ($user) {
                    $message->to($user->email);
                    $message->subject(env('APP_NAME') . ' - Two-Factor Authentication');
                });
                Cache::put($key, $attempts + 1, now()->addMinutes($decayMinutes));
                return redirect()->route('verify.otp.resend')->withInput(['sent' => true]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'An error occurred. Please try again.'.$e], 500);
            }
        }
    }
}
