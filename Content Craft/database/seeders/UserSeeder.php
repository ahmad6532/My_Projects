<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'firstName' => 'Muhammad',
            'lastName' => 'Ahmad',
            'email' => 'iahmad8473@gmail.com',
            'password' => Hash::make('11111111'),
            'gender' => 'Male',
            'address' => 'Gulbarg III Lahore',
            'avatar' => '/images/man.PNG',
            'phone' => '03077838473',
            'status' => UserStatusEnum::ACTIVE,
            'country' => 'Pakistan',
            'postalCode' => 12345,
            'managerId' => null,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
        $user->assignRole(UserRoleEnum::ADMIN);
    }
}
