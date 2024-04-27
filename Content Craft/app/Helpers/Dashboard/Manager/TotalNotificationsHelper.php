<?php

namespace App\Helpers\Dashboard\Manager;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class TotalNotificationsHelper
{
    public static function totalNotifications($users)
    {
        try {
            $notifications = 0;
            foreach ($users as $user) {
                $notifications += $user->userToNotifications->count();
            }
            return $notifications;
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
