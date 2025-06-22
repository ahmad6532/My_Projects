<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubDepartment extends Model
{
    // use HasFactory;
    protected $table = "sub_departments";
    protected $fillable = ['department_id','name','is_deleted','is_active','created_at','updated_at'];

    protected $hidden = [
        "is_deleted",
        "is_active",
        "created_at",
        "updated_at"
    ];

    public function departments()
    {
        return $this->belongsTo(Department::class,'id','id');
    }
}
