<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role_has_permission;
use Illuminate\Database\Seeder;

class AssignPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            Role_has_permission::create([
                'permission_id' => $permission->id,
                'role_id' => 1,
            ]);
        }
    }
}
