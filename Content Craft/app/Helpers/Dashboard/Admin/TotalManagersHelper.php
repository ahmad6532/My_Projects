<?php

namespace App\Helpers\Dashboard\Admin;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class TotalManagersHelper
{
    public static function totalManagers(){
        try {
           $managers = User::role(UserRoleEnum::MANAGER)->get();
            return $managers;
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
 }
    }
}