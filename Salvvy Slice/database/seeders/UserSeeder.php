<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'Ahmad',
            'email'=>'iahmad8473@gmail.com',
            'password'=>Hash::make('11111111'),
            'phone' => '03077838473',
            'riderId' => null,
            'role' => 'Admin',
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);
    }
}
