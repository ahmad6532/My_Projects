<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create model 
        $admin = Role::create(['name' => UserRoleEnum::ADMIN, 'guard_name' => 'web']);
        $manager = Role::create(['name' => UserRoleEnum::MANAGER, 'guard_name' => 'web']);
        $user = Role::create(['name' => UserRoleEnum::USER, 'guard_name' => 'web']);

        // create permissions
        $userCrud = Permission::create(['name' => 'userCrud', 'guard_name' => 'web']);
        $plans = Permission::create(['name' => 'plans', 'guard_name' => 'web']);

        //give permissions to role
        $manager->syncPermissions($userCrud);
        $user->syncPermissions($plans);


    }
}
