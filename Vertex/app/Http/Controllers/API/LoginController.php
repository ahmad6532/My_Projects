<?php

namespace App\Http\Controllers\API;


use App\User;
use Carbon\Carbon;
use App\Models\Location;
use App\Models\Setting;
use App\Models\Designation;
use App\Models\UserDevice;
use App\Models\user_approval;
use App\Models\PlatformVersion;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EmployeeDetail;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Role;
use App\Http\Controllers\API\BaseController as BaseController;
use DateTime;
use App\Traits\ProfileImage;

class LoginController extends BaseController
{
    use HasApiTokens;
    use ProfileImage;
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success, 'User register successfully.');
    }

    // public function adminLogin(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email|exists:users,email',
    //         'password' => 'required',
    //     ]);
    //     if ($validator->fails()) {
    //         $validator = $validator->errors()->first();
    //         return $this->sendError('Validation Error.', $validator);
    //     }
    //     $user = [];
    //     if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    //         if (Auth::user()->is_active == 1) {
    //             $user = Auth::user();
    //             $user_role = $user->role_id;
    //             $user_company_id = explode(',', $user->company_id);
    //             $user_branch_id = explode(',', $user->branch_id);

    //             $branchDetail = [];
    //             if ($user_role == '1') {
    //                 $branches = Location::get();
    //                 foreach ($branches as $branch) {
    //                     $branchDetail[] = $branch->branch_name;
    //                 }
    //             } else {
    //                 $branches = Location::whereIn('id', $user_branch_id)->get();
    //                 foreach ($branches as $branch) {
    //                     $branchDetail[] = $branch->branch_name;
    //                 }
    //             }

    //             $emp_detail = EmployeeDetail::where('id', $user->emp_id)->first();
    //             if ($emp_detail) {
    //                 $emp_approval = user_approval::where('emp_id', $emp_detail->id)->first();
    //                 if ($emp_approval) {
    //                     $designation_detail = Designation::where('id', $emp_approval->designation_id)->first();
    //                 }
    //             }

    //             $user['id'] =  $user->id;
    //             $user['designation'] =  isset($designation_detail) ? $designation_detail->name : null;
    //             $user['image'] = $user->image ? asset($user->image) : null;
    //             $user['email'] =  $user->email;
    //             $user['token'] =  $user->createToken('MyApp')->accessToken;

    //             return $this->sendResponse($user, 'User login successfully.');
    //         } else {
    //             return $this->sendResponse([], 'Please contact the HR for assistance.');
    //         }
    //     } else {
    //         return $this->sendError('Unauthorised.', 'Password is incorrect');
    //     }
    // }


    // public function adminLogin(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email|exists:users,email',
    //         'password' => 'required',
    //     ]);
    //     //dd('ok');

    //     if ($validator->fails()) {
    //         $validator = $validator->errors()->first();
    //         return $this->sendError('Validation Error.', $validator);
    //     }

    //     $user = [];
    //     if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    //         if (Auth::user()->is_active == 1) {
    //             $user = Auth::user();
    //            // dd($user);
    //             $user_role_id = $user->role_id;

    //             // Fetch all permissions associated with the user's role_id
    //             $role = Role::find($user_role_id);
    //             $permissions = $role ? $role->permissions->pluck('name') : [];

    //             $user['id'] = $user->id;
    //             $user['role_id'] = $user_role_id;
    //             $user['permissions'] = $permissions;
    //             $user['email'] = $user->email;
    //             $user['token'] = $user->createToken('MyApp')->accessToken;

    //             return $this->sendResponse($user, 'User login successfully.');
    //         } else {
    //             return $this->sendResponse([], 'Please contact the HR for assistance.');
    //         }
    //     } else {
    //         return $this->sendError('Unauthorised.', 'Password is incorrect');
    //     }
    // }

    public function adminLogin(Request $request)
    {
        $messages = [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'Email does not exist .',
            'password.required' => 'Password is required.',
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ], $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();

            if ($errors->has('email')) {
                $emailError = $errors->first('email');
                return $this->sendError('Validation Error.', $emailError);
            }

            return $this->sendError('Validation Error.', $errors->first());
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $employeeImage = DB::table('users')
                ->leftJoin('employee_details', 'employee_details.id', '=', 'users.emp_id')
                ->select('employee_details.emp_image')
                ->where('users.id', '=', $user->id)
                ->first();

            if ($employeeImage && isset($employeeImage->emp_image)) {
                $employeeImageString = (string) $employeeImage->emp_image;
                $userImage = $this->imgFunc($employeeImageString, $user->gender);
            } else {
                $employeeImageString = '';
                $userImage = $this->imgFunc($employeeImageString, $user->gender);
            }
            // dd($employeeImageString);
            $user['user_image'] = $userImage;

            if ($user->is_active == 1) {
                $user_role_id = $user->role_id;

                $role = Role::find($user_role_id);
                $permissions = $role ? $role->permissions->pluck('name') : [];

                $user['permissions'] = $permissions;
                $user['token'] = $user->createToken('MyApp')->accessToken;
                $msg = $user->fullname . " Login Successfully.";
                createLog('user-action', $msg);
                return $this->sendResponse($user, 'User login successfully.');
            } else {
                return $this->sendResponse([], 'Please contact the HR for assistance.');
            }
        } else {
            return $this->sendError('Unauthorised.', 'Password is incorrect');
        }
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $validator = $validator->errors()->first();
            return $this->sendError('Validation Error.', $validator);
        }
        $platform = $request['platform'];
        $platformData = [
            'platform' => $platform,
            'app_version' => $request->version
        ];
        $user = [];
        $version = $this->verify($platformData);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_active' => 1])) {
            if (Auth::user()->is_active == 1) {
                $user = Auth::user();
                $user_role = $user->role_id;
                $user_company_id = explode(',', $user->company_id);
                $user_branch_id = explode(',', $user->branch_id);

                $this->addUserDeviceInfo($request, auth()->user()->id);
                $branchDetail = [];
                if ($user_role == '1') {
                    $branches = Location::get();
                    foreach ($branches as $branch) {
                        $branchDetail[] = $branch->branch_name;
                    }
                } else {
                    $branches = Location::whereIn('id', $user_branch_id)->get();
                    foreach ($branches as $branch) {
                        $branchDetail[] = $branch->branch_name;
                    }
                }

                $fingerprint = '';
                $designation = '';
                $emp_id = '';
                $emp_pin = '';

                $emp_detail = EmployeeDetail::where('id', $user->emp_id)->first();
                if ($emp_detail) {
                    $fingerprint = $emp_detail->fingerprint;
                    if ($fingerprint == null) {
                        $user->can_update_face = '1';
                    }
                    $emp_id = (string) $emp_detail['emp_id'];
                    $emp_pin = (string) $emp_detail['attend_pin'];

                    $emp_approval = user_approval::where('emp_id', $emp_detail->id)->first();
                    if ($emp_approval) {
                        $designation_detail = Designation::where('id', $emp_approval->designation_id)->first();
                        $designation = $designation_detail->name;
                    }
                }

                $user['id'] = $user->id;
                $user['fingerPrint'] = $fingerprint;
                $user['can_update_face'] = $user->can_update_face;
                $user['designation'] = $designation;
                $user['emp_id'] = $emp_id;
                $user['emp_pin'] = $emp_pin;
                $user['image'] = $user->image ? asset($user->image) : null;
                $user['image'] = $this->imgFunc($emp_detail->emp_image, $emp_detail->emp_gender);
                $user['email'] = $user->email;
                $user['is_attendance_allowed'] = (string) $user->is_attendance_allowed;
                $user['token'] = $user->createToken('MyApp')->accessToken;
                $user['branch_name'] = $branchDetail;
                $user['phone'] = strval($user['phone']);

                if ($version['success'] != 1) {
                    return response()->json([
                        'status' => $version['success'],
                        'message' => $version['message'],
                        'details' => $user
                    ]);
                }

                return $this->sendResponse($user, 'User login successfully.');
            } else {
                return $this->sendResponse([], 'Please contact the HR for assistance.');
            }
        } else {
            return $this->sendError('Unauthorised.', 'Password is incorrect ');
        }
    }

    public function verify($request)
    {
        $response = array();
        $platform = strtolower($request['platform']);
        $version = $request['app_version'];
        $version = str_replace(".", "", $version);

        $data = PlatformVersion::where('platform', $platform)->first();
        if ($data) {
            $min_optional = $data['min_optional'];
            $min_optional = str_replace(".", "", $min_optional);
            $max_optional = $data['max_optional'];
            $max_optional = str_replace(".", "", $max_optional);
            $min_force = $data['min_force'];
            $min_force = str_replace(".", "", $min_force);
            $max_force = $data['max_force'];
            $max_force = str_replace(".", "", $max_force);
            if ($version <= $max_force && $version >= $min_force) {
                $response['success'] = -5;
                $response['message'] = "Please update app to latest version";
                return $response;
            } elseif ($version <= $max_optional && $version >= $min_optional) {
                $response['success'] = -4;
                $response['message'] = "Please update app to latest version";
                return $response;
            } else {
                $response['success'] = 1;
                $response['message'] = "Login Successful";
                return $response;
            }
        }
    }

    public function addUserDeviceInfo(Request $request, $userId)
    {
        if ($request->token != 'need_to_define_lib_in_flutter_app') {
            $userDeviceInfo = UserDevice::updateOrCreate(
                [
                    "serial" => $request->serial
                ],
                [
                    "user_id" => $userId,
                    "model" => $request->model,
                    "version" => $request->version,
                    "platform" => $request->platform,
                    "serial" => $request->serial,
                    "uuid" => null,
                    "app_version" => $request->version,
                    "token" => $request->token,
                    "status" => 'A',
                    "entry_date" => Carbon::now(),
                    "manufacturer" => $request->manufacturer
                ]
            );
            return $userDeviceInfo;
        }
    }

    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => "We couldn't find an account associated with that email address.",
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first();
            return $this->sendError('Validation Error.', $errors);
        }
        DB::beginTransaction();
        try {
            $min = 1000; // Minimum 4-digit number (inclusive)
            $max = 9999; // Maximum 4-digit number (inclusive)
            $otp = random_int($min, $max);
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $otp,
                'created_at' => Carbon::now(),
                'expire_at' => Carbon::now()->addMinutes(1),
            ]);

            Mail::send('email.forgetPassword', ['otp' => $otp], function ($message) use ($request) {
                $emailServicesFromName = Setting::where('perimeter', 'smtp_from_name')->first();
                $emailServicesFromEmail = Setting::where('perimeter', 'smtp_from_email')->first();

                $message->from($emailServicesFromEmail->value, $emailServicesFromName->value);
                $message->to($request->email);
                $message->subject('Reset Password');
            });
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $response['error'] = true;
            $response['message'] = $e->getMessage();
            $response['status'] = 500;
            return response()->json($response);
        }

        return $this->sendResponse('message', 'An OTP to reset your password has been emailed to you.');
    }

    public function verifyPasswordOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verify_otp' => 'required',
            'email' => 'required|email', // Adding validation for email
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        try {
            DB::beginTransaction();

            $otp = $request->verify_otp;
            $reset = DB::table('password_resets')->where('email', $request->email)->where('token', $otp)->first();

            if (!empty($reset)) {
                $currentDateTime = Carbon::now();
                $diffInMinutes = $currentDateTime->diffInMinutes($reset->expire_at);

                if ($diffInMinutes > 1) {
                    DB::table('password_resets')->where('email', $request->email)->where('token', $otp)->delete();
                    $response['error'] = true;
                    $response['message'] = 'Your OTP has expired. Please click on Resend OTP to receive a new one.';
                    return response()->json($response, 400);
                } else {
                    $response['error'] = false;
                    $response['email'] = $request->email;
                    $response['message'] = 'Your ' . $otp . ' OTP matched successfully!';
                    $response['OTP'] = $otp;
                    return $response;
                }
            } else {
                $response['error'] = true;
                $response['message'] = 'The OTP you provided is invalid or has expired. Please click on Resend OTP to receive a new one.';
                return response()->json($response, 400);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $response['error'] = true;
            $response['message'] = $e->getMessage();
            $response['status'] = 500;
            return response()->json($response, 500);
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

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        if ($user) {
            $response['error'] = false;
            $response['message'] = 'Password Reset Successfully';
            return $response;
        } else {
            $response['error'] = true;
            $response['message'] = 'Password Not Reset';
            return $response;
        }
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $request->user()->tokens()->delete();
            return $this->sendResponse(['message' => 'Logout successfully!'], 200);
        } else {
            return $this->sendResponse(['message' => 'Logout failed'], 403);
        }
    }

    public function userLogout(Request $request)
    {
        if (Auth::check()) {
            $request->user()->tokens()->delete();
            return $this->sendResponse(['message' => 'Logout successfully!'], 200);
        } else {
            return $this->sendResponse(['message' => 'Logout failed'], 403);
        }
    }
}
