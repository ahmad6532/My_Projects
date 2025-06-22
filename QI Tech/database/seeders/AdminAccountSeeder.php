<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        if(Admin::all()->count() == 0)
        {
            $admin = new Admin();
            $admin->first_name = "Mr.";
            $admin->surname = "Raja";
            $admin->email = "admin@test.com";
            $admin->password = Hash::make('123456');
            $admin->save();
        }
        $user = User::where('email','external@qitech.com')->first();
        if(!isset($user)){
            $user = new User();
            $user->setIncrementing(false);
            $user->position_id = 1;
            $user->first_name = 'External';
            $user->surname = 'User';
            $user->email = 'external@qitech.com';
            $user->mobile_no = '1234567';
            $user->password = Hash::make('123456');
            $user->save();
        }
        $location = Location::where('email','external@qitech.com')->first();
        if(!isset($location)){
            $location = new Location();
            $location->location_type_id = 1;
            $location->trading_name = 'External';
            $location->registered_company_name = 'External';
            $location->registration_no = 'External';
            $location->address_line1 = 'External';
            $location->town = 'External';
            $location->county = 'External';
            $location->country = 'External';
            $location->postcode = 'External';
            $location->telephone_no = 'External';
            $location->username = 'External';
            $location->email = 'external@qitech.com';
            $location->password = Hash::make('123456');
            $location->save();
        }
    }
}
