<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as ModelsRole;
use App\Models\User;
use App\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class Role extends Model
{
    use HasRoles;
   protected $table ='roles';
    protected $fillable =
    [
        'role_name',
        'guard_name',
        'user_id',
        'is_active',
        'is_deleted',

    ];

    public function users(){
        return $this->hasMany(User::class,'model_has_roles','model_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions', 'role_id', 'permission_id');
    }

}
