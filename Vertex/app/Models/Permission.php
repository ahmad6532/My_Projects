<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Permission extends Model
{
    protected $table ='permissions';
    protected $fillable =
    [
        'module_id',
        'name',
        'guard_name',

    ];
    use HasRoles;
}
