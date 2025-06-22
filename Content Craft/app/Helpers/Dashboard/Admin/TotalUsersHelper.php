<?php

namespace App\Helpers\Dashboard\Admin;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class TotalUsersHelper
{
    public static function totalUsers()
    {
        try {
            return User::role(UserRoleEnum::USER)->count();
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
