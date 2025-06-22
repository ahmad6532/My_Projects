<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronJobHistory extends Model
{
    use HasFactory;
    protected $table = 'cron_job_history';
    protected $fillable = [
        'id',
        'start_time',
        'end_time',
        'type',
        'status',
    ];
}
