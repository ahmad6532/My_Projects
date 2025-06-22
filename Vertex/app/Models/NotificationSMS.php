<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSMS extends Model
{
    protected $table ='notification_sms';
    protected $fillable =
    [
        'user_id',
        'phone_number',
        'sms_body',
        'from_phone_number',
        'sms_schedule_date',
        'sms_sent_status',
        'campaign_entry',
        'response',
        'sent_date',
    ];
    // use HasFactory;
}
