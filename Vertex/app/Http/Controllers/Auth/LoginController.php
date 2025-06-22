<?php

namespace App\Http\Controllers\Auth;

use Mail;
use App\User;
use Carbon\Carbon;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    // protected function redirectTo()
    // {
    //     if (Auth::user()->role === '5') {
    //         return redirect('/');
    //     } else {
    //         return '/home';
    //     }
    // }
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email',$request->email)->first();
        $remember_me = $request->has('remember_me') ? true : false;
        if($user) {
            if($user->role_id == 1 || $user->role_id > 1 && $user->expiry_date >= Carbon::now()->format('Y-m-d')){
                if (Auth::attempt(['email' => $request->email, 'password' => request('password')],$remember_me)) {
                        $msg = '"'.$user->fullname.'" login. IP: "'.request()->ip().'"';
                        createLog('login_action',$msg);

                    return redirect('/home')->with('success', 'Logged In Successfully!');
                }
            }else{
                return redirect()->back()->withInput()->withErrors(['errors'=>'Your license has expired. Please contact our support team for further assistance.']);
            }
        }
        return redirect()->back()->withInput()->withErrors(['errors'=>'Your email or password do not match. Please try again.']);
    }

    public function forgetPasswordForm(){
        return view('auth.forget_password');
    }

    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
        ]);
        // ,['email.exists'=>"We couldn't find an account associated with that email address."]
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $min = 1000; // Minimum 4-digit number (inclusive)
        $max = 9999; // Maximum 4-digit number (inclusive)
        $otp = random_int($min, $max);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $otp,
            'created_at' => Carbon::now()
        ]);

        Mail::send('email.forgetPassword', ['otp' => $otp], function($message) use($request){
            $emailServicesFromName = Setting::where('perimeter', 'smtp_from_name')->first();
            $emailServicesFromEmail = Setting::where('perimeter', 'smtp_from_email')->first();

            $message->from($emailServicesFromEmail->value,$emailServicesFromName->value);
            $message->to($request->email);
            $message->subject('Reset Password');
        });
        return redirect('forget-password')->with('success', 'An OTP to reset your password has been emailed to you.');
    }

    public function verifyPasswordOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verify_otp' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $otp = $request->verify_otp;
        $reset = DB::table('password_resets')->where(['token'=> $otp])->first();
        $email= '';
        if($reset && $reset->email){
            $email = $reset->email;

            $response['error']=false;
            $response['email']=$email;
            $response['OTP']=$otp;
            return $response;
        }else{
            $response['error']=true;
            $response['message']= 'You entered wrong OTP';
            return $response;
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|string|min:4|confirmed',
            'password_confirmation' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        if($user){
            $response['error']=false;
            $response['message']= 'Password Reset Successfully';
            return $response;
        }else{
            $response['error']=true;
            $response['message']= 'Password Not Reset';
            return $response;
        }
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
