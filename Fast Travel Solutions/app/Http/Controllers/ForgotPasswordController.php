<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\ForgetPassword;
use App\Models\NotificationManagement;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;




class ForgotPasswordController extends Controller
{
    // public function forgot_password(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required'

    //     ]);
    //     if ($validator->fails()) {
    //         $msg = $validator->errors()->first();
    //         $res['status'] = false;
    //         $res['message'] = $msg;

    //         return response()->json($res);
    //     }

    //     $update_pass = User::where('email', $request->email)->first();
    //     // return $update_pass;
    //     if (is_null($update_pass)) {

    //         $res['status'] = false;
    //         $res['message'] = "User Can't Exist";
    //         return response()->json($res);
    //     } else {

    //         $code = random_int(1000, 9999);

    //         $send_email = Mail::to($request->email)->send(new ForgetPassword($code));


    //         if ($send_email) {

    //             $update_pass = User::where('email',  $request->email)->first();
    //             $update_pass->otp = $code;
    //             $update_pass->save();

    //             $res['status'] = true;
    //             $res['message'] = "Otp Sent to your email";
    //             return response()->json($res);

    //         } else {
    //             $res['status'] = false;
    //             $res['message'] = "Techniqal Error!";
    //             return response()->json($res);
    //         }
    //     }
    // }
    public function forgot_password(Request $request) // optimize code
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        // Check if user with provided email exists
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => "User doesn't exist",
            ]);
        }

        // Generate a random code for OTP
        $password = random_int(1000, 9999);



        $checkMail = NotificationManagement::where('type', 'Password Reset')
            ->first();

        if ($checkMail->send_email == "Y") {


            $patterns = [
                '/\{(otp)}]?/',
            ];

            $replacements = [
                $password
            ];

            $email_body = preg_replace($patterns, $replacements, $checkMail->header. $checkMail->mail .$checkMail->footer);

            $setting_comm_email = Setting::where('parameter', 'smtp_from_email')->first();
            $setting_comm_email_name = Setting::where('parameter', 'smtp_from_name')->first();

            $to_email = $request->email;

            $email_subject = $checkMail->type;

            $queryUser = NotificationManagement::where('type', 'User Register Notification')
                ->where('user_type', 'LIKE', '%user%')
                ->exists();
            $queryCompany = NotificationManagement::where('type', 'User Register Notification')
                ->where('user_type', 'LIKE', '%company%')
                ->exists();
            $queryDriver = NotificationManagement::where('type', 'User Register Notification')
                ->where('user_type', 'LIKE', '%driver%')
                ->exists();
            $queryAdmin = NotificationManagement::where('type', 'User Register Notification')
                ->where('user_type', 'LIKE', '%admin%')
                ->exists();

            if ($queryUser) {


                try {

                    Mail::html($email_body, function ($message) use (
                        $setting_comm_email,
                        $setting_comm_email_name,
                        $to_email,
                        $email_subject
                    ) {
                        $message->from($setting_comm_email->value, $setting_comm_email_name->value)
                            ->to($to_email)
                            ->subject($email_subject);
                    });

                    // Check if there's an existing OTP record for the user
                    $existingOtp = DB::table('otps')
                        ->where('email', $request->email)
                        ->first();

                    if ($existingOtp) {
                        // Update the existing OTP record
                        DB::table('otps')
                            ->where('email', $request->email)
                            ->update([
                                'otp' => $password,
                                'expires_at' => Carbon::now()->addMinutes(60), // Update expiration to 10 minutes from now
                                'resend_attempts' => DB::raw('resend_attempts + 1') // Increment resend attempts
                            ]);
                    } else {
                        // Create a new OTP record
                        DB::table('otps')->insert([
                            'email' => $request->email,
                            'otp' => $password,
                            'expires_at' => Carbon::now()->addMinutes(60), // OTP expires in 10 minutes
                            'resend_attempts' => 0 // Initial resend attempts set to 0
                        ]);
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP sent to your email',
                    ]);
                } catch (\Exception $e) {
                    // Log the exception for debugging purposes
                    Log::error('Error sending email: ' . $e->getMessage());

                    return response()->json([
                        'status' => false,
                        'message' => 'Technical error! Please try again later.',
                    ]);
                }
            } else if ($queryCompany) {


                try {

                    Mail::html($email_body, function ($message) use (
                        $setting_comm_email,
                        $setting_comm_email_name,
                        $to_email,
                        $email_subject
                    ) {
                        $message->from($setting_comm_email->value, $setting_comm_email_name->value)
                            ->to($to_email)
                            ->subject($email_subject);
                    });

                    // Check if there's an existing OTP record for the user
                    $existingOtp = DB::table('otps')
                        ->where('email', $request->email)
                        ->first();

                    if ($existingOtp) {
                        // Update the existing OTP record
                        DB::table('otps')
                            ->where('email', $request->email)
                            ->update([
                                'otp' => $password,
                                'expires_at' => Carbon::now()->addMinutes(60), // Update expiration to 10 minutes from now
                                'resend_attempts' => DB::raw('resend_attempts + 1') // Increment resend attempts
                            ]);
                    } else {
                        // Create a new OTP record
                        DB::table('otps')->insert([
                            'email' => $request->email,
                            'otp' => $password,
                            'expires_at' => Carbon::now()->addMinutes(60), // OTP expires in 10 minutes
                            'resend_attempts' => 0 // Initial resend attempts set to 0
                        ]);
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP sent to your email',
                    ]);
                } catch (\Exception $e) {
                    // Log the exception for debugging purposes
                    Log::error('Error sending email: ' . $e->getMessage());

                    return response()->json([
                        'status' => false,
                        'message' => 'Technical error! Please try again later.',
                    ]);
                }
            } else if ($queryDriver) {


                try {

                    Mail::html($email_body, function ($message) use (
                        $setting_comm_email,
                        $setting_comm_email_name,
                        $to_email,
                        $email_subject
                    ) {
                        $message->from($setting_comm_email->value, $setting_comm_email_name->value)
                            ->to($to_email)
                            ->subject($email_subject);
                    });

                    // Check if there's an existing OTP record for the user
                    $existingOtp = DB::table('otps')
                        ->where('email', $request->email)
                        ->first();

                    if ($existingOtp) {
                        // Update the existing OTP record
                        DB::table('otps')
                            ->where('email', $request->email)
                            ->update([
                                'otp' => $password,
                                'expires_at' => Carbon::now()->addMinutes(60), // Update expiration to 10 minutes from now
                                'resend_attempts' => DB::raw('resend_attempts + 1') // Increment resend attempts
                            ]);
                    } else {
                        // Create a new OTP record
                        DB::table('otps')->insert([
                            'email' => $request->email,
                            'otp' => $password,
                            'expires_at' => Carbon::now()->addMinutes(60), // OTP expires in 10 minutes
                            'resend_attempts' => 0 // Initial resend attempts set to 0
                        ]);
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP sent to your email',
                    ]);
                } catch (\Exception $e) {
                    // Log the exception for debugging purposes
                    Log::error('Error sending email: ' . $e->getMessage());

                    return response()->json([
                        'status' => false,
                        'message' => 'Technical error! Please try again later.',
                    ]);
                }
            } else if ($queryAdmin) {

                try {

                    Mail::html($email_body, function ($message) use (
                        $setting_comm_email,
                        $setting_comm_email_name,
                        $to_email,
                        $email_subject
                    ) {
                        $message->from($setting_comm_email->value, $setting_comm_email_name->value)
                            ->to($to_email)
                            ->subject($email_subject);
                    });

                    // Check if there's an existing OTP record for the user
                    $existingOtp = DB::table('otps')
                        ->where('email', $request->email)
                        ->first();

                    if ($existingOtp) {
                        // Update the existing OTP record
                        DB::table('otps')
                            ->where('email', $request->email)
                            ->update([
                                'otp' => $password,
                                'expires_at' => Carbon::now()->addMinutes(60), // Update expiration to 10 minutes from now
                                'resend_attempts' => DB::raw('resend_attempts + 1') // Increment resend attempts
                            ]);
                    } else {
                        // Create a new OTP record
                        DB::table('otps')->insert([
                            'email' => $request->email,
                            'otp' => $password,
                            'expires_at' => Carbon::now()->addMinutes(60), // OTP expires in 10 minutes
                            'resend_attempts' => 0 // Initial resend attempts set to 0
                        ]);
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP sent to your email',
                    ]);
                } catch (\Exception $e) {
                    // Log the exception for debugging purposes
                    Log::error('Error sending email: ' . $e->getMessage());

                    return response()->json([
                        'status' => false,
                        'message' => 'Technical error! Please try again later.',
                    ]);
                }
            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'Please on email from notification settings',
                ]);

            }

        }

        // Attempt to send the email with OTP
        // try {
        //     Mail::to($request->email)->send(new ForgetPassword($password));

        //     // Update user record with OTP
        //     // $user->otp = $password;
        //     // $user->save();

        //     // Check if there's an existing OTP record for the user
        //     $existingOtp = DB::table('otps')
        //         ->where('email', $request->email)
        //         ->first();

        //     if ($existingOtp) {
        //         // Update the existing OTP record
        //         DB::table('otps')
        //             ->where('email', $request->email)
        //             ->update([
        //                 'otp' => $password,
        //                 'expires_at' => Carbon::now()->addMinutes(60), // Update expiration to 10 minutes from now
        //                 'resend_attempts' => DB::raw('resend_attempts + 1') // Increment resend attempts
        //             ]);
        //     } else {
        //         // Create a new OTP record
        //         DB::table('otps')->insert([
        //             'email' => $request->email,
        //             'otp' => $password,
        //             'expires_at' => Carbon::now()->addMinutes(60), // OTP expires in 10 minutes
        //             'resend_attempts' => 0 // Initial resend attempts set to 0
        //         ]);
        //     }
        //     return response()->json([
        //         'status' => true,
        //         'message' => 'OTP sent to your email',
        //     ]);
        // } catch (\Exception $e) {
        //     // Log the exception for debugging purposes
        //     Log::error('Error sending email: ' . $e->getMessage());

        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Technical Error! Please try again later.',
        //     ]);
        // }
    }
    public function check_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|digits:4',
            'email' => 'required|email',

        ]);

        if ($validator->fails()) {
            $msg = $validator->errors()->first();
            $res['status'] = false;
            $res['message'] = $msg;

            return response()->json($res);
        }

        // $user = User::where('email', $request->email)->where('otp', $request->otp)->first();

        $otpRecord = DB::table('otps')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>=', Carbon::now())
            ->first();

        if ($otpRecord) {
            $res['status'] = true;
            $res['message'] = 'OTP matched';
            $res['response'] = $otpRecord->otp;
        } else {
            $res['status'] = false;
            $res['message'] = 'Invalid OTP or expired';
        }

        return response()->json($res);
    }
    public function update_password(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|max:50',
            'email' => 'required',
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            $msg = $validator->errors()->first();
            $res['status'] = false;
            $res['message'] = $msg;

            return response()->json($res);
        }

        // $verify = User::where('email', $request->email)->where('otp', $request->otp)->first();

        $verify = DB::table('otps')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>=', Carbon::now())
            ->first();

        if ($verify) {

            $user = User::where('email', $request->email)->first();
            if ($user) {
                $user->password = bcrypt($request->password);
                $user->save();
            }
            $res['status'] = true;
            $res['message'] = "Password updated successfully!!";
            return response()->json($res);
        } else {

            $res['status'] = false;
            $res['message'] = 'Invalid OTP';
            return response()->json($res);
        }
    }
}
