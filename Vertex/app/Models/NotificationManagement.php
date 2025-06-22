<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationManagement extends Model
{
    protected $table ='notification_management';
    protected $fillable =
    [
        'type',
        'type_key',
        'is_hidden',
        'sms',
        'notify_by',
        'mail',
        'mobile_app_title',
        'mobile_app_description',
        'send_sms',
        'send_sms',
        'mail_subject',
        'to_email',
        'header',
        'footer',
        'send_app_noti',
        'variable_list',
    ];
}
