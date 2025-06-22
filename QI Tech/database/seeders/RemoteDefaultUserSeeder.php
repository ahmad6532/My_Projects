<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RemoteDefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email = 'remote@qitech.com';
        $password = '$2a$12$PRT8DkCidzYrlLman7F7F.HKlLkO4M9MdAil4rPTUee0uQ2A2XnIK';
        $first_name = "Remote";
        $position_id = 1;
        $sur_name = "User";
        $mobile_no = '00000000000000';
        if(!User::where('email',$email)->first())
        {
            $user = new User();
            $user->id = 1000;
            $user->email = $email;
            $user->password = $password;
            $user->first_name = $first_name;
            $user->surname = $sur_name;
            $user->position_id = $position_id;
            $user->mobile_no = $mobile_no;
            $user->email_verified_at = Carbon::now();
            $user->save();

        }
    }
}
