<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\module;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class RolesPermissionController extends BaseController
{
    public function rolesPermission()
    {

            $roles = Role::where('is_active', '1')
            ->where('is_deleted', '0')
            ->orderBy('id', 'asc')->skip(1)->take(PHP_INT_MAX)->get();


        return $this->sendResponse($roles, 'Roles fetched successfully!', 200);
    }

    public function addRolesPermission(Request $request)
    {
        try {
            $user = Auth::user();

            $module_query = Module::with('modulesPermission')->select('id', 'name');
            $modules = $module_query->orderBy('sort_order', 'asc')->get();

            $allowed_modules = [];
            if ($user->role_id > 1) {
                $user_id = User::where('role_id', $user->role_id)->pluck('id')->first();
                $role_id = DB::table('model_has_roles')->where('model_id', $user_id)->pluck('role_id')->first();
                $permissions = DB::table('role_has_permissions')->where('role_id', $role_id)->pluck('permission_id')->toArray();
                $allowed_modules = array_unique(Permission::whereIn('id', $permissions)->pluck('module_id')->toArray());
            } else {
                $role_id = DB::table('model_has_roles')->where('model_id', $user->id)->pluck('role_id')->first();
            }

            return $this->sendResponse([
                'modules' => $modules,
                // 'allowed_modules' => $allowed_modules,
                // 'role_id' => $role_id
            ], 'Roles and permissions fetched successfully!', 200);

        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch roles and permissions.', $e->getMessage(), 500);
        }
    }

    public function saveRolesPermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'required|unique:roles,role_name',
        ]);

        if ($validator->fails()) {
            return $this->sendError([],$validator->errors(),400);
        }
        DB::beginTransaction();
        try {

        $role = new Role();
        $role->role_name = $request->role_name;
        $role->guard_name = 'web';
        $role->user_id = auth()->user()->id;
        $role->save();

        if($request->has('all')){
            foreach ($request->all as $key => $value) {
                $module = Module::find($key);
                $module_name = strtolower(str_replace(' ', '-', $module->name));
                $name = $module_name . '-all';
                $permission = Permission::where('name',$name)->first();
                if(!$permission){
                    $permission = new Permission;
                }
                $permission->module_id = $key;
                $permission->name = $name;

                $permission->guard_name = 'web';
                $permission->save();
                DB::table('role_has_permissions')->insert([
                        'permission_id' => $permission->id,
                        'role_id' => $role->id,
                    ]
                );
            }
        }

        if($request->has('read')) {
            foreach ($request->read as $key => $value) {
                $module = Module::find($key);
                $module_name = strtolower(str_replace(' ', '-', $module->name));
                $name = $module_name . '-read';
                $permission = Permission::where('name',$name)->first();
              // dd($permission->id);
                if(!$permission){
                    $permission = new Permission;
                }
                $permission->module_id = $key;
                $permission->name = $name;
                $permission->guard_name = 'web';
                $permission->save();

                DB::table('role_has_permissions')->insert([
                        'permission_id' => $permission->id,
                        'role_id' => $role->id,
                    ]
                );
            }
        }

        if($request->has('write')) {
            foreach ($request->write as $key => $value) {
                $module = Module::find($key);
                $module_name = strtolower(str_replace(' ', '-', $module->name));
                $name = $module_name . '-write';
                $permission = Permission::where('name',$name)->first();
                if(!$permission){
                    $permission = new Permission;
                }
                $permission->module_id = $key;
                $permission->name = $name;
                $permission->guard_name = 'web';
                $permission->save();

                DB::table('role_has_permissions')->insert([
                        'permission_id' => $permission->id,
                        'role_id' => $role->id,
                    ]
                );
            }
        }

        if($request->has('delete')) {
            foreach ($request->delete as $key => $value) {
                $module = Module::find($key);
                $module_name = strtolower(str_replace(' ', '-', $module->name));
                $name = $module_name . '-delete';
                $permission = Permission::where('name',$name)->first();
                if(!$permission){
                    $permission = new Permission;
                }
                $permission->module_id = $key;
                $permission->name = $name;
                $permission->guard_name = 'web';
                $permission->save();
                DB::table('role_has_permissions')->insert([
                        'permission_id' => $permission->id,
                        'role_id' => $role->id,
                    ]
                );
            }
        }
        DB::commit();
        $data = [
            'role' => $role,
            'permission' => $permission
        ];

        // $msg = 'Added new Role "'.$request->role_name.'"';
        // createLog('user_action',$msg);
        return $this->sendResponse($data,'Role saved successfully!', 200);

    } catch (\Exception $e) {
        DB::rollBack();
        return $this->sendError([], 'An error occurred while saving the role: ' . $e->getMessage(), 500);
    }

    }


    public function editRolesPermission(Request $request, int $id)
    {
        $user = Auth::user();
        $role = Role::find($id);
        $module_query = Module::with('modulesPermission')->select('id','name');
        $modules = $module_query->orderBy('sort_order','asc')->orderBy('sub_order','asc')->get();
        $allowed_modules  = [];

        if ($user->role_id == 1) { // admin role id
            $user_id = User::where('role_id', '1')->pluck('id')->first();
            $role_id = DB::table('model_has_roles')->where(['model_id' => $user_id])->pluck('role_id')->first();
            $permissions = DB::table('role_has_permissions')->where(['role_id' => $role_id])->pluck('permission_id')->toArray();
            $allowed_modules = array_unique(Permission::whereIn('id', $permissions)->pluck('module_id')->toArray());
        } else {
            $role_id = DB::table('model_has_roles')->where(['model_id' => auth()->user()->id])->pluck('role_id')->first();
            $permissions = DB::table('role_has_permissions')->where(['role_id' => $role_id])->pluck('permission_id')->toArray();
            $allowed_modules = array_unique(Permission::whereIn('id', $permissions)->pluck('module_id')->toArray());
        }

        $rolePermissions = Permission::join('role_has_permissions', 'role_has_permissions.permission_id', 'permissions.id')
            ->select('permissions.module_id', 'permissions.name', 'role_has_permissions.permission_id', 'role_has_permissions.role_id')
            ->where('role_has_permissions.role_id', $id)
            ->get()
            ->toArray();

        $assignedPermission = DB::table('role_has_permissions')->where('role_id', $id)->pluck('permission_id')->toArray();

        $data = [
            'role' => $role,
           // 'modules' => $modules,
            //'assignedPermission' => $assignedPermission,
            // 'role_id' => $role_id,
            //'allowed_modules' => $allowed_modules,
            'rolePermissions' => $rolePermissions
        ];

        return $this->sendResponse($data,'Show edit roles permission!', 200);
    }


    public function updateRolesPermission(Request $request)
    {
       // dd($request->toArray());
        $user = auth()->user();
        $role_id = $user->role_id;

        $id = $request->id;
        $role_name = Role::find($id);
        if($role_name->role_name != $request->role_name){
            $validator = Validator::make($request->all(), [
                'role_name' => 'required|unique:roles,role_name'
            ]);

            if ($validator->fails()) {
                return $this->sendError([],$validator->errors(),400);
            }
        }

        $role = Role::find($id);
        $role->role_name = $request->role_name;
        $role->user_id = auth()->user()->id;
        $role->save();

        if($role_id == 1) {
            $user_ids = DB::table('model_has_roles')->where('role_id', $role->id)->pluck('model_id');
            //dd($user_ids);
            foreach ($user_ids as $user_id) {
                $user = User::where('id', $user_id)->where('role_id','1')->first();
                if ($user) {
                    $company_users = User::where('role_id', '2')->get();
                    foreach ($company_users as $company_user) {
                        $role_id = $company_user->role_id;
                        $permissions = DB::table('role_has_permissions')->where('role_id', $role_id)->pluck('permission_id');
                        $active_permissions = DB::table('permissions')->whereIn('id', $permissions)->get();

                        $allowed_permissions = [];
                        $not_allowed_permissions = [];
                        foreach ($active_permissions as $permission) {
                            if ($request->has('all')) {
                                foreach ($request->all as $key => $value) {
                                    $module = Module::find($key);
                                    $module_name = strtolower(str_replace(' ', '-', $module->name));
                                    $name = $module_name . '-all';

                                    if ($name == $permission->name) {
                                        $allowed_permissions[] = ['perm_id' => $permission->id, 'name' => $permission->name, 'id' => $module->id];
                                    } else {
                                        $not_allowed_permissions[] = $permission->id;
                                    }
                                }
                            }
                            if ($request->has('read')) {
                                foreach ($request->all as $key => $value) {
                                    $module = Module::find($key);
                                    $module_name = strtolower(str_replace(' ', '-', $module->name));
                                    $name = $module_name . '-read';

                                    if ($name == $permission->name) {
                                        $allowed_permissions[] = ['perm_id' => $permission->id, 'name' => $permission->name, 'id' => $module->id];
                                    } else {
                                        $not_allowed_permissions[] = $permission->id;
                                    }
                                }
                            }
                        }

                        if (count($not_allowed_permissions) > 0) {
                            $not_allowed_permissions = array_unique($not_allowed_permissions);
                            DB::table('role_has_permissions')->where('role_id', $role_id)->whereIn('permission_id', $not_allowed_permissions)->delete();
                        }
                        if (count($allowed_permissions) > 0) {
                            foreach ($allowed_permissions as $allowed_permission) {
                                $permission = Permission::where('id', $allowed_permission['perm_id'])->first();
                                if (!$permission) {
                                    $permission = new Permission;
                                }
                                $permission->module_id = $allowed_permission['id'];
                                $permission->name = $allowed_permission['name'];
                                $permission->guard_name = 'web';
                                $permission->save();
                                DB::table('role_has_permissions')->insert([
                                        'permission_id' => $permission->id,
                                        'role_id' => $role_id,
                                    ]
                                );
                            }
                        }
                    }
                }
            }
        }

        DB::table('role_has_permissions')->where('role_id' ,$role->id)->delete();
        if($request->has('all')){
            foreach ($request->all as $key => $value) {
                $module = Module::find($key);
                $module_name = strtolower(str_replace(' ', '-', $module->name));
                $name = $module_name . '-all';

                $permission = Permission::where('name',$name)->first();
                if(!$permission){
                    $permission = new Permission;
                }
                $permission->module_id = $key;
                $permission->name = $name;
                $permission->guard_name = 'web';
                $permission->save();
                DB::table('role_has_permissions')->insert([
                        'permission_id' => $permission->id,
                        'role_id' => $role->id,
                    ]
                );
            }
        }
        if($request->has('read')) {
            foreach ($request->read as $key => $value) {
                $module = Module::find($key);
                $module_name = strtolower(str_replace(' ', '-', $module->name));
                $name = $module_name . '-read';
                $permission = Permission::where('name',$name)->first();
                if(!$permission){
                    $permission = new Permission;
                }
                $permission->module_id = $key;
                $permission->name = $name;
                $permission->guard_name = 'web';
                $permission->save();
                DB::table('role_has_permissions')->insert([
                        'permission_id' => $permission->id,
                        'role_id' => $role->id,
                    ]
                );
            }
        }
        if($request->has('write')) {
            foreach ($request->write as $key => $value) {
                $module = Module::find($key);
                $module_name = strtolower(str_replace(' ', '-', $module->name));
                $name = $module_name . '-write';
                $permission = Permission::where('name',$name)->first();
                if(!$permission){
                    $permission = new Permission;
                }
                $permission->module_id = $key;
                $permission->name = $name;
                $permission->guard_name = 'web';
                $permission->save();
                DB::table('role_has_permissions')->insert([
                        'permission_id' => $permission->id,
                        'role_id' => $role->id,
                    ]
                );
            }
        }
        if($request->has('delete')) {
            foreach ($request->delete as $key => $value) {
                $module = Module::find($key);
                $module_name = strtolower(str_replace(' ', '-', $module->name));
                $name = $module_name . '-delete';
                $permission = Permission::where('name',$name)->first();
                if(!$permission){
                    $permission = new Permission;
                }
                $permission->module_id = $key;
                $permission->name = $name;
                $permission->guard_name = 'web';
                $permission->save();
                DB::table('role_has_permissions')->insert([
                        'permission_id' => $permission->id,
                        'role_id' => $role->id,
                    ]
                );
            }
        }
        // Artisan::call('cache:clear');
        // $msg = 'Updated Role "'.$role->role_name.'" as "'.$request->role_name.'"';
        // createLog('user_action',$msg);

        $data = [
            'role' => $role,
            'permission' => $permission
        ];

        return $this->sendResponse($data,'Role Updated Successfully!', 200);

    }




}
