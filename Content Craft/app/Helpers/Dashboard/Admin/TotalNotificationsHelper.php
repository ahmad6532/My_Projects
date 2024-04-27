<?php

namespace App\Helpers\Dashboard\Admin;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class TotalNotificationsHelper
{
    public static function totalNotifications(){
        try {
          return Notification::count();
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
 }
    }
}