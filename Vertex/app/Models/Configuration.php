<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $table ='configurations';
    protected $fillable =
    [
        'SMTP_email',
        'SMTP_password',
        'SMTP_email_from',
        'SMTP_password_from',
        'is_active',
        'is_deleted',
       
    ];
    // use HasFactory;
}
