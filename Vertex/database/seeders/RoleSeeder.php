<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use DB;
use Illuminate\Database\Seeder;
use Spatie\Permission\Traits\HasRoles;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     *
     */
    public function run()
    {
        $roles = [
            ['role_name' => 'Administrator', 'guard_name' => 'web', 'status' => 'default', 'user_id' => '1', 'is_active' => '1', 'is_deleted' => '0', 'created_at' => '2024-01-18 11:39:27', 'updated_at' => '2024-07-27 11:46:56'],
            ['role_name' => 'HR and Admin', 'guard_name' => 'web', 'status' => 'default', 'user_id' => '1', 'is_active' => '1', 'is_deleted' => '0', 'created_at' => '2024-01-18 11:40:00', 'updated_at' => '2024-09-16 18:01:48'],
            ['role_name' => 'User', 'guard_name' => 'web', 'status' => 'custom', 'user_id' => '1', 'is_active' => '1', 'is_deleted' => '0', 'created_at' => '2024-01-18 11:40:30', 'updated_at' => '2024-09-16 18:49:53'],
            ['role_name' => 'Branch Admin', 'guard_name' => 'web', 'status' => 'custom', 'user_id' => '1', 'is_active' => '1', 'is_deleted' => '0', 'created_at' => '2024-08-31 12:30:24', 'updated_at' => '2024-09-04 17:00:51'],

        ];
        DB::table('roles')->insert($roles);
    }
}
