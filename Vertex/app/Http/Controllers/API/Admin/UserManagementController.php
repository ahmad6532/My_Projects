<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\EmployeeDetail;
use App\Traits\ProfileImage;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Location;
use Illuminate\Support\Facades\Validator;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
// use Spatie\Permission\Models\Role as SpatieRoleModel;

class UserManagementController extends BaseController
{
    use ProfileImage;
    // public function usersList(Request $request)
    // {
    //     //user information
    //     $user = auth()->user();
    //     $user_role = $user->role_id;
    //     $user_company_id = explode(',',$user->company_id);
    //     $user_branch_id = explode(',',$user->branch_id);
    //     $searchBy = $request->search_by;
    //     $status = $request->status;
    //     // $query = User::query();
    //     if ($user_role == 1) {
    //         $users = User::select('id', 'fullname', 'is_active', 'role_id', 'branch_id')->where(function ($query) use ($searchBy, $status) {
    //             $query->orWhere('fullname', 'LIKE', '%' . $searchBy . '%');
    //         })->where('is_deleted', '0')->orderBy('id', 'desc')->orWhere('is_active', $status)->get();
    //     } else {
    //         $users = User::select('id', 'fullname', 'is_active', 'role_id', 'branch_id')->where(function ($query) use ($searchBy, $status) {
    //             $query->orWhere('fullname', 'LIKE', '%' . $searchBy . '%');
    //         })->whereIn('company_id', $user_company_id)
    //         ->whereIn('branch_id', $user_branch_id)->where('is_deleted', '0')->orWhere('is_active', $status)->orderBy('id', 'desc')->paginate(20);
    //     }

    //     foreach($users as $user){
    //         $branch_id = explode(',', $user->branch_id);
    //         $branches = Location::whereIn('id',$branch_id)->pluck('branch_name')->implode(', ');
    //         $user->branch_names = $branches;
    //         $user->role_name = role::where('id',$user->role_id)->first()['role_name'];
    //     }

    //     if(count($users) > 0){
    //         return $this->sendResponse($users,'User list fetched successfully!',200);
    //     }else{
    //         return $this->sendResponse($users,'Data not found!',200);
    //     }
    // }

    public function usersList(Request $request)
    {
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);
        $searchBy = $request->search_by;
        $status = $request->status;
        $perPage = $request->per_page ?? 10;

        $query = User::select('id', 'fullname', 'is_active', 'role_id', 'branch_id')
            ->where('is_deleted', '0');

        if ($user_role == 1) {
            $query->where('fullname', 'LIKE', '%' . $searchBy . '%')
                ->orWhere('is_active', $status);
        } else {
            $query->whereIn('company_id', $user_company_id)
                ->whereIn('branch_id', $user_branch_id)
                ->where('fullname', 'LIKE', '%' . $searchBy . '%')
                ->orWhere('is_active', $status);
        }

        $users = $query->orderBy('id', 'desc')->paginate($perPage);

        foreach ($users as $user) {
            $branch_id = explode(',', $user->branch_id);
            $branches = Location::whereIn('id', $branch_id)->pluck('branch_name')->implode(', ');
            $user->branch_names = $branches;
            $user->role_name = Role::where('id', $user->role_id)->value('role_name');
        }

        if ($users->count() > 0) {
            return $this->sendResponse($users, 'User list fetched successfully!', 200);
        } else {
            return $this->sendResponse([], 'No users found!', 404);
        }
    }






    // public function saveUser(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'company_id' => 'required',
    //         // 'company_id.*' => 'required|exists:companies,id',
    //         'branch_id' => 'required',
    //         // 'branch_id.*' => 'required|exists:locations,id',
    //         'user_name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'user_password' => 'required|string|min:8',
    //         'expiry_date' => 'required|date',
    //         'role_id' => 'required|exists:roles,id',
    //     ]);
    //     if ($validator->fails()) {
    //         return $this->sendError([], $validator->errors(), 400);
    //     }

    //     $companyIds = $request->company_id;
    //     $branchIds = $request->branch_id;
    //     if (in_array('all', $companyIds)) {
    //         $companyIds = Company::where('is_active', 1)
    //             ->where('is_deleted', 0)
    //             ->pluck('id')
    //             ->toArray();
    //     }

    //     if (in_array('all', $branchIds)) {
    //         $branchIds = Location::where('is_deleted', 0)
    //         ->whereIn('company_id', $companyIds)
    //             ->pluck('id')
    //             ->toArray();
    //     }

    //     $user = User::create([
    //         'role_id' => $request->role_id,
    //         'branch_id' => implode(',', $branchIds),
    //         'company_id' => implode(',', $companyIds),
    //         'email' => $request->email,
    //         'fullname' => ucwords($request->user_name),
    //         'password' => Hash::make($request->user_password),
    //         'is_active' => $request->input('user_status'),
    //         'is_pin_enable' => $request->input('user_pin_status'),
    //         'can_update_face' => $request->input('user_face_status'),
    //         'is_attendance_allowed' => $request->input('is_app_attendance_allowed'),
    //         'expiry_date' => Carbon::parse($request->expiry_date)->format('Y-m-d')
    //     ]);
    //     if ($user) {
    //         $msg = 'Added "' . $user->fullname . '"';
    //         createLog('user_action', $msg);
    //         $user->role_name = role::where('id',$request->role_id)->first()['role_name'];
    //         // $user->syncRoles([$user->role_id]);
    //         return $this->sendResponse($user, 'User saved successfully!', 200);
    //     } else {
    //         return $this->sendError([], 'User not created successfully!', 500);
    //     }
    // }

    public function saveUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            // 'company_id.*' => 'required|exists:companies,id',
            'branch_id' => 'required',
            // 'branch_id.*' => 'required|exists:locations,id',
            'user_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'user_password' => 'required|string|min:8',
            'expiry_date' => 'required|date',
            'role_id' => 'required|exists:roles,id',
            'user_id' => 'sometimes|required|exists:users,id', // Validate user_id if provided
        ]);

        if ($validator->fails()) {
            return $this->sendError([], $validator->errors(), 400);
        }

        $companyIds = $request->company_id;
        $branchIds = $request->branch_id;
        if (in_array('all', $companyIds)) {
            $companyIds = Company::where('is_active', 1)
                ->where('is_deleted', 0)
                ->pluck('id')
                ->toArray();
        }

        if (in_array('all', $branchIds)) {
            $branchIds = Location::where('is_deleted', 0)
                ->whereIn('company_id', $companyIds)
                ->pluck('id')
                ->toArray();
        }

        // Determine if it's an update or create operation
        if ($request->has('user_id')) {
            // Update existing user
            $user = User::find($request->user_id);
            if (!$user) {
                return $this->sendError([], 'User not found!', 404);
            }

            $user->role_id = $request->role_id;
            $user->branch_id = implode(',', $branchIds);
            $user->company_id = implode(',', $companyIds);
            $user->email = $request->email;
            $user->fullname = ucwords($request->user_name);
            $user->password = Hash::make($request->user_password);
            $user->is_active = $request->input('user_status');
            $user->is_pin_enable = $request->input('user_pin_status');
            $user->can_update_face = $request->input('user_face_status');
            $user->is_attendance_allowed = $request->input('is_app_attendance_allowed');
            $user->expiry_date = Carbon::parse($request->expiry_date)->format('Y-m-d');

            if ($user->save()) {
                $msg = 'Updated "' . $user->fullname . '"';
                createLog('user_action', $msg);
                $user->role_name = Role::where('id', $request->role_id)->first()['role_name'];
                return $this->sendResponse($user, 'User updated successfully!', 200);
            } else {
                return $this->sendError([], 'Failed to update user!', 500);
            }
        } else {
            // Create new user
            $user = User::create([
                'role_id' => $request->role_id,
                'branch_id' => implode(',', $branchIds),
                'company_id' => implode(',', $companyIds),
                'email' => $request->email,
                'fullname' => ucwords($request->user_name),
                'password' => Hash::make($request->user_password),
                'is_active' => $request->input('user_status'),
                'is_pin_enable' => $request->input('user_pin_status'),
                'can_update_face' => $request->input('user_face_status'),
                'is_attendance_allowed' => $request->input('is_app_attendance_allowed'),
                'expiry_date' => Carbon::parse($request->expiry_date)->format('Y-m-d')
            ]);

            if ($user) {
                $msg = 'Added "' . $user->fullname . '"';
                createLog('user_action', $msg);
                $user->role_name = Role::where('id', $request->role_id)->first()['role_name'];
                return $this->sendResponse($user, 'User saved successfully!', 200);
            } else {
                return $this->sendError([], 'User not created successfully!', 500);
            }
        }
    }

    public function editProfile()
    {
        $user = auth()->user();
        $empData = EmployeeDetail::where('id', $user->emp_id)->first();
        if ($empData) {
            $data = [
                'id' => $empData->id,
                'user_name' => $user->fullname,
                'company' => $empData->company->company_name,
                'designation' => $empData->approval->designation->name,
                'email' => $user->email,
                'contact' => $user->phone,
                'user_image' => $this->imgFunc($empData->emp_image, $empData->emp_gender)
            ];
            return response()->json([
                'status' => true,
                'success' => 1,
                'response' => $data
            ]);
        } else {
            return response()->json([
                'status' => false,
                'success' => 0,
                'message' => 'Data not found'
            ]);
        }
    }
    public function destroyUser(Request $request)
    {
        $id = $request->user_id;
        $delUser = User::findOrFail($id);
        $delUser->is_deleted = '1';
        $delUser->update();

        $msg = 'Deleted to "' . $delUser->fullname . '"';
        createLog('user_action', $msg);
        if ($delUser) {
            return $this->sendResponse([], 'User delete successfully!', 200);
        } else {
            return $this->sendResponse([], 'Data not found!', 200);
        }
    }

    // public function editUserDetails(Request $request)
    // {

    //     $id = $request->user_id;
    //     //user information
    //     $user = auth()->user();
    //     $user_role = $user->role_id;
    //     $user_company_id = explode(',',$user->company_id);
    //     $user_branch_id = explode(',',$user->branch_id);

    //     if ($user_role == 1) {
    //         $roles = role::orderBy('role_name','asc')->get();
    //         $companies = Company::orderBy('company_name','asc')->get();
    //         $branches = Location::where('is_deleted', '0')->orderBy('branch_name','asc')->get();
    //     } else {
    //         $roles = role::where('user_id', $user->id)->get();
    //         $companies = Company::whereIn('id', $user_company_id)->orderBy('company_name','asc')->get();
    //         $branches = Location::whereIn('company_id', $user_company_id)
    //             ->where('is_deleted', '0')
    //             ->orderBy('branch_name','asc')
    //             ->get();
    //     }

    //     $Userdetails = User::findOrFail($id);
    //     $data['user_data'] = $Userdetails;
    //     $data['roles'] = $roles;
    //     if($data){
    //         return $this->sendResponse($data,'User fetched successfully!',200);
    //     }else{
    //         return $this->sendResponse($data,'Data not found!',200);
    //     }
    // }

    // public function editUserDetails(Request $request)
    // {
    //     $emp_id = $request->employee_id;
    //     $user = auth()->user();
    //     $user_role = $user->role_id;
    //     $user_company_id = explode(',', $user->company_id);
    //     $user_branch_id = explode(',', $user->branch_id);

    //     $Userdetails = User::where('emp_id', $emp_id)->first();

    //     if ($Userdetails) {
    //         $role_id = $Userdetails->role_id;
    //         if ($user_role == 1) {
    //             $roles = Role::orderBy('role_name', 'asc')->get();
    //             $companies = Company::orderBy('company_name', 'asc')->get();
    //             $branches = Location::where('is_deleted', '0')->orderBy('branch_name', 'asc')->get();
    //         } else {
    //             $roles = Role::where('user_id', $user->id)->get();
    //           //  dd($roles);
    //             $companies = Company::whereIn('id', $user_company_id)->orderBy('company_name', 'asc')->get();
    //             $branches = Location::whereIn('company_id', $user_company_id)
    //                 ->where('is_deleted', '0')
    //                 ->orderBy('branch_name', 'asc')
    //                 ->get();
    //         }

    //         $data['user_data'] = $Userdetails;
    //         $data['role_id'] = $role_id;

    //         // $data['roles'] = $roles;
    //         // $data['companies'] = $companies;
    //         // $data['branches'] = $branches;

    //         return $this->sendResponse($data, 'User fetched successfully!', 200);
    //     } else {
    //         return $this->sendResponse([], 'Data not found!', 200);
    //     }
    // }


    public function editUserDetails(Request $request)
    {
        $emp_id = $request->employee_id;
        $user = auth()->user();
        $user_role = $user->role_id;

        // Convert comma-separated strings to arrays
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);

        $Userdetails = User::where('emp_id', $emp_id)->first();
        if ($Userdetails) {
            $empData = EmployeeDetail::where('id', $Userdetails->emp_id)->first();
            $role_id = $Userdetails->role_id;

            // Fetch roles, companies, and branches based on user role
            if ($user_role == 1) {
                $roles = Role::orderBy('role_name', 'asc')->skip(1)->take(PHP_INT_MAX)->get();
                $companies = Company::orderBy('company_name', 'asc')->get();
                $branches = Location::where('is_deleted', '0')->orderBy('branch_name', 'asc')->get();
            } else {
                $roles = Role::orderBy('role_name', 'asc')->skip(1)->take(PHP_INT_MAX)->get();
                $companies = Company::whereIn('id', $user_company_id)->orderBy('company_name', 'asc')->get();
                $branches = Location::whereIn('company_id', $user_company_id)
                    ->where('is_deleted', '0')
                    ->orderBy('branch_name', 'asc')
                    ->get();
            }

            // Convert the comma-separated string fields in Userdetails to arrays
            $Userdetails->company_id = array_map('intval', explode(',', $Userdetails->company_id));
            $Userdetails->branch_id = array_map('intval', explode(',', $Userdetails->branch_id));

            $data['user_data'] = $Userdetails;
            $data['role_id'] = $role_id;
            $data['pin'] = $empData->attend_pin;

            // Uncomment if needed
            // $data['roles'] = $roles;
            // $data['companies'] = $companies;
            // $data['branches'] = $branches;

            return $this->sendResponse($data, 'User fetched successfully!', 200);
        } else {
            return $this->sendResponse([], 'Data not found!', 200);
        }
    }




    // public function updateUser(Request $request)  // user update
    // {
    //     $id = $request->user_id;
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|unique:users,email,' . $request->id,
    //         'expiry_date' => 'required',
    //         'branch_id' => 'required',
    //         'company_id' => 'required',
    //         'user_role_id' => 'required',
    //     ]);
    //     if ($validator->fails()) {
    //         return $this->sendError([],$validator->errors(),400);
    //     }

    //     $company_id = $request->company_id;
    //     $companyString = implode(',', $company_id);

    //     $branch_id = $request->branch_id;
    //     $branchString = implode(',', $branch_id);

    //     $user = User::where('id', $id)->first();
    //     $user->role_id = $request->user_role_id;
    //     $user->branch_id = $branchString;
    //     $user->company_id = $companyString;

    //     if (!empty($request->email)) {
    //         $user->email = $request->email;
    //     }
    //     $user->fullname = $request->user_name;
    //     if (!empty($request->new_password)) {
    //         $newpass = Hash::make($request->new_password);
    //     } else {
    //         $newpass = $user->password;
    //     }
    //     $user->password = $newpass;
    //     $user->is_active = $request->input('user_status');
    //     $user->is_pin_enable = $request->input('user_pin_status');
    //     $user->can_update_face = $request->input('user_face_status');
    //     $user->is_attendance_allowed = $request->input('is_app_attendance_allowed');
    //     $user->expiry_date = Carbon::parse($request->expiry_date)->format('Y-m-d');
    //     $user->update();

    //     DB::table('model_has_roles')
    //         ->where('model_id', $id)
    //         ->update([
    //             'role_id' => $request->user_role_id,
    //         ]);

    //     $msg = 'updated "'.$user->fullname.'"';
    //     createLog('user_action',$msg);
    //     if($user){
    //         return $this->sendResponse($user,'User update successfully!',200);
    //     }else{
    //         return $this->sendResponse($user,'Data not found!',200);
    //     }
    // }


    // public function updateUser(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $emp_id = $request->employee_id;

    //         // $validator = Validator::make($request->all(), [
    //         //     'email' => 'required|email|unique:users,email,' . $request->id,
    //         //     'expiry_date' => 'required|date',
    //         //     'branch_id' => 'required|array',
    //         //     'company_id' => 'required|array',
    //         //     'user_role_id' => 'required|exists:roles,id',
    //         //     'new_password' => 'nullable|min:6', // Add any password validation rules here
    //         // ]);

    //         // if ($validator->fails()) {
    //         //     return $this->sendError([], $validator->errors(), 400);
    //         // }



    //         $companyString = implode(',', $request->company_id);
    //         $branchString = implode(',', $request->branch_id);

    //         // $companyString = $request->company_id;
    //         // $branchString = $request->branch_id;


    //         $user = User::where('emp_id', $emp_id)->first();

    //         if ($user) {
    //             // Update existing user
    //             $user->role_id = $request->user_role_id;
    //             $user->branch_id = $branchString;
    //             $user->company_id = $companyString;

    //             if (!empty($request->email)) {
    //                 $user->email = $request->email;
    //             }

    //             if (!empty($request->new_password)) {
    //                 $user->password = Hash::make($request->new_password);
    //             }

    //             $user->is_active = $request->input('user_status');
    //             $user->is_pin_enable = $request->input('user_pin_status');
    //             $user->can_update_face = $request->input('user_face_status');
    //             $user->is_attendance_allowed = $request->input('is_app_attendance_allowed');
    //             $user->expiry_date = Carbon::parse($request->expiry_date)->format('Y-m-d');
    //             $user->update();

    //             // Update user's role using pivot table
    //             DB::table('model_has_roles')
    //                 ->where('model_id', $user->id)
    //                 ->update([
    //                     'role_id' => $request->user_role_id,
    //                 ]);

    //             // $msg = 'updated "' . $user->fullname . '"';
    //             // createLog('user_action', $msg);

    //             DB::commit();

    //             return $this->sendResponse($user, 'User updated successfully!', 200);
    //         } else {
    //             // Create new user
    //             $user = User::create([
    //                 'emp_id' => $emp_id,
    //                 'role_id' => $request->user_role_id,
    //                 'branch_id' => $branchString,
    //                 'company_id' => $companyString,
    //                 'email' => $request->email,
    //                 'password' => !empty($request->new_password) ? Hash::make($request->new_password) : '',
    //                 'is_active' => $request->input('user_status'),
    //                 'is_pin_enable' => $request->input('user_pin_status'),
    //                 'can_update_face' => $request->input('user_face_status'),
    //                 'is_attendance_allowed' => $request->input('is_app_attendance_allowed'),
    //                 'expiry_date' => Carbon::parse($request->expiry_date)->format('Y-m-d'),
    //             ]);

    //             // Assign role to new user using pivot table
    //              $user->assignRole($request->user_role_id);

    //             // $msg = 'created "' . $user->fullname . '"';
    //             // createLog('user_action', $msg);

    //             DB::commit();

    //             return $this->sendResponse($user, 'User created successfully!', 201);
    //         }
    //     } catch (\Exception $e) {
    //         DB::rollback();

    //         return $this->sendError([], $e->getMessage(), 500); // 500 for internal server error
    //     }
    // }


    // public function updateUser(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $emp_id = $request->employee_id;

    //         // $validator = Validator::make($request->all(), [
    //         //     'email' => 'required|email|unique:users,email,' . $request->id,
    //         //     'expiry_date' => 'required|date',
    //         //     'branch_id' => 'required|array',
    //         //     'company_id' => 'required|array',
    //         //     'user_role_id' => 'required|exists:roles,id',
    //         //     'new_password' => 'nullable|min:6',
    //         // ]);

    //         // if ($validator->fails()) {
    //         //     return $this->sendError([], $validator->errors(), 400);
    //         // }

    //         // Check if the role exists
    //         $role = DB::table('roles')->where('id', $request->user_role_id)->first();
    //         if (!$role) {
    //             return $this->sendError([], 'The specified role does not exist.', 400);
    //         }

    //         $companyString = implode(',', $request->company_id);
    //         $branchString = implode(',', $request->branch_id);


    //         $user = User::where('emp_id', $emp_id)->first();

    //         if ($user) {
    //             // Update existing user
    //             $user->role_id = $request->user_role_id;
    //             $user->branch_id = $branchString;
    //             $user->company_id = $companyString;

    //             if (!empty($request->email)) {
    //                 $user->email = $request->email;
    //             }

    //             if (!empty($request->new_password)) {
    //                 $user->password = Hash::make($request->new_password);
    //             }

    //             $user->is_active = $request->input('user_status');
    //             $user->is_pin_enable = $request->input('user_pin_status');
    //             $user->can_update_face = $request->input('user_face_status');
    //             $user->is_attendance_allowed = $request->input('is_app_attendance_allowed');
    //             $user->expiry_date = Carbon::parse($request->expiry_date)->format('Y-m-d');
    //             $user->update();

    //             // Update user's role using pivot table
    //             DB::table('model_has_roles')
    //                 ->where('model_id', $user->id)
    //                 ->update([
    //                     'role_id' => $request->user_role_id,
    //                 ]);

    //             DB::commit();

    //             return $this->sendResponse($user, 'User updated successfully!', 200);
    //         } else {
    //             // Create new user
    //             $user = User::create([
    //                 'emp_id' => $emp_id,
    //                 'role_id' => $request->user_role_id,
    //                 'branch_id' => $branchString,
    //                 'company_id' => $companyString,
    //                 'email' => $request->email,
    //                 'password' => !empty($request->new_password) ? Hash::make($request->new_password) : '',
    //                 'is_active' => $request->input('user_status'),
    //                 'is_pin_enable' => $request->input('user_pin_status'),
    //                 'can_update_face' => $request->input('user_face_status'),
    //                 'is_attendance_allowed' => $request->input('is_app_attendance_allowed'),
    //                 'expiry_date' => Carbon::parse($request->expiry_date)->format('Y-m-d'),
    //             ]);

    //             // Assign role to new user using pivot table
    //         // $user->assignRole($request->user_role_id);
    //         //    Role::where('id',$request->user_role_id)

    //             DB::commit();

    //             return $this->sendResponse($user, 'User created successfully!', 201);
    //         }
    //     } catch (\Exception $e) {
    //         DB::rollback();

    //         return $this->sendError([], $e->getMessage(), 500);
    //     }
    // }



    public function updateUser(Request $request)
    {
        try {
            DB::beginTransaction();

            $emp_id = $request->employee_id;

            // Check if the role exists
            $role = DB::table('roles')->where('id', $request->user_role_id)->first();
            if (!$role) {
                return $this->sendError([], 'The specified role does not exist.', 400);
            }

            // Prepare arrays
            $companyArray = array_unique($request->company_id);
            $branchArray = array_unique($request->branch_id);

            // Convert arrays to comma-separated strings for storage
            $companyString = implode(',', $companyArray);
            $branchString = implode(',', $branchArray);

            $user = User::where('emp_id', $emp_id)->first();
            $empData = EmployeeDetail::where('id', $emp_id)->where('status', '1')
                ->where('is_deleted', '0')
                ->where('is_active', '1')->first();
            if ($user) {
                // Update existing user with new values
                $user->role_id = $request->user_role_id;
                $user->branch_id = $branchString;
                $user->company_id = $companyString;

                if (!empty($request->email)) {
                    $user->email = $request->email;
                }

                if (!empty($request->update_password)) {
                    $user->password = Hash::make($request->update_password);
                }

                $user->is_active = $request->input('user_status');
                $user->is_pin_enable = $request->input('user_pin_status');
                $user->can_update_face = $request->input('user_face_status');
                $user->is_attendance_allowed = $request->input('is_app_attendance_allowed');
                $user->expiry_date = Carbon::parse($request->expiry_date)->format('Y-m-d');
                $user->update();

                // Update user's role using pivot table
                DB::table('model_has_roles')
                    ->where('model_id', $user->id)
                    ->update([
                        'role_id' => $request->user_role_id,
                    ]);

                $empData = EmployeeDetail::where('id', $emp_id)->first();
                if ($empData) {
                    $empData->update([
                        'attend_pin' => $request->pin
                    ]);
                }

                DB::commit();

                // Convert stored comma-separated strings back to arrays for the response
                $user->branch_id = explode(',', $user->branch_id);
                $user->company_id = explode(',', $user->company_id);

                return $this->sendResponse($user, 'User updated successfully!', 200);
            } else {
                // Create new user with unique values
                $user = User::create([
                    'emp_id' => $emp_id,
                    'role_id' => $request->user_role_id,
                    'branch_id' => $branchString,
                    'company_id' => $companyString,
                    'email' => $request->email,
                    'fullname' => $empData ? $empData->emp_name : '',
                    'password' => !empty($request->update_password) ? Hash::make($request->update_password) : '',
                    'is_active' => $request->input('user_status'),
                    'is_pin_enable' => $request->input('user_pin_status'),
                    'can_update_face' => $request->input('user_face_status'),
                    'is_attendance_allowed' => $request->input('is_app_attendance_allowed'),
                    'expiry_date' => Carbon::parse($request->expiry_date)->format('Y-m-d'),
                ]);
                $empData = EmployeeDetail::where('id', $emp_id)->first();
                if ($empData) {
                    $empData->update([
                        'attend_pin' => $request->pin
                    ]);
                }
                DB::commit();

                // Convert stored comma-separated strings back to arrays for the response
                $user->branch_id = $branchArray;
                $user->company_id = $companyArray;

                return $this->sendResponse($user, 'User created successfully!', 201);
            }
        } catch (\Exception $e) {
            DB::rollback();

            return $this->sendError([], $e->getMessage(), 500);
        }
    }



}
