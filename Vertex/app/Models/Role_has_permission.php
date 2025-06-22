<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role_has_permission extends Model
{
    protected $table ='role_has_permissions';
    public $timestamps = false;
    protected $fillable =
    [
        'permission_id',
        'role_id',

    ];
    // use HasFactory;
}
