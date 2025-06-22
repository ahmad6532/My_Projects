<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPUnit\Framework\Constraint\Operator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsController extends Controller
{
    public function addRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => ["required","string","max:255",
                Rule::unique("roles", "name")->ignore($request->id),
            ],
            "id" => "nullable|exists:roles,id",
        ]);


        if ($validator->fails()) {
            $errors = $validator->errors()->first();
            return response()->json(
                [
                    "success" => false,
                    "message" => $errors,
                ],
            );
        }

        try {
            DB::beginTransaction();
            if ($request->has("id")) {
                $role = Role::find($request->id);
                if (!$role) {
                    return response()->json([
                        "status" => false,
                        "message" => "Record not found.",
                    ]);
                }
                $role->update([
                    "name" => $request->name,
                    "is_role" => $request->is_role,

                ]);

                DB::commit();
                return response()->json([
                    "status" => true,
                    "message" => "Data updated successfully.",
                    "response" => $role,
                ]);
            } else {
                $role = Role::where("name", $request->name)->first();
                if ($role) {
                    return response()->json([
                        "status" => false,
                        "message" => "Role already exists.",
                    ]);
                }
                $role = Role::create([
                    "name" => $request->name,
                    "is_role" => $request->is_role,
                ]);

                DB::commit();
                return response()->json([
                    "status" => true,
                    "message" => "New role created successfully.",
                    "response" => $role,
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    "status" => false,
                    "message" => $e->getMessage(),
                ],
            );
        }
    }


    public function getRole(Request $request)
    {
        try {
            $roles = Role::query();
            $roles->whereNotIn('name', ['Admin', 'Company']);
            $roles->where('is_deleted', false);
            if($request->has('role_name')){
               $roles->where('name', 'like', '%' . $request->role_name . '%');
            }

            $perPage = $request->input('per_page', 10);
            $roles = $roles->paginate($perPage);

            return response()->json([
                "status" => true,
                "message" => "Role fetched successfully",
                "response" => $roles,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    "status" => false,
                    "message" => $e->getMessage(),
                ],
            );
        }
    }
    public function getRoleOperator(Request $request)
    {

        $auth = Auth::user();

        $roles = $auth->role_id == 2
        ? Role::where('is_role', 'operator')->whereNotIn('name', ['Admin', 'Company'])->where('is_deleted', false)->get()
        : Role::where('is_role', 'admin')->whereNotIn('name', ['Admin', 'Company'])->where('is_deleted', false)->get();
    

        if($roles){

            return response()->json([
                "status" => true,
                "message" => "Operator roles fetched successfully",
                "response" => $roles,
            ]);
            
        } else {

            return response()->json([
                "status" => false,
                "message" => "Operator roles does not exist",
            ]);

        }
  
    }

    

    public function showRole($id)
    {
        try {
            $role = Role::find($id);

            if (!$role) {
                return response()->json([
                    "status" => false,
                    "message" => "Record not found.",
                ]);
            }
            return response()->json([
                "status" => true,
                "message" => "Role fetched successfully",
                "response" => $role,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    "status" => false,
                    "message" => $e->getMessage(),
                ],
                422
            );
        }
    }

    public function deleteRole($id)
    {
        try {
            $role = Role::find($id);

            if (!$role) {
                return response()->json([
                    "status" => false,
                    "message" => "Record not found.",
                ]);
            }
            $role->update(['is_deleted' => true ]);
            return response()->json([
                "status" => true,
                "message" => "Role deleted successfully",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    "status" => false,
                    "message" => $e->getMessage(),
                ],
            );
        }
    }

    public function getPermissions()
    {
        $query = Permission::query();
        $query
            ->join("modules", "modules.id", "=", "permissions.module_id")
            ->select(
                "modules.name as module_name",
                "permissions.id",
                "permissions.name as permission_name"
            );
        $permissions = $query->get();
        $data = [];
        foreach ($permissions as $permission) {
            $data[$permission->module_name][] = (object) [
                "permission_id" => $permission->id,
                "permission_name" => $permission->permission_name,
            ];
        }

        return response()->json([
            "status" => true,
            "message" => "Permissions fetched successfully",
            "response" => $data,
        ]);
    }

    public function saveRollPermissions(Request $request)
    {
        try {
            DB::beginTransaction();

            $role = Role::where("id", $request->role_id)->first();
            if (!$role) {
                return response()->json([
                    "status" => false,
                    "message" => "Record not found",
                ]);
            }

            DB::table("role_has_permissions")
                ->where("role_id", $role->id)
                ->delete();
            $permissions = $request->permissions;
            if ($permissions) {
                foreach ($permissions as $permission) {
                    if (!$role->hasPermissionTo($permission)) {
                        $role->givePermissionTo($permission);
                    }
                }
            }

            DB::commit();
            return response()->json([
                "status" => true,
                "message" => "Role permissions assigned successfully",
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    "status" => false,
                    "message" => $e->getMessage(),
                ],
            );
        }
    }

    public function editRollPermissions(Request $request)
    {
        try {
            $role = Role::where("id", $request->role_id)->first();

            $data = [];
            if ($role) {
                $permissionsQuery = Permission::query();
                $permissionsQuery
                    ->join(
                        "modules",
                        "modules.id",
                        "=",
                        "permissions.module_id"
                    )
                    ->select(
                        "modules.name as module_name",
                        "permissions.id",
                        "permissions.name as permission_name"
                    )
                    ->orderBy("modules.name");
                $permissions = $permissionsQuery->get();

                $modulePermissions = [];

                foreach ($permissions as $permission) {
                    $hasPermission = DB::table("role_has_permissions")
                        ->where("role_id", $role->id)
                        ->where("permission_id", $permission->id)
                        ->exists();

                    if($hasPermission){
                        $modulePermissions[$permission->module_name][] = (object) [
                            "permission_id" => $permission->id,
                            "permission_name" => $permission->permission_name,
                            // "has_permission" => $hasPermission,
                        ];
                    }
                }
                $data['role'] = $role;
                $data['permissions'] = $modulePermissions;
            }

            return response()->json([
                "status" => true,
                "message" => "Role and permissions retrieved successfully",
                "response" => $data,
            ]);
        } catch (Exception $e) {
            return response()->json(
                [
                    "status" => false,
                    "message" => $e->getMessage(),
                ],
            );
        }
    }

    public function updateRollPermissions(Request $request)
    {
        try {
            DB::beginTransaction();
            $role = Role::where("id", $request->role_id)->first();
            if (!$role) {
                return response()->json([
                    "status" => false,
                    "message" => "No role found",
                ]);
            }

            DB::table("role_has_permissions")
                ->where("role_id", $role->id)
                ->delete();

            $permissions = $request->permissions;
            foreach ($permissions as $permission) {
                $role->givePermissionTo($permission);
            }

            DB::commit();
            return response()->json([
                "status" => true,
                "message" => "Role updated successfully",
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    "status" => false,
                    "message" => $e->getMessage(),
                ],
            );
        }
    }
}
