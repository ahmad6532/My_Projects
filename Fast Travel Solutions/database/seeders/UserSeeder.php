<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin FTS',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => '1',
            'created_at' => now(),
            'updated_at' => now()

        ])->assignRole('Admin');

        // User::create([
        //     'name' => 'user',
        //     'email' => 'user@gmail.com',
        //     'password' => bcrypt('password'),
        //     'role_id' => '3',
        //     'created_at' => now(),
        //     'updated_at' => now()

        // ])->assignRole('User');
        // User::create([
        //     'name' => 'company',
        //     'email' => 'company@gmail.com',
        //     'password' => bcrypt('password'),
        //     'profile_picture' => 'please upload',
        //     'role_id' => '2',
        //     'created_at' => now(),
        //     'updated_at' => now()

        // ])->assignRole('Company');
    }
}
