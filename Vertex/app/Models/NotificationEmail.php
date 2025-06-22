<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationEmail extends Model
{
    protected $table ='notification_email';
    protected $fillable =
    [
        'user_id',
        'to_email',
        'email_subject',
        'email_body',
        'from_email',
        'cc_email',
        'bcc_email',
        'schedule_date',
        'email_sent_status',
        'campaign_entry',
        'response',
        'from_name',
        'sent_date',
       
    ];
    // use HasFactory;
}
