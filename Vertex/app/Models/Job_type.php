<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job_type extends Model
{
    // use HasFactory;
    protected $table = 'job_status';
    protected $fillable = [
        'job_status',
    ];
}
