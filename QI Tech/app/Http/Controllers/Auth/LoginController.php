<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordFormRequest;
use App\Models\ActivityLog;
use App\Models\HeadOffice;
use App\Models\HeadOfficeLocation;
use App\Models\HeadOfficeUser;
use App\Models\LocationReceivedAlert;
use App\Models\LocationUser;
use App\Models\PasswordReset;
use App\Models\ServiceMessage;
use Cache;
use Exception;
use Http;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\LocationQuickLogin;
use App\Models\LocationUserLoginSession;
use App\Models\User;
use App\Models\UserLoginSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Jenssegers\Agent\Facades\Agent;
use Lang;
use RateLimiter;
use Session;

class LoginController extends Controller
{
    //

    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
    }

    protected function formatRemainingTime($seconds)
    {
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;
    
        $timeString = '';
    
        if ($minutes > 0) {
            $timeString .= $minutes . ' minute' . ($minutes > 1 ? 's' : '');
        }
    
        if ($remainingSeconds > 0) {
            if ($timeString) {
                $timeString .= ' and ';
            }
            $timeString .= $remainingSeconds . ' second' . ($remainingSeconds > 1 ? 's' : '');
        }
    
        return $timeString ?: '0 seconds'; // Default if no time is left
    }
    public function markIpWithFailedAttempts($request)
{
    $ipAddress = $request->ip();
    Cache::put('failed_attempts_' . $ipAddress, true, now()->addMinutes(30)); // Expires in 30 mins, adjust as necessary
}
public function hasMarkedIp($request)
{
    $ipAddress = $request->ip();
    return Cache::has('failed_attempts_' . $ipAddress);
}
public function clearFailedAttemptsFlag($request)
{
    $ipAddress = $request->ip();
    Cache::forget('failed_attempts_' . $ipAddress);
}

public function validateRecaptcha($recaptchaToken)
{
    $secretKey = config('services.recaptcha.secret'); // Add this key to your config/services.php
    $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        'secret' => $secretKey,
        'response' => $recaptchaToken,
        'remoteip' => request()->ip(),
    ]);

    $recaptchaResponse = $response->json();

    if (!$recaptchaResponse['success'] || $recaptchaResponse['score'] < 0.5) {
        return false; // Recaptcha validation failed
    }
    
    return true; // Recaptcha validated successfully
}


    public function login(Request $request)
    {
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];

        $link_token_check = HeadOffice::where('link_token', $subdomain)->first();

        
        $request->session()->forget('remote_access');
        $this->validateLogin($request);
        $request->validate([
            'type' => 'required|numeric|min:0|max:3',
            'pin_check' => 'nullable|numeric|min:0|max:1',
        ]);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->markIpWithFailedAttempts($request);
            $seconds = RateLimiter::availableIn($this->throttleKey($request));
            $formattedTime = $this->formatRemainingTime($seconds);
            if(isset($link_token_check)){
                return redirect('/app.html#!/loginPer?error=-429&seconds='.$seconds.'&g_time=true');
            }
            return redirect('/app.html#!/login?error=-429&seconds='.$seconds.'&g_time=true');
        }


        if ($this->hasMarkedIp($request)) {
            $recaptchaToken = $request->input('recaptcha_token');
                if (!$this->validateRecaptcha($recaptchaToken)) {
                    if(isset($link_token_check)){
                        return redirect('/app.html#!/loginPer?error=-444'.'&g_time=true'); // Or return a response with an error
                    }
                return redirect('/app.html#!/login?error=-444'.'&g_time=true'); // Or return a response with an error
                }else{
                    $this->clearFailedAttemptsFlag($request);
                }
        }

        //// Switch User Types ///
        // =================== These lines check if request comes from both_found page, you can remove this if page is removed =============
        if (isset($request->token) && $request->token != 'localhost') {
            $head_office = HeadOffice::where('link_token', $request->token)->first();
            if (isset($head_office)) {
                $head_office_locations = HeadOfficeLocation::where('head_office_id', optional($head_office)->id)->get();
                $head_office_user = HeadOfficeUser::where('head_office_id', $head_office->id)->first();
                $user_with_head = User::where('id', $head_office_user->user_id)->first();
                // if (isset($user_with_head) && $user_with_head->email === $request->email) {
    
                // } else {
                //     $locationsUnderConsideration = [];
                //     foreach ($head_office_locations as $head_office_location) {
                //         if (Location::where('username', $request->email)->where('id', $head_office_location->location_id)->exists()) {
                //             $locationsUnderConsideration[] = $head_office_location;
                //             break;
                //         }
                //     }
    
                //     if (empty($locationsUnderConsideration)) {
                //         return redirect('/app.html#!/loginPer?error=-5&email=' . $request->email . '&type=' . $request->type);
                //     }
                // }
            }

        }

        session(['user_session' => Str::random(20)]);

        $type = (int) $request->type;
        $mn = "";
        $rt = "";
        $hd = false;
        switch ($type) {
            case 0: // Location
                $mn = "location";
                $rt = 'location.user_login_view';
                break;
            case 1: // User 
                $mn = "user";
                $rt = 'user.view_profile';
                if (Auth::guard('location')->check())
                    $rt = 'location.dashboard';
                break;
            case 2: // HO
                $mn = "web";
                $hd = true;
                //                Auth::guard('web')->logout(); // we need to re login for security purpose for HO account.

                $user = User::where('email', $request->email)->first();
                // dd($user);

                // =================== These lines checks for request comes from both_found page, you can remove this if page is removed =============
                // if($user && Location::where('email', $request->email)->exists() && !isset($request->from_both)  ){
                //     if(isset($request->token)){
                //         $token = $request->token;
                //         return view('head_office.both_found',compact('token'));
                //     }
                //     return redirect('/both_found');
                // }


                //            $head_office_user=$user->
                if ($user && count($user->head_office_admins)) {
                    if (count($user->head_office_admins) == 1) {
                        //            redirest to head office dashboard
                        $user->selected_head_office_id = $user->head_office_admins->first()->head_office_id;
                        $user->save();
                        //                        $rt = 'head_office.dashboard';
                        $rt = 'case_manager.index';
                    } else {
                        //                redirect to head office list route
                        $rt = 'head_office.preview_list';
                    }
                } else {
                    // Also for both_found;
                    $mn = "location";
                    $rt = 'location.user_login_view';
                    // return redirect('/app.html#!/login?error=-1&email=' . $request->email . '&type=' . $request->type); // back()->withInput()->withErrors(['error' => 'Invalid login credentials.']);
                }
                break;
        }
        //$found_user = Location::where('email', $request->email)->first();
        //if($found_user && Hash::check($request->password,$found_user->password))// && (!$found_user->company->is_active || $found_user->company->is_expired))
        //{
        //  return back()->withInput()->withErrors(['error'=>'We are sorry, your account has been deactivated. Please contact customer support.']);
        //}

        if ($mn != 'location' && Auth::guard($mn)->attempt(['email' => $request->email, 'password' => $request->password])) {
            //$found_user->authenticated($request, $found_user);
            //Log::UserLoggedIn($found_user);
            Session::put('company_last_activity', time());
            if ($rt == 'head_office.dashboard') {
                ActivityLog::create([
                    'user_id' => $user->id,
                    'head_office_id' => Auth::guard('web')->user()->selected_head_office->id,
                    'action' => 'User Login in Company Account',
                    'timestamp' => now(),
                ]);
            } elseif ($rt == 'user.view_profile') {
                
            }

            if (config('app.env') !== 'local') {
                $ip = $request->header('X-Forwarded-For'); //'31.94.18.73'; /* Static IP address */
            } else {
                $ip = $request->ip(); //'31.94.18.73'; /* Static IP address */
            }

            //$currentUserInfo = Location::get($ip);
            if (Auth::guard('web')->user()) {
                session(['show_toast' => true]);
                $ho = Auth::guard('web')->user()->selected_head_office;
                if ($ho->restricted == true && strtolower($ho->link_token) !== strtolower($subdomain)) {
                    Auth::guard('web')->logout();
                    return redirect('/app.html#!/login?error=-10&msg=' . 'You can only access this from the company\'s unique link');
                }
                $ho_u = Auth::guard('web')->user()->getHeadOfficeUser();
                if(isset($ho_u)){
                    $ho_u->is_active = true;
                    $ho_u->save();
                }
                try {
                    $ip_key = env('woosmap_private_key');
                    $geo = ['country_name' => "", 'city' => "", 'latitude' => "", 'longitude' => ""];
                    if (!str_contains($ip, "127.0.")) {
                        // $geo = json_dec  ode(file_get_contents('https://api.woosmap.com/geolocation/position/?ip_address=' . $ip . '&private_key='. $ip_key .''), true);
                        $geo['city'] = "unknown"; // will patch it later !
                    }
                    $browser = Agent::browser();
                    $version = Agent::version($browser);
                    $platform = Agent::platform();

                    //ya line check kro ya chaye hamesha user ka data ka get krny k liya
                    //Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password]);

                    $user = Auth::guard('web')->user();
                    $checking = $user->userLoginSessions->where('user_session', session('user_session'))->first();

                    if (!$checking) {
                        $checking = new UserLoginSession();
                        $checking->user_id = $user->id;
                        $checking->head_office_id = $ho_u->head_office_id;
                        $checking->ip = $ip;
                        $checking->browser = $browser . $version;
                        $checking->platform = $platform;
                        $checking->country = $geo['country_name'];
                        $checking->city = $geo['city'];
                        $checking->lat = $geo['latitude'];
                        $checking->long = $geo['longitude'];
                    }
                    if ($hd) {
                        $checking->is_head_office = 1;
                        // $head_office = Auth::guard('web')->user()->selected_head_office;
                        // $head_office->
                    }
                    $checking->user_session = session('user_session');
                    $checking->is_active = 1;
                    $checking->save();


                } catch (Exception $e) {
                    //Todo: Send an email to update about system ip find error failure !
                    dd($e);
                }
            }

            if (Auth::guard('user')->user()) {
                try {
                    $ip_key = env('woosmap_private_key');
                    $geo = ['country_name' => "", 'city' => "", 'latitude' => "", 'longitude' => ""];
                    if (!str_contains($ip, "127.0.")) {
                        // $geo = json_dec  ode(file_get_contents('https://api.woosmap.com/geolocation/position/?ip_address=' . $ip . '&private_key='. $ip_key .''), true);
                        $geo['city'] = "unknown"; // will patch it later !
                    }
                    $browser = Agent::browser();
                    $version = Agent::version($browser);
                    $platform = Agent::platform();

                    //ya line check kro ya chaye hamesha user ka data ka get krny k liya
                    //Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password]);

                    $user = Auth::guard('user')->user();
                    $checking = $user->userLoginSessions->where('user_session', session('user_session'))->first();

                    if (!$checking) {
                        $checking = new UserLoginSession();
                        $checking->user_id = $user->id;
                        $checking->ip = $ip;
                        $checking->browser = $browser . $version;
                        $checking->platform = $platform;
                        $checking->country = $geo['country_name'];
                        $checking->city = $geo['city'];
                        $checking->lat = $geo['latitude'];
                        $checking->long = $geo['longitude'];
                    }
                    if ($hd) {
                        $checking->is_head_office = 1;
                        $ho_u = Auth::guard('web')->user()->getHeadOfficeUser();
                        $checking->head_office_id = $ho_u->head_office_id;
                        // $head_office = Auth::guard('web')->user()->selected_head_office;
                        // $head_office->
                    }
                    $checking->user_session = session('user_session');
                    $checking->is_active = 1;
                    $checking->save();


                } catch (Exception $e) {
                    //Todo: Send an email to update about system ip find error failure !
                    dd($e);
                }
            }

            if ($type == 1) {
                $location = Auth::guard('location')->user();
                $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
                $user->last_login_at = Carbon::now();
                if(isset($location) && $location->is_active == false){
                    if(isset($link_token_check)){
                        return redirect('/app.html#!/loginPer?error=-888&msg=' . 'This account is inactive. Please contact the administrator.');
                    }
                    return redirect('/app.html#!/login?error=-888&msg=' . 'This account is inactive. Please contact the administrator.');
                }
                if ($location && $user) {
                    $location->last_login_at = Carbon::now();
                    $location->last_login_user_id = $user->id;
                    $user->last_login_location_id = $location->id;
                    $location->save();

                    $location_user = LocationUser::where([['user_id', $user->id], ['location_id', $location->id]])->first();
                    if (!$location_user) {
                        $new_location_user = new LocationUser;
                        $new_location_user->user_id = $user->id;
                        $new_location_user->location_id = $location->id;
                        $new_location_user->save();
                    }
                    # Receive patient safety alerts.
                    LocationReceivedAlert::receive($location);
                    LocationReceivedAlert::createNotificationForUser($user);
                }
                $user->save();

            }

            if ($request->has('pin_check')) {
                $pinCheck = true;
                $head_office_timing = [];
                $head_office = $location->head_office();
                return view('location.user_login', compact('location', 'head_office', 'head_office_timing', 'pinCheck'));
            }
            if ($request->has('token')) {

                session()->put('token', $request->token);
                return redirect()->route($rt);
            }
            return redirect()->route($rt);
        } elseif ($mn == 'location' && Auth::guard($mn)->attempt(['username' => $request->email, 'password' => $request->password])) {
            //$found_user->authenticated($request, $found_user);
            //Log::UserLoggedIn($found_user);
            Session::put('location_last_activity', time());
            $location = Auth::guard('location')->user();
            if(isset($location) && $location->is_active == false){
                $location->logout();
                if(isset($link_token_check)){
                    return redirect('/app.html#!/loginPer?error=-888&msg=' . 'This account is inactive. Please contact the administrator.');
                }
                return redirect('/app.html#!/login?error=-888&msg=' . 'This account is inactive. Please contact the administrator.');
            }
            $loc_token_check = HeadOffice::where('link_token', $request->loc_token)->first();
            if (!isset($loc_token_check) && Auth::guard('location')->user()->head_office()->restricted == true) {
                Auth::guard('location')->logout();
                return redirect('/app.html#!/login?error=-10&msg=' . 'You can only access this from the company\'s unique link');
            }
            if ($rt == 'location.user_login_view') {
                ActivityLog::create([
                    'user_id' => Auth::guard('location')->user()->id,
                    'head_office_id' => Auth::guard('location')->user()->head_office()->id,
                    'action' => 'User Login in location view',
                    'timestamp' => now(),
                ]);
            }

            $ip = $request->ip(); //'31.94.18.73'; /* Static IP address */
            //$currentUserInfo = Location::get($ip);
            if (Auth::guard('user')->user()) {
                try {
                    $ip_key = env('woosmap_private_key');
                    $geo = ['country_name' => "", 'city' => "", 'latitude' => "", 'longitude' => ""];
                    if (!str_contains($ip, "127.0.")) {
                        // $geo = json_decode(file_get_contents('https://api.woosmap.com/geolocation/position/?ip_address=' . $ip . '&private_key='. $ip_key .''), true);
                        $geo['city'] = "unknown"; // will patch it later !
                    }
                    $browser = Agent::browser();
                    $version = Agent::version($browser);

                    //ya line check kro ya chaye hamesha user ka data ka get krny k liya
                    //Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password]);

                    $user = Auth::guard('user')->user();
                    $checking = $user->userLoginSessions->where('user_session', session('user_session'))->first();

                    if (!$checking) {
                        $checking = new UserLoginSession();
                        $checking->user_id = $user->id;
                        $checking->ip = $ip;
                        $checking->browser = $browser . $version;
                        $checking->country = $geo['country_name'];
                        $checking->city = $geo['city'];
                        $checking->lat = $geo['latitude'];
                        $checking->long = $geo['longitude'];
                    }
                    if ($hd) {
                        $checking->is_head_office = 1;
                        // $head_office = Auth::guard('web')->user()->selected_head_office;
                        // $head_office->
                    }
                    $checking->user_session = session('user_session');
                    $checking->is_active = 1;
                    $checking->save();


                } catch (Exception $e) {
                    //Todo: Send an email to update about system ip find error failure !
                    dd($e);
                }
            }
            if ($type == 1) {
                $location = Auth::guard('location')->user();
                $user = Auth::guard('user')->user();
                $user->last_login_at = Carbon::now();
                if ($location && $user) {
                    $location->last_login_at = Carbon::now();
                    $location->last_login_user_id = $user->id;
                    $user->last_login_location_id = $location->id;
                    $location->save();

                    $location_user = LocationUser::where([['user_id', $user->id], ['location_id', $location->id]])->first();
                    if (!$location_user) {
                        $new_location_user = new LocationUser;
                        $new_location_user->user_id = $user->id;
                        $new_location_user->location_id = $location->id;
                        $new_location_user->save();
                    }
                    # Receive patient safety alerts.
                    LocationReceivedAlert::receive($location);
                    LocationReceivedAlert::createNotificationForUser($user);
                }
                $user->save();

            }

            if ($request->has('pin_check')) {
                return redirect()->route('location.create_pin');
            }
            if ($request->has('token')) {
                return redirect()->route($rt)->with(['token' => $request->token]);
            }
            return redirect()->route($rt);
        } else {
            $this->incrementLoginAttempts($request);
            if (Auth::guard('location')->check())
                return redirect()->route('location.user_login_view')->withErrors(['email' => 'Provided email or password is incorrect']);

            if(isset($link_token_check)){
                return redirect('/app.html#!/loginPer?error=-1&email=' . $request->email . '&type=' . $request->type); // back()->withInput()->withErrors(['error' => 'Invalid login credentials.']);
            }
            return redirect('/app.html#!/login?error=-1&email=' . $request->email . '&type=' . $request->type); // back()->withInput()->withErrors(['error' => 'Invalid login credentials.']);
        }

    }

    public function temporaryLogin($token, Request $request)
    {


        $userId = $userId = Cache::get('login_token_' . $token);
        ;
        if (!$userId) {
            abort(403, 'Unauthorized access - Invalid or expired token.');
        }
        $user = User::find($userId);

        Cache::forget('login_token_' . $token);
        session()->forget('remote_access');
        session(['user_session' => Str::random(20)]);
        if ($user) {

            Auth::guard('web')->login($user);
            $user->last_login_at = Carbon::now();
            $ip = $request->ip();
            try {
                $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$ip"));
                $browser = Agent::browser();
                $version = Agent::version($browser);
                $user = Auth::guard('web')->user();
                if (!is_null($user->userLoginSessions)) {
                    $checking = $user->userLoginSessions->where('ip', $ip)->where('browser', $browser . $version)->where('user_session', session('user_session'))->first();

                    if (!$checking) {
                        $checking = new UserLoginSession();
                        $checking->user_id = $user->id;
                        $checking->ip = $ip;
                        $checking->browser = $browser . $version;
                        $checking->country = $geo['geoplugin_countryName'];
                        $checking->city = $geo['geoplugin_city'];
                        $checking->lat = $geo['geoplugin_latitude'];
                        $checking->long = $geo['geoplugin_longitude'];
                    }
                    $checking->user_session = session('user_session');
                    $checking->is_active = 1;
                    $checking->save();
                } else {
                    $checking = new UserLoginSession();
                    $checking->user_id = $user->id;
                    $checking->ip = $ip;
                    $checking->browser = $browser . $version;
                    $checking->country = $geo['geoplugin_countryName'];
                    $checking->city = $geo['geoplugin_city'];
                    $checking->lat = $geo['geoplugin_latitude'];
                    $checking->long = $geo['geoplugin_longitude'];
                    $checking->user_session = session('user_session');
                    $checking->is_active = 1;
                    $checking->save();
                }


            } catch (Exception $e) {
                // dd($e);
            }

            return redirect()->route('head_office.dashboard');


        } else {
            // Dump failure message
            dd("Failed to log in user: {$user->id}");
        }
    }



    public function pinlogin(Request $request)
    {
        $request->validate([
            'pin2' => 'required|min:4|max:4',
            'uid' => 'required|numeric|min:1'
        ]);


        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        //// Switch User Types ///
        $location = Auth::guard('location')->user();
        $ql = $location->quick_logins->where('user_id', (int) $request->uid)->where('pin', (int) $request->pin2)->first();
        if ($ql) {
            $user = $ql->user;

            Auth::guard('user')->login($user);
            $user->last_login_at = Carbon::now();

            $location->last_login_at = Carbon::now();
            $location->last_login_user_id = $user->id;
            $user->last_login_location_id = $location->id;
            $location->save();
            $user->save();
            $ip = $request->ip();
            try {
                $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$ip"));
                $browser = Agent::browser();
                $version = Agent::version($browser);

                //ya line check kro ya chaye hamesha user ka data ka get krny k liya
                //Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password]);
                $user = Auth::guard('user')->user();
                if (!is_null($user->userLoginSessions)) {
                    $checking = $user->userLoginSessions->where('ip', $ip)->where('browser', $browser . $version)->where('user_session', session('user_session'))->first();

                    if (!$checking) {
                        $checking = new UserLoginSession();
                        $checking->user_id = $user->id;
                        $checking->ip = $ip;
                        $checking->browser = $browser . $version;
                        $checking->country = $geo['geoplugin_countryName'];
                        $checking->city = $geo['geoplugin_city'];
                        $checking->lat = $geo['geoplugin_latitude'];
                        $checking->long = $geo['geoplugin_longitude'];
                    }
                    $checking->user_session = session('user_session');
                    $checking->is_active = 1;
                    $checking->save();
                } else {
                    $checking = new UserLoginSession();
                    $checking->user_id = $user->id;
                    $checking->ip = $ip;
                    $checking->browser = $browser . $version;
                    $checking->country = $geo['geoplugin_countryName'];
                    $checking->city = $geo['geoplugin_city'];
                    $checking->lat = $geo['geoplugin_latitude'];
                    $checking->long = $geo['geoplugin_longitude'];
                    $checking->user_session = session('user_session');
                    $checking->is_active = 1;
                    $checking->save();
                }

            } catch (Exception $e) {
                // dd($e);
            }


            // dd($user);
            return redirect()->route('location.dashboard');
        } else {
            $this->incrementLoginAttempts($request);
            if (Auth::guard('location')->check())

                return redirect()->route('location.user_login_view')->withErrors(['email' => 'Provided pin code is incorrect']);

            return back()->withInput()->withErrors(['error' => 'Invalid pin.']);
        }

    }

    protected function sendLockoutResponse2(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        return response()->json([
            'error' => Lang::get('auth.throttle', ['seconds' => $seconds]),
        ], JsonResponse::HTTP_TOO_MANY_REQUESTS);
    }
    public function pinlogin2(Request $request)
    {
        $request->validate([
            'pin2' => 'required|min:4|max:4',
            'uid' => 'required|numeric|min:1'
        ]);


        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse2($request);
        }

        //// Switch User Types ///
        $location = Auth::guard('location')->user();
        $ql = $location->quick_logins->where('user_id', (int) $request->uid)->where('pin', (int) $request->pin2)->first();
        if ($ql) {
            $user = $ql->user;
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'User Login in Location Account',
                'timestamp' => now(),
            ]);
            Session::put('location_last_activity', time());
            Auth::guard('user')->login($user);
            $user->last_login_at = Carbon::now();

            $location->last_login_at = Carbon::now();
            $location->last_login_user_id = $user->id;
            $user->last_login_location_id = $location->id;
            $location->save();
            $user->save();
            $ip = $request->ip();
            try {
                $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$ip"));
                $browser = Agent::browser();
                $version = Agent::version($browser);

                //ya line check kro ya chaye hamesha user ka data ka get krny k liya
                //Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password]);
                $user = Auth::guard('user')->user();
                if (!is_null($user->userLoginSessions)) {
                    $checking = $user->userLoginSessions->where('ip', $ip)->where('browser', $browser . $version)->where('user_session', session('user_session'))->first();

                    if (!$checking) {
                        $checking = new UserLoginSession();
                        $checking->user_id = $user->id;
                        $checking->ip = $ip;
                        $checking->browser = $browser . $version;
                        $checking->country = $geo['geoplugin_countryName'];
                        $checking->city = $geo['geoplugin_city'];
                        $checking->lat = $geo['geoplugin_latitude'];
                        $checking->location_id = $location->id;
                        $checking->long = $geo['geoplugin_longitude'];
                    }
                    $checking->user_session = session('user_session');
                    $checking->is_active = 1;
                    $checking->save();
                } else {
                    $checking = new UserLoginSession();
                    $checking->user_id = $user->id;
                    $checking->ip = $ip;
                    $checking->browser = $browser . $version;
                    $checking->country = $geo['geoplugin_countryName'];
                    $checking->city = $geo['geoplugin_city'];
                    $checking->lat = $geo['geoplugin_latitude'];
                    $checking->long = $geo['geoplugin_longitude'];
                    $checking->user_session = session('user_session');
                    $checking->is_active = 1;
                    $checking->location_id = $location->id;
                    $checking->save();
                }

            } catch (Exception $e) {
                // dd($e);
            }



            return response()->json('user found!', 200);
        } else {
            $this->incrementLoginAttempts($request);
            if (Auth::guard('location')->check()) {
                return response()->json(['error' => 'Provided pin code is incorrect'], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
            return response()->json(['error' => 'Invalid pin'], JsonResponse::HTTP_UNAUTHORIZED);
        }

    }

    /// Remove below funciton. no more is in  use.
    public function head_office_admin_login(Request $request)
    {
        $this->validateLogin($request);


        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $admin_to_login = User::where('email', $request->email)->first();
        if (!$admin_to_login) {
            $this->incrementLoginAttempts($request);
            return redirect()->route('head_office.admin_login')->withInput()->withErrors(['email' => "Email or password is invalid"]);
        }

        $ho = Auth::guard('ho')->user();
        if (!$ho->users->where('user_id', $admin_to_login->id)->first()) {
            $this->incrementLoginAttempts($request);
            return redirect()->route('head_office.admin_login')->withInput()->withErrors(['email' => "Email or password is invalid"]);
        }

        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('case_manager.index');
        } else {
            $this->incrementLoginAttempts($request);
            if (Auth::guard('ho')->check())
                return redirect()->route('head_office.admin_login')->withErrors(['email' => 'Email or password is invalid']);

            return redirect('/app.html#!/login?error=-1'); // back()->withInput()->withErrors(['error' => 'Invalid login credentials.']);
        }

    }

    public function adminlogin(Request $request)
    {
        $this->validateLogin($request);


        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            //$found_user->authenticated($request, $found_user);
            //Log::UserLoggedIn($found_user);
            return redirect()->route('admin.dashboard');
        } else {
            $this->incrementLoginAttempts($request);

            return back()->withInput()->withErrors(['error' => 'Invalid login credentials.']);
        }

    }


    public function reset_password(Request $request)
    {
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];
        $ho = null;
        if ($subdomain != 'qi-tech') {
            $ho = HeadOffice::where('link_token', $subdomain)->first();
        }
        $logo = isset($ho->logo) ? $ho->logo : asset('/images/svg/logo_blue.png');
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        try {
            $data_user = NUll;
            if ($validator->fails()) {
                $data_user = Location::where('username', $request->email)->first();
                if (isset($data_user) && $data_user->is_admin_password == false) {
                    return redirect()->back()->with('error', "This location's password can only be reset by the company admin. Please contact them to reset the password");
                }
                $type = 1;
            } else {
                $data_user = User::where('email', $request->email)->first();
                $type = 2;
            }
            if ($data_user) {
                $name = $type == 1 ? $data_user->username : $data_user->name;

                $data['token'] = Str::random(64);


                $reset_password = new PasswordReset;

                $reset_password->email = $data_user->email;
                $reset_password->model_type = $type;
                $reset_password->expires_at = Carbon::now()->addMinutes(10);
                $reset_password->token = $data['token'];
                $reset_password->save();

                $link = route('location.confirm_location_password', $data['token']);

                // Time to send email to registered account email //
                Mail::send(
                    'emails.password_reset',
                    [
                        'token' => $data['token'],
                        'link' => $link,
                        'expires_at' => $reset_password->expires_at->format('d M Y (D) h:i a'),
                        'type' => $type,
                        "name" => $name,
                        'logo' => $logo
                    ]
                    ,
                    function ($message) use ($data_user) {
                        $message->to($data_user->email);
                        $message->subject(env('APP_NAME') . ' - Reset Password Requested');
                    }
                );


                return redirect()->back()->withInput()->with('success_message', "We have sent you a password reset link to " . $data_user->email . ". Don’t forget to check your spam");

            } else {
                return redirect()->back()->withInput()->with(['success_message' => 'We’ve received your request. you will receive an email.']);
            }


        } catch (Exception $e) {
            // dd($e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'unexpected_error caused please contact team support']);
        }


    }

    public function reset_password_location($id)
    {
        $ho = Auth::guard('web')->user()->selected_head_office;
        $ho_locations = $ho->locations;
        $location = $ho_locations->where('location_id', $id)->first() !== null ? $ho_locations->where('location_id', $id)->first()->location : null;
        $logo = isset($ho->logo) ? $ho->logo : asset('/images/svg/logo_blue.png');

        try {
            if (isset($location)) {
                if($location->is_admin_password == false){
                    return redirect()->back()->with('error', "This location's password can only be reset by the company admin. Please contact them to reset the password");
                }
                $name = $location->username;

                $token = Str::random(64);


                $reset_password = new PasswordReset;

                $reset_password->email = $location->email;
                $reset_password->model_type = 1;
                $reset_password->expires_at = Carbon::now()->addMinutes(10);
                $reset_password->token = $token;
                $reset_password->save();

                $link = route('location.confirm_location_password', $token);

                // Time to send email to registered account email //
                Mail::send(
                    'emails.password_reset',
                    [
                        'token' => $token,
                        'link' => $link,
                        'expires_at' => $reset_password->expires_at->format('d M Y (D) h:i a'),
                        'type' => 1,
                        "name" => $name,
                        'logo' => $logo,
                        'ho' => $ho
                    ]
                    ,
                    function ($message) use ($location, $ho) {
                        $message->to($location->email);
                        $message->subject(env('APP_NAME') . ' - Reset Password by ' . $ho->company_name);
                    }
                );


                return redirect()->back()->withInput()->with('success_message', "We have sent you a password reset link to " . $location->email . ". Don’t forget to check your spam");

            } else {
                return redirect()->back()->withInput()->withErrors(['error' => 'Not Found']);
            }


        } catch (Exception $e) {
            // dd($e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'unexpected_error caused please contact team support']);
        }


    }

    public function reset_password_view($type, $token, Request $request)
    {
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];
        $ho = null;
        if ($subdomain != 'qi-tech') {
            $ho = HeadOffice::where('link_token', $subdomain)->first();
        }
        $logo = isset($ho->logo) ? $ho->logo : asset('images/svg/logo_blue.png');

        $check = PasswordReset::where([['token', $token], ['expires_at', '>', Carbon::now()]])->first();
        if ($check) {
            return view('reset_password', compact('type', 'token', 'logo', 'ho'));
        } else {
            return redirect()->route('forgot_password')->withErrors(['error' => 'Reset password link has been expired']);

        }

    }
    public function forgot_password_view(Request $request)
    {
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];
        $ho = null;
        if ($subdomain != 'qi-tech') {
            $ho = HeadOffice::where('link_token', $subdomain)->first();
        }
        return view('forgot_password', compact('ho'));
    }
    public function reset_password_update(ResetPasswordFormRequest $request)
    {

        $data = $request->getData();


        $check = PasswordReset::where([['token', $data['token']], ['expires_at', '>', Carbon::now()]])->first();

        if (!isset($check)) {
            return redirect()->route('forgot_password')->withErrors(['error' => 'Reset password link has been expired']);
        } else if ($data['type'] == 1) {
            $data_user = Location::where('email', $check->email)->first();
        } else if ($data['type'] == 2) {
            $data_user = User::where('email', $check->email)->first();
        }

        if ($data_user) {
            $data_user->password = $data['password'];
            $data_user->save();
            $check->delete();
            return redirect('/app.html#!/login')->withErrors(['error' => '666']);
        } else {
            return redirect('/app.html#!/login')->withErrors(['error' => 'System Error. Kindly contact system administrator']);
        }


    }
    static function user_logout()
    {
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        $ho_u = isset($user) ? $user->getHeadOfficeUser() : null;
        if(isset($ho_u)){
            $ho_u->is_active = 0;
            $ho_u->save();
        }
        try {
            $l_user = $user->userLoginSessions->where('user_session', session('user_session'))->first();
            if ($l_user) {
                // $l_user->is_active = 0;
                // $l_user->save();
                $l_user->delete();
            }
        } catch (Exception $e) {
        }
        Auth::guard('web')->logout();
        return redirect('/app.html#!/login');
    }

    static function otp_logout()
    {
        $user = Auth::guard('user')->user() ?? Auth::guard('web')->user();
        if(!isset($user)){
            $user = Auth::guard('location')->user();
        }
        $user->logout();
        return redirect('/app.html#!/login');
    }

    public function clearRemoteAccessSession(Request $request)
    {
        $request->session()->forget('remote_access');

        return response()->json(['success' => true]);
    }

}