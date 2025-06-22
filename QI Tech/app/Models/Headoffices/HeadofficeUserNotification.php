<?php

namespace App\Models\Headoffices;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadofficeUserNotification extends Model
{
    use HasFactory;
    protected $table ="head_office_user_notifications";
    public static $userNoticationTypePSA = "patient_safety_alert";
    public static $statusUnread = 'unread';
    public static $statusRead = 'read';
    public static function alertColoring($type){
        if($type == self::$userNoticationTypePSA){
            return ['background_class' => 'bg-warning', 'icon' => 'fas fa-exclamation-triangle'];
        }

        return ['background_class' => 'bg-primary', 'icon' => 'fas fa-file-alt'];
    }
}
