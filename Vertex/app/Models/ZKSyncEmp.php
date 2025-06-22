<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZKSyncEmp extends Model
{
    use HasFactory;
    protected $table = 'zk_synced_employees';
    protected $fillable = [
        'emp_id',
        'synced',
        'action',
        'old_branch'
    ];
}
