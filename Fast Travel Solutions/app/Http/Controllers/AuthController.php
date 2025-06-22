<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Spatie\Permission\Contracts\Role;
use App\Traits\FileUploadTrait;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use FileUploadTrait;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'false',
                'message' => 'Invalid credentials',
            ]);
        }

        $user = Auth::user();

        if ($user->active_status == 1) {

            $roles = DB::table('roles')->where('id', $user->role_id)->first();
            $role_name = $roles->name;

            if($user->role_id == '2'){

                $company = Company::find($user->company_id);
                // $company = Company::where('user_id', $user->id)->first();

                $setting = Setting::where('parameter', 'set_hour_for_cancel_booking')->first();

                $company->cancel_booking_hours = $setting->value;

                return response()->json([
                    'status' => true,
                    'user' => $user,
                    'role' => $role_name,
                    'company_details' => $company,
                    'authorisation' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ]);

            } else {

                return response()->json([
                    'status' => true,
                    'user' => $user,
                    'role' => $role_name,
                    'authorisation' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ]);


            }

        } else {

            return response()->json([
                'status' => 'false',
                'message' => 'Your status in-active, please contact with admin!',
            ]);
        }
    }

    public function register(Request $request)
    {

        // return $request;
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP 
        }


        // if ($request->hasFile('profile_picture')) {

        //     $imagePath = $this->handleFileUpload($request->file('profile_picture'));

        // } else {

        //     $imagePath = NULL; 

        // }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password =  Hash::make($request->password);
        $user->role_id = 3;
        $user->save();

        // $user->assignRole('Admin');

        // Generate JWT token
        $token = JWTAuth::fromUser($user);

        $roles = DB::table('roles')->where('id', $user->role_id)->first();
        $role_name = $roles->name;

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'user' => User::find($user->id),
            'role' => $role_name,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);

        // return response()->json([
        //     'status' => true,
        //     'message' => 'User created successfully',
        //     'user' => User::find($user->id),
        // ]);
    }

    public function logout_user()
    {

        $user = Auth::user();
        //  return $user;

        if ($user) {
            Auth::logout();

            return response()->json([
                'status' => true,
                'message' => 'Successfully logged out',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated',
            ]);
        }
    }


    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function profile(Request $request)
    {
        $auth = Auth::user();

        if (($auth->role_id == 3 || $auth->role_id == 1) && $auth->id == $request->user_id) {

            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP 
            }

            $user = User::find($request->user_id);

            if ($user) {

                return response()->json([

                    'status' => true,
                    'message' => 'User!!',
                    'response' => $user,
                ]);
            } else {

                return response()->json([
                    'status' => true,
                    'message' => 'User not found!!',
                ]);
            }
        } else {

            return response()->json([
                'status' => false,
                'message' => 'You have not permission to see others profile',
            ]);
        }
    }

    public function edit_profile(Request $request)
    {
        $auth = Auth::user();

        if (($auth->role_id == 3 || $auth->role_id == 1) && ($auth->id == $request->user_id || $auth->role_id == 1)) {

            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP 
            }

            $user = User::find($request->user_id);
            $user->name = $request->name;
            if ($request->hasFile('profile_picture')) {

                $user->profile_picture = $this->handleFileUpload($request->file('profile_picture'));
            }
            $user->phone = $request->phone;
            if ($request->password) {

                $user->password = Hash::make($request->password);
            }
            $user->save();


            return response()->json([

                'status' => true,
                'message' => 'User updated successfully!!',
                'response' => $user,
            ]);
        } else {

            return response()->json([
                'status' => false,
                'message' => 'You have not permission to Edit Others Profile',
            ]);
        }
    }
}
