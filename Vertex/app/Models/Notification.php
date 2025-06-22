<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table ='notification';
    protected $fillable =
    [
        'user_id',
        'title',
        'description',
        'entry_date',
        'schedule_date',
        'read_status',
        'read_date',
        'app_sent_date',
        'for_admin',
        'notification_type',
        'type_id',
        'sent_status',
        'campaign_entry',
        'device_type',
        'is_msg_app',
        'is_msg_sms',
        'is_msg_email',
        'user_notification',
        'is_notification_required',
        'message_error',
       
       
    ];
    // use HasFactory;
}
