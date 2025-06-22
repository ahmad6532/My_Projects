<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationUserNotification extends Model
{
    use HasFactory;
    # PSA is patient safety alert.
    // public static $typePSA = 'patient_safety_alert';
    public static $userNoticationType = "patient_safety_alert";
    public static $statusUnread = 'unread';
    public static $statusRead = 'read';
    protected $table = 'location_user_notifications';

    public static function alertColoring($type){
        if($type == self::$userNoticationType){
            return ['background_class' => 'bg-warning', 'icon' => 'fas fa-exclamation-triangle'];
        }

        return ['background_class' => 'bg-primary', 'icon' => 'fas fa-file-alt'];
    }
}
