<?php

namespace App\Helpers\Dashboard\Manager;

use App\Models\User;

class AllUsersByManager 
{
    public static function usersByManager()
    {
        try {
            $manager = User::where('id',auth()->user()->id)->first();
            return $manager->managerToUsers;
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}