<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'fullname' => 'Admin User',
            'email' => 'info@viiontech.com',
            'password' => Hash::make('password'),
            'gender' => 'M',
            'is_active' => '1',
            'role_id' => 1,
        ]);
    }
}
