<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalStatus extends Model
{
    use HasFactory;
    protected $fillable = [
        'requested_id',
        'module_id',
        'approval_level',
        'status'
    ];
}
