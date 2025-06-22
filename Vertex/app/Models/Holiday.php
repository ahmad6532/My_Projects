<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    // use HasFactory;
    protected $table = 'holidays';
    protected $fillable = [
        'company_id',
        'branch_id',
        'event_name',
        'start_date',
        'end_date',
        'is_repeated',
        'is_active',
        'is_deleted'
    ];

    protected $hidden = [
        //'company_id',
       // 'branch_id',
        'is_deleted',
    ];

    public function employee_detail(){
        return $this->belongsTo(EmployeeDetail::class,'branch_id','branch_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s A');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s A');
    }
}
